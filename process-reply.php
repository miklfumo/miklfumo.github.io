<?php
require_once __DIR__ . '/includes/registration_service.php';

set_security_headers();

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method !== 'POST' && $method !== 'GET') {
    reg_response_json(['error' => 'Method Not Allowed'], 405);
}

try {
    $payload = $method === 'GET' ? $_GET : reg_json_input();
    $applicationNumber = (int)($payload['application_number'] ?? 0);
    $decision = reg_str($payload['decision'] ?? null);

    if ($applicationNumber <= 0) {
        reg_response_json(['error' => 'Поле application_number заполнено некорректно'], 400);
    }
    if (!in_array($decision, ['yes', 'no'], true)) {
        reg_response_json(['error' => 'Поле decision должно быть yes или no'], 400);
    }

    $pdo = reg_db();
    try {
        reg_ensure_schema($pdo);
    } catch (Throwable $schemaError) {
        error_log('[process-reply] schema ensure failed: ' . $schemaError->getMessage());
    }

    $select = $pdo->prepare(
        'SELECT application_number, email, full_name, organization, category, status
         FROM registrations
         WHERE application_number = :application_number
         LIMIT 1'
    );
    $select->execute([':application_number' => $applicationNumber]);
    $row = $select->fetch();

    if (!$row) {
        if ($method === 'GET') {
            http_response_code(404);
            echo 'Заявка не найдена';
            exit;
        }
        reg_response_json(['error' => 'Заявка не найдена'], 404);
    }

    $operator = reg_operator_for_category((string)$row['category']);
    if ($method === 'GET') {
        $ts = (int)($payload['ts'] ?? 0);
        $sig = (string)($payload['sig'] ?? '');
        if ($sig === '' || !reg_is_action_signature_valid($applicationNumber, (string)$decision, $operator, $ts, $sig)) {
            http_response_code(403);
            echo 'Недействительная или просроченная ссылка подтверждения.';
            exit;
        }
    }

    $status = $decision === 'yes' ? 'approved' : 'rejected';
    $previousStatus = (string)($row['status'] ?? 'pending');

    if ($previousStatus !== $status) {
        $update = $pdo->prepare(
            'UPDATE registrations SET status = :status WHERE application_number = :application_number'
        );
        $update->execute([
            ':status' => $status,
            ':application_number' => $applicationNumber,
        ]);

        reg_send_decision_email([
            'application_number' => $applicationNumber,
            'email' => $row['email'],
            'category' => $row['category'],
            'status' => $status,
        ]);
    }

    if ($method === 'GET') {
        $title = $status === 'approved' ? 'Заявка одобрена' : 'Заявка отклонена';
        $subtitle = $previousStatus === $status
            ? 'Статус уже был установлен ранее.'
            : 'Статус успешно обновлен и письмо отправлено заявителю.';

        echo '<!doctype html><html lang="ru"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</title></head>'
            . '<body style="font-family:Arial,sans-serif;background:#0f1424;color:#e7edff;margin:0;padding:24px;">'
            . '<div style="max-width:720px;margin:40px auto;border:1px solid #2a3553;border-radius:14px;padding:24px;background:#151d32;">'
            . '<h1 style="margin:0 0 10px;font-size:24px;">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h1>'
            . '<p style="margin:0 0 12px;color:#b8c7e6;">' . htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8') . '</p>'
            . '<p style="margin:0;color:#d9e3fb;">Номер заявки: <strong>' . (int)$applicationNumber . '</strong></p>'
            . '</div></body></html>';
        exit;
    }

    reg_response_json([
        'application_number' => $applicationNumber,
        'status' => $status,
    ]);
} catch (Throwable $e) {
    error_log('[process-reply] fatal: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
    reg_response_json(['error' => 'Внутренняя ошибка сервера'], 500);
}
