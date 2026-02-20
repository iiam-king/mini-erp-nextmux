<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-4">
  <a href="index.php?module=depenses&action=index" class="text-muted text-decoration-none" style="font-size:0.85rem">
    <i class="bi bi-arrow-left me-1"></i> Retour aux dépenses
  </a>
  <h2 class="fw-bold mt-1"><?= htmlspecialchars($page_title) ?></h2>
</div>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<div class="card" style="max-width:580px">
  <div class="card-body p-4">
    <?php $action = isset($depense)
      ? "index.php?module=depenses&action=edit&id={$depense['id']}"
      : "index.php?module=depenses&action=create"; ?>
    <form method="POST" action="<?= $action ?>">
      <div class="mb-3">
        <label class="form-label fw-semibold">Libellé <span class="text-danger">*</span></label>
        <input type="text" name="libelle" class="form-control" required
               value="<?= htmlspecialchars($data['libelle'] ?? '') ?>" placeholder="Licence logiciel, sous-traitance...">
      </div>
      <div class="row mb-3">
        <div class="col">
          <label class="form-label fw-semibold">Montant (€) <span class="text-danger">*</span></label>
          <input type="number" name="montant" class="form-control" step="0.01" min="0.01" required
                 value="<?= $data['montant'] ?? '' ?>">
        </div>
        <div class="col">
          <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
          <input type="date" name="date_depense" class="form-control" required
                 value="<?= $data['date_depense'] ?? date('Y-m-d') ?>">
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Catégorie</label>
        <input type="text" name="categorie" class="form-control" list="categories"
               value="<?= htmlspecialchars($data['categorie'] ?? '') ?>"
               placeholder="Logiciels, Hébergement, Sous-traitance...">
        <datalist id="categories">
          <option value="Logiciels">
          <option value="Hébergement">
          <option value="Sous-traitance">
          <option value="API externes">
          <option value="Formation">
          <option value="Matériel">
          <option value="Frais généraux">
          <option value="Frais bancaires">
        </datalist>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Projet associé</label>
        <select name="projet_id" class="form-select">
          <option value="">— Aucun (dépense générale) —</option>
          <?php foreach ($projets as $p): ?>
            <option value="<?= $p['id'] ?>" <?= ($data['projet_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($p['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" class="form-control" rows="2"><?= htmlspecialchars($data['notes'] ?? '') ?></textarea>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-accent">
          <i class="bi bi-check-lg me-1"></i> <?= isset($depense) ? 'Enregistrer' : 'Ajouter la dépense' ?>
        </button>
        <a href="index.php?module=depenses&action=index" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
