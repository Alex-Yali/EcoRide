<?php
// csrf.php

// Démarre la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Génère un token CSRF unique si nécessaire
if (!function_exists('generate_csrf_token')) {
    function generate_csrf_token(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

// Vérifie la validité d'un token CSRF
if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token(?string $token): bool {
        return isset($_SESSION['csrf_token']) && $token && hash_equals($_SESSION['csrf_token'], $token);
    }
}

// Génère un champ HTML caché à mettre dans tes formulaires
if (!function_exists('csrf_input')) {
    function csrf_input(): string {
        $token = generate_csrf_token();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }
}
