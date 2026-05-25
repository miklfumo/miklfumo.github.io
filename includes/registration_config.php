<?php
/**
 * Registration backend configuration.
 * Secrets must be provided via environment variables or local config override.
 */

if (!function_exists('reg_load_env_file')) {
    function reg_load_env_file(string $path): void {
        if (!is_file($path) || !is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!is_array($lines)) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $key = trim($parts[0]);
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

$rootDir = dirname(__DIR__);
reg_load_env_file($rootDir . '/.env');
reg_load_env_file($rootDir . '/.env.local');

$config = [
    'db' => [
        'host' => getenv('REG_DB_HOST') ?: '',
        'port' => (int)(getenv('REG_DB_PORT') ?: 3306),
        'name' => getenv('REG_DB_NAME') ?: '',
        'user' => getenv('REG_DB_USER') ?: '',
        'pass' => getenv('REG_DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
    'smtp' => [
        'host' => getenv('REG_SMTP_HOST') ?: 'mail.nic.ru',
        'port' => (int)(getenv('REG_SMTP_PORT') ?: 465),
        'encryption' => getenv('REG_SMTP_ENCRYPTION') ?: 'ssl', // ssl | tls | none
        'timeout' => (int)(getenv('REG_SMTP_TIMEOUT') ?: 15),
        'accounts' => [
            'info' => [
                'email' => getenv('REG_MAIL_INFO_EMAIL') ?: 'info@isedu.ru',
                'password' => getenv('REG_MAIL_INFO_PASS') ?: '',
            ],
            'reg' => [
                'email' => getenv('REG_MAIL_REG_EMAIL') ?: 'reg@isedu.ru',
                'password' => getenv('REG_MAIL_REG_PASS') ?: '',
            ],
            'partners' => [
                'email' => getenv('REG_MAIL_PARTNERS_EMAIL') ?: 'partners@isedu.ru',
                'password' => getenv('REG_MAIL_PARTNERS_PASS') ?: '',
            ],
        ],
    ],
];

$localConfigPath = __DIR__ . '/registration_config.local.php';
if (is_file($localConfigPath) && is_readable($localConfigPath)) {
    $localConfig = require $localConfigPath;
    if (is_array($localConfig)) {
        $config = array_replace_recursive($config, $localConfig);
    }
}

return $config;
