<?php
// models/Tache.php

class Tache {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getByProjet(int $projetId): array {
        $stmt = $this->db->prepare("
            SELECT * FROM taches WHERE projet_id = ?
            ORDER BY FIELD(priorite,'urgente','haute','normale','basse'), date_echeance
        ");
        $stmt->execute([$projetId]);
        return $stmt->fetchAll();
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT t.*, p.nom AS projet_nom
            FROM taches t
            JOIN projets p ON t.projet_id = p.id
            ORDER BY FIELD(t.priorite,'urgente','haute','normale','basse'), t.date_echeance
        ");
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM taches WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare("
            INSERT INTO taches (projet_id, titre, description, priorite, statut, date_echeance)
            VALUES (:projet_id, :titre, :description, :priorite, :statut, :date_echeance)
        ");
        return $stmt->execute([
            ':projet_id'     => $data['projet_id'],
            ':titre'         => $data['titre'],
            ':description'   => $data['description'] ?? null,
            ':priorite'      => $data['priorite'] ?? 'normale',
            ':statut'        => $data['statut'] ?? 'a_faire',
            ':date_echeance' => $data['date_echeance'] ?: null,
        ]);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE taches SET projet_id=:projet_id, titre=:titre, description=:description,
                priorite=:priorite, statut=:statut, date_echeance=:date_echeance
            WHERE id=:id
        ");
        return $stmt->execute([
            ':id'            => $id,
            ':projet_id'     => $data['projet_id'],
            ':titre'         => $data['titre'],
            ':description'   => $data['description'] ?? null,
            ':priorite'      => $data['priorite'],
            ':statut'        => $data['statut'],
            ':date_echeance' => $data['date_echeance'] ?: null,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM taches WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateStatut(int $id, string $statut): bool {
        $stmt = $this->db->prepare("UPDATE taches SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }
}
