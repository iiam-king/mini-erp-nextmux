<?php
// controllers/clientsController.php

require_once __DIR__ . '/../models/Client.php';

class ClientsController {
    private Client $model;

    public function __construct() {
        $this->model = new Client();
    }

    // GET index.php?module=clients&action=index
    public function index(): void {
        $clients    = $this->model->getAll();
        $page_title = 'Clients';
        $flash      = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        require_once __DIR__ . '/../views/clients/index.php';
    }

    // GET index.php?module=clients&action=show&id=X
    public function show(): void {
        $id     = (int)($_GET['id'] ?? 0);
        $client = $this->model->getById($id);
        if (!$client) { $this->notFound(); return; }

        $projets    = $this->model->getProjets($id);
        $page_title = 'Client – ' . $client['nom'];
        require_once __DIR__ . '/../views/clients/show.php';
    }

    // GET  index.php?module=clients&action=create
    // POST index.php?module=clients&action=create
    public function create(): void {
        $errors = [];
        $data   = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom'       => trim($_POST['nom'] ?? ''),
                'email'     => trim($_POST['email'] ?? ''),
                'telephone' => trim($_POST['telephone'] ?? ''),
                'adresse'   => trim($_POST['adresse'] ?? ''),
            ];

            if (empty($data['nom'])) $errors[] = 'Le nom est obligatoire.';

            if (empty($errors)) {
                $this->model->create($data);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Client créé avec succès.'];
                header('Location: index.php?module=clients&action=index');
                exit;
            }
        }

        $page_title = 'Nouveau client';
        require_once __DIR__ . '/../views/clients/form.php';
    }

    // GET  index.php?module=clients&action=edit&id=X
    // POST index.php?module=clients&action=edit&id=X
    public function edit(): void {
        $id     = (int)($_GET['id'] ?? 0);
        $client = $this->model->getById($id);
        if (!$client) { $this->notFound(); return; }

        $errors = [];
        $data   = $client;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom'       => trim($_POST['nom'] ?? ''),
                'email'     => trim($_POST['email'] ?? ''),
                'telephone' => trim($_POST['telephone'] ?? ''),
                'adresse'   => trim($_POST['adresse'] ?? ''),
            ];

            if (empty($data['nom'])) $errors[] = 'Le nom est obligatoire.';

            if (empty($errors)) {
                $this->model->update($id, $data);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Client mis à jour.'];
                header('Location: index.php?module=clients&action=index');
                exit;
            }
        }

        $page_title = 'Modifier – ' . $client['nom'];
        require_once __DIR__ . '/../views/clients/form.php';
    }

    // POST index.php?module=clients&action=delete&id=X
    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->delete($id);
            $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Client supprimé.'];
        }
        header('Location: index.php?module=clients&action=index');
        exit;
    }

    private function notFound(): void {
        http_response_code(404);
        $page_title = 'Introuvable';
        echo "<div class='alert alert-danger m-4'>Client introuvable.</div>";
    }
}
