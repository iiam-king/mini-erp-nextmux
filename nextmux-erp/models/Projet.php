<?php
// models/Projet.php

class Projet {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll(?int $clientId = null): array {
        $sql = "SELECT p.*, c.nom AS client_nom,
                    COUNT(DISTINCT t.id) AS nb_taches,
                    COUNT(DISTINCT f.id) AS nb_factures
                FROM projets p
                JOIN clients c ON p.client_id = c.id
                LEFT JOIN taches t ON t.projet_id = p.id
                LEFT JOIN factures f ON f.projet_id = p.id";
        $params = [];
        if ($clientId) {
            $sql .= " WHERE p.client_id = ?";
            $params[] = $clientId;
        }
        $sql .= " GROUP BY p.id ORDER BY p.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("
            SELECT p.*, c.nom AS client_nom
            FROM projets p
            JOIN clients c ON p.client_id = c.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO projets (client_id, nom, description, date_debut, date_fin, statut, budget)
            VALUES (:client_id, :nom, :description, :date_debut, :date_fin, :statut, :budget)
        ");
        $stmt->execute([
            ':client_id'   => $data['client_id'],
            ':nom'         => $data['nom'],
            ':description' => $data['description'] ?? null,
            ':date_debut'  => $data['date_debut'] ?: null,
            ':date_fin'    => $data['date_fin'] ?: null,
            ':statut'      => $data['statut'] ?? 'en_cours',
            ':budget'      => $data['budget'] ?? 0,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE projets SET client_id=:client_id, nom=:nom, description=:description,
                date_debut=:date_debut, date_fin=:date_fin, statut=:statut, budget=:budget
            WHERE id=:id
        ");
        return $stmt->execute([
            ':id'          => $id,
            ':client_id'   => $data['client_id'],
            ':nom'         => $data['nom'],
            ':description' => $data['description'] ?? null,
            ':date_debut'  => $data['date_debut'] ?: null,
            ':date_fin'    => $data['date_fin'] ?: null,
            ':statut'      => $data['statut'],
            ':budget'      => $data['budget'] ?? 0,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM projets WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getFinances(int $projetId): array {
        $stmt = $this->db->prepare("
            SELECT
                COALESCE(SUM(f.montant_ttc), 0)          AS total_facture,
                COALESCE(SUM(p.montant), 0)               AS total_paye,
                COALESCE(SUM(d.montant), 0)               AS total_depenses
            FROM projets pr
            LEFT JOIN factures f  ON f.projet_id  = pr.id
            LEFT JOIN paiements p ON p.facture_id = f.id
            LEFT JOIN depenses d  ON d.projet_id  = pr.id
            WHERE pr.id = ?
        ");
        $stmt->execute([$projetId]);
        return $stmt->fetch();
    }
}
