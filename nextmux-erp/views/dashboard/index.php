<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-4">
  <h2 class="fw-bold mb-1">Bonjour, <?= htmlspecialchars(explode(' ', $_SESSION['user']['nom'])[0]) ?> 👋</h2>
  <p class="text-muted" style="font-size:0.85rem">Vue d'ensemble de Nextmux ERP · <?= date('d/m/Y') ?></p>
</div>

<!-- KPIs principaux -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card p-3 text-center h-100" style="border-left:4px solid #1d4ed8">
      <div class="text-muted" style="font-size:0.68rem;text-transform:uppercase;letter-spacing:1px">Total facturé</div>
      <div class="fs-3 fw-bold text-primary"><?= number_format($total_facture, 0, ',', ' ') ?> €</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card p-3 text-center h-100" style="border-left:4px solid #15803d">
      <div class="text-muted" style="font-size:0.68rem;text-transform:uppercase;letter-spacing:1px">Encaissé</div>
      <div class="fs-3 fw-bold text-success"><?= number_format($total_paye, 0, ',', ' ') ?> €</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card p-3 text-center h-100" style="border-left:4px solid #b91c1c">
      <div class="text-muted" style="font-size:0.68rem;text-transform:uppercase;letter-spacing:1px">Dépenses</div>
      <div class="fs-3 fw-bold text-danger"><?= number_format($total_depenses, 0, ',', ' ') ?> €</div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card p-3 text-center h-100" style="border-left:4px solid <?= $marge >= 0 ? '#15803d' : '#b91c1c' ?>">
      <div class="text-muted" style="font-size:0.68rem;text-transform:uppercase;letter-spacing:1px">Marge nette</div>
      <div class="fs-3 fw-bold <?= $marge >= 0 ? 'text-success' : 'text-danger' ?>">
        <?= number_format($marge, 0, ',', ' ') ?> €
      </div>
    </div>
  </div>
</div>

<!-- Compteurs opérationnels -->
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <a href="index.php?module=clients&action=index" class="card p-3 text-center text-decoration-none h-100">
      <i class="bi bi-people fs-2 text-primary d-block mb-1"></i>
      <div class="fs-4 fw-bold"><?= $nb_clients ?></div>
      <div class="text-muted" style="font-size:0.8rem">Clients</div>
    </a>
  </div>
  <div class="col-6 col-md-3">
    <a href="index.php?module=projets&action=index" class="card p-3 text-center text-decoration-none h-100">
      <i class="bi bi-folder2-open fs-2 text-warning d-block mb-1"></i>
      <div class="fs-4 fw-bold"><?= $nb_projets ?></div>
      <div class="text-muted" style="font-size:0.8rem">Projets actifs</div>
    </a>
  </div>
  <div class="col-6 col-md-3">
    <a href="index.php?module=taches&action=index" class="card p-3 text-center text-decoration-none h-100">
      <i class="bi bi-check2-square fs-2 text-info d-block mb-1"></i>
      <div class="fs-4 fw-bold"><?= $nb_taches ?></div>
      <div class="text-muted" style="font-size:0.8rem">Tâches en cours</div>
    </a>
  </div>
  <div class="col-6 col-md-3">
    <a href="index.php?module=factures&action=index" class="card p-3 text-center text-decoration-none h-100">
      <i class="bi bi-receipt fs-2 text-danger d-block mb-1"></i>
      <div class="fs-4 fw-bold"><?= $nb_factures_open ?></div>
      <div class="text-muted" style="font-size:0.8rem">Factures ouvertes</div>
    </a>
  </div>
</div>

<div class="row g-3">
  <!-- Projets en cours -->
  <div class="col-md-7">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-folder2-open me-1"></i>Projets en cours</span>
        <a href="index.php?module=projets&action=create" class="btn btn-sm btn-accent">+ Nouveau</a>
      </div>
      <div class="card-body p-0">
        <?php if (empty($projets_recents)): ?>
          <div class="text-center py-4 text-muted">Aucun projet en cours.</div>
        <?php else: ?>
        <table class="table table-hover mb-0">
          <thead class="table-light"><tr><th>Projet</th><th>Client</th><th>Avancement</th></tr></thead>
          <tbody>
            <?php foreach ($projets_recents as $p):
              $pct = $p['nb_taches'] > 0
                ? round($p['nb_terminees'] / $p['nb_taches'] * 100)
                : 0;
            ?>
            <tr>
              <td>
                <a href="index.php?module=projets&action=show&id=<?= $p['id'] ?>"
                   class="fw-semibold text-dark text-decoration-none"><?= htmlspecialchars($p['nom']) ?></a>
                <div style="font-size:0.72rem;color:#aaa"><?= $p['nb_taches'] ?> tâche(s)</div>
              </td>
              <td class="text-muted" style="font-size:0.85rem"><?= htmlspecialchars($p['client_nom']) ?></td>
              <td style="min-width:120px">
                <div class="d-flex align-items-center gap-2">
                  <div class="progress flex-grow-1" style="height:6px">
                    <div class="progress-bar bg-primary" style="width:<?= $pct ?>%"></div>
                  </div>
                  <span style="font-size:0.75rem;color:#aaa;white-space:nowrap"><?= $pct ?>%</span>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Factures ouvertes -->
  <div class="col-md-5">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-receipt me-1"></i>Factures à encaisser</span>
        <a href="index.php?module=factures&action=index" class="btn btn-sm btn-outline-secondary">Voir tout</a>
      </div>
      <div class="card-body p-0">
        <?php if (empty($factures_ouvertes)): ?>
          <div class="text-center py-4 text-muted text-success">
            <i class="bi bi-check-circle-fill fs-3 d-block mb-1"></i>Tout est encaissé !
          </div>
        <?php else: ?>
          <?php foreach ($factures_ouvertes as $f): ?>
          <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
            <div>
              <a href="index.php?module=factures&action=show&id=<?= $f['id'] ?>"
                 class="fw-semibold text-dark text-decoration-none" style="font-size:0.875rem">
                <?= htmlspecialchars($f['numero']) ?>
              </a>
              <div style="font-size:0.75rem;color:#aaa"><?= htmlspecialchars($f['client_nom']) ?></div>
            </div>
            <div class="text-end">
              <div class="fw-semibold text-warning" style="font-size:0.875rem">
                <?= number_format($f['montant_ttc'] - $f['total_paye'], 2, ',', ' ') ?> €
              </div>
              <div style="font-size:0.72rem;color:#aaa"><?= date('d/m/Y', strtotime($f['date_emission'])) ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
