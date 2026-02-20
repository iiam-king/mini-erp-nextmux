<?php
// controllers/dashboardController.php

class DashboardController {

    public function index(): void {
        $db = getDB();

        // Chiffres financiers globaux
        $fin = $db->query("
            SELECT
                COALESCE(SUM(f.montant_ttc), 0)    AS total_facture,
                COALESCE(SUM(p.montant), 0)         AS total_paye,
                COALESCE(SUM(d.montant), 0)         AS total_depenses
            FROM factures f
            LEFT JOIN paiements p ON p.facture_id = f.id
            CROSS JOIN (SELECT COALESCE(SUM(montant),0) AS montant FROM depenses) d
        ")->fetch();
        // Correction : requête séparée propre
        $total_facture  = (float)$db->query("SELECT COALESCE(SUM(montant_ttc),0) FROM factures")->fetchColumn();
        $total_paye     = (float)$db->query("SELECT COALESCE(SUM(montant),0) FROM paiements")->fetchColumn();
        $total_depenses = (float)$db->query("SELECT COALESCE(SUM(montant),0) FROM depenses")->fetchColumn();
        $marge          = $total_paye - $total_depenses;

        // Compteurs
        $nb_clients = (int)$db->query("SELECT COUNT(*) FROM clients")->fetchColumn();
        $nb_projets = (int)$db->query("SELECT COUNT(*) FROM projets WHERE statut = 'en_cours'")->fetchColumn();
        $nb_taches  = (int)$db->query("SELECT COUNT(*) FROM taches WHERE statut != 'termine'")->fetchColumn();
        $nb_factures_open = (int)$db->query("SELECT COUNT(*) FROM factures WHERE statut IN ('brouillon','envoyee')")->fetchColumn();

        // Projets récents
        $projets_recents = $db->query("
            SELECT p.*, c.nom AS client_nom,
                COUNT(DISTINCT t.id) AS nb_taches,
                COUNT(DISTINCT CASE WHEN t.statut='termine' THEN t.id END) AS nb_terminees
            FROM projets p
            JOIN clients c ON p.client_id = c.id
            LEFT JOIN taches t ON t.projet_id = p.id
            WHERE p.statut = 'en_cours'
            GROUP BY p.id
            ORDER BY p.created_at DESC LIMIT 5
        ")->fetchAll();

        // Factures non payées
        $factures_ouvertes = $db->query("
            SELECT f.*, p.nom AS projet_nom, c.nom AS client_nom,
                COALESCE(SUM(pa.montant),0) AS total_paye
            FROM factures f
            JOIN projets p ON f.projet_id = p.id
            JOIN clients c ON p.client_id = c.id
            LEFT JOIN paiements pa ON pa.facture_id = f.id
            WHERE f.statut IN ('brouillon','envoyee')
            GROUP BY f.id
            ORDER BY f.date_emission DESC LIMIT 5
        ")->fetchAll();

        $page_title = 'Dashboard';
        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}
