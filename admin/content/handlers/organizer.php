<?php
/**
 * Organizer Handler - Create, Update, Delete organizers
 */

// Start session
session_start();

// Include required files
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';

// Check if user is logged in and has access
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('login'));
    exit;
}

$userRoles = $_SESSION['roles'] ?? [];
$hasAccess = false;

foreach ($userRoles as $role) {
    if (in_array($role['role_name'], ['contentmanager', 'databasemanager'])) {
        $hasAccess = true;
        break;
    }
}

if (!$hasAccess) {
    $_SESSION['error'] = 'Du har ikke tilgang til denne funksjonen';
    header('Location: ' . base_url('admin'));
    exit;
}

// Handle delete request
if (isset($_GET['delete'])) {
    $organizer_id = intval($_GET['delete']);
    
    try {
        $database->execute(
            "UPDATE jaktfelt_organizers SET is_active = FALSE WHERE id = ?",
            [$organizer_id]
        );
        
        $_SESSION['success'] = 'Arrangør deaktivert';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Kunne ikke deaktivere arrangør: ' . $e->getMessage();
    }
    
    header('Location: ' . base_url('admin/content/organizers'));
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $organizer_id = $_POST['organizer_id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $organization_type = $_POST['organization_type'] ?? 'skytterlag';
    $contact_person = trim($_POST['contact_person'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    // Validate
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Navn er påkrevd';
    }
    
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Ugyldig e-postadresse';
    }
    
    if (!empty($errors)) {
        $_SESSION['error'] = implode(', ', $errors);
        header('Location: ' . base_url('admin/content/organizers'));
        exit;
    }
    
    try {
        if ($organizer_id) {
            // Update existing organizer
            $database->execute(
                "UPDATE jaktfelt_organizers 
                 SET name = ?, organization_type = ?, contact_person = ?, email = ?, 
                     phone = ?, city = ?, description = ?
                 WHERE id = ?",
                [$name, $organization_type, $contact_person, $email, $phone, $city, $description, $organizer_id]
            );
            
            $_SESSION['success'] = 'Arrangør oppdatert';
        } else {
            // Create new organizer
            $database->execute(
                "INSERT INTO jaktfelt_organizers 
                 (name, organization_type, contact_person, email, phone, city, description) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                [$name, $organization_type, $contact_person, $email, $phone, $city, $description]
            );
            
            $_SESSION['success'] = 'Ny arrangør opprettet';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Feil ved lagring: ' . $e->getMessage();
    }
    
    header('Location: ' . base_url('admin/content/organizers'));
    exit;
}

// If we get here, redirect back
header('Location: ' . base_url('admin/content/organizers'));
exit;

