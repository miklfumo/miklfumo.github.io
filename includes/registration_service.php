<?php
require_once __DIR__ . '/security.php';

/**
 * @return array<string,mixed>
 */
function reg_config(): array {
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/registration_config.php';
    }
    return $config;
}

function reg_json_input(): array {
    $contentType = strtolower($_SERVER['CONTENT_TYPE'] ?? '');
    if (str_contains($contentType, 'application/json')) {
        $raw = file_get_contents('php://input');
        if ($raw === false || trim($raw) === '') {
            return [];
        }
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }
    return $_POST;
}

function reg_response_json(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function reg_normalize_bool(mixed $value, bool $default = false): bool {
    if (is_bool($value)) return $value;
    if (is_int($value)) return $value === 1;
    if (is_string($value)) {
        $v = strtolower(trim($value));
        if (in_array($v, ['1', 'true', 'yes', 'on'], true)) return true;
        if (in_array($v, ['0', 'false', 'no', 'off'], true)) return false;
    }
    return $default;
}

function reg_str(mixed $value): ?string {
    if ($value === null) return null;
    $result = trim((string)$value);
    return $result === '' ? null : $result;
}

function reg_email_valid(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function reg_allowed_value(?string $value, array $allowed): bool {
    return $value !== null && in_array($value, $allowed, true);
}

function reg_db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $cfg = reg_config()['db'];
    if (($cfg['host'] ?? '') === '' || ($cfg['name'] ?? '') === '' || ($cfg['user'] ?? '') === '' || ($cfg['pass'] ?? '') === '') {
        throw new RuntimeException('Database credentials are not configured');
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        $cfg['host'],
        (int)$cfg['port'],
        $cfg['name'],
        $cfg['charset']
    );

    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function reg_ensure_schema(PDO $pdo): void {
    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS registrations (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  application_number BIGINT UNSIGNED DEFAULT NULL,
  full_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(64) NOT NULL,
  organization VARCHAR(255) NOT NULL,
  position VARCHAR(255) NOT NULL,
  category ENUM('education','federal','other') NOT NULL,
  org_type ENUM('apkits_azi','dpo','other') DEFAULT NULL,
  is_paid TINYINT(1) NOT NULL DEFAULT 0,
  payment_type ENUM('company','self') DEFAULT NULL,
  inn VARCHAR(20) DEFAULT NULL,
  wants_partner TINYINT(1) NOT NULL DEFAULT 0,
  plans_report TINYINT(1) NOT NULL DEFAULT 0,
  report_topic VARCHAR(255) DEFAULT NULL,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_application_number (application_number),
  KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL;

    $pdo->exec($sql);

    // Backward-compatible upgrades for existing installations.
    try {
        $pdo->exec("ALTER TABLE registrations ADD COLUMN IF NOT EXISTS plans_report TINYINT(1) NOT NULL DEFAULT 0 AFTER wants_partner");
        $pdo->exec("ALTER TABLE registrations ADD COLUMN IF NOT EXISTS report_topic VARCHAR(255) NULL AFTER plans_report");
    } catch (Throwable $e) {
        // Some MySQL versions may not support IF NOT EXISTS for columns.
        // Keep registration flow alive; migration script is provided separately.
    }
}

function reg_subject(int $applicationNumber): string {
    return 'Заявка №' . $applicationNumber;
}

function reg_operator_for_category(string $category): string {
    return $category === 'other' ? 'partners' : 'reg';
}

function reg_public_base_url(): string {
    $base = function_exists('app_env') ? app_env('APP_BASE_URL', '') : '';
    if ($base === '') {
        $base = 'https://isedu.ru';
    }
    return rtrim($base, '/');
}

function reg_action_secret(): string {
    $secret = function_exists('app_env') ? app_env('APP_ACTION_SECRET', '') : '';
    if ($secret !== '') return $secret;
    $cfg = reg_config();
    return hash('sha256', (string)($cfg['db']['pass'] ?? '') . '|isedu-action-v1');
}

function reg_export_token(): string {
    $token = function_exists('app_env') ? app_env('APP_EXPORT_TOKEN', '') : '';
    if ($token !== '') return $token;
    $cfg = reg_config();
    return hash('sha256', (string)($cfg['db']['pass'] ?? '') . '|isedu-export-v1');
}

function reg_make_action_signature(int $applicationNumber, string $decision, string $operator, int $ts): string {
    $payload = $applicationNumber . '|' . $decision . '|' . $operator . '|' . $ts;
    return hash_hmac('sha256', $payload, reg_action_secret());
}

function reg_is_action_signature_valid(int $applicationNumber, string $decision, string $operator, int $ts, string $sig): bool {
    if ($ts <= 0 || abs(time() - $ts) > 7 * 24 * 3600) {
        return false;
    }
    $expected = reg_make_action_signature($applicationNumber, $decision, $operator, $ts);
    return hash_equals($expected, $sig);
}

function reg_action_link(int $applicationNumber, string $decision, string $operator): string {
    $ts = time();
    $sig = reg_make_action_signature($applicationNumber, $decision, $operator, $ts);
    return reg_public_base_url() . '/process-reply?application_number=' . urlencode((string)$applicationNumber)
        . '&decision=' . urlencode($decision)
        . '&operator=' . urlencode($operator)
        . '&ts=' . urlencode((string)$ts)
        . '&sig=' . urlencode($sig);
}

function reg_export_link(): string {
    return reg_public_base_url() . '/export?format=csv&token=' . urlencode(reg_export_token());
}

function reg_mail_html_escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
}

function reg_details_rows_html(array $row): string {
    $pairs = [
        'Номер заявки' => (string)($row['application_number'] ?? ''),
        'ФИО' => (string)($row['full_name'] ?? ''),
        'Email' => (string)($row['email'] ?? ''),
        'Телефон' => (string)($row['phone'] ?? ''),
        'Организация' => (string)($row['organization'] ?? ''),
        'Должность' => (string)($row['position'] ?? ''),
        'Категория' => (string)($row['category'] ?? ''),
        'Тип организации' => (string)(($row['org_type'] ?? null) ?: '-'),
        'Тип оплаты' => (string)(($row['payment_type'] ?? null) ?: '-'),
        'ИНН' => (string)(($row['inn'] ?? null) ?: '-'),
        'Интерес к партнерству' => !empty($row['wants_partner']) ? 'Да' : 'Нет',
        'Планируется доклад' => !empty($row['plans_report']) ? 'Да' : 'Нет',
        'Тема доклада' => (string)(($row['report_topic'] ?? null) ?: '-'),
        'Статус' => (string)($row['status'] ?? 'pending'),
    ];

    $html = '';
    foreach ($pairs as $key => $value) {
        $html .= '<tr>'
            . '<td style="padding:8px 12px;border-bottom:1px solid #2b324a;color:#8fa2c7;font-size:13px;">' . reg_mail_html_escape($key) . '</td>'
            . '<td style="padding:8px 12px;border-bottom:1px solid #2b324a;color:#e8eef9;font-size:13px;">' . reg_mail_html_escape($value) . '</td>'
            . '</tr>';
    }
    return $html;
}

function reg_mail_layout_html(string $title, string $subtitle, string $bodyHtml, string $actionsHtml = ''): string {
    return '<!doctype html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>'
        . '<body style="margin:0;padding:0;background:#0b1020;background-image:linear-gradient(135deg,#0b1020 0%,#101a33 100%);font-family:Arial,sans-serif;">'
        . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 0;">'
        . '<tr><td align="center">'
        . '<table role="presentation" width="680" cellspacing="0" cellpadding="0" style="max-width:680px;width:100%;border:1px solid #263455;border-radius:16px;overflow:hidden;background:#111a2e;">'
        . '<tr><td style="padding:26px 28px;background:repeating-linear-gradient(135deg,#12203a,#12203a 8px,#101a33 8px,#101a33 16px);border-bottom:1px solid #2d3e63;">'
        . '<div style="font-size:12px;letter-spacing:.12em;text-transform:uppercase;color:#8db8ff;">Регистрация мероприятия</div>'
        . '<h1 style="margin:10px 0 0;font-size:24px;line-height:1.3;color:#f3f7ff;">' . reg_mail_html_escape($title) . '</h1>'
        . '<p style="margin:8px 0 0;font-size:14px;line-height:1.5;color:#c8d6f1;">' . reg_mail_html_escape($subtitle) . '</p>'
        . '</td></tr>'
        . '<tr><td style="padding:22px 28px;color:#dbe6fb;font-size:14px;line-height:1.6;">' . $bodyHtml . '</td></tr>'
        . ($actionsHtml !== '' ? '<tr><td style="padding:0 28px 24px;">' . $actionsHtml . '</td></tr>' : '')
        . '<tr><td style="padding:16px 28px 22px;border-top:1px solid #2d3e63;color:#8fa2c7;font-size:12px;">'
        . 'Служебное письмо. Если у вас есть вопросы, напишите на info@isedu.ru'
        . '</td></tr>'
        . '</table></td></tr></table></body></html>';
}

function reg_smtp_send(string $fromEmail, string $fromPass, string $toEmail, string $subject, string $text, string $html = ''): void {
    $smtp = reg_config()['smtp'];
    $host = $smtp['host'];
    $port = (int)$smtp['port'];
    $encryption = strtolower((string)$smtp['encryption']);
    $timeout = (int)$smtp['timeout'];

    if ($fromEmail === '' || $fromPass === '') {
        throw new RuntimeException('SMTP credentials are not configured for sender');
    }

    $transportHost = $encryption === 'ssl' ? 'ssl://' . $host : $host;
    $socket = @stream_socket_client($transportHost . ':' . $port, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);
    if (!is_resource($socket)) {
        throw new RuntimeException('SMTP connect error: ' . $errstr);
    }
    stream_set_timeout($socket, $timeout);

    reg_smtp_expect($socket, [220]);
    reg_smtp_cmd($socket, 'EHLO isedu.ru', [250]);

    if ($encryption === 'tls') {
        reg_smtp_cmd($socket, 'STARTTLS', [220]);
        if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            fclose($socket);
            throw new RuntimeException('SMTP STARTTLS failed');
        }
        reg_smtp_cmd($socket, 'EHLO isedu.ru', [250]);
    }

    reg_smtp_cmd($socket, 'AUTH LOGIN', [334]);
    reg_smtp_cmd($socket, base64_encode($fromEmail), [334]);
    reg_smtp_cmd($socket, base64_encode($fromPass), [235]);

    reg_smtp_cmd($socket, 'MAIL FROM:<' . $fromEmail . '>', [250]);
    reg_smtp_cmd($socket, 'RCPT TO:<' . $toEmail . '>', [250, 251]);
    reg_smtp_cmd($socket, 'DATA', [354]);

    $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $headers = [
        'Date: ' . date(DATE_RFC2822),
        'From: ' . $fromEmail,
        'To: ' . $toEmail,
        'Subject: ' . $encodedSubject,
        'MIME-Version: 1.0',
    ];

    $plain = str_replace(["\r\n", "\r"], "\n", $text);
    if ($html !== '') {
        $boundary = 'bnd_' . bin2hex(random_bytes(8));
        $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
        $headers[] = 'Content-Transfer-Encoding: 7bit';

        $plainBody = chunk_split(base64_encode(str_replace("\n", "\r\n", $plain)), 76, "\r\n");
        $htmlBody = chunk_split(base64_encode(str_replace("\n", "\r\n", $html)), 76, "\r\n");

        $message = "--{$boundary}\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\n"
            . "Content-Transfer-Encoding: base64\r\n\r\n"
            . $plainBody . "\r\n"
            . "--{$boundary}\r\n"
            . "Content-Type: text/html; charset=UTF-8\r\n"
            . "Content-Transfer-Encoding: base64\r\n\r\n"
            . $htmlBody . "\r\n"
            . "--{$boundary}--\r\n";
    } else {
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        $headers[] = 'Content-Transfer-Encoding: base64';
        $message = chunk_split(base64_encode(str_replace("\n", "\r\n", $plain)), 76, "\r\n") . "\r\n";
    }

    $message = preg_replace("/(?m)^\./", '..', $message);
    fwrite($socket, implode("\r\n", $headers) . "\r\n\r\n" . $message . "\r\n.\r\n");
    reg_smtp_expect($socket, [250]);

    reg_smtp_cmd($socket, 'QUIT', [221]);
    fclose($socket);
}

function reg_smtp_cmd($socket, string $cmd, array $expect): void {
    fwrite($socket, $cmd . "\r\n");
    reg_smtp_expect($socket, $expect);
}

function reg_smtp_expect($socket, array $codes): void {
    $response = '';
    while (($line = fgets($socket, 515)) !== false) {
        $response .= $line;
        if (strlen($line) >= 4 && $line[3] === ' ') {
            break;
        }
    }
    $code = (int)substr($response, 0, 3);
    if (!in_array($code, $codes, true)) {
        throw new RuntimeException('SMTP error: ' . trim($response));
    }
}

function reg_send_registration_emails(array $row): void {
    $smtp = reg_config()['smtp']['accounts'];
    $applicationNumber = (int)$row['application_number'];
    $subject = reg_subject($applicationNumber);
    $category = (string)($row['category'] ?? 'education');
    $operator = reg_operator_for_category($category);
    $operatorEmail = $smtp[$operator]['email'];

    // 1) User acknowledgement for education/federal from reg@isedu.ru
    if ($category !== 'other') {
        $userText = "Ваша заявка №{$applicationNumber} получена и направлена на рассмотрение.\n"
            . "Через некоторое время мы рассмотрим вашу заявку и направим итоговое решение.";
        $userHtml = reg_mail_layout_html(
            'Заявка получена',
            'Спасибо за регистрацию. Ваша заявка направлена на рассмотрение.',
            '<p style="margin:0 0 12px;">Здравствуйте, ' . reg_mail_html_escape((string)$row['full_name']) . '.</p>'
            . '<p style="margin:0 0 12px;">Спасибо за регистрацию на мероприятие. Ваша заявка <strong>№' . reg_mail_html_escape((string)$applicationNumber) . '</strong> получена и направлена на рассмотрение.</p>'
            . '<p style="margin:0;">Через некоторое время мы рассмотрим заявку и направим итоговое решение.</p>'
        );
        reg_smtp_send($smtp['reg']['email'], $smtp['reg']['password'], (string)$row['email'], $subject, $userText, $userHtml);
    }

    // 2) Operator email from info@isedu.ru with approve/reject links + export link
    $approveLink = reg_action_link($applicationNumber, 'yes', $operator);
    $rejectLink = reg_action_link($applicationNumber, 'no', $operator);
    $exportLink = reg_export_link();

    $operatorText = "Поступила новая заявка №{$applicationNumber}.\n\n"
        . "Одобрить: {$approveLink}\n"
        . "Отклонить: {$rejectLink}\n"
        . "Выгрузка CSV: {$exportLink}\n\n"
        . reg_details_text($row);

    $actionsHtml = '<table role="presentation" cellspacing="0" cellpadding="0" style="width:100%;margin-top:4px;"><tr>'
        . '<td style="padding:0 8px 0 0;"><a href="' . reg_mail_html_escape($approveLink) . '" style="display:inline-block;padding:12px 18px;border-radius:10px;background:#00a872;color:#ffffff;text-decoration:none;font-weight:700;">Одобрить</a></td>'
        . '<td style="padding:0 8px;"><a href="' . reg_mail_html_escape($rejectLink) . '" style="display:inline-block;padding:12px 18px;border-radius:10px;background:#d14343;color:#ffffff;text-decoration:none;font-weight:700;">Отклонить</a></td>'
        . '<td style="padding:0 0 0 8px;"><a href="' . reg_mail_html_escape($exportLink) . '" style="display:inline-block;padding:12px 18px;border-radius:10px;background:#2c436f;color:#ffffff;text-decoration:none;font-weight:700;">Выгрузка CSV</a></td>'
        . '</tr></table>';

    $operatorHtml = reg_mail_layout_html(
        'Новая заявка №' . $applicationNumber,
        'Проверьте данные и примите решение по заявке.',
        '<p style="margin:0 0 14px;">Поступила новая заявка на регистрацию.</p>'
        . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #2b324a;border-radius:10px;overflow:hidden;">'
        . reg_details_rows_html($row)
        . '</table>',
        $actionsHtml
    );

    reg_smtp_send($smtp['info']['email'], $smtp['info']['password'], $operatorEmail, $subject, $operatorText, $operatorHtml);
}

function reg_send_decision_email(array $row): void {
    $smtp = reg_config()['smtp']['accounts'];
    $applicationNumber = (int)$row['application_number'];
    $subject = reg_subject($applicationNumber);
    $category = (string)($row['category'] ?? 'education');
    $operator = reg_operator_for_category($category);
    $fromEmail = $smtp[$operator]['email'];
    $fromPass = $smtp[$operator]['password'];
    $approved = ($row['status'] ?? '') === 'approved';

    if ($approved) {
        $text = "Спасибо за регистрацию в нашем мероприятии.\n"
            . "Ваша заявка №{$applicationNumber} успешно одобрена.\n\n"
            . "Ждем Вас 25-27 октября 2026 года на Всероссийской научно-практической конференции "
            . "\"Кадровое обеспечение информационной безопасности Российской Федерации\" и XXX юбилейном Пленуме ФУМО ИБ, "
            . "проходящем на базе МИРЭА — Российского технологического университета по адресу: "
            . "проспект Вернадского, 78, стр. 6, г. Москва, 119454.\n"
            . "Не забудьте взять с собой документ, удостоверяющий личность.";

        $html = reg_mail_layout_html(
            'Заявка одобрена',
            'Спасибо за регистрацию. Ждём вас на мероприятии.',
            '<p style="margin:0 0 12px;">Ваша заявка <strong>№' . reg_mail_html_escape((string)$applicationNumber) . '</strong> успешно одобрена.</p>'
            . '<p style="margin:0 0 12px;">Ждём Вас <strong>25-27 ноября 2026 года</strong> на Всероссийской научно-практической конференции '
            . '«Кадровое обеспечение информационной безопасности Российской Федерации» и XXX юбилейном Пленуме ФУМО ИБ.</p>'
            . '<p style="margin:0 0 12px;">Место проведения: МИРЭА — Российский технологический университет, проспект Вернадского, 78, стр. 6, г. Москва, 119454.</p>'
            . '<p style="margin:0;">Не забудьте взять с собой документ, удостоверяющий личность.</p>'
        );
    } else {
        $text = "К сожалению, Ваша заявка №{$applicationNumber} отклонена.\n"
            . "Если вы считаете, что произошла ошибка — свяжитесь с нами по адресу info@isedu.ru.";

        $html = reg_mail_layout_html(
            'Заявка отклонена',
            'К сожалению, ваша заявка не была одобрена.',
            '<p style="margin:0 0 12px;">К сожалению, ваша заявка <strong>№' . reg_mail_html_escape((string)$applicationNumber) . '</strong> отклонена.</p>'
            . '<p style="margin:0;">Если вы считаете, что произошла ошибка, свяжитесь с нами: <a href="mailto:info@isedu.ru" style="color:#9ac3ff;">info@isedu.ru</a>.</p>'
        );
    }

    reg_smtp_send($fromEmail, $fromPass, (string)$row['email'], $subject, $text, $html);
}

function reg_details_text(array $row): string {
    return implode("\n", [
        'Номер заявки: ' . ($row['application_number'] ?? ''),
        'ФИО: ' . ($row['full_name'] ?? ''),
        'Email: ' . ($row['email'] ?? ''),
        'Телефон: ' . ($row['phone'] ?? ''),
        'Организация: ' . ($row['organization'] ?? ''),
        'Должность: ' . ($row['position'] ?? ''),
        'Категория: ' . ($row['category'] ?? ''),
        'Тип организации: ' . (($row['org_type'] ?? null) ?: '-'),
        'Оплачиваемое участие: ' . (!empty($row['is_paid']) ? 'Да' : 'Нет'),
        'Тип оплаты: ' . (($row['payment_type'] ?? null) ?: '-'),
        'ИНН: ' . (($row['inn'] ?? null) ?: '-'),
        'Интерес к партнерству: ' . (!empty($row['wants_partner']) ? 'Да' : 'Нет'),
        'Планируется доклад: ' . (!empty($row['plans_report']) ? 'Да' : 'Нет'),
        'Тема доклада: ' . (($row['report_topic'] ?? null) ?: '-'),
        'Статус: ' . ($row['status'] ?? ''),
    ]);
}

function reg_csv_escape(mixed $value): string {
    $cell = $value === null ? '' : (string)$value;
    if (str_contains($cell, '"') || str_contains($cell, ',') || str_contains($cell, "\n") || str_contains($cell, "\r")) {
        return '"' . str_replace('"', '""', $cell) . '"';
    }
    return $cell;
}




