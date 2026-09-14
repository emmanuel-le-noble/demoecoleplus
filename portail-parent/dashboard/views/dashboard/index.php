<?php
/**
 * Vue : Index du Tableau de bord Parent
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);
?>
<main class="main-content">
    <?php include __DIR__ . '/../../../includes/topbar.php'; ?>

    <div class="row g-4">
        <div class="col-xl-8 col-lg-7">
            
            <div class="col-12 mb-4">
                <div class="liquid-card welcome-banner p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-uppercase tracking-wider small"><?= date('l j F Y') ?></span>
                        <h4 class="fw-semibold m-0 mt-1">Bonjour, M. <?= htmlspecialchars(explode(' ', (string)($_SESSION['parent_nom'] ?? 'Parent'))[0], ENT_QUOTES, 'UTF-8') ?>.</h4>
                        <p class="m-0 small">Voici les dernières nouvelles de <span class="fw-semibold"><?= htmlspecialchars((string)($data['eleve']['PRENOM_ELEVE'] ?? 'votre enfant'), ENT_QUOTES, 'UTF-8') ?></span> en <span class="text-info"><?= htmlspecialchars((string)($data['eleve']['NOMSALLE'] ?? 'Non assigné'), ENT_QUOTES, 'UTF-8') ?></span> (<span class="text-secondary"><?= htmlspecialchars((string)($data['eleve']['NOMCLASSE'] ?? 'Non assigné'), ENT_QUOTES, 'UTF-8') ?></span>).</p>
                    </div>
                    <a href="../notes/notes.php" class="btn btn-glass-success py-2 px-3 small" style="font-size:13px;">
                        Voir le rapport complet <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
                    </a>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="liquid-card p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="small" style="font-size: 11px;">MOYENNE GÉNÉRALE</span>
                            <div class="stat-icon"><i class="fa-solid fa-chart-line text-success-glow"></i></div>
                        </div>
                        <h2 class="fw-bold m-0"><?= htmlspecialchars((string)($data['moyenne_generale'] ?? 'N/A'), ENT_QUOTES, 'UTF-8') ?> <span class="fs-6 small">/20</span></h2>
                        <small class="text-success-glow small d-block mt-2"><i class="fa-solid fa-arrow-up me-1"></i> Basé sur l'historique</small>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="liquid-card p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="small" style="font-size: 11px;">ABSENCES NON JUSTIFIÉES</span>
                            <div class="stat-icon"><i class="fa-solid fa-circle-exclamation text-danger-glow"></i></div>
                        </div>
                        <h2 class="fw-bold m-0"><?= htmlspecialchars((string)($data['absences_non_justifiees'] ?? 0), ENT_QUOTES, 'UTF-8') ?></h2>
                        <small class="text-danger-glow small d-block mt-2"><i class="fa-solid fa-triangle-exclamation me-1"></i> Heures cumulées</small>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="liquid-card p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="small" style="font-size: 11px;">DEVOIRS EN ATTENTE</span>
                            <div class="stat-icon"><i class="fa-regular fa-clock text-warning-glow"></i></div>
                        </div>
                        <h2 class="fw-bold m-0"><?= htmlspecialchars((string)($data['devoirs_en_attente'] ?? 0), ENT_QUOTES, 'UTF-8') ?></h2>
                        <small class="text-warning-glow small d-block mt-2">À rendre prochainement</small>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="liquid-card p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="small" style="font-size: 11px;">SOLDE EN COURS</span>
                            <div class="stat-icon"><i class="fa-solid fa-wallet text-info-glow"></i></div>
                        </div>
                        <h2 class="fw-bold m-0"><?= fcfa($data['solde_financier'] ?? 0) ?></h2>
                        <small class="text-info-glow small d-block mt-2">Reste à recouvrer</small>
                    </div>
                </div>
            </div>

            <div class="liquid-card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0">Évaluations récentes</h5>
                        <small class="small">Dernières notes enregistrées</small>
                    </div>
                    <a href="../notes/notes.php" class="text-success small nav-link p-0">Voir tout →</a>
                </div>

                <div class="d-flex flex-column gap-2">
                    <?php if (!empty($data['notes_recentes']) && is_array($data['notes_recentes'])): foreach ($data['notes_recentes'] as $note): ?>
                        <div class="list-item-glass p-3 d-flex align-items-center justify-content-between rounded-3 bg-dark bg-opacity-10 border border-secondary border-opacity-10">
                            <div class="d-flex align-items-center">
                                <div class="icon-badge me-3 p-2 rounded-2 text-center fw-bold <?= ($note['MOYENTRIMES'] ?? 0) >= 10 ? 'bg-success bg-opacity-20 text-success' : 'bg-danger bg-opacity-20 text-danger' ?>" style="min-width: 45px;">
                                    <?= number_format((float)($note['MOYENTRIMES'] ?? 0), 0, ',', ' ') ?>
                                </div>
                                <div>
                                    <h6 class="m-0 fw-semibold"><?= htmlspecialchars((string)($note['NOM_MATIERE'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h6>
                                    <small class="small text-muted">Coefficient <?= htmlspecialchars((string)($note['COEF'] ?? ''), ENT_QUOTES, 'UTF-8') ?> • <?= htmlspecialchars((string)($note['OBSERVATION'] ?: 'Aucune remarque'), ENT_QUOTES, 'UTF-8') ?></small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold"><?= number_format((float)($note['MOYENTRIMES'] ?? 0), 2, ',', ' ') ?>/20</span><br>
                                <small class="small text-muted" style="font-size:10px;">NOTE</small>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="text-center py-4 text-muted small">Aucune note enregistrée récemment.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="liquid-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold m-0">Cahier de devoirs</h5>
                        <small class="small">Travaux planifiés à venir</small>
                    </div>
                    <a href="../cahier_texte/cahier_texte.php" class="text-success small nav-link p-0">Accéder au cahier →</a>
                </div>

                <div class="d-flex flex-column gap-3">
                    <?php if (!empty($data['liste_devoirs']) && is_array($data['liste_devoirs'])): foreach ($data['liste_devoirs'] as $dev): ?>
                        <div class="d-flex align-items-start border-bottom border-secondary border-opacity-10 pb-2">
                            <div class="pt-1 p-2">
                                <input type="checkbox" class="form-check-input bg-transparent border-secondary" style="cursor:pointer; width:17px; height:17px; border-radius:50%;">
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="m-0 small fw-bold text-uppercase"><?= htmlspecialchars((string)($dev['NOM_MATIERE'] ?? ''), ENT_QUOTES, 'UTF-8') ?> <span class="text-capitalize fw-normal small text-muted">• Enseignant : <?= htmlspecialchars((string)($dev['PROF_NOM'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span></h6>
                                <p class="m-0 small text-muted text-truncate" style="max-width: 400px; font-size:12px;">
                                    <?= htmlspecialchars((string)($dev['DEVOIRS_A_FAIRE'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            </div>
                            <div class="text-end">
                                <span class="text-danger small fw-semibold" style="font-size:11px;">ÉCHÉANCE</span><br>
                                <small class="small text-muted" style="font-size:10px;"><?= !empty($dev['DATE_ECHEANCE']) ? date('d M Y', strtotime((string)$dev['DATE_ECHEANCE'])) : 'N/A' ?></small>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div class="text-center py-4 text-muted small">Aucun devoir programmé à venir.</div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="d-flex flex-column gap-4">
                
                <div class="liquid-card p-4">
                    <h5 class="fw-bold m-0">Suivi financier</h5>
                    <small class="small">État de la scolarité annuelle</small>
                    
                    <div class="mt-4 bg-dark bg-opacity-25 p-3 rounded-3 border border-secondary border-opacity-10">
                        <span class="text-uppercase tracking-widest text-muted small d-block" style="font-size: 10px;">RESTE À PAYER</span>
                        <span class="fw-bold fs-3"><?= fcfa($data['solde_financier'] ?? 0) ?></span>
                        <div class="mt-1 small text-muted" style="font-size:11px;">Scolarité • Année Scolaire Active</div>
                        
                        <div class="progress custom-progress mt-3" style="height: 6px; background-color: rgba(255,255,255,0.1);">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= htmlspecialchars((string)($data['pourcentage_paiement'] ?? 0), ENT_QUOTES, 'UTF-8') ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2" style="font-size:11px;">
                            <span class="text-muted">Payé : <?= htmlspecialchars((string)($data['pourcentage_paiement'] ?? 0), ENT_QUOTES, 'UTF-8') ?>%</span>
                            <span class="text-muted"><?= fcfa($data['montant_deja_paye'] ?? 0) ?> / <?= fcfa($data['montant_total_theorique'] ?? 0) ?></span>
                        </div>
                    </div>
                    <a href="../paiements/paiements.php" class="btn btn-glass-success w-100 py-2 mt-3">Effectuer un versement <i class="fa-solid fa-arrow-right-long ms-1"></i></a>
                </div>

                <div class="liquid-card p-4">
                    <div class="mb-3">
                        <h5 class="fw-bold m-0">Annonces officielles</h5>
                        <small class="small">Notes d'information administratives</small>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <?php if (!empty($data['annonces']) && is_array($data['annonces'])): foreach ($data['annonces'] as $annonce): ?>
                            <div class="p-3 rounded-3 bg-dark bg-opacity-25 border border-secondary border-opacity-10">
                                <div class="d-flex align-items-start">
                                    <div class="text-success me-3 mt-1"><i class="fa-solid fa-bullhorn"></i></div>
                                    <div>
                                        <h6 class="small fw-bold m-0"><?= htmlspecialchars((string)($annonce['TITRE'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h6>
                                        <small class="small text-muted d-block mb-1" style="font-size: 11px;"><?= !empty($annonce['DATE_PUBLICATION']) ? date('d/m/Y à H:i', strtotime((string)$annonce['DATE_PUBLICATION'])) : 'N/A' ?></small>
                                        <p class="small m-0 text-muted text-truncate" style="max-width: 250px;"><?= htmlspecialchars((string)($annonce['CONTENU'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; else: ?>
                            <div class="text-center py-3 text-muted small">Aucun communiqué officiel disponible.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="liquid-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold m-0">Messagerie</h5>
                            <small class="small">Communications récentes</small>
                        </div>
                        <a href="../messagerie/messagerie.php" class="text-success small nav-link p-0" style="font-size:12px;">Ouvrir la boîte →</a>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <?php if (!empty($data['messages_recents']) && is_array($data['messages_recents'])): foreach ($data['messages_recents'] as $msg): ?>
                            <div class="p-3 rounded-3 bg-success bg-opacity-25 border border-secondary border-opacity-10 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-secondary bg-opacity-20 d-flex align-items-center justify-content-center p-2 me-3" style="width:35px; height:35px;">
                                        <span class="small fw-bold text-uppercase" style="font-size: 11px;"><?= mb_substr((string)($msg['EXPEDITEUR_TYPE'] ?? 'U'), 0, 2, 'UTF-8') ?></span>
                                    </div>
                                    <div>
                                        <h6 class="small fw-bold m-0 text-capitalize"><?= htmlspecialchars((string)($msg['EXPEDITEUR_TYPE'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h6>
                                        <p class="small m-0 text-muted text-truncate" style="max-width: 180px; font-size: 12px;">
                                            <?= htmlspecialchars((string)($msg['CONTENU'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                        </p>
                                    </div>
                                </div>
                                <small class="small text-muted" style="font-size: 11px;"><?= !empty($msg['DATE_ENVOI']) ? date('d M', strtotime((string)$msg['DATE_ENVOI'])) : 'N/A' ?></small>
                            </div>
                        <?php endforeach; else: ?>
                            <div class="text-center py-3 text-muted small">Aucun message disponible.</div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>