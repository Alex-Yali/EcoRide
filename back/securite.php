<?php
session_start();

// --- 1. Protection CSRF ---
if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['token']) {
    exit("Tentative d’accès non autorisée !");
}

// --- 2. Connexion à la base de données (PDO) ---
$dsn = 'mysql:host=localhost;dbname=ecoride;charset=utf8';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    exit("Erreur de connexion : " . $e->getMessage());
}

// --- 3. Nettoyage et validation des données ---
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Adresse email invalide.");
}

if (strlen($password) < 8) {
    exit("Le mot de passe doit contenir au moins 8 caractères.");
}

// --- 4. Vérifier si l’utilisateur existe déjà ---
$stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email");
$stmt->bindValue(':email', $email);
$stmt->execute();

if ($stmt->fetch()) {
    exit("Cet email est déjà inscrit.");
}

// --- 5. Hachage sécurisé du mot de passe ---
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// --- 6. Insertion dans la base ---
$stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, date_inscription) VALUES (:email, :mot_de_passe, NOW())");
$stmt->bindValue(':email', $email);
$stmt->bindValue(':mot_de_passe', $hashedPassword);

if ($stmt->execute()) {
    echo "Inscription réussie ! <a href='espace.php'>Connectez-vous ici</a>";
} else {
    echo "Erreur lors de l’inscription.";
}
