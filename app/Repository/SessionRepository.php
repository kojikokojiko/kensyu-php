<?php
declare(strict_types=1);

namespace App\Repository;

class SessionRepository
{
    /**
     * Start the session if not already started.
     */
    public function startSession(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Destroy the session.
     */
    public function clearSession(): void
    {
        session_unset(); // セッション変数を全てクリア
        session_destroy(); // セッションを破棄
    }

    /**
     * Regenerate session ID.
     */
    public function regenerateSession(): void
    {
        session_regenerate_id(true); // セッションIDを再生成し、古いセッションを削除
    }

    /**
     * Set a session variable.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable.
     *
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Delete a session variable.
     *
     * @param string $key
     */
    public function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Get all session errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $_SESSION['errors'] ?? [];
    }

    /**
     * Set session errors.
     *
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $_SESSION['errors'] = $errors;
    }

    /**
     * Clear session errors.
     */
    public function clearErrors(): void
    {
        unset($_SESSION['errors']);
    }
}
