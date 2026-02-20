<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-4">
  <a href="index.php?module=factures&action=index" class="text-muted text-decoration-none" style="font-size:0.85rem">
    <i class="bi bi-arrow-left me-1"></i> Retour aux factures
  </a>
  <div class="d-flex align-items-center gap-3 mt-2 flex-wrap">
    <h2 class="fw-bold mb-0"><?= htmlspecialchars($facture['numero']) ?></h2>
    <span class="status-badge badge-<?= $facture['statut'] ?>"><?= ucfirst($facture['statut']) ?></span>
    <a href="index.php?module=factures&action=edit&id=<?= $facture['id'] ?>" class="btn btn-sm btn-outline-primary">
      <i class="bi bi-pencil me-1"></i> Modifier
    </a>
  </div>
  <p class="text-muted mt-1" style="font-size:0.85rem">
    <?= htmlspecialchars($facture['client_nom']) ?> · Projet : <?= htmlspecialchars($facture['projet_nom']) ?>
    · Émise le <?= date('d/m/Y', strtotime($facture['date_emission'])) ?>
  </p>
</div>

<?php if ($flash): ?>
  <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'warning' ?> alert-dismissible fade show">
    <?= htmlspecialchars($flash['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="row g-3 mb-4">
  <div class="col-md-3"><div class="card text-center p-3">
    <div class="text-muted" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px">Montant HT</div>
    <div class="fs-4 fw-bold"><?= number_format($facture['montant_ht'], 2, ',', ' ') ?> €</div>
  </div></div>
  <div class="col-md-3"><div class="card text-center p-3">
    <div class="text-muted" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px">TVA (<?= $facture['tva'] ?>%)</div>
    <div class="fs-4 fw-bold text-muted"><?= number_format($facture['montant_ttc'] - $facture['montant_ht'], 2, ',', ' ') ?> €</div>
  </div></div>
  <div class="col-md-3"><div class="card text-center p-3">
    <div class="text-muted" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px">Total TTC</div>
    <div class="fs-4 fw-bold text-primary"><?= number_format($facture['montant_ttc'], 2, ',', ' ') ?> €</div>
  </div></div>
  <div class="col-md-3"><div class="card text-center p-3">
    <div class="text-muted" style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px">Encaissé</div>
    <div class="fs-4 fw-bold <?= $total_paye >= $facture['montant_ttc'] ? 'text-success' : 'text-warning' ?>">
      <?= number_format($total_paye, 2, ',', ' ') ?> €
    </div>
  </div></div>
</div>

<div class="row g-3">
  <!-- Paiements -->
  <div class="col-md-7">
    <div class="card">
      <div class="card-header"><i class="bi bi-credit-card me-1"></i>Paiements reçus</div>
      <div class="card-body p-0">
        <?php if (empty($paiements)): ?>
          <div class="text-center py-3 text-muted" style="font-size:0.85rem">Aucun paiement enregistré.</div>
        <?php else: ?>
          <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Date</th><th>Montant</th><th>Mode</th><th>Référence</th><th></th></tr></thead>
            <tbody>
              <?php foreach ($paiements as $p): ?>
              <tr>
                <td><?= date('d/m/Y', strtotime($p['date_paiement'])) ?></td>
                <td class="fw-semibold text-success"><?= number_format($p['montant'], 2, ',', ' ') ?> €</td>
                <td><?= ucfirst($p['mode']) ?></td>
                <td class="text-muted" style="font-size:0.82rem"><?= htmlspecialchars($p['reference'] ?? '—') ?></td>
                <td>
                  <form method="POST"
                        action="index.php?module=paiements&action=delete&id=<?= $p['id'] ?>&facture_id=<?= $facture['id'] ?>"
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
  </div>

  <!-- Formulaire ajout paiement -->
  <div class="col-md-5">
    <?php $restant = $facture['montant_ttc'] - $total_paye; ?>
    <?php if ($restant > 0): ?>
    <div class="card">
      <div class="card-header fw-semibold"><i class="bi bi-plus-circle me-1"></i>Enregistrer un paiement</div>
      <div class="card-body">
        <form method="POST" action="index.php?module=factures&action=addPaiement">
          <input type="hidden" name="facture_id" value="<?= $facture['id'] ?>">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:0.85rem">Montant (€) <span class="text-danger">*</span></label>
            <input type="number" name="montant" class="form-control" step="0.01" min="0.01"
                   max="<?= $restant ?>" value="<?= $restant ?>" required>
            <div class="form-text">Restant : <?= number_format($restant, 2, ',', ' ') ?> €</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:0.85rem">Date</label>
            <input type="date" name="date_paiement" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:0.85rem">Mode</label>
            <select name="mode" class="form-select">
              <?php foreach (['virement','cheque','carte','especes','autre'] as $m): ?>
                <option value="<?= $m ?>"><?= ucfirst($m) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:0.85rem">Référence</label>
            <input type="text" name="reference" class="form-control" placeholder="VIR-20250101-001">
          </div>
          <button type="submit" class="btn btn-accent w-100">
            <i class="bi bi-check-lg me-1"></i> Enregistrer
          </button>
        </form>
      </div>
    </div>
    <?php else: ?>
    <div class="alert alert-success">
      <i class="bi bi-check-circle-fill me-2"></i><strong>Facture entièrement payée !</strong>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
