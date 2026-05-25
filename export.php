<?php
require_once __DIR__ . '/includes/registration_service.php';

set_security_headers();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    reg_response_json(['error' => 'Method Not Allowed'], 405);
}

try {
    $requiredToken = reg_export_token();
    $providedToken = (string)($_GET['token'] ?? '');
    if ($requiredToken !== '' && !hash_equals($requiredToken, $providedToken)) {
        reg_response_json(['error' => 'Доступ запрещен'], 403);
    }

    $pdo = reg_db();
    try {
        reg_ensure_schema($pdo);
    } catch (Throwable $schemaError) {
        error_log('[export] schema ensure failed: ' . $schemaError->getMessage());
    }

    $stmt = $pdo->query(
        'SELECT application_number, full_name, email, organization, status
         FROM registrations
         ORDER BY id DESC'
    );
    $rows = $stmt->fetchAll();

    $items = array_map(static function (array $row): array {
        return [
            'application_number' => (int)$row['application_number'],
            'full_name' => $row['full_name'],
            'email' => $row['email'],
            'organization' => $row['organization'],
            'status' => $row['status'],
        ];
    }, $rows);

    $format = strtolower((string)($_GET['format'] ?? 'json'));
    if ($format === 'csv') {
        $header = ['номер заявки', 'ФИО', 'email', 'организация', 'статус'];
        $lines = [implode(',', array_map('reg_csv_escape', $header))];
        foreach ($items as $item) {
            $lines[] = implode(',', array_map('reg_csv_escape', [
                $item['application_number'],
                $item['full_name'],
                $item['email'],
                $item['organization'],
                $item['status'],
            ]));
        }

        $csv = "\xEF\xBB\xBF" . implode("\n", $lines);
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="registrations.csv"');
        echo $csv;
        exit;
    }

    reg_response_json($items);
} catch (Throwable $e) {
    error_log('[export] fatal: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
    reg_response_json(['error' => 'Внутренняя ошибка сервера'], 500);
}
