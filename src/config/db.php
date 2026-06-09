<?php
/**
 * Returns a shared PDO connection, configured from environment variables.
 *
 * The MySQL container can take a few seconds to accept connections after the
 * web container starts, so we retry briefly before giving up.
 */
declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $name = getenv('DB_NAME') ?: 'delphianlogic';
    $user = getenv('DB_USER') ?: 'app';
    $pass = getenv('DB_PASS') ?: 'app_secret';
    $dsn  = "mysql:host={$host};dbname={$name};charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $lastError = null;
    for ($attempt = 1; $attempt <= 15; $attempt++) {
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        } catch (PDOException $e) {
            $lastError = $e;
            sleep(2); // wait for the db container to become ready
        }
    }

    http_response_code(503);
    throw new RuntimeException(
        'Could not connect to the database after several attempts: '
        . ($lastError ? $lastError->getMessage() : 'unknown error')
    );
}
