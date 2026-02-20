<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-4">
  <a href="index.php?module=factures&action=index" class="text-muted text-decoration-none" style="font-size:0.85rem">
    <i class="bi bi-arrow-left me-1"></i> Retour aux factures
  </a>
  <h2 class="fw-bold mt-1"><?= htmlspecialchars($page_title) ?></h2>
</div>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<div class="card" style="max-width:620px">
  <div class="card-body p-4">
    <?php $action = isset($facture)
      ? "index.php?module=factures&action=edit&id={$facture['id']}"
      : "index.php?module=factures&action=create"; ?>
    <form method="POST" action="<?= $action ?>" id="factureForm">
      <div class="mb-3">
        <label class="form-label fw-semibold">Projet <span class="text-danger">*</span></label>
        <select name="projet_id" class="form-select" required>
          <option value="">— Sélectionner —</option>
          <?php foreach ($projets as $p): ?>
            <option value="<?= $p['id'] ?>" <?= ($data['projet_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($p['nom']) ?> – <?= htmlspecialchars($p['client_nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="row mb-3">
        <div class="col">
          <label class="form-label fw-semibold">Montant HT (€) <span class="text-danger">*</span></label>
          <input type="number" name="montant_ht" id="montant_ht" class="form-control"
                 step="0.01" min="0" required
                 value="<?= $data['montant_ht'] ?? '' ?>" oninput="calculTTC()">
        </div>
        <div class="col">
          <label class="form-label fw-semibold">TVA (%)</label>
          <select name="tva" id="tva" class="form-select" onchange="calculTTC()">
            <?php foreach ([0, 5.5, 10, 20] as $t): ?>
              <option value="<?= $t ?>" <?= ($data['tva'] ?? 20) == $t ? 'selected' : '' ?>>
                <?= $t ?>%
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="mb-3">
        <div class="alert alert-info py-2 mb-0" style="font-size:0.9rem">
          <strong>Montant TTC : <span id="preview_ttc">—</span> €</strong>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col">
          <label class="form-label fw-semibold">Date d'émission</label>
          <input type="date" name="date_emission" class="form-control"
                 value="<?= $data['date_emission'] ?? date('Y-m-d') ?>" required>
        </div>
        <div class="col">
          <label class="form-label fw-semibold">Date d'échéance</label>
          <input type="date" name="date_echeance" class="form-control"
                 value="<?= $data['date_echeance'] ?? '' ?>">
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Statut</label>
        <select name="statut" class="form-select">
          <?php foreach (['brouillon','envoyee','payee','annulee'] as $s): ?>
            <option value="<?= $s ?>" <?= ($data['statut'] ?? 'brouillon') === $s ? 'selected' : '' ?>>
              <?= ucfirst($s) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" class="form-control" rows="2"
                  placeholder="Remarques sur la facture..."><?= htmlspecialchars($data['notes'] ?? '') ?></textarea>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-accent">
          <i class="bi bi-check-lg me-1"></i> <?= isset($facture) ? 'Enregistrer' : 'Créer la facture' ?>
        </button>
        <a href="index.php?module=factures&action=index" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>

<script>
function calculTTC() {
  const ht  = parseFloat(document.getElementById('montant_ht').value) || 0;
  const tva = parseFloat(document.getElementById('tva').value) || 0;
  const ttc = (ht * (1 + tva / 100)).toFixed(2);
  document.getElementById('preview_ttc').textContent = ttc.replace('.', ',');
}
calculTTC();
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
