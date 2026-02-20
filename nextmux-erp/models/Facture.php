<?php
// models/Facture.php

class Facture {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll(?int $projetId = null): array {
        $sql = "SELECT f.*, p.nom AS projet_nom, c.nom AS client_nom,
                    COALESCE(SUM(pa.montant), 0) AS total_paye
                FROM factures f
                JOIN projets p ON f.projet_id = p.id
                JOIN clients c ON p.client_id = c.id
                LEFT JOIN paiements pa ON pa.facture_id = f.id";
        $params = [];
        if ($projetId) {
            $sql .= " WHERE f.projet_id = ?";
            $params[] = $projetId;
        }
        $sql .= " GROUP BY f.id ORDER BY f.date_emission DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("
            SELECT f.*, p.nom AS projet_nom, c.nom AS client_nom
            FROM factures f
            JOIN projets p ON f.projet_id = p.id
            JOIN clients c ON p.client_id = c.id
            WHERE f.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(array $data): int {
        $numero = $this->genererNumero();
        $ttc    = round($data['montant_ht'] * (1 + $data['tva'] / 100), 2);

        $stmt = $this->db->prepare("
            INSERT INTO factures (projet_id, numero, montant_ht, tva, montant_ttc, date_emission, date_echeance, statut, notes)
            VALUES (:projet_id, :numero, :montant_ht, :tva, :montant_ttc, :date_emission, :date_echeance, :statut, :notes)
        ");
        $stmt->execute([
            ':projet_id'     => $data['projet_id'],
            ':numero'        => $numero,
            ':montant_ht'    => $data['montant_ht'],
            ':tva'           => $data['tva'],
            ':montant_ttc'   => $ttc,
            ':date_emission' => $data['date_emission'],
            ':date_echeance' => $data['date_echeance'] ?: null,
            ':statut'        => $data['statut'] ?? 'brouillon',
            ':notes'         => $data['notes'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $ttc = round($data['montant_ht'] * (1 + $data['tva'] / 100), 2);
        $stmt = $this->db->prepare("
            UPDATE factures SET projet_id=:projet_id, montant_ht=:montant_ht, tva=:tva,
                montant_ttc=:montant_ttc, date_emission=:date_emission,
                date_echeance=:date_echeance, statut=:statut, notes=:notes
            WHERE id=:id
        ");
        return $stmt->execute([
            ':id'            => $id,
            ':projet_id'     => $data['projet_id'],
            ':montant_ht'    => $data['montant_ht'],
            ':tva'           => $data['tva'],
            ':montant_ttc'   => $ttc,
            ':date_emission' => $data['date_emission'],
            ':date_echeance' => $data['date_echeance'] ?: null,
            ':statut'        => $data['statut'],
            ':notes'         => $data['notes'] ?? null,
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM factures WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getPaiements(int $factureId): array {
        $stmt = $this->db->prepare("SELECT * FROM paiements WHERE facture_id = ? ORDER BY date_paiement DESC");
        $stmt->execute([$factureId]);
        return $stmt->fetchAll();
    }

    public function updateStatutApresPaiement(int $factureId): void {
        $stmt = $this->db->prepare("
            SELECT f.montant_ttc, COALESCE(SUM(p.montant), 0) AS total_paye
            FROM factures f
            LEFT JOIN paiements p ON p.facture_id = f.id
            WHERE f.id = ?
            GROUP BY f.id
        ");
        $stmt->execute([$factureId]);
        $row = $stmt->fetch();

        if ($row && $row['total_paye'] >= $row['montant_ttc']) {
            $this->db->prepare("UPDATE factures SET statut = 'payee' WHERE id = ?")->execute([$factureId]);
        } elseif ($row && $row['total_paye'] > 0) {
            $this->db->prepare("UPDATE factures SET statut = 'envoyee' WHERE id = ?")->execute([$factureId]);
        }
    }

    private function genererNumero(): string {
        $annee = date('Y');
        $stmt  = $this->db->prepare("SELECT COUNT(*) FROM factures WHERE YEAR(date_emission) = ?");
        $stmt->execute([$annee]);
        $count = (int)$stmt->fetchColumn() + 1;
        return "FAC-{$annee}-" . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
