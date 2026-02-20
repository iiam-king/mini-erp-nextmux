<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="mb-0 fw-bold">Dépenses</h2>
    <p class="text-muted mb-0" style="font-size:0.85rem"><?= count($depenses) ?> dépense(s)</p>
  </div>
  <a href="index.php?module=depenses&action=create" class="btn btn-accent">
    <i class="bi bi-plus-lg me-1"></i> Nouvelle dépense
  </a>
</div>

<?php if ($flash): ?>
  <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'warning' ?> alert-dismissible fade show">
    <?= htmlspecialchars($flash['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php $total = array_sum(array_column($depenses, 'montant')); ?>
<div class="card mb-3 text-center p-3" style="max-width:250px">
  <div class="text-muted" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px">Total dépenses</div>
  <div class="fs-3 fw-bold text-danger"><?= number_format($total, 2, ',', ' ') ?> Fcfa</div>
</div>

<div class="card">
  <div class="card-body p-0">
    <?php if (empty($depenses)): ?>
      <div class="text-center py-5 text-muted"><i class="bi bi-cash-coin fs-1 d-block mb-2"></i>Aucune dépense.</div>
    <?php else: ?>
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th>Date</th><th>Libellé</th><th>Catégorie</th><th>Projet</th><th>Montant</th><th class="text-end">Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($depenses as $d): ?>
        <tr>
          <td style="font-size:0.85rem"><?= date('d/m/Y', strtotime($d['date_depense'])) ?></td>
          <td class="fw-semibold"><?= htmlspecialchars($d['libelle']) ?></td>
          <td>
            <?php if ($d['categorie']): ?>
              <span class="badge bg-light text-dark border"><?= htmlspecialchars($d['categorie']) ?></span>
            <?php else: ?>—<?php endif; ?>
          </td>
          <td class="text-muted" style="font-size:0.85rem">
            <?= $d['projet_nom'] ? htmlspecialchars($d['projet_nom']) : '<span class="text-muted">—</span>' ?>
          </td>
          <td class="fw-semibold text-danger"><?= number_format($d['montant'], 2, ',', ' ') ?> Fcfa</td>
          <td class="text-end">
            <a href="index.php?module=depenses&action=edit&id=<?= $d['id'] ?>"
               class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
            <form method="POST" action="index.php?module=depenses&action=delete&id=<?= $d['id'] ?>"
                  class="d-inline" onsubmit="return confirm('Supprimer cette dépense ?')">
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
