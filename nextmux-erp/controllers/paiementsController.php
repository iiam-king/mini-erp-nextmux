<?php
// controllers/paiementsController.php

require_once __DIR__ . '/../models/Paiement.php';
require_once __DIR__ . '/../models/Facture.php';

class PaiementsController {
    private Paiement $model;
    private Facture  $factureModel;

    public function __construct() {
        $this->model        = new Paiement();
        $this->factureModel = new Facture();
    }

    public function index(): void {
        $paiements  = $this->model->getAll();
        $page_title = 'Paiements';
        $flash      = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        require_once __DIR__ . '/../views/paiements/index.php';
    }

    public function delete(): void {
        $id        = (int)($_GET['id'] ?? 0);
        $factureId = (int)($_GET['facture_id'] ?? 0);
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->delete($id);
            if ($factureId) $this->factureModel->updateStatutApresPaiement($factureId);
            $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Paiement supprimé.'];
        }
        $back = $factureId
            ? "index.php?module=factures&action=show&id=$factureId"
            : "index.php?module=paiements&action=index";
        header("Location: $back");
        exit;
    }
}
