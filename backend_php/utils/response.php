<?php
function jsonResponse(int $statusCode, bool $success, string $message, $data = null, array $errors = []): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');

    $response = [
        'success' => $success,
        'message' => $message,
    ];

    if ($success) {
        $response['data'] = $data ?? new stdClass();
    } else {
        $response['errors'] = empty($errors) ? new stdClass() : $errors;
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

function successResponse(string $message, $data = null, int $statusCode = 200): void
{
    jsonResponse($statusCode, true, $message, $data);
}

function errorResponse(string $message, array $errors = [], int $statusCode = 400): void
{
    jsonResponse($statusCode, false, $message, null, $errors);
}

function paginatedResponse(string $message, array $items, int $page, int $limit, int $total): void
{
    successResponse($message, [
        'items' => $items,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total
        ]
    ]);
}