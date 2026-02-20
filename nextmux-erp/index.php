<?php
// index.php — Point d'entrée & routeur

session_start();
require_once __DIR__ . '/config/db.php';

// Routes publiques (sans auth)
$public_routes = ['auth/login', 'auth/logout'];

// Récupération du module et de l'action
$module = $_GET['module'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
$route  = "$module/$action";

// Protection : redirection si non connecté
if (!in_array($route, $public_routes) && empty($_SESSION['user'])) {
    header('Location: index.php?module=auth&action=login');
    exit;
}

// Chargement du contrôleur
$controller_file = __DIR__ . "/controllers/{$module}Controller.php";

if (file_exists($controller_file)) {
    require_once $controller_file;
    $class = ucfirst($module) . 'Controller';
    if (class_exists($class)) {
        $ctrl = new $class();
        if (method_exists($ctrl, $action)) {
            $ctrl->$action();
        } else {
            http_response_code(404);
            echo "<h2>Action '$action' introuvable dans $class.</h2>";
        }
    }
} else {
    http_response_code(404);
    echo "<h2>Module '$module' introuvable.</h2>";
}
