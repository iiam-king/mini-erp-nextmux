<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="mb-0 fw-bold">Projets</h2>
    <p class="text-muted mb-0" style="font-size:0.85rem"><?= count($projets) ?> projet(s)</p>
  </div>
  <a href="index.php?module=projets&action=create" class="btn btn-accent">
    <i class="bi bi-plus-lg me-1"></i> Nouveau projet
  </a>
</div>

<?php if ($flash): ?>
  <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'warning' ?> alert-dismissible fade show">
    <?= htmlspecialchars($flash['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<!-- Filtres statut -->
<div class="mb-3 d-flex gap-2 flex-wrap">
  <?php
  $statuts = ['tous' => 'Tous', 'en_cours' => 'En cours', 'termine' => 'Terminé', 'suspendu' => 'Suspendu'];
  $filtre  = $_GET['statut'] ?? 'tous';
  foreach ($statuts as $val => $label): ?>
    <a href="?module=projets&action=index&statut=<?= $val ?>"
       class="btn btn-sm <?= $filtre === $val ? 'btn-dark' : 'btn-outline-secondary' ?>">
      <?= $label ?>
    </a>
  <?php endforeach; ?>
</div>

<div class="card">
  <div class="card-body p-0">
    <?php
    $liste = $projets;
    if ($filtre !== 'tous') $liste = array_filter($projets, fn($p) => $p['statut'] === $filtre);
    ?>
    <?php if (empty($liste)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-folder2-open fs-1 d-block mb-2"></i>Aucun projet.
      </div>
    <?php else: ?>
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>Projet</th><th>Client</th><th>Statut</th>
          <th>Budget</th><th>Tâches</th><th>Factures</th><th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($liste as $p): ?>
        <tr>
          <td>
            <a href="index.php?module=projets&action=show&id=<?= $p['id'] ?>"
               class="fw-semibold text-dark text-decoration-none">
              <?= htmlspecialchars($p['nom']) ?>
            </a>
            <?php if ($p['date_fin']): ?>
              <div style="font-size:0.75rem;color:#aaa">→ <?= date('d/m/Y', strtotime($p['date_fin'])) ?></div>
            <?php endif; ?>
          </td>
          <td class="text-muted"><?= htmlspecialchars($p['client_nom']) ?></td>
          <td>
            <span class="status-badge badge-<?= $p['statut'] ?>">
              <?= ucfirst(str_replace('_', ' ', $p['statut'])) ?>
            </span>
          </td>
          <td class="fw-semibold"><?= number_format($p['budget'], 0, ',', ' ') ?> €</td>
          <td class="text-center"><span class="badge bg-secondary"><?= $p['nb_taches'] ?></span></td>
          <td class="text-center"><span class="badge bg-info text-dark"><?= $p['nb_factures'] ?></span></td>
          <td class="text-end">
            <a href="index.php?module=projets&action=show&id=<?= $p['id'] ?>"
               class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></a>
            <a href="index.php?module=projets&action=edit&id=<?= $p['id'] ?>"
               class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
            <form method="POST" action="index.php?module=projets&action=delete&id=<?= $p['id'] ?>"
                  class="d-inline" onsubmit="return confirm('Supprimer ce projet ?')">
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
