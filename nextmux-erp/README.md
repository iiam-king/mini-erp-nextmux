# Nextmux Mini ERP — Guide d'installation

## Prérequis
- WAMP (Windows) ou XAMPP (multi-OS) installé
- PHP 8.0+ avec PDO MySQL activé
- Navigateur web moderne

---

## Installation en 4 étapes

### 1. Placer le projet
Copiez le dossier `nextmux-erp/` dans votre répertoire web :
- WAMP : `C:/wamp64/www/nextmux-erp/`
- XAMPP : `C:/xampp/htdocs/nextmux-erp/`

### 2. Créer la base de données
- Ouvrez phpMyAdmin : http://localhost/phpmyadmin
- Cliquez sur **Importer**
- Sélectionnez le fichier `database/nextmux.sql`
- Cliquez sur **Exécuter**

### 3. Configurer la connexion (si besoin)
Ouvrez `config/db.php` et ajustez si nécessaire :
```php
define('DB_USER', 'root');   // votre utilisateur MySQL
define('DB_PASS', '');       // votre mot de passe (vide par défaut sur WAMP/XAMPP)
```

### 4. Lancer l'application
Ouvrez : http://localhost/nextmux-erp/

---

## Compte de test
| Email | Mot de passe |
|-------|-------------|
| admin@nextmux.fr | password |

> ⚠️ Le mot de passe "password" est hashé en bcrypt dans la BDD via `password_hash()`.
> Si la connexion échoue, re-générez le hash avec : `echo password_hash('password', PASSWORD_DEFAULT);`
> et mettez-le à jour dans la table `utilisateurs`.

---

## Structure MVC
```
nextmux-erp/
├── index.php              ← Routeur principal
├── config/db.php          ← Connexion PDO
├── models/                ← Logique BDD (Client, Projet, Facture…)
├── controllers/           ← Logique métier + routage des actions
├── views/                 ← Templates HTML/PHP
│   ├── layout/            ← header.php + footer.php partagés
│   ├── auth/              ← login
│   ├── dashboard/
│   ├── clients/
│   ├── projets/
│   ├── taches/
│   ├── factures/
│   ├── paiements/
│   └── depenses/
└── database/nextmux.sql   ← Schéma + données de test
```

## URLs de navigation
| Page | URL |
|------|-----|
| Dashboard | `index.php` |
| Clients | `index.php?module=clients&action=index` |
| Projets | `index.php?module=projets&action=index` |
| Tâches | `index.php?module=taches&action=index` |
| Factures | `index.php?module=factures&action=index` |
| Paiements | `index.php?module=paiements&action=index` |
| Dépenses | `index.php?module=depenses&action=index` |
