<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-4">
  <a href="index.php?module=clients&action=index" class="text-muted text-decoration-none" style="font-size:0.85rem">
    <i class="bi bi-arrow-left me-1"></i> Retour aux clients
  </a>
  <h2 class="fw-bold mt-1"><?= htmlspecialchars($page_title) ?></h2>
</div>

<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?>
        <li><?= htmlspecialchars($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="card" style="max-width:600px">
  <div class="card-body p-4">
    <?php
      $action = isset($client) ? "index.php?module=clients&action=edit&id={$client['id']}" : "index.php?module=clients&action=create";
    ?>
    <form method="POST" action="<?= $action ?>">
      <div class="mb-3">
        <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
        <input type="text" name="nom" class="form-control" required
               value="<?= htmlspecialchars($data['nom'] ?? '') ?>" placeholder="AlphaCorp">
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control"
               value="<?= htmlspecialchars($data['email'] ?? '') ?>" placeholder="contact@client.fr">
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Téléphone</label>
        <input type="text" name="telephone" class="form-control"
               value="<?= htmlspecialchars($data['telephone'] ?? '') ?>" placeholder="01 23 45 67 89">
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold">Adresse</label>
        <textarea name="adresse" class="form-control" rows="3"
                  placeholder="12 rue de la Paix, 75001 Paris"><?= htmlspecialchars($data['adresse'] ?? '') ?></textarea>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-accent">
          <i class="bi bi-check-lg me-1"></i>
          <?= isset($client) ? 'Enregistrer les modifications' : 'Créer le client' ?>
        </button>
        <a href="index.php?module=clients&action=index" class="btn btn-outline-secondary">Annuler</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
