<?php
declare(strict_types=1);

$enfant_actif = $data['enfant_actif'] ?? [];
$positions = $data['positions'] ?? [];
$trimestre_selectionne = $data['trimestre_selectionne'] ?? 'Tous';
$bulletin_officiel = $data['bulletin_officiel'] ?? null;
$details_bulletin = $data['details_bulletin'] ?? [];
$notes = $data['notes'] ?? [];
$moyenne_calculee = $data['moyenne_calculee'] ?? null;
$note_max = $data['note_max'] ?? null;
$note_min = $data['note_min'] ?? null;
$chart_labels = $data['chart_labels'] ?? [];
$chart_eleve = $data['chart_eleve'] ?? [];
$chart_classe = $data['chart_classe'] ?? [];
?>
<main class="main-content">
    <?php include __DIR__ . '/../../../includes/topbar.php'; ?>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold m-0">Notes & bulletins scolaires</h3>
            <p class="text-muted small m-0">Suivi des performances académiques de <span class="text-success fw-semibold"><?= htmlspecialchars(($enfant_actif['PRENOM_ELEVE'] ?? '') . ' ' . ($enfant_actif['NOM_ELEVE'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span> (Classe : <?= htmlspecialchars($enfant_actif['NOMSALLE'] ?? 'Non définie', ENT_QUOTES, 'UTF-8') ?>)</p>
        </div>
        
        <form method="GET" action="" class="d-flex gap-2">
            <select name="trimestre" class="form-select border-secondary border-opacity-25 py-2 small" id="select-trimestre-notes">
                <option value="Tous" <?= $trimestre_selectionne == 'Tous' ? 'selected' : '' ?>> Tous les trimestres</option>
                <?php foreach ($positions as $trim): ?>
                    <option value="<?= $trim['idposition'] ?>" <?= $trimestre_selectionne == $trim['idposition'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($trim['libposition'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="liquid-card p-3 d-flex align-items-center justify-content-between border border-secondary border-opacity-10 rounded-3 h-100">
                <div>
                    <span class="text-muted small d-block mb-1" style="font-size: 10px; letter-spacing: 0.5px;">MOYENNE PÉRIODE</span>
                    <h4 class="fw-bold m-0 text-info">
                        <?php 
                        if ($bulletin_officiel && isset($bulletin_officiel['MOYENNE_GENE'])) {
                            echo number_format((float)$bulletin_officiel['MOYENNE_GENE'], 2, ',', ' ');
                        } elseif ($trimestre_selectionne !== 'Tous' && is_numeric($moyenne_calculee)) {
                            echo number_format((float)$moyenne_calculee, 2, ',', ' ');
                        } else {
                            echo 'N/A';
                        }
                        ?> <span class="fs-6 text-muted">/20</span>
                    </h4>
                    <small class="text-muted" style="font-size:10px;"><?= $bulletin_officiel ? 'Validée en conseil' : ($trimestre_selectionne !== 'Tous' ? 'Calcul provisoire' : 'Sélectionnez un trimestre') ?></small>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info p-3 rounded-3 d-none d-sm-flex"><i class="fa-solid fa-graduation-cap"></i></div>
            </div>
        </div>
        
        <div class="col-6 col-md-3">
            <div class="liquid-card p-3 d-flex align-items-center justify-content-between border border-secondary border-opacity-10 rounded-3 h-100">
                <div>
                    <span class="text-muted small d-block mb-1" style="font-size: 10px; letter-spacing: 0.5px;">RANG CLASSE</span>
                    <h4 class="fw-bold m-0 text-warning">
                        <?= ($bulletin_officiel && isset($bulletin_officiel['RANG']) && $bulletin_officiel['RANG'] !== null) ? $bulletin_officiel['RANG'] . ($bulletin_officiel['RANG'] == 1 ? 'er' : 'e') : 'N/A' ?>
                    </h4>
                    <small class="text-muted" style="font-size:10px;">Sur la période sélectionnée</small>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning p-3 rounded-3 d-none d-sm-flex"><i class="fa-solid fa-trophy"></i></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="liquid-card p-3 d-flex align-items-center justify-content-between border border-secondary border-opacity-10 rounded-3 h-100">
                <div>
                    <span class="text-muted small d-block mb-1" style="font-size: 10px; letter-spacing: 0.5px;">NOTE MAXIMALE</span>
                    <h4 class="fw-bold text-success m-0"><?= is_numeric($note_max) ? number_format((float)$note_max, 2, ',', ' ') : ($note_max ?? 'N/A') ?> <span class="fs-6 text-muted">/20</span></h4>
                    <small class="text-muted" style="font-size:10px;">Plus haute note obtenue</small>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success p-3 rounded-3 d-none d-sm-flex"><i class="fa-solid fa-arrow-trend-up"></i></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="liquid-card p-3 d-flex align-items-center justify-content-between border border-secondary border-opacity-10 rounded-3 h-100">
                <div>
                    <span class="text-muted small d-block mb-1" style="font-size: 10px; letter-spacing: 0.5px;">NOTE MINIMALE</span>
                    <h4 class="fw-bold text-danger m-0"><?= is_numeric($note_min) ? number_format((float)$note_min, 2, ',', ' ') : ($note_min ?? 'N/A') ?> <span class="fs-6 text-muted">/20</span></h4>
                    <small class="text-muted" style="font-size:10px;">Points de vigilance</small>
                </div>
                <div class="stat-icon bg-danger bg-opacity-10 text-danger p-3 rounded-3 d-none d-sm-flex"><i class="fa-solid fa-arrow-trend-down"></i></div>
            </div>
        </div>
    </div>

    <?php if (!empty($chart_labels)): ?>
    <div class="liquid-card p-4 border border-secondary border-opacity-10 rounded-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="fw-bold m-0">
                <i class="fa-solid fa-chart-bar text-info me-2"></i>Analyse des performances par matière
            </h5>
            <div class="d-flex gap-3 small text-muted">
                <span><span class="d-inline-block rounded me-1" style="width:12px;height:12px;background:rgba(16,185,129,0.85);"></span>Votre enfant</span>
                <span><span class="d-inline-block rounded me-1" style="width:12px;height:12px;background:rgba(148,163,184,0.5);"></span>Moyenne classe</span>
            </div>
        </div>
        <div style="position: relative; height: 280px;">
            <canvas id="notesChart"></canvas>
        </div>
    </div>

    <script nonce="<?= htmlspecialchars($GLOBALS['csp_nonce'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('notesChart').getContext('2d');

        const labels  = <?= json_encode($chart_labels, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const eleve   = <?= json_encode($chart_eleve, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const classe  = <?= json_encode($chart_classe, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Votre enfant',
                        data: eleve,
                        backgroundColor: eleve.map(v => v >= 10 ? 'rgba(16, 185, 129, 0.75)' : 'rgba(239, 68, 68, 0.75)'),
                        borderColor:     eleve.map(v => v >= 10 ? 'rgba(16, 185, 129, 1)' : 'rgba(239, 68, 68, 1)'),
                        borderWidth: 1.5,
                        borderRadius: 6,
                        borderSkipped: false,
                    },
                    {
                        label: 'Moyenne classe',
                        data: classe,
                        backgroundColor: 'rgba(148, 163, 184, 0.35)',
                        borderColor: 'rgba(148, 163, 184, 0.7)',
                        borderWidth: 1.5,
                        borderRadius: 6,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255,255,255,0.95)',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: 'rgba(0,0,0,0.08)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: function(ctx) {
                                const val = ctx.raw;
                                if (val === null) return ctx.dataset.label + ': N/D';
                                return ctx.dataset.label + ' : ' + val.toFixed(2).replace('.', ',') + '/20';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 10 },
                            maxRotation: 30,
                        }
                    },
                    y: {
                        min: 0,
                        max: 20,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 },
                            callback: function(v) { return v + '/20'; }
                        }
                    }
                }
            }
        });
    });
    </script>
    <?php endif; ?>

    <?php if ($trimestre_selectionne !== 'Tous'): ?>
        <div class="liquid-card p-4 border border-secondary border-opacity-10 rounded-3 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-file-invoice text-warning me-2"></i>Synthèse du bulletin du conseil</h5>
                <?php if (!empty($bulletin_officiel)): ?>
                    <a href="bulletin_print.php?id=<?= $bulletin_officiel['ID'] ?>" target="_blank" class="btn btn-sm btn-outline-success py-1 px-3">
                        <i class="fa-solid fa-print me-1"></i> Imprimer le bulletin
                    </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($bulletin_officiel)): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle custom-table-glass m-0">
                        <thead>
                            <tr class="text-muted" style="font-size: 11px;">
                                <th>MATIÈRE</th>
                                <th class="text-center">INTERRO (`INTE`)</th>
                                <th class="text-center">DEV. SURVEILLÉ (`DS`)</th>
                                <th class="text-center">DEV. NORMALISÉ (`DN`)</th>
                                <th class="text-center">NOTE COMP.</th>
                                <th class="text-center">MOY. CLASSE</th>
                                <th class="text-center">COEF</th>
                                <th class="text-center">MOY. TRIMESTRE</th>
                                <th>APPRÉCIATION DE L'ENSEIGNANT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($details_bulletin as $row): ?>
                                <tr class="border-bottom border-secondary border-opacity-10-50 small">
                                    <td class="fw-bold"><?= htmlspecialchars($row['NOM_MATIERE'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-center text-info"><?= $row['INTE'] !== null && is_numeric($row['INTE']) ? number_format((float)$row['INTE'], 2, ',', ' ') : '-' ?></td>
                                    <td class="text-center text-info"><?= $row['DS'] !== null && is_numeric($row['DS']) ? number_format((float)$row['DS'], 2, ',', ' ') : '-' ?></td>
                                    <td class="text-center text-info"><?= $row['DN'] !== null && is_numeric($row['DN']) ? number_format((float)$row['DN'], 2, ',', ' ') : '-' ?></td>
                                    <td class="text-center text-muted"><?= $row['NOTES_COMP'] !== null && is_numeric($row['NOTES_COMP']) ? number_format((float)$row['NOTES_COMP'], 2, ',', ' ') : '-' ?></td>
                                    <td class="text-center text-muted small"><?= $row['MOY_CLASSE'] !== null && is_numeric($row['MOY_CLASSE']) ? number_format((float)$row['MOY_CLASSE'], 2, ',', ' ') : '-' ?></td>
                                    <td class="text-center fw-bold"><?= htmlspecialchars((string)($row['COEF'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="text-center">
                                        <span class="fw-bold fs-6 <?= $row['MOY_TRIMES'] >= 10 ? 'text-success' : 'text-danger' ?>">
                                            <?= $row['MOY_TRIMES'] !== null && is_numeric($row['MOY_TRIMES']) ? number_format((float)$row['MOY_TRIMES'], 2, ',', ' ') : '-' ?>
                                        </span>
                                    </td>
                                    <td class="fst-italic text-muted small"><?= htmlspecialchars($row['APPRECIATION'] ?: 'Aucune remarque', ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3 p-3 bg-secondary bg-opacity-10 rounded border-start border-warning border-3">
                    <span class="text-warning fw-bold small d-block mb-1"><i class="fa-solid fa-comment-dots me-1"></i> Décision globale du Conseil de Classe :</span>
                    <p class="m-0 small font-monospace"><?= htmlspecialchars($bulletin_officiel['observation'] ?: 'Aucune observation globale saisie.', ENT_QUOTES, 'UTF-8') ?></p>
                </div>

            <?php else: ?>
                <div class="text-center py-4 text-muted small">
                    <i class="fa-solid fa-circle-exclamation d-block mb-2 text-warning fs-4"></i>
                    Le bulletin officiel de cette période n'a pas encore été arrêté par l'établissement.
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="liquid-card p-4 border border-secondary border-opacity-10 rounded-3">
        <h5 class="fw-bold mb-3"><i class="fa-solid fa-list-check me-2 text-info"></i>Détails du carnet de notes régulier</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle custom-table-glass m-0">
                <thead>
                    <tr class="text-muted" style="font-size: 11px;">
                        <th>MATIÈRE</th>
                        <th class="text-center">PÉRIODE</th>
                        <th class="text-center">INTERRO (`INT`)</th>
                        <th class="text-center">DS</th>
                        <th class="text-center">DN</th>
                        <th class="text-center">COMP.</th>
                        <th class="text-center">MOYENNE COMPILÉE</th>
                        <th class="text-center">COEF</th>
                        <th>OBSERVATION ENSEIGNANT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($notes)): foreach ($notes as $note): ?>
                        <tr class="border-bottom border-secondary border-opacity-10-50 small">
                            <td class="fw-semibold"><?= htmlspecialchars($note['NOM_MATIERE'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="text-center">
                                <span class="badge bg-secondary bg-opacity-20-50 px-2 py-1" style="font-size:9px;">
                                    <?= htmlspecialchars($note['libposition'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </td>
                            <td class="text-center text-muted"><?= is_numeric($note['NOTEINT']) ? number_format((float)$note['NOTEINT'], 2, ',', ' ') : '-' ?></td>
                            <td class="text-center text-muted"><?= is_numeric($note['NOTEDS']) ? number_format((float)$note['NOTEDS'], 2, ',', ' ') : '-' ?></td>
                            <td class="text-center text-muted"><?= is_numeric($note['NOTEDN']) ? number_format((float)$note['NOTEDN'], 2, ',', ' ') : '-' ?></td>
                            <td class="text-center text-muted"><?= is_numeric($note['NOTECOMP']) ? number_format((float)$note['NOTECOMP'], 2, ',', ' ') : '-' ?></td>
                            <td class="text-center">
                                <span class="fw-bold fs-5 <?= $note['MOYENTRIMES'] >= 10 ? 'text-success' : 'text-danger' ?>">
                                    <?= is_numeric($note['MOYENTRIMES']) ? number_format((float)$note['MOYENTRIMES'], 2, ',', ' ') : '-' ?>
                                </span>
                            </td>
                            <td class="text-center fw-bold"><?= htmlspecialchars((string)($note['COEF'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="fst-italic text-muted"><?= htmlspecialchars($note['OBSERVATION'] ?: 'R.A.S.', ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted small">
                                <i class="fa-solid fa-folder-open mb-2 fs-3 d-block text-secondary"></i>
                                Aucune note individuelle n'a été publiée pour cette sélection.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script nonce="<?= htmlspecialchars($GLOBALS['csp_nonce'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
try {
document.getElementById('select-trimestre-notes')?.addEventListener('change', function() {
    try { this.form.submit(); } catch (e) { console.error(e); }
});
} catch (e) { console.error('[Notes] Erreur setup select trimestre:', e); }
</script>
