<?php
require_once __DIR__ . '/includes/registration_service.php';

set_security_headers();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    reg_response_json(['error' => 'Method Not Allowed'], 405);
}

try {
    $payload = reg_json_input();

    $fullName = reg_str($payload['full_name'] ?? null);
    $email = reg_str($payload['email'] ?? null);
    $phone = reg_str($payload['phone'] ?? null);
    $organization = reg_str($payload['organization'] ?? null);
    $position = reg_str($payload['position'] ?? null);
    $category = reg_str($payload['category'] ?? null);
    $orgType = reg_str($payload['org_type'] ?? null);
    $isPaid = reg_normalize_bool($payload['is_paid'] ?? false, false);
    $paymentType = reg_str($payload['payment_type'] ?? null);
    $inn = reg_str($payload['inn'] ?? null);
    $wantsPartner = reg_normalize_bool($payload['wants_partner'] ?? false, false);
    $plansReport = reg_normalize_bool($payload['plans_report'] ?? false, false);
    $reportTopic = reg_str($payload['report_topic'] ?? null);
    $smartToken = reg_str($payload['smart_token'] ?? ($payload['smart-token'] ?? null));

    if ($fullName === null) reg_response_json(['error' => 'Поле full_name обязательно'], 400);
    if ($email === null || !reg_email_valid($email)) reg_response_json(['error' => 'Поле email заполнено некорректно'], 400);
    if ($phone === null) reg_response_json(['error' => 'Поле phone обязательно'], 400);
    if ($organization === null) reg_response_json(['error' => 'Поле organization обязательно'], 400);
    if ($position === null) reg_response_json(['error' => 'Поле position обязательно'], 400);
    if (!reg_allowed_value($category, ['education', 'federal', 'other'])) reg_response_json(['error' => 'Поле category заполнено некорректно'], 400);

    $smartCaptchaSiteKey = trim(function_exists('app_env') ? app_env('SMARTCAPTCHA_SITEKEY') : (string)(getenv('SMARTCAPTCHA_SITEKEY') ?: ''));
    $smartCaptchaSecret = trim(function_exists('app_env') ? app_env('SMARTCAPTCHA_SECRET') : (string)(getenv('SMARTCAPTCHA_SECRET') ?: ''));
    if ($smartCaptchaSiteKey !== '' && $smartCaptchaSecret !== '') {
        if ($smartToken === null || $smartToken === '') {
            reg_response_json(['error' => 'Не пройдена проверка SmartCaptcha'], 400);
        }
        $clientIp = (string)($_SERVER['REMOTE_ADDR'] ?? '');
        if (!smartcaptcha_validate($smartToken, $smartCaptchaSecret, $clientIp)) {
            reg_response_json(['error' => 'Проверка SmartCaptcha не пройдена'], 400);
        }
    }

    if ($category === 'other') {
        if (!reg_allowed_value($orgType, ['apkits_azi', 'dpo', 'other'])) {
            reg_response_json(['error' => 'Поле org_type обязательно для category=other'], 400);
        }
        if (!reg_allowed_value($paymentType, ['company', 'self'])) {
            reg_response_json(['error' => 'Поле payment_type обязательно для category=other'], 400);
        }
        if ($inn === null) {
            reg_response_json(['error' => 'Поле inn обязательно для category=other'], 400);
        }

        $digits = preg_replace('/\D/', '', $inn);
        $requiredLen = $paymentType === 'company' ? 10 : 12;
        if (!is_string($digits) || strlen($digits) !== $requiredLen) {
            reg_response_json(['error' => 'Поле inn должно содержать ' . $requiredLen . ' цифр'], 400);
        }
        $inn = $digits;
        $plansReport = false;
        $reportTopic = null;
    } else {
        $orgType = null;
        $isPaid = false;
        $paymentType = null;
        $inn = null;
        $wantsPartner = false;
        if ($plansReport && ($reportTopic === null || strlen($reportTopic) < 3)) {
            reg_response_json(['error' => 'Поле report_topic обязательно при выборе доклада'], 400);
        }
    }

    $pdo = reg_db();
    try {
        // On some shared hostings CREATE privileges may be restricted.
        // If table already exists, registration can still work without this step.
        reg_ensure_schema($pdo);
    } catch (Throwable $schemaError) {
        error_log('[register] schema ensure failed: ' . $schemaError->getMessage());
    }

    $stmt = $pdo->prepare(
        'INSERT INTO registrations
        (full_name, email, phone, organization, position, category, org_type, is_paid, payment_type, inn, wants_partner, plans_report, report_topic, status)
        VALUES
        (:full_name, :email, :phone, :organization, :position, :category, :org_type, :is_paid, :payment_type, :inn, :wants_partner, :plans_report, :report_topic, :status)'
    );

    $stmt->execute([
        ':full_name' => $fullName,
        ':email' => $email,
        ':phone' => $phone,
        ':organization' => $organization,
        ':position' => $position,
        ':category' => $category,
        ':org_type' => $orgType,
        ':is_paid' => $isPaid ? 1 : 0,
        ':payment_type' => $paymentType,
        ':inn' => $inn,
        ':wants_partner' => $wantsPartner ? 1 : 0,
        ':plans_report' => $plansReport ? 1 : 0,
        ':report_topic' => $reportTopic,
        ':status' => 'pending',
    ]);

    $id = (int)$pdo->lastInsertId();
    $applicationNumber = 10336000 + $id;

    $update = $pdo->prepare('UPDATE registrations SET application_number = :application_number WHERE id = :id');
    $update->execute([
        ':application_number' => $applicationNumber,
        ':id' => $id,
    ]);

    try {
        reg_send_registration_emails([
            'application_number' => $applicationNumber,
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'organization' => $organization,
            'position' => $position,
            'category' => $category,
            'org_type' => $orgType,
            'is_paid' => $isPaid,
            'payment_type' => $paymentType,
            'inn' => $inn,
            'wants_partner' => $wantsPartner,
            'plans_report' => $plansReport,
            'report_topic' => $reportTopic,
            'status' => 'pending',
        ]);
    } catch (Throwable $mailError) {
        error_log('[register] mail send failed for application ' . $applicationNumber . ': ' . $mailError->getMessage());
    }

    reg_response_json(['application_number' => $applicationNumber], 201);
} catch (Throwable $e) {
    $errorId = bin2hex(random_bytes(4));
    error_log('[register][' . $errorId . '] fatal: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());

    $debug = function_exists('app_env') ? app_env('APP_DEBUG') : (getenv('APP_DEBUG') ?: '');
    $message = 'Внутренняя ошибка сервера';
    if ($debug === '1') {
        $message = 'Внутренняя ошибка сервера: ' . $e->getMessage();
    }

    reg_response_json(['error' => $message, 'error_id' => $errorId], 500);
}
