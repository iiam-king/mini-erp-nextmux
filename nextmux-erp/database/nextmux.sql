-- =============================================
-- Mini ERP Nextmux – Schéma base de données
-- =============================================

CREATE DATABASE IF NOT EXISTS nextmux_erp
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE nextmux_erp;

-- Table utilisateurs (auth)
CREATE TABLE IF NOT EXISTS utilisateurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  mot_de_passe VARCHAR(255) NOT NULL,
  role ENUM('admin','gestionnaire') DEFAULT 'gestionnaire',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table clients
CREATE TABLE IF NOT EXISTS clients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(150),
  telephone VARCHAR(20),
  adresse TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table projets
CREATE TABLE IF NOT EXISTS projets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL,
  nom VARCHAR(150) NOT NULL,
  description TEXT,
  date_debut DATE,
  date_fin DATE,
  statut ENUM('en_cours','termine','suspendu','annule') DEFAULT 'en_cours',
  budget DECIMAL(10,2) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- Table taches
CREATE TABLE IF NOT EXISTS taches (
  id INT AUTO_INCREMENT PRIMARY KEY,
  projet_id INT NOT NULL,
  titre VARCHAR(200) NOT NULL,
  description TEXT,
  priorite ENUM('basse','normale','haute','urgente') DEFAULT 'normale',
  statut ENUM('a_faire','en_cours','termine') DEFAULT 'a_faire',
  date_echeance DATE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (projet_id) REFERENCES projets(id) ON DELETE CASCADE
);

-- Table factures
CREATE TABLE IF NOT EXISTS factures (
  id INT AUTO_INCREMENT PRIMARY KEY,
  projet_id INT NOT NULL,
  numero VARCHAR(50) NOT NULL UNIQUE,
  montant_ht DECIMAL(10,2) NOT NULL DEFAULT 0,
  tva DECIMAL(5,2) NOT NULL DEFAULT 20.00,
  montant_ttc DECIMAL(10,2) NOT NULL DEFAULT 0,
  date_emission DATE NOT NULL,
  date_echeance DATE,
  statut ENUM('brouillon','envoyee','payee','annulee') DEFAULT 'brouillon',
  notes TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (projet_id) REFERENCES projets(id) ON DELETE CASCADE
);

-- Table paiements
CREATE TABLE IF NOT EXISTS paiements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  facture_id INT NOT NULL,
  montant DECIMAL(10,2) NOT NULL,
  date_paiement DATE NOT NULL,
  mode ENUM('virement','cheque','carte','especes','autre') DEFAULT 'virement',
  reference VARCHAR(100),
  notes TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (facture_id) REFERENCES factures(id) ON DELETE CASCADE
);

-- Table dépenses
CREATE TABLE IF NOT EXISTS depenses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  projet_id INT,
  libelle VARCHAR(200) NOT NULL,
  montant DECIMAL(10,2) NOT NULL,
  categorie VARCHAR(100),
  date_depense DATE NOT NULL,
  notes TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (projet_id) REFERENCES projets(id) ON DELETE SET NULL
);

-- =============================================
-- Données de test
-- =============================================

