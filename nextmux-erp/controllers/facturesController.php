<?php
// controllers/facturesController.php

require_once __DIR__ . '/../models/Facture.php';
require_once __DIR__ . '/../models/Paiement.php';
require_once __DIR__ . '/../models/Projet.php';

class FacturesController {
    private Facture  $model;
    private Projet   $projetModel;

    public function __construct() {
        $this->model       = new Facture();
        $this->projetModel = new Projet();
    }

    public function index(): void {
        $projetId   = (int)($_GET['projet_id'] ?? 0) ?: null;
        $factures   = $this->model->getAll($projetId);
        $page_title = 'Factures';
        $flash      = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        require_once __DIR__ . '/../views/factures/index.php';
    }

    public function show(): void {
        $id      = (int)($_GET['id'] ?? 0);
        $facture = $this->model->getById($id);
        if (!$facture) { echo "<div class='alert alert-danger m-4'>Facture introuvable.</div>"; return; }

        $paiements  = $this->model->getPaiements($id);
        $total_paye = array_sum(array_column($paiements, 'montant'));
        $page_title = $facture['numero'];
        $flash      = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        require_once __DIR__ . '/../views/factures/show.php';
    }

    public function create(): void {
        $projets = $this->projetModel->getAll();
        $errors  = [];
        $data    = ['projet_id' => $_GET['projet_id'] ?? '', 'tva' => 20, 'date_emission' => date('Y-m-d')];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data   = $this->extractPostData();
            $errors = $this->validate($data);

            if (empty($errors)) {
                $id = $this->model->create($data);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Facture créée avec succès.'];
                header("Location: index.php?module=factures&action=show&id=$id");
                exit;
            }
        }

        $page_title = 'Nouvelle facture';
        require_once __DIR__ . '/../views/factures/form.php';
    }

    public function edit(): void {
        $id      = (int)($_GET['id'] ?? 0);
        $facture = $this->model->getById($id);
        if (!$facture) { echo "<div class='alert alert-danger m-4'>Facture introuvable.</div>"; return; }

        $projets = $this->projetModel->getAll();
        $errors  = [];
        $data    = $facture;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data   = $this->extractPostData();
            $errors = $this->validate($data);

            if (empty($errors)) {
                $this->model->update($id, $data);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Facture mise à jour.'];
                header("Location: index.php?module=factures&action=show&id=$id");
                exit;
            }
        }

        $page_title = 'Modifier – ' . $facture['numero'];
        require_once __DIR__ . '/../views/factures/form.php';
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->delete($id);
            $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Facture supprimée.'];
        }
        header('Location: index.php?module=factures&action=index');
        exit;
    }

    // POST ajout paiement depuis la page facture
    public function addPaiement(): void {
        $factureId = (int)($_POST['facture_id'] ?? 0);
        if ($factureId && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $paiementModel = new Paiement();
            $paiementModel->create([
                'facture_id'    => $factureId,
                'montant'       => (float)$_POST['montant'],
                'date_paiement' => $_POST['date_paiement'],
                'mode'          => $_POST['mode'] ?? 'virement',
                'reference'     => trim($_POST['reference'] ?? ''),
            ]);
            $this->model->updateStatutApresPaiement($factureId);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Paiement enregistré.'];
        }
        header("Location: index.php?module=factures&action=show&id=$factureId");
        exit;
    }

    private function extractPostData(): array {
        return [
            'projet_id'     => (int)($_POST['projet_id'] ?? 0),
            'montant_ht'    => (float)($_POST['montant_ht'] ?? 0),
            'tva'           => (float)($_POST['tva'] ?? 20),
            'date_emission' => $_POST['date_emission'] ?? date('Y-m-d'),
            'date_echeance' => $_POST['date_echeance'] ?? '',
            'statut'        => $_POST['statut'] ?? 'brouillon',
            'notes'         => trim($_POST['notes'] ?? ''),
        ];
    }

    private function validate(array $data): array {
        $errors = [];
        if (empty($data['projet_id']))    $errors[] = 'Veuillez sélectionner un projet.';
        if ($data['montant_ht'] <= 0)     $errors[] = 'Le montant HT doit être supérieur à 0.';
        if (empty($data['date_emission'])) $errors[] = "La date d'émission est obligatoire.";
        return $errors;
    }
}
