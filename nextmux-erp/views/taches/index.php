<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="mb-0 fw-bold">Tâches</h2>
    <p class="text-muted mb-0" style="font-size:0.85rem"><?= count($taches) ?> tâche(s)</p>
  </div>
  <a href="index.php?module=taches&action=create" class="btn btn-accent">
    <i class="bi bi-plus-lg me-1"></i> Nouvelle tâche
  </a>
</div>

<?php if ($flash): ?>
  <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'warning' ?> alert-dismissible fade show">
    <?= htmlspecialchars($flash['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body p-0">
    <?php if (empty($taches)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-check2-square fs-1 d-block mb-2"></i>Aucune tâche.
      </div>
    <?php else: ?>
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th>Tâche</th><th>Projet</th><th>Priorité</th><th>Statut</th><th>Échéance</th><th class="text-end">Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($taches as $t): ?>
        <tr>
          <td class="<?= $t['statut'] === 'termine' ? 'text-decoration-line-through text-muted' : 'fw-semibold' ?>">
            <?= htmlspecialchars($t['titre']) ?>
          </td>
          <td>
            <a href="index.php?module=projets&action=show&id=<?= $t['projet_id'] ?>"
               class="text-muted text-decoration-none" style="font-size:0.85rem">
              <?= htmlspecialchars($t['projet_nom']) ?>
            </a>
          </td>
          <td><span class="status-badge badge-<?= $t['priorite'] ?>"><?= ucfirst($t['priorite']) ?></span></td>
          <td><span class="status-badge badge-<?= $t['statut'] ?>"><?= ucfirst(str_replace('_',' ',$t['statut'])) ?></span></td>
          <td style="font-size:0.82rem;color:#aaa">
            <?= $t['date_echeance'] ? date('d/m/Y', strtotime($t['date_echeance'])) : '—' ?>
          </td>
          <td class="text-end">
            <a href="index.php?module=taches&action=edit&id=<?= $t['id'] ?>&projet_id=<?= $t['projet_id'] ?>"
               class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
            <form method="POST"
                  action="index.php?module=taches&action=delete&id=<?= $t['id'] ?>&projet_id=<?= $t['projet_id'] ?>"
                  class="d-inline" onsubmit="return confirm('Supprimer cette tâche ?')">
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
