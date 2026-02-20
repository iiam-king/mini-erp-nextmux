<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-4">
  <a href="index.php?module=projets&action=index" class="text-muted text-decoration-none" style="font-size:0.85rem">
    <i class="bi bi-arrow-left me-1"></i> Retour aux projets
  </a>
  <div class="d-flex align-items-center gap-3 mt-2 flex-wrap">
    <h2 class="fw-bold mb-0"><?= htmlspecialchars($projet['nom']) ?></h2>
    <span class="status-badge badge-<?= $projet['statut'] ?>"><?= ucfirst(str_replace('_',' ',$projet['statut'])) ?></span>
    <a href="index.php?module=projets&action=edit&id=<?= $projet['id'] ?>" class="btn btn-sm btn-outline-primary">
      <i class="bi bi-pencil me-1"></i> Modifier
    </a>
  </div>
  <p class="text-muted mt-1" style="font-size:0.85rem">
    Client : <strong><?= htmlspecialchars($projet['client_nom']) ?></strong>
    <?php if ($projet['date_debut']): ?>
    · <?= date('d/m/Y', strtotime($projet['date_debut'])) ?> → <?= $projet['date_fin'] ? date('d/m/Y', strtotime($projet['date_fin'])) : '…' ?>
    <?php endif; ?>
  </p>
</div>

<!-- KPIs financiers -->
<div class="row g-3 mb-4">
  <?php
  $facture  = (float)$finances['total_facture'];
  $paye     = (float)$finances['total_paye'];
  $depenses = (float)$finances['total_depenses'];
  $marge    = $paye - $depenses;
  ?>
  <div class="col-md-3"><div class="card text-center p-3">
    <div class="text-muted" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:1px">Budget</div>
    <div class="fs-4 fw-bold"><?= number_format($projet['budget'], 0, ',', ' ') ?> €</div>
  </div></div>
  <div class="col-md-3"><div class="card text-center p-3">
    <div class="text-muted" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:1px">Facturé</div>
    <div class="fs-4 fw-bold text-primary"><?= number_format($facture, 0, ',', ' ') ?> €</div>
  </div></div>
  <div class="col-md-3"><div class="card text-center p-3">
    <div class="text-muted" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:1px">Encaissé</div>
    <div class="fs-4 fw-bold text-success"><?= number_format($paye, 0, ',', ' ') ?> €</div>
  </div></div>
  <div class="col-md-3"><div class="card text-center p-3">
    <div class="text-muted" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:1px">Marge nette</div>
    <div class="fs-4 fw-bold <?= $marge >= 0 ? 'text-success' : 'text-danger' ?>">
      <?= number_format($marge, 0, ',', ' ') ?> €
    </div>
  </div></div>
</div>

<div class="row g-3">
  <!-- Tâches -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-check2-square me-1"></i>Tâches (<?= count($taches) ?>)</span>
        <a href="index.php?module=taches&action=create&projet_id=<?= $projet['id'] ?>" class="btn btn-sm btn-accent">
          <i class="bi bi-plus"></i> Ajouter
        </a>
      </div>
      <div class="card-body p-0">
        <?php if (empty($taches)): ?>
          <div class="text-center py-4 text-muted">Aucune tâche.</div>
        <?php else: ?>
          <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Tâche</th><th>Priorité</th><th>Statut</th><th>Échéance</th><th></th></tr></thead>
            <tbody>
              <?php foreach ($taches as $t): ?>
              <tr>
                <td class="<?= $t['statut'] === 'termine' ? 'text-decoration-line-through text-muted' : '' ?>">
                  <?= htmlspecialchars($t['titre']) ?>
                </td>
                <td><span class="status-badge badge-<?= $t['priorite'] ?>"><?= ucfirst($t['priorite']) ?></span></td>
                <td><span class="status-badge badge-<?= $t['statut'] ?>"><?= ucfirst(str_replace('_',' ',$t['statut'])) ?></span></td>
                <td style="font-size:0.82rem;color:#aaa"><?= $t['date_echeance'] ? date('d/m/Y', strtotime($t['date_echeance'])) : '—' ?></td>
                <td class="text-end">
                  <a href="index.php?module=taches&action=edit&id=<?= $t['id'] ?>&projet_id=<?= $projet['id'] ?>"
                     class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Raccourcis finance -->
  <div class="col-md-4">
    <div class="card">
      <div class="card-header"><i class="bi bi-receipt me-1"></i>Finance</div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <a href="index.php?module=factures&action=create&projet_id=<?= $projet['id'] ?>"
             class="btn btn-outline-primary btn-sm"><i class="bi bi-plus me-1"></i>Nouvelle facture</a>
          <a href="index.php?module=factures&action=index&projet_id=<?= $projet['id'] ?>"
             class="btn btn-outline-secondary btn-sm"><i class="bi bi-list me-1"></i>Voir les factures</a>
          <a href="index.php?module=depenses&action=create&projet_id=<?= $projet['id'] ?>"
             class="btn btn-outline-secondary btn-sm"><i class="bi bi-cash-coin me-1"></i>Ajouter dépense</a>
        </div>
      </div>
    </div>
    <?php if ($projet['description']): ?>
    <div class="card mt-3">
      <div class="card-header"><i class="bi bi-info-circle me-1"></i>Description</div>
      <div class="card-body" style="font-size:0.875rem;color:#555">
        <?= nl2br(htmlspecialchars($projet['description'])) ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
