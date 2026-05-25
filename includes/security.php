<?php
/**
 * Security functions: CSRF, rate limiting, headers, sanitization
 */

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool {
        if ($needle === '') {
            return true;
        }
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool {
        if ($needle === '') {
            return true;
        }
        $length = strlen($needle);
        if ($length > strlen($haystack)) {
            return false;
        }
        return substr($haystack, -$length) === $needle;
    }
}

if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool {
        if ($needle === '') {
            return true;
        }
        return strpos($haystack, $needle) !== false;
    }
}

if (!function_exists('app_load_env_file')) {
    function app_load_env_file(string $path): void {
        if (!is_file($path) || !is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!is_array($lines)) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            // Drop UTF-8 BOM if present (common for files saved from Windows editors).
            if (strncmp($line, "\xEF\xBB\xBF", 3) === 0) {
                $line = substr($line, 3);
            }
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $key = trim($parts[0]);
            if (strncmp($key, "\xEF\xBB\xBF", 3) === 0) {
                $key = substr($key, 3);
            }
            $value = trim($parts[1]);
            if ($key === '') {
                continue;
            }

            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

$projectRoot = dirname(__DIR__);
app_load_env_file($projectRoot . '/.env');
app_load_env_file($projectRoot . '/.env.local');

if (!function_exists('app_env')) {
    function app_env(string $key, string $default = ''): string {
        $value = getenv($key);
        if ($value !== false && $value !== '') {
            return (string)$value;
        }
        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return (string)$_ENV[$key];
        }
        if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
            return (string)$_SERVER[$key];
        }
        return $default;
    }
}

if (!headers_sent()) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        ini_set('session.cookie_secure', '1');
    }
}

/**
 * Ensure session storage path is writable.
 * On some hosts/local stacks default session.save_path can be unavailable.
 */
if (!function_exists('app_ensure_session_save_path')) {
    function app_ensure_session_save_path(string $projectRoot): void {
        $currentPath = (string)ini_get('session.save_path');
        if ($currentPath !== '' && @is_dir($currentPath) && @is_writable($currentPath)) {
            return;
        }

        $candidates = [
            $projectRoot . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'sessions',
            sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'isedu_sessions',
        ];

        foreach ($candidates as $candidate) {
            if (!is_dir($candidate)) {
                @mkdir($candidate, 0700, true);
            }
            if (is_dir($candidate) && is_writable($candidate)) {
                ini_set('session.save_path', $candidate);
                return;
            }
        }
    }
}

app_ensure_session_save_path($projectRoot);

// Start session for CSRF + rate-limiting
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

/**
 * Set security headers
 */
function set_security_headers(): void {
    if (headers_sent()) {
        return;
    }
    header_remove('X-Powered-By');
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("X-XSS-Protection: 1; mode=block");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
    header("Cross-Origin-Opener-Policy: same-origin");
    header("Cross-Origin-Resource-Policy: same-origin");
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
    }
    // CSP: allow self, Yandex Maps, inline styles/scripts needed for map
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://api-maps.yandex.ru https://yandex.ru https://smartcaptcha.yandexcloud.net https://smartcaptcha.cloud.yandex.ru; style-src 'self' 'unsafe-inline'; img-src 'self' data: https://*.yandex.ru https://*.yandex.net https://smartcaptcha.yandexcloud.net https://smartcaptcha.cloud.yandex.ru; frame-src https://yandex.ru https://*.yandex.ru https://smartcaptcha.yandexcloud.net https://smartcaptcha.cloud.yandex.ru; connect-src 'self' https://smartcaptcha.yandexcloud.net https://smartcaptcha.cloud.yandex.ru;");
}

/**
 * Generate CSRF token
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 */
function csrf_validate(string $token): bool {
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Regenerate CSRF token after successful validation
 */
function csrf_regenerate(): void {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Generate image-based CAPTCHA
 * Creates a math challenge with a distorted image
 */
function captcha_generate(): array {
    $operators = ['+', '-', '*'];
    $op = $operators[random_int(0, 2)];

    switch ($op) {
        case '+':
            $a = random_int(10, 50);
            $b = random_int(10, 50);
            $answer = $a + $b;
            break;
        case '-':
            $a = random_int(20, 60);
            $b = random_int(1, $a - 1);
            $answer = $a - $b;
            break;
        case '*':
            $a = random_int(2, 12);
            $b = random_int(2, 9);
            $answer = $a * $b;
            break;
        default:
            $a = random_int(10, 50);
            $b = random_int(10, 50);
            $answer = $a + $b;
    }

    $opSymbol = $op === '*' ? "\u{00D7}" : $op;
    $question = "{$a} {$opSymbol} {$b} = ?";

    $_SESSION['captcha_answer'] = $answer;
    $_SESSION['captcha_time'] = time();

    return ['question' => $question, 'answer' => $answer];
}

/**
 * Validate CAPTCHA answer with time limit (5 min)
 */
function captcha_validate(string $input): bool {
    if (empty($_SESSION['captcha_answer']) || empty($_SESSION['captcha_time'])) {
        return false;
    }
    // Expire after 5 minutes
    if (time() - $_SESSION['captcha_time'] > 300) {
        unset($_SESSION['captcha_answer'], $_SESSION['captcha_time']);
        return false;
    }
    $valid = (int)$input === (int)$_SESSION['captcha_answer'];
    if ($valid) {
        unset($_SESSION['captcha_answer'], $_SESSION['captcha_time']);
    }
    return $valid;
}


/**
 * Verify Yandex SmartCaptcha token (server-side).
 * Returns true only when Yandex confirms success.
 */
function smartcaptcha_validate(string $token, string $secret, string $userIp = ''): bool {
    if ($token === '' || $secret === '') {
        return false;
    }

    $payload = http_build_query([
        'secret' => $secret,
        'token' => $token,
        'ip' => $userIp,
    ]);

    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
                . 'Content-Length: ' . strlen($payload) . "\r\n",
            'content' => $payload,
            'timeout' => 6,
        ],
    ]);

    $response = @file_get_contents('https://smartcaptcha.cloud.yandex.ru/validate', false, $context);
    if ($response === false) {
        return false;
    }

    $decoded = json_decode($response, true);
    return is_array($decoded) && !empty($decoded['status']) && $decoded['status'] === 'ok';
}

