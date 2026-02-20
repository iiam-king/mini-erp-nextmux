<?php
// models/Paiement.php

class Paiement {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT pa.*, f.numero AS facture_numero, f.montant_ttc,
                   p.nom AS projet_nom, c.nom AS client_nom
            FROM paiements pa
            JOIN factures f ON pa.facture_id = f.id
            JOIN projets p ON f.projet_id = p.id
            JOIN clients c ON p.client_id = c.id
            ORDER BY pa.date_paiement DESC
        ");
        return $stmt->fetchAll();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare("
            INSERT INTO paiements (facture_id, montant, date_paiement, mode, reference, notes)
            VALUES (:facture_id, :montant, :date_paiement, :mode, :reference, :notes)
        ");
        return $stmt->execute([
            ':facture_id'    => $data['facture_id'],
            ':montant'       => $data['montant'],
            ':date_paiement' => $data['date_paiement'],
            ':mode'          => $data['mode'] ?? 'virement',
            ':reference'     => $data['reference'] ?? null,
            ':notes'         => $data['notes'] ?? null,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM paiements WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
