<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion – Nextmux ERP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #0a0a0f; min-height: 100vh; display:flex; align-items:center; justify-content:center; }
    .login-card { background: white; border-radius: 14px; padding: 2.5rem; width: 100%; max-width: 400px; }
    .brand { font-size: 1.8rem; font-weight: 800; letter-spacing: -1px; margin-bottom: 0.3rem; }
    .brand span { color: #FF4D1C; }
    .form-control:focus { border-color: #FF4D1C; box-shadow: 0 0 0 0.2rem rgba(255,77,28,.15); }
    .btn-login { background: #FF4D1C; color: white; width:100%; padding: 0.7rem; font-weight:600; border:none; border-radius:8px; }
    .btn-login:hover { background: #e03a0a; color:white; }
    .hint { background: #f8f9fa; border-radius:8px; padding:0.8rem 1rem; font-size:0.8rem; color:#555; margin-top:1.5rem; }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="brand">next<span>mux</span></div>
    <p class="text-muted mb-4" style="font-size:0.85rem">Mini ERP – Connexion</p>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:0.85rem">Email</label>
        <input type="email" name="email" class="form-control"
               placeholder="admin@nextmux.fr" required
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>
      <div class="mb-4">
        <label class="form-label fw-semibold" style="font-size:0.85rem">Mot de passe</label>
        <input type="password" name="mot_de_passe" class="form-control" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn-login">Se connecter</button>
    </form>

    <div class="hint">
      <strong>Compte de test :</strong><br>
      Email : <code>admin@nextmux.fr</code><br>
      Mot de passe : <code>password</code>
    </div>
  </div>
</body>
</html>
