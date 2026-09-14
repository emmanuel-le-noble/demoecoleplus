<?php declare(strict_types=1); ?>
<style>
    .prof-card { border: 0; border-radius: 16px; box-shadow: 0 4px 20px rgba(15,23,42,.06); transition: transform .2s, box-shadow .2s; }
    .prof-card:hover { transform: translateY(-3px); box-shadow: 0 8px 30px rgba(15,23,42,.1); }
    .prof-avatar { width: 56px; height: 56px; border-radius: 16px; display: grid; place-items: center; font-weight: 700; font-size: 1.3rem; color: #fff; }
    .prof-matieres .badge { font-size: .72rem; font-weight: 500; }
    .prof-detail-header { background: linear-gradient(135deg, #0f766e, #0e7490); border-radius: 16px 16px 0 0; color: #fff; padding: 2rem; }
    .matiere-card { border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; transition: background .2s; }
    .matiere-card:hover { background: #f0fdfa; }
    .btn-contact { background: linear-gradient(135deg, #0f766e, #0e9388); border: 0; color: #fff; border-radius: 10px; }
    .btn-contact:hover { background: linear-gradient(135deg, #0b5f59, #0f766e); color: #fff; }
</style>
<main class="main-content">
    <?php include __DIR__ . '/../../../includes/topbar.php'; ?>

    <?php if ($action === 'detail' && $profDetail): ?>
        <!-- ═══ DÉTAIL PROFESSEUR ═══ -->
        <div class="mb-4">
            <a href="professeurs.php" class="text-decoration-none text-muted small">
                <i class="fa-solid fa-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
            <div class="prof-detail-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="prof-avatar" style="width:72px;height:72px;font-size:1.6rem;background:rgba(255,255,255,.2);">
                        <?= strtoupper(mb_substr($profDetail['professeur_nom'], 0, 1)) ?>
                    </div>
                    <div>
                        <h3 class="mb-1 fw-bold"><?= htmlspecialchars($profDetail['professeur_titre'] . ' ' . $profDetail['professeur_nom'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <?php if (!empty($profDetail['professeur_contact'])): ?>
                            <small class="opacity-75"><i class="fa-solid fa-phone me-1"></i> <?= htmlspecialchars($profDetail['professeur_contact'], ENT_QUOTES, 'UTF-8') ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3"><i class="fa-solid fa-book me-2 text-success"></i>Matières enseignées</h5>
                <div class="row g-3 mb-4">
                    <?php foreach ($profDetail['matieres'] as $mat): ?>
                        <div class="col-md-6">
                            <div class="matiere-card">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-palette text-info"></i>
                                    <div>
                                        <strong><?= htmlspecialchars($mat['matiere_nom'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        <br><small class="text-muted"><?= htmlspecialchars($mat['classe_nom'], ENT_QUOTES, 'UTF-8') ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (!empty($profDetail['professeur_signature'])): ?>
                    <h5 class="fw-bold mb-2"><i class="fa-solid fa-signature me-2 text-secondary"></i>Signature</h5>
                    <p class="text-muted mb-4"><?= nl2br(htmlspecialchars($profDetail['professeur_signature'], ENT_QUOTES, 'UTF-8')) ?></p>
                <?php endif; ?>

                <div class="d-flex gap-2">
                    <?php if ($profDetail['conversation_id']): ?>
                        <a href="../../../messagerie/messagerie.php?conv=<?= $profDetail['conversation_id'] ?>" class="btn btn-contact">
                            <i class="fa-regular fa-comment me-1"></i> Envoyer un message
                        </a>
                    <?php else: ?>
                        <a href="professeurs.php?action=start_chat&id=<?= $profDetail['professeur_id'] ?>" class="btn btn-contact">
                            <i class="fa-regular fa-comment me-1"></i> Démarrer une conversation
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- ═══ LISTE DES PROFESSEURS ═══ -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-1">Professeurs</h4>
                <small class="text-muted">
                    Professeurs de <strong><?= htmlspecialchars($enfant_actif['PRENOM_ELEVE'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
                    — <?= htmlspecialchars($enfant_actif['NOMSALLE'] ?? 'Aucune classe', ENT_QUOTES, 'UTF-8') ?>
                </small>
            </div>
            <div class="position-relative">
                <i class="fa-solid fa-magnifying-glass position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 11px;"></i>
                <input type="text" id="prof-search" class="form-control form-control-sm py-1" style="padding-left: 30px; border-radius: 8px; font-size: 12px; width: 200px;" placeholder="Rechercher...">
            </div>
        </div>

        <?php if (empty($professeurs)): ?>
            <div class="text-center py-5">
                <i class="fa-solid fa-chalkboard-user text-muted" style="font-size: 3rem;"></i>
                <h5 class="text-muted mt-3">Aucun professeur trouvé</h5>
                <p class="text-muted">Aucun professeur n'est associé aux classes de cet enfant.</p>
            </div>
        <?php else: ?>
            <div id="prof-grid" class="row g-3">
                <?php
                $colors = ['#0f766e', '#0e7490', '#7c3aed', '#c2410c', '#15803d', '#b91c1c', '#1d4ed8'];
                $i = 0;
                ?>
                <?php foreach ($professeurs as $prof): ?>
                    <?php $color = $colors[$i % count($colors)]; $i++; ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card prof-card h-100">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <div class="prof-avatar" style="background: <?= $color ?>;">
                                        <?= strtoupper(mb_substr($prof['nom'], 0, 1)) ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-0">
                                            <?= htmlspecialchars(($prof['titre'] ?? '') . ' ' . $prof['nom'], ENT_QUOTES, 'UTF-8') ?>
                                        </h6>
                                        <?php if (!empty($prof['contact'])): ?>
                                            <small class="text-muted"><i class="fa-solid fa-phone me-1"></i><?= htmlspecialchars($prof['contact'], ENT_QUOTES, 'UTF-8') ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="prof-matieres mb-3">
                                    <?php foreach ($prof['matieres'] as $m): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success me-1 mb-1"><?= htmlspecialchars($m, ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="fa-solid fa-users text-muted small"></i>
                                    <small class="text-muted"><?= htmlspecialchars(implode(', ', $prof['classes']), ENT_QUOTES, 'UTF-8') ?></small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="professeurs.php?action=detail&id=<?= $prof['id'] ?>" class="btn btn-sm btn-outline-success flex-fill">
                                        <i class="fa-solid fa-eye me-1"></i> Détails
                                    </a>
                                    <?php if ($prof['contact']): ?>
                                        <a href="professeurs.php?action=start_chat&id=<?= $prof['id'] ?>" class="btn btn-sm btn-contact" title="Contacter">
                                            <i class="fa-regular fa-comment"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

<script nonce="<?= htmlspecialchars((string)($GLOBALS['csp_nonce'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('prof-search');
    if (!input) return;
    input.addEventListener('input', function() {
        var q = this.value.trim().toLowerCase();
        document.querySelectorAll('#prof-grid .col-md-6').forEach(function(card) {
            var text = card.textContent.toLowerCase();
            card.style.display = text.includes(q) ? '' : 'none';
        });
    });
});
</script>
