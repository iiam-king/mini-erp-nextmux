<?php
// controllers/tachesController.php

require_once __DIR__ . '/../models/Tache.php';
require_once __DIR__ . '/../models/Projet.php';

class TachesController {
    private Tache  $model;
    private Projet $projetModel;

    public function __construct() {
        $this->model       = new Tache();
        $this->projetModel = new Projet();
    }

    public function index(): void {
        $taches     = $this->model->getAll();
        $page_title = 'Toutes les tâches';
        $flash      = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        require_once __DIR__ . '/../views/taches/index.php';
    }

    public function create(): void {
        $projets = $this->projetModel->getAll();
        $errors  = [];
        $data    = ['projet_id' => $_GET['projet_id'] ?? ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data   = $this->extractPostData();
            $errors = $this->validate($data);

            if (empty($errors)) {
                $this->model->create($data);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Tâche créée.'];
                $back = $data['projet_id']
                    ? "index.php?module=projets&action=show&id={$data['projet_id']}"
                    : "index.php?module=taches&action=index";
                header("Location: $back");
                exit;
            }
        }

        $page_title = 'Nouvelle tâche';
        require_once __DIR__ . '/../views/taches/form.php';
    }

    public function edit(): void {
        $id    = (int)($_GET['id'] ?? 0);
        $tache = $this->model->getById($id);
        if (!$tache) { echo "<div class='alert alert-danger m-4'>Tâche introuvable.</div>"; return; }

        $projets = $this->projetModel->getAll();
        $errors  = [];
        $data    = $tache;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data   = $this->extractPostData();
            $errors = $this->validate($data);

            if (empty($errors)) {
                $this->model->update($id, $data);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Tâche mise à jour.'];
                $projetId = $_GET['projet_id'] ?? $data['projet_id'];
                header("Location: index.php?module=projets&action=show&id=$projetId");
                exit;
            }
        }

        $page_title = 'Modifier tâche';
        require_once __DIR__ . '/../views/taches/form.php';
    }

    public function delete(): void {
        $id       = (int)($_GET['id'] ?? 0);
        $projetId = (int)($_GET['projet_id'] ?? 0);
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->delete($id);
            $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Tâche supprimée.'];
        }
        $back = $projetId
            ? "index.php?module=projets&action=show&id=$projetId"
            : "index.php?module=taches&action=index";
        header("Location: $back");
        exit;
    }

    private function extractPostData(): array {
        return [
            'projet_id'     => (int)($_POST['projet_id'] ?? 0),
            'titre'         => trim($_POST['titre'] ?? ''),
            'description'   => trim($_POST['description'] ?? ''),
            'priorite'      => $_POST['priorite'] ?? 'normale',
            'statut'        => $_POST['statut'] ?? 'a_faire',
            'date_echeance' => $_POST['date_echeance'] ?? '',
        ];
    }

    private function validate(array $data): array {
        $errors = [];
        if (empty($data['titre']))     $errors[] = 'Le titre est obligatoire.';
        if (empty($data['projet_id'])) $errors[] = 'Veuillez sélectionner un projet.';
        return $errors;
    }
}
