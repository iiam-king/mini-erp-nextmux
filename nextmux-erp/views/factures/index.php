<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="mb-0 fw-bold">Factures</h2>
    <p class="text-muted mb-0" style="font-size:0.85rem"><?= count($factures) ?> facture(s)</p>
  </div>
  <a href="index.php?module=factures&action=create" class="btn btn-accent">
    <i class="bi bi-plus-lg me-1"></i> Nouvelle facture
  </a>
</div>

<?php if ($flash): ?>
  <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'warning' ?> alert-dismissible fade show">
    <?= htmlspecialchars($flash['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php
$total_facture = array_sum(array_column($factures, 'montant_ttc'));
$total_paye    = array_sum(array_column($factures, 'total_paye'));
?>
<div class="row g-3 mb-4">
  <div class="col-md-4"><div class="card text-center p-3">
    <div class="text-muted" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px">Total facturé</div>
    <div class="fs-4 fw-bold text-primary"><?= number_format($total_facture, 2, ',', ' ') ?> €</div>
  </div></div>
  <div class="col-md-4"><div class="card text-center p-3">
    <div class="text-muted" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px">Total encaissé</div>
    <div class="fs-4 fw-bold text-success"><?= number_format($total_paye, 2, ',', ' ') ?> €</div>
  </div></div>
  <div class="col-md-4"><div class="card text-center p-3">
    <div class="text-muted" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px">Reste à encaisser</div>
    <div class="fs-4 fw-bold text-warning"><?= number_format($total_facture - $total_paye, 2, ',', ' ') ?> €</div>
  </div></div>
</div>

<div class="card">
  <div class="card-body p-0">
    <?php if (empty($factures)): ?>
      <div class="text-center py-5 text-muted"><i class="bi bi-receipt fs-1 d-block mb-2"></i>Aucune facture.</div>
    <?php else: ?>
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th>N°</th><th>Client</th><th>Projet</th><th>HT</th><th>TTC</th><th>Encaissé</th><th>Émission</th><th>Statut</th><th class="text-end">Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($factures as $f): ?>
        <tr>
          <td>
            <a href="index.php?module=factures&action=show&id=<?= $f['id'] ?>"
               class="fw-semibold text-dark text-decoration-none"><?= htmlspecialchars($f['numero']) ?></a>
          </td>
          <td class="text-muted" style="font-size:0.85rem"><?= htmlspecialchars($f['client_nom']) ?></td>
          <td class="text-muted" style="font-size:0.85rem"><?= htmlspecialchars($f['projet_nom']) ?></td>
          <td><?= number_format($f['montant_ht'], 2, ',', ' ') ?> €</td>
          <td class="fw-semibold"><?= number_format($f['montant_ttc'], 2, ',', ' ') ?> €</td>
          <td class="<?= $f['total_paye'] >= $f['montant_ttc'] ? 'text-success' : 'text-warning' ?>">
            <?= number_format($f['total_paye'], 2, ',', ' ') ?> €
          </td>
          <td style="font-size:0.82rem"><?= date('d/m/Y', strtotime($f['date_emission'])) ?></td>
          <td><span class="status-badge badge-<?= $f['statut'] ?>"><?= ucfirst($f['statut']) ?></span></td>
          <td class="text-end">
            <a href="index.php?module=factures&action=show&id=<?= $f['id'] ?>"
               class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></a>
            <a href="index.php?module=factures&action=edit&id=<?= $f['id'] ?>"
               class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
            <form method="POST" action="index.php?module=factures&action=delete&id=<?= $f['id'] ?>"
                  class="d-inline" onsubmit="return confirm('Supprimer cette facture ?')">
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
