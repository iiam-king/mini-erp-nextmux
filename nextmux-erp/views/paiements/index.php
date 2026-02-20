<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="mb-0 fw-bold">Paiements</h2>
    <p class="text-muted mb-0" style="font-size:0.85rem"><?= count($paiements) ?> paiement(s)</p>
  </div>
</div>

<?php if ($flash): ?>
  <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'warning' ?> alert-dismissible fade show">
    <?= htmlspecialchars($flash['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php $total = array_sum(array_column($paiements, 'montant')); ?>
<div class="card mb-3 text-center p-3" style="max-width:250px">
  <div class="text-muted" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px">Total encaissé</div>
  <div class="fs-3 fw-bold text-success"><?= number_format($total, 2, ',', ' ') ?> €</div>
</div>

<div class="card">
  <div class="card-body p-0">
    <?php if (empty($paiements)): ?>
      <div class="text-center py-5 text-muted"><i class="bi bi-credit-card fs-1 d-block mb-2"></i>Aucun paiement.</div>
    <?php else: ?>
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th>Date</th><th>Facture</th><th>Client</th><th>Projet</th><th>Montant</th><th>Mode</th><th>Référence</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($paiements as $p): ?>
        <tr>
          <td><?= date('d/m/Y', strtotime($p['date_paiement'])) ?></td>
          <td>
            <a href="index.php?module=factures&action=show&id=<?= $p['facture_id'] ?>"
               class="text-decoration-none fw-semibold"><?= htmlspecialchars($p['facture_numero']) ?></a>
          </td>
          <td class="text-muted" style="font-size:0.85rem"><?= htmlspecialchars($p['client_nom']) ?></td>
          <td class="text-muted" style="font-size:0.85rem"><?= htmlspecialchars($p['projet_nom']) ?></td>
          <td class="fw-semibold text-success"><?= number_format($p['montant'], 2, ',', ' ') ?> €</td>
          <td><?= ucfirst($p['mode']) ?></td>
          <td class="text-muted" style="font-size:0.82rem"><?= htmlspecialchars($p['reference'] ?? '—') ?></td>
          <td>
            <form method="POST"
                  action="index.php?module=paiements&action=delete&id=<?= $p['id'] ?>&facture_id=<?= $p['facture_id'] ?>"
                  onsubmit="return confirm('Supprimer ce paiement ?')">
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
