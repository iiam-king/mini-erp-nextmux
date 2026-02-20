<?php
// controllers/projetsController.php

require_once __DIR__ . '/../models/Projet.php';
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../models/Tache.php';

class ProjetsController {
    private Projet $model;
    private Client $clientModel;

    public function __construct() {
        $this->model       = new Projet();
        $this->clientModel = new Client();
    }

    public function index(): void {
        $projets    = $this->model->getAll();
        $page_title = 'Projets';
        $flash      = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        require_once __DIR__ . '/../views/projets/index.php';
    }

    public function show(): void {
        $id     = (int)($_GET['id'] ?? 0);
        $projet = $this->model->getById($id);
        if (!$projet) { echo "<div class='alert alert-danger m-4'>Projet introuvable.</div>"; return; }

        $tacheModel = new Tache();
        $taches     = $tacheModel->getByProjet($id);
        $finances   = $this->model->getFinances($id);
        $page_title = $projet['nom'];
        require_once __DIR__ . '/../views/projets/show.php';
    }

    public function create(): void {
        $clients = $this->clientModel->getAll();
        $errors  = [];
        $data    = ['client_id' => $_GET['client_id'] ?? ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->extractPostData();
            $errors = $this->validate($data);

            if (empty($errors)) {
                $this->model->create($data);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Projet créé avec succès.'];
                header('Location: index.php?module=projets&action=index');
                exit;
            }
        }

        $page_title = 'Nouveau projet';
        require_once __DIR__ . '/../views/projets/form.php';
    }

    public function edit(): void {
        $id     = (int)($_GET['id'] ?? 0);
        $projet = $this->model->getById($id);
        if (!$projet) { echo "<div class='alert alert-danger m-4'>Projet introuvable.</div>"; return; }

        $clients = $this->clientModel->getAll();
        $errors  = [];
        $data    = $projet;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data   = $this->extractPostData();
            $errors = $this->validate($data);

            if (empty($errors)) {
                $this->model->update($id, $data);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Projet mis à jour.'];
                header('Location: index.php?module=projets&action=show&id=' . $id);
                exit;
            }
        }

        $page_title = 'Modifier – ' . $projet['nom'];
        require_once __DIR__ . '/../views/projets/form.php';
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->delete($id);
            $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Projet supprimé.'];
        }
        header('Location: index.php?module=projets&action=index');
        exit;
    }

    private function extractPostData(): array {
        return [
            'client_id'   => (int)($_POST['client_id'] ?? 0),
            'nom'         => trim($_POST['nom'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'date_debut'  => $_POST['date_debut'] ?? '',
            'date_fin'    => $_POST['date_fin'] ?? '',
            'statut'      => $_POST['statut'] ?? 'en_cours',
            'budget'      => (float)($_POST['budget'] ?? 0),
        ];
    }

    private function validate(array $data): array {
        $errors = [];
        if (empty($data['nom']))       $errors[] = 'Le nom du projet est obligatoire.';
        if (empty($data['client_id'])) $errors[] = 'Veuillez sélectionner un client.';
        return $errors;
    }
}
