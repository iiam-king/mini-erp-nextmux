<?php
// models/Client.php

class Client {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT c.*, COUNT(p.id) AS nb_projets
            FROM clients c
            LEFT JOIN projets p ON p.client_id = c.id
            GROUP BY c.id
            ORDER BY c.nom
        ");
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare("
            INSERT INTO clients (nom, email, telephone, adresse)
            VALUES (:nom, :email, :telephone, :adresse)
        ");
        return $stmt->execute([
            ':nom'       => $data['nom'],
            ':email'     => $data['email'] ?? null,
            ':telephone' => $data['telephone'] ?? null,
            ':adresse'   => $data['adresse'] ?? null,
        ]);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE clients SET nom = :nom, email = :email,
                telephone = :telephone, adresse = :adresse
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id'        => $id,
            ':nom'       => $data['nom'],
            ':email'     => $data['email'] ?? null,
            ':telephone' => $data['telephone'] ?? null,
            ':adresse'   => $data['adresse'] ?? null,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM clients WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getProjets(int $clientId): array {
        $stmt = $this->db->prepare("SELECT * FROM projets WHERE client_id = ? ORDER BY created_at DESC");
        $stmt->execute([$clientId]);
        return $stmt->fetchAll();
    }
}
