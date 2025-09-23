<?php
/**
 * Registration Handler
 */

// Start session
session_start();

// Include required files
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../src/Services/EmailService.php';

// Get form data
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$date_of_birth = $_POST['date_of_birth'] ?? null;
$address = trim($_POST['address'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';
$terms = isset($_POST['terms']);

// Validate input
$errors = [];

if (empty($first_name)) $errors[] = 'Fornavn er påkrevd';
if (empty($last_name)) $errors[] = 'Etternavn er påkrevd';
if (empty($username)) $errors[] = 'Brukernavn er påkrevd';
if (empty($email)) $errors[] = 'E-post er påkrevd';
if (empty($password)) $errors[] = 'Passord er påkrevd';
if (!$terms) $errors[] = 'Du må godta brukervilkårene';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Ugyldig e-postadresse';
}

if (strlen($password) < 8) {
    $errors[] = 'Passord må være minst 8 tegn';
}

if ($password !== $password_confirm) {
    $errors[] = 'Passordene stemmer ikke overens';
}

// Check if user already exists
$existingUser = $database->queryOne(
    "SELECT id FROM users WHERE email = ? OR username = ?",
    [$email, $username]
);

if ($existingUser) {
    $errors[] = 'Bruker med denne e-post eller brukernavn finnes allerede';
}

// If there are errors, redirect back
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header('Location: /register');
    exit;
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert user
try {
    $database->execute(
        "INSERT INTO users (username, email, password_hash, first_name, last_name, phone, date_of_birth, address, role, email_verified) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'participant', FALSE)",
        [
            $username,
            $email,
            $password_hash,
            $first_name,
            $last_name,
            $phone ?: null,
            $date_of_birth ?: null,
            $address ?: null
        ]
    );
    
    // Get the new user ID
    $userId = $database->lastInsertId();
    
    // Initialize email service
    $emailService = new EmailService($app_config, $database);
    
    // Send verification email
    $emailSent = $emailService->sendVerificationCode($userId, $email, $first_name);
    
    if ($emailSent) {
        $_SESSION['success'] = 'Registrering vellykket! Sjekk din e-post for bekreftelseskode.';
        header('Location: /verify-email');
    } else {
        $_SESSION['warning'] = 'Registrering vellykket, men e-post kunne ikke sendes. Kontakt administrator.';
        header('Location: /login');
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = 'En feil oppstod under registrering. Prøv igjen.';
    $_SESSION['form_data'] = $_POST;
    header('Location: /register');
}
exit;
