<?php

namespace Jaktfeltcup\Auth;

use Jaktfeltcup\Core\Database;

/**
 * Authentication Service
 * 
 * Handles user authentication, session management, and authorization.
 */
class AuthService
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Register a new user
     */
    public function register(array $userData): array
    {
        // Validate input
        $this->validateRegistrationData($userData);

        // Check if user already exists
        if ($this->userExists($userData['email'], $userData['username'])) {
            throw new \Exception('Bruker med denne e-post eller brukernavn finnes allerede');
        }

        // Hash password
        $passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);

        // Insert user
        $this->database->execute(
            "INSERT INTO jaktfelt_users (username, email, password_hash, first_name, last_name, phone, date_of_birth, address, role) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $userData['username'],
                $userData['email'],
                $passwordHash,
                $userData['first_name'],
                $userData['last_name'],
                $userData['phone'] ?? null,
                $userData['date_of_birth'] ?? null,
                $userData['address'] ?? null,
                $userData['role'] ?? 'participant'
            ]
        );

        return $this->getUserById($this->database->lastInsertId());
    }

    /**
     * Authenticate user login
     */
    public function login(string $email, string $password): array
    {
        $user = $this->database->queryOne(
            "SELECT * FROM jaktfelt_users WHERE email = ? AND is_active = 1",
            [$email]
        );

        if (!$user || !password_verify($password, $user['password_hash'])) {
            throw new \Exception('Ugyldig e-post eller passord');
        }

        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

        return $user;
    }

    /**
     * Logout user
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        session_destroy();
    }

    /**
     * Check if user is authenticated
     */
    public function isAuthenticated(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user_id']);
    }

    /**
     * Get current authenticated user
     */
    public function getCurrentUser(): ?array
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        return $this->getUserById($_SESSION['user_id']);
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        if (!$this->isAuthenticated()) {
            return false;
        }

        return $_SESSION['user_role'] === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is organizer
     */
    public function isOrganizer(): bool
    {
        return $this->hasRole('organizer');
    }

    /**
     * Check if user is participant
     */
    public function isParticipant(): bool
    {
        return $this->hasRole('participant');
    }

    /**
     * Get user by ID
     */
    public function getUserById(int $userId): ?array
    {
        return $this->database->queryOne(
            "SELECT id, username, email, first_name, last_name, phone, date_of_birth, address, role, is_active, email_verified, phone_verified, created_at 
             FROM jaktfelt_users WHERE id = ? AND is_active = 1",
            [$userId]
        );
    }

    /**
     * Update user profile
     */
    public function updateProfile(int $userId, array $data): bool
    {
        $fields = [];
        $values = [];

        if (isset($data['first_name'])) {
            $fields[] = 'first_name = ?';
            $values[] = $data['first_name'];
        }

        if (isset($data['last_name'])) {
            $fields[] = 'last_name = ?';
            $values[] = $data['last_name'];
        }

        if (isset($data['phone'])) {
            $fields[] = 'phone = ?';
            $values[] = $data['phone'];
        }

        if (isset($data['date_of_birth'])) {
            $fields[] = 'date_of_birth = ?';
            $values[] = $data['date_of_birth'];
        }

        if (isset($data['address'])) {
            $fields[] = 'address = ?';
            $values[] = $data['address'];
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $fields[] = 'password_hash = ?';
            $values[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (empty($fields)) {
            return false;
        }

        $values[] = $userId;

        return $this->database->execute(
            "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?",
            $values
        ) > 0;
    }

    /**
     * Check if user exists
     */
    private function userExists(string $email, string $username): bool
    {
        $user = $this->database->queryOne(
            "SELECT id FROM jaktfelt_users WHERE email = ? OR username = ?",
            [$email, $username]
        );

        return $user !== null;
    }

    /**
     * Validate registration data
     */
    private function validateRegistrationData(array $data): void
    {
        $required = ['username', 'email', 'password', 'first_name', 'last_name'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \Exception("Felt {$field} er påkrevd");
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Ugyldig e-post format');
        }

        if (strlen($data['password']) < 8) {
            throw new \Exception('Passord må være minst 8 tegn');
        }
    }
}