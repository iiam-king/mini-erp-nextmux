<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-4">
  <a href="javascript:history.back()" class="text-muted text-decoration-none" style="font-size:0.85rem">
    <i class="bi bi-arrow-left me-1"></i> Retour
  </a>
  <h2 class="fw-bold mt-1"><?= htmlspecialchars($page_title) ?></h2>
</div>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
  </div>
<?php endif; ?>

<div class="card" style="max-width:600px">
  <div class="card-body p-4">
    <?php $action = isset($tache)
      ? "index.php?module=taches&action=edit&id={$tache['id']}&projet_id=" . ($data['projet_id'] ?? '')
      : "index.php?module=taches&action=create"; ?>
    <form method="POST" action="<?= $action ?>">
      <div class="mb-3">
        <label class="form-label fw-semibold">Projet <span class="text-danger">*</span></label>
        <select name="projet_id" class="form-select" required>
          <option value="">— Sélectionner —</option>
          <?php foreach ($projets as $p): ?>
            <option value="<?= $p['id'] ?>" <?= ($data['projet_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($p['nom']) ?> (<?= htmlspecialchars($p['client_nom']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
        <input type="text" name="titre" class="form-control" required
               value="<?= htmlspecialchars($data['titre'] ?? '') ?>" placeholder="Développement frontend…">
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
      </div>
      <div class="row mb-3">
        <div class="col">
          <label class="form-label fw-semibold">Priorité</label>
          <select name="priorite" class="form-select">
            <?php foreach (['basse','normale','haute','urgente'] as $p): ?>
              <option value="<?= $p ?>" <?= ($data['priorite'] ?? 'normale') === $p ? 'selected' : '' ?>>
                <?= ucfirst($p) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col">
          <label class="form-label fw-semibold">Statut</label>
          <select name="statut" class="form-select">
            <?php foreach (['a_faire','en_cours','termine'] as $s): ?>
              <option value="<?= $s ?>" <?= ($data['statut'] ?? 'a_faire') === $s ? 'selected' : '' ?>>
                <?= ucfirst(str_replace('_',' ',$s)) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold">Date d'échéance</label>
        <input type="date" name="date_echeance" class="form-control" value="<?= $data['date_echeance'] ?? '' ?>">
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-accent">
          <i class="bi bi-check-lg me-1"></i>
          <?= isset($tache) ? 'Enregistrer' : 'Créer la tâche' ?>
        </button>
        <a href="javascript:history.back()" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
