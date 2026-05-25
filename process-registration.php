<?php
/**
 * Registration form processing endpoint
 * Handles POST submissions with full server-side validation
 */

require_once __DIR__ . '/includes/security.php';
require_once __DIR__ . '/includes/functions.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Location: /');
    exit;
}

set_security_headers();

$errors = [];
$success = false;

// 0. Basic anti-bot / anti-DDoS checks
$clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
if (!ip_rate_limit_check('registration_ip', $clientIp, 12, 60)) {
    http_response_code(429);
    $errors[] = 'Слишком много запросов с вашего IP. Повторите попытку через минуту.';
}

$honeypot = trim($_POST['company_website'] ?? '');
if ($honeypot !== '') {
    $errors[] = 'Обнаружена подозрительная активность. Попробуйте отправить форму ещё раз.';
}

$formStartedAt = (int)($_POST['form_started_at'] ?? 0);
if ($formStartedAt > 0 && (time() - $formStartedAt) < 3) {
    $errors[] = 'Форма отправлена слишком быстро. Пожалуйста, проверьте данные и повторите.';
}

// 1. CSRF validation
$csrf = $_POST['csrf_token'] ?? '';
if (!csrf_validate($csrf)) {
    $errors[] = 'Ошибка безопасности. Пожалуйста, обновите страницу и попробуйте снова.';
}

// 2. Rate limiting
if (!rate_limit_check('registration', 3, 300)) {
    $errors[] = 'Слишком много попыток. Пожалуйста, подождите 5 минут.';
}

// 3. CAPTCHA validation: SmartCaptcha (if configured) + math fallback
$config = get_conference_config();
$smartCaptchaSecret = $config['smartcaptcha_secret'] ?? '';
$smartCaptchaToken = trim($_POST['smartcaptcha_token'] ?? '');

if (empty($errors) && $smartCaptchaSecret !== '' && $smartCaptchaToken !== '') {
    $userIp = $_SERVER['REMOTE_ADDR'] ?? '';
    if (!smartcaptcha_validate($smartCaptchaToken, $smartCaptchaSecret, $userIp)) {
        $errors[] = 'Не удалось подтвердить SmartCaptcha. Попробуйте ещё раз.';
    }
}

$captcha_input = $_POST['captcha'] ?? '';
if (empty($errors) && !captcha_validate($captcha_input)) {
    $errors[] = 'Неверный ответ на проверочный вопрос. Попробуйте ещё раз.';
}

// 4. Required field validation
$participant_type = $_POST['participant_type'] ?? 'education';
$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$organization = trim($_POST['organization'] ?? '');
$position = trim($_POST['position'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$payment_type = $_POST['payment_type'] ?? 'organization';
$inn = trim($_POST['inn'] ?? '');
$want_partner = isset($_POST['want_partner']);
$agreed_personal_data = isset($_POST['agreed_personal_data']);
$agreed_offer = isset($_POST['agreed_offer']);

if (empty($errors)) {
    if (empty($fullname) || mb_strlen($fullname) < 3) {
        $errors[] = 'Укажите ФИО (не менее 3 символов).';
    }
    if (!validate_email_strict($email)) {
        $errors[] = 'Укажите корректный адрес электронной почты.';
    }
    if (empty($organization)) {
        $errors[] = 'Укажите название организации.';
    }
    if (empty($position)) {
        $errors[] = 'Укажите должность.';
    }
    if (!validate_phone($phone)) {
        $errors[] = 'Укажите корректный номер телефона.';
    }
    if ($participant_type === 'other' && !empty($inn)) {
        $is_org = $payment_type === 'organization';
        if (!validate_inn($inn, $is_org)) {
            $errors[] = $is_org
                ? 'ИНН юридического лица должен содержать 10 цифр.'
                : 'ИНН физического лица должен содержать 12 цифр.';
        }
    }
    if (!$agreed_personal_data) {
        $errors[] = 'Необходимо дать согласие на обработку персональных данных.';
    }
    if (!$agreed_offer) {
        $errors[] = 'Необходимо принять условия договора-оферты.';
    }
}

// 5. Process valid submission
if (empty($errors)) {
    csrf_regenerate();

    // Here you would save to database, send email, etc.
    // For now, store in session to show success message
    $_SESSION['reg_success'] = true;
    $_SESSION['reg_name'] = $fullname;

    header('Location: /#registration');
    exit;
}

// If errors, store them in session and redirect back
$_SESSION['reg_errors'] = $errors;
$_SESSION['reg_old'] = $_POST;
header('Location: /#registration');
exit;
