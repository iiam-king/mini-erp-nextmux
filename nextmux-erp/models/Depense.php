<?php
// models/Depense.php

class Depense {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT d.*, p.nom AS projet_nom
            FROM depenses d
            LEFT JOIN projets p ON d.projet_id = p.id
            ORDER BY d.date_depense DESC
        ");
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM depenses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare("
            INSERT INTO depenses (projet_id, libelle, montant, categorie, date_depense, notes)
            VALUES (:projet_id, :libelle, :montant, :categorie, :date_depense, :notes)
        ");
        return $stmt->execute([
            ':projet_id'    => $data['projet_id'] ?: null,
            ':libelle'      => $data['libelle'],
            ':montant'      => $data['montant'],
            ':categorie'    => $data['categorie'] ?? null,
            ':date_depense' => $data['date_depense'],
            ':notes'        => $data['notes'] ?? null,
        ]);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE depenses SET projet_id=:projet_id, libelle=:libelle, montant=:montant,
                categorie=:categorie, date_depense=:date_depense, notes=:notes
            WHERE id=:id
        ");
        return $stmt->execute([
            ':id'           => $id,
            ':projet_id'    => $data['projet_id'] ?: null,
            ':libelle'      => $data['libelle'],
            ':montant'      => $data['montant'],
            ':categorie'    => $data['categorie'] ?? null,
            ':date_depense' => $data['date_depense'],
            ':notes'        => $data['notes'] ?? null,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM depenses WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
