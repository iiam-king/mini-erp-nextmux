<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-4">
  <a href="index.php?module=projets&action=index" class="text-muted text-decoration-none" style="font-size:0.85rem">
    <i class="bi bi-arrow-left me-1"></i> Retour aux projets
  </a>
  <h2 class="fw-bold mt-1"><?= htmlspecialchars($page_title) ?></h2>
</div>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<div class="card" style="max-width:680px">
  <div class="card-body p-4">
    <?php $action = isset($projet)
      ? "index.php?module=projets&action=edit&id={$projet['id']}"
      : "index.php?module=projets&action=create"; ?>
    <form method="POST" action="<?= $action ?>">
      <div class="mb-3">
        <label class="form-label fw-semibold">Client <span class="text-danger">*</span></label>
        <select name="client_id" class="form-select" required>
          <option value="">— Sélectionner —</option>
          <?php foreach ($clients as $c): ?>
            <option value="<?= $c['id'] ?>"
              <?= ($data['client_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Nom du projet <span class="text-danger">*</span></label>
        <input type="text" name="nom" class="form-control" required
               value="<?= htmlspecialchars($data['nom'] ?? '') ?>" placeholder="Site E-commerce">
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" class="form-control" rows="3"
                  placeholder="Description du projet..."><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
      </div>
      <div class="row mb-3">
        <div class="col">
          <label class="form-label fw-semibold">Date début</label>
          <input type="date" name="date_debut" class="form-control" value="<?= $data['date_debut'] ?? '' ?>">
        </div>
        <div class="col">
          <label class="form-label fw-semibold">Date fin</label>
          <input type="date" name="date_fin" class="form-control" value="<?= $data['date_fin'] ?? '' ?>">
        </div>
      </div>
      <div class="row mb-4">
        <div class="col">
          <label class="form-label fw-semibold">Statut</label>
          <select name="statut" class="form-select">
            <?php foreach (['en_cours','termine','suspendu','annule'] as $s): ?>
              <option value="<?= $s ?>" <?= ($data['statut'] ?? 'en_cours') === $s ? 'selected' : '' ?>>
                <?= ucfirst(str_replace('_', ' ', $s)) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col">
          <label class="form-label fw-semibold">Budget (€)</label>
          <input type="number" name="budget" class="form-control" min="0" step="0.01"
                 value="<?= $data['budget'] ?? '0' ?>">
        </div>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-accent">
          <i class="bi bi-check-lg me-1"></i>
          <?= isset($projet) ? 'Enregistrer' : 'Créer le projet' ?>
        </button>
        <a href="index.php?module=projets&action=index" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
