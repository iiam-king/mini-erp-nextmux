<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nextmux ERP – <?= htmlspecialchars($page_title ?? 'Dashboard') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root {
      --sidebar-w: 240px;
      --accent: #FF4D1C;
      --dark: #0a0a0f;
    }
    body { background: #f4f5f7; font-family: 'Segoe UI', sans-serif; }

    /* SIDEBAR */
    #sidebar {
      width: var(--sidebar-w); min-height: 100vh;
      background: var(--dark); position: fixed; top: 0; left: 0; z-index: 100;
      display: flex; flex-direction: column;
    }
    .sidebar-brand {
      padding: 1.4rem 1.5rem;
      font-size: 1.3rem; font-weight: 800; color: white; letter-spacing: -0.5px;
      border-bottom: 1px solid #1e1e2e;
    }
    .sidebar-brand span { color: var(--accent); }
    .sidebar-nav { padding: 1rem 0; flex: 1; }
    .nav-section { padding: 0.5rem 1.5rem 0.3rem;
      font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1.5px; color: #555; }
    .sidebar-link {
      display: flex; align-items: center; gap: 0.75rem;
      padding: 0.6rem 1.5rem; color: #aaa; text-decoration: none;
      font-size: 0.875rem; transition: all 0.15s; border-left: 3px solid transparent;
    }
    .sidebar-link:hover, .sidebar-link.active {
      color: white; background: #161622; border-left-color: var(--accent);
    }
    .sidebar-link i { font-size: 1rem; width: 18px; }
    .sidebar-footer {
      padding: 1rem 1.5rem; border-top: 1px solid #1e1e2e;
      font-size: 0.78rem; color: #555;
    }
    .sidebar-footer strong { color: #aaa; display: block; }

    /* MAIN */
    #main { margin-left: var(--sidebar-w); min-height: 100vh; }
    .topbar {
      background: white; padding: 0.85rem 2rem;
      border-bottom: 1px solid #e5e7eb;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 50;
    }
    .topbar h1 { font-size: 1.15rem; font-weight: 700; margin: 0; color: var(--dark); }
    .content { padding: 2rem; }

    /* CARDS */
    .card { border: 1px solid #e5e7eb; border-radius: 10px; }
    .card-header { background: white; border-bottom: 1px solid #e5e7eb;
      font-weight: 600; font-size: 0.9rem; }

    /* BADGES statuts */
    .badge-en_cours   { background: #dbeafe; color: #1d4ed8; }
    .badge-termine    { background: #dcfce7; color: #15803d; }
    .badge-suspendu   { background: #fef9c3; color: #a16207; }
    .badge-annule     { background: #fee2e2; color: #b91c1c; }
    .badge-brouillon  { background: #f3f4f6; color: #6b7280; }
    .badge-envoyee    { background: #dbeafe; color: #1d4ed8; }
    .badge-payee      { background: #dcfce7; color: #15803d; }
    .badge-a_faire    { background: #f3f4f6; color: #6b7280; }
    .badge-haute      { background: #fee2e2; color: #b91c1c; }
    .badge-urgente    { background: #fce7f3; color: #9d174d; }
    .badge-normale    { background: #dbeafe; color: #1d4ed8; }
    .badge-basse      { background: #dcfce7; color: #15803d; }

    .status-badge {
      display: inline-block; padding: 3px 10px; border-radius: 20px;
      font-size: 0.72rem; font-weight: 600; letter-spacing: 0.3px;
    }
    .alert { border-radius: 8px; font-size: 0.875rem; }
    .btn-accent { background: var(--accent); color: white; border: none; }
    .btn-accent:hover { background: #e03a0a; color: white; }
    table.table th { font-size: 0.78rem; text-transform: uppercase;
      letter-spacing: 0.5px; color: #6b7280; font-weight: 600; }
  </style>
</head>
<body>

<?php
$module_actuel = $_GET['module'] ?? 'dashboard';
$user = $_SESSION['user'] ?? ['nom' => 'Invité'];
?>

<div id="sidebar">
  <div class="sidebar-brand">next<span>mux</span></div>
  <nav class="sidebar-nav">
    <div class="nav-section">Principal</div>
    <a href="index.php?module=dashboard&action=index"
       class="sidebar-link <?= $module_actuel === 'dashboard' ? 'active' : '' ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <div class="nav-section">Opérationnel</div>
    <a href="index.php?module=clients&action=index"
       class="sidebar-link <?= $module_actuel === 'clients' ? 'active' : '' ?>">
      <i class="bi bi-people"></i> Clients
    </a>
    <a href="index.php?module=projets&action=index"
       class="sidebar-link <?= $module_actuel === 'projets' ? 'active' : '' ?>">
      <i class="bi bi-folder2-open"></i> Projets
    </a>
    <a href="index.php?module=taches&action=index"
       class="sidebar-link <?= $module_actuel === 'taches' ? 'active' : '' ?>">
      <i class="bi bi-check2-square"></i> Tâches
    </a>

    <div class="nav-section">Finance</div>
    <a href="index.php?module=factures&action=index"
       class="sidebar-link <?= $module_actuel === 'factures' ? 'active' : '' ?>">
      <i class="bi bi-receipt"></i> Factures
    </a>
    <a href="index.php?module=paiements&action=index"
       class="sidebar-link <?= $module_actuel === 'paiements' ? 'active' : '' ?>">
      <i class="bi bi-credit-card"></i> Paiements
    </a>
    <a href="index.php?module=depenses&action=index"
       class="sidebar-link <?= $module_actuel === 'depenses' ? 'active' : '' ?>">
      <i class="bi bi-cash-coin"></i> Dépenses
    </a>
  </nav>
  <div class="sidebar-footer">
    <strong><?= htmlspecialchars($user['nom']) ?></strong>
    <a href="index.php?module=auth&action=logout" style="color:#555;text-decoration:none">
      <i class="bi bi-box-arrow-right"></i> Déconnexion
    </a>
  </div>
</div>

<div id="main">
  <div class="topbar">
    <h1><?= htmlspecialchars($page_title ?? 'Nextmux ERP') ?></h1>
    <div class="d-flex align-items-center gap-2">
      <i class="bi bi-person-circle fs-5 text-muted"></i>
      <span class="text-muted" style="font-size:0.85rem"><?= htmlspecialchars($user['nom']) ?></span>
    </div>
  </div>
  <div class="content">