-- Utilisateur admin (mot de passe: admin123)
INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES
('Admin Nextmux', 'admin@nextmux.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Clients
INSERT INTO clients (nom, email, telephone, adresse) VALUES
('AlphaCorp', 'contact@alphacorp.fr', '01 23 45 67 89', '12 rue de la Paix, 75001 Paris'),
('BetaGroup', 'info@betagroup.fr', '04 56 78 90 12', '45 avenue Victor Hugo, 69002 Lyon'),
('GammaSAS', 'hello@gammasas.fr', '05 67 89 01 23', '8 cours de l''Intendance, 33000 Bordeaux'),
('DeltaLtd', 'contact@deltaltd.fr', '02 34 56 78 90', '22 rue du Commerce, 44000 Nantes');

-- Projets
INSERT INTO projets (client_id, nom, description, date_debut, date_fin, statut, budget) VALUES
(1, 'Site E-commerce', 'Refonte complète du site e-commerce avec paiement en ligne', '2025-01-10', '2025-04-30', 'en_cours', 15000.00),
(2, 'App Mobile RH', 'Application mobile de gestion des ressources humaines', '2025-02-01', '2025-06-30', 'en_cours', 22000.00),
(3, 'Dashboard BI', 'Tableau de bord analytique pour suivi des ventes', '2024-11-01', '2025-01-31', 'termine', 8500.00),
(1, 'API Paiement', 'Intégration API Stripe et gestion des abonnements', '2025-03-01', '2025-05-31', 'en_cours', 6000.00),
(4, 'Refonte UI', 'Modernisation de l''interface utilisateur', '2025-02-15', '2025-04-15', 'en_cours', 4500.00);

-- Tâches
INSERT INTO taches (projet_id, titre, priorite, statut, date_echeance) VALUES
(1, 'Maquettes Figma validées', 'haute', 'termine', '2025-01-25'),
(1, 'Développement frontend', 'haute', 'en_cours', '2025-03-15'),
(1, 'Intégration paiement Stripe', 'urgente', 'a_faire', '2025-04-01'),
(2, 'Cahier des charges', 'normale', 'termine', '2025-02-15'),
(2, 'Développement backend API', 'haute', 'en_cours', '2025-05-01'),
(3, 'Connexion sources de données', 'normale', 'termine', '2024-12-01'),
(3, 'Création des graphiques', 'normale', 'termine', '2025-01-15'),
(4, 'Documentation API', 'normale', 'a_faire', '2025-03-20'),
(5, 'Audit UX existant', 'normale', 'termine', '2025-02-28');

-- Factures
INSERT INTO factures (projet_id, numero, montant_ht, tva, montant_ttc, date_emission, date_echeance, statut) VALUES
(1, 'FAC-2025-001', 5000.00, 20.00, 6000.00, '2025-01-15', '2025-02-15', 'payee'),
(1, 'FAC-2025-002', 5000.00, 20.00, 6000.00, '2025-02-15', '2025-03-15', 'envoyee'),
(2, 'FAC-2025-003', 8000.00, 20.00, 9600.00, '2025-02-05', '2025-03-05', 'payee'),
(3, 'FAC-2025-004', 8500.00, 20.00, 10200.00, '2025-01-31', '2025-03-02', 'payee'),
(4, 'FAC-2025-005', 2000.00, 20.00, 2400.00, '2025-03-05', '2025-04-05', 'envoyee'),
(5, 'FAC-2025-006', 3000.00, 20.00, 3600.00, '2025-03-01', '2025-04-01', 'brouillon');

-- Paiements
INSERT INTO paiements (facture_id, montant, date_paiement, mode, reference) VALUES
(1, 6000.00, '2025-02-10', 'virement', 'VIR-20250210-001'),
(3, 9600.00, '2025-03-01', 'virement', 'VIR-20250301-002'),
(4, 10200.00, '2025-02-28', 'cheque', 'CHQ-20250228-003');

-- Dépenses
INSERT INTO depenses (projet_id, libelle, montant, categorie, date_depense) VALUES
(1, 'Licence Adobe XD', 599.00, 'Logiciels', '2025-01-10'),
(2, 'Sous-traitance dev mobile', 4500.00, 'Sous-traitance', '2025-02-20'),
(NULL, 'Abonnement serveur OVH', 249.00, 'Hébergement', '2025-01-01'),
(3, 'API données financières', 150.00, 'API externes', '2024-11-15'),
(NULL, 'Formation React Native', 890.00, 'Formation', '2025-02-01'),
(4, 'Documentation Stripe', 0.00, 'Documentation', '2025-03-01'),
(NULL, 'Frais bancaires', 45.00, 'Frais', '2025-03-01');