/**
 * Generate CAPTCHA image (PNG) as base64 data URI
 */
function captcha_image(string $text): string {
    $width = 200;
    $height = 60;
    $img = imagecreatetruecolor($width, $height);

    // Colors
    $bgColor = imagecolorallocate($img, 13, 15, 25); // dark bg
    $textColor = imagecolorallocate($img, 0, 212, 255); // cyan
    $noiseColor = imagecolorallocate($img, 40, 50, 70);

    imagefill($img, 0, 0, $bgColor);

    // Add noise lines
    for ($i = 0; $i < 8; $i++) {
        imageline($img,
            random_int(0, $width), random_int(0, $height),
            random_int(0, $width), random_int(0, $height),
            $noiseColor
        );
    }

    // Add noise dots
    for ($i = 0; $i < 100; $i++) {
        imagesetpixel($img, random_int(0, $width), random_int(0, $height), $noiseColor);
    }

    // Remove "= ?" for image display, just show the expression
    $display = str_replace(' = ?', '', $text);

    // Draw text with built-in font (size 5 = largest built-in)
    $fontSize = 5;
    $textWidth = imagefontwidth($fontSize) * strlen($display);
    $textHeight = imagefontheight($fontSize);
    $x = ($width - $textWidth) / 2;
    $y = ($height - $textHeight) / 2;

    imagestring($img, $fontSize, (int)$x, (int)$y, $display, $textColor);

    // Output as base64
    ob_start();
    imagepng($img);
    $data = ob_get_clean();
    imagedestroy($img);

    return 'data:image/png;base64,' . base64_encode($data);
}

/**
 * Rate limiter: max $limit submissions per $window seconds
 */
function rate_limit_check(string $action = 'form', int $limit = 3, int $window = 300): bool {
    $key = "rate_{$action}";
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [];
    }

    $now = time();
    // Remove expired entries
    $_SESSION[$key] = array_filter($_SESSION[$key], fn($t) => ($now - $t) < $window);

    if (count($_SESSION[$key]) >= $limit) {
        return false; // Rate limited
    }

    $_SESSION[$key][] = $now;
    return true;
}


/**
 * IP-based rate limiter for basic anti-DDoS protection on forms.
 * Stores counters in a temp file with flock to work on shared hosting.
 */
function ip_rate_limit_check(string $action, string $ip, int $limit, int $window): bool {
    $safeAction = preg_replace('/[^a-z0-9_\-]/i', '_', $action);
    $safeIp = preg_replace('/[^a-z0-9_:\.\-]/i', '_', $ip ?: 'unknown');
    $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "rate_{$safeAction}_{$safeIp}.json";

    $now = time();
    $entries = [];

    $fp = fopen($file, 'c+');
    if ($fp === false) {
        // Fail-open to avoid blocking legitimate users when FS is unavailable
        return true;
    }

    try {
        if (!flock($fp, LOCK_EX)) {
            fclose($fp);
            return true;
        }

        $raw = stream_get_contents($fp);
        if ($raw !== false && $raw !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $entries = array_values(array_filter($decoded, fn($t) => is_int($t) && ($now - $t) < $window));
            }
        }

        if (count($entries) >= $limit) {
            flock($fp, LOCK_UN);
            fclose($fp);
            return false;
        }

        $entries[] = $now;
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($entries));
        fflush($fp);
        flock($fp, LOCK_UN);
    } finally {
        fclose($fp);
    }

    return true;
}

/**
 * Sanitize output for HTML (XSS protection)
 */
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * Validate INN (10 digits for org, 12 for individual)
 */
function validate_inn(string $inn, bool $is_org = true): bool {
    $digits = preg_replace('/\D/', '', $inn);
    return $is_org ? strlen($digits) === 10 : strlen($digits) === 12;
}

/**
 * Validate email strictly
 */
function validate_email_strict(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone (Russian format)
 */
function validate_phone(string $phone): bool {
    $digits = preg_replace('/\D/', '', $phone);
    return strlen($digits) >= 10 && strlen($digits) <= 12;
}
