<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-4">
  <a href="index.php?module=clients&action=index" class="text-muted text-decoration-none" style="font-size:0.85rem">
    <i class="bi bi-arrow-left me-1"></i> Retour aux clients
  </a>
  <div class="d-flex align-items-center gap-3 mt-2">
    <h2 class="fw-bold mb-0"><?= htmlspecialchars($client['nom']) ?></h2>
    <a href="index.php?module=clients&action=edit&id=<?= $client['id'] ?>" class="btn btn-sm btn-outline-primary">
      <i class="bi bi-pencil me-1"></i> Modifier
    </a>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card h-100">
      <div class="card-body">
        <h6 class="text-muted text-uppercase" style="font-size:0.7rem;letter-spacing:1px">Contact</h6>
        <div class="mt-2" style="font-size:0.9rem">
          <div class="mb-2"><i class="bi bi-envelope me-2 text-muted"></i><?= htmlspecialchars($client['email'] ?? '—') ?></div>
          <div class="mb-2"><i class="bi bi-telephone me-2 text-muted"></i><?= htmlspecialchars($client['telephone'] ?? '—') ?></div>
          <div><i class="bi bi-geo-alt me-2 text-muted"></i><?= nl2br(htmlspecialchars($client['adresse'] ?? '—')) ?></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Projets (<?= count($projets) ?>)</span>
        <a href="index.php?module=projets&action=create&client_id=<?= $client['id'] ?>" class="btn btn-sm btn-accent">
          <i class="bi bi-plus"></i> Nouveau projet
        </a>
      </div>
      <div class="card-body p-0">
        <?php if (empty($projets)): ?>
          <div class="text-center py-4 text-muted">Aucun projet pour ce client.</div>
        <?php else: ?>
          <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Projet</th><th>Statut</th><th>Budget</th><th></th></tr></thead>
            <tbody>
              <?php foreach ($projets as $p): ?>
              <tr>
                <td><?= htmlspecialchars($p['nom']) ?></td>
                <td><span class="status-badge badge-<?= $p['statut'] ?>"><?= ucfirst(str_replace('_',' ',$p['statut'])) ?></span></td>
                <td><?= number_format($p['budget'], 2, ',', ' ') ?> €</td>
                <td><a href="index.php?module=projets&action=show&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary">Voir</a></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
