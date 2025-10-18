<?php
session_start();

// --- 1. Protection CSRF ---
if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
    exit("Tentative d’accès non autorisée !");
}

