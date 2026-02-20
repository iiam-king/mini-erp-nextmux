<?php
// controllers/authController.php

class AuthController {

    public function login() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $mdp   = $_POST['mot_de_passe'] ?? '';

            if ($email && $mdp) {
                $db   = getDB();
                $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($mdp, $user['mot_de_passe'])) {
                    $_SESSION['user'] = [
                        'id'   => $user['id'],
                        'nom'  => $user['nom'],
                        'role' => $user['role'],
                    ];
                    header('Location: index.php?module=dashboard&action=index');
                    exit;
                } else {
                    $error = 'Email ou mot de passe incorrect.';
                }
            } else {
                $error = 'Veuillez remplir tous les champs.';
            }
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?module=auth&action=login');
        exit;
    }
}
