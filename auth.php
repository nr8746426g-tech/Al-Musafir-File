<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** The logged-in user's {id, username, role}, or null if not logged in. */
function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

/** Redirects to the login page if no one is logged in. */
function require_login(): void
{
    if (!current_user()) {
        header('Location: login.php');
        exit;
    }
}

/** Same as require_login(), but also requires the admin role. */
function require_admin(): void
{
    require_login();
    if (current_user()['role'] !== 'admin') {
        http_response_code(403);
        exit('هذه الصفحة للأدمن فقط / This page is for admins only.');
    }
}

function is_admin(): bool
{
    $user = current_user();
    return $user !== null && $user['role'] === 'admin';
}
