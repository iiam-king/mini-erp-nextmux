<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="mb-0 fw-bold">Clients</h2>
    <p class="text-muted mb-0" style="font-size:0.85rem"><?= count($clients) ?> client(s) enregistré(s)</p>
  </div>
  <a href="index.php?module=clients&action=create" class="btn btn-accent">
    <i class="bi bi-plus-lg me-1"></i> Nouveau client
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
    <?php if (empty($clients)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-people fs-1 d-block mb-2"></i>
        Aucun client pour l'instant.
      </div>
    <?php else: ?>
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>Nom</th><th>Email</th><th>Téléphone</th>
          <th class="text-center">Projets</th><th>Créé le</th><th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clients as $c): ?>
        <tr>
          <td>
            <a href="index.php?module=clients&action=show&id=<?= $c['id'] ?>"
               class="fw-semibold text-decoration-none text-dark">
              <?= htmlspecialchars($c['nom']) ?>
            </a>
          </td>
          <td class="text-muted"><?= htmlspecialchars($c['email'] ?? '—') ?></td>
          <td class="text-muted"><?= htmlspecialchars($c['telephone'] ?? '—') ?></td>
          <td class="text-center">
            <span class="badge bg-primary rounded-pill"><?= $c['nb_projets'] ?></span>
          </td>
          <td class="text-muted" style="font-size:0.82rem">
            <?= date('d/m/Y', strtotime($c['created_at'])) ?>
          </td>
          <td class="text-end">
            <a href="index.php?module=clients&action=show&id=<?= $c['id'] ?>"
               class="btn btn-sm btn-outline-secondary me-1" title="Voir">
              <i class="bi bi-eye"></i>
            </a>
            <a href="index.php?module=clients&action=edit&id=<?= $c['id'] ?>"
               class="btn btn-sm btn-outline-primary me-1" title="Modifier">
              <i class="bi bi-pencil"></i>
            </a>
            <form method="POST"
                  action="index.php?module=clients&action=delete&id=<?= $c['id'] ?>"
                  class="d-inline"
                  onsubmit="return confirm('Supprimer ce client et tous ses projets ?')">
              <button class="btn btn-sm btn-outline-danger" title="Supprimer">
                <i class="bi bi-trash"></i>
              </button>
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
