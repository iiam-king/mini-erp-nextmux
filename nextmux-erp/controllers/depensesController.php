<?php
// controllers/depensesController.php

require_once __DIR__ . '/../models/Depense.php';
require_once __DIR__ . '/../models/Projet.php';

class DepensesController {
    private Depense $model;
    private Projet  $projetModel;

    public function __construct() {
        $this->model       = new Depense();
        $this->projetModel = new Projet();
    }

    public function index(): void {
        $depenses   = $this->model->getAll();
        $page_title = 'Dépenses';
        $flash      = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        require_once __DIR__ . '/../views/depenses/index.php';
    }

    public function create(): void {
        $projets = $this->projetModel->getAll();
        $errors  = [];
        $data    = ['projet_id' => $_GET['projet_id'] ?? '', 'date_depense' => date('Y-m-d')];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data   = $this->extractPostData();
            $errors = $this->validate($data);

            if (empty($errors)) {
                $this->model->create($data);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Dépense enregistrée.'];
                header('Location: index.php?module=depenses&action=index');
                exit;
            }
        }

        $page_title = 'Nouvelle dépense';
        require_once __DIR__ . '/../views/depenses/form.php';
    }

    public function edit(): void {
        $id      = (int)($_GET['id'] ?? 0);
        $depense = $this->model->getById($id);
        if (!$depense) { echo "<div class='alert alert-danger m-4'>Dépense introuvable.</div>"; return; }

        $projets = $this->projetModel->getAll();
        $errors  = [];
        $data    = $depense;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data   = $this->extractPostData();
            $errors = $this->validate($data);

            if (empty($errors)) {
                $this->model->update($id, $data);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Dépense mise à jour.'];
                header('Location: index.php?module=depenses&action=index');
                exit;
            }
        }

        $page_title = 'Modifier dépense';
        require_once __DIR__ . '/../views/depenses/form.php';
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->delete($id);
            $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Dépense supprimée.'];
        }
        header('Location: index.php?module=depenses&action=index');
        exit;
    }

    private function extractPostData(): array {
        return [
            'projet_id'    => $_POST['projet_id'] ?: null,
            'libelle'      => trim($_POST['libelle'] ?? ''),
            'montant'      => (float)($_POST['montant'] ?? 0),
            'categorie'    => trim($_POST['categorie'] ?? ''),
            'date_depense' => $_POST['date_depense'] ?? date('Y-m-d'),
            'notes'        => trim($_POST['notes'] ?? ''),
        ];
    }

    private function validate(array $data): array {
        $errors = [];
        if (empty($data['libelle']))  $errors[] = 'Le libellé est obligatoire.';
        if ($data['montant'] <= 0)    $errors[] = 'Le montant doit être supérieur à 0.';
        if (empty($data['date_depense'])) $errors[] = 'La date est obligatoire.';
        return $errors;
    }
}
