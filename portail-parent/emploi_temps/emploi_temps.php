<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/EmploiTempsController.php';

$controller = new EmploiTempsController($pdo);
$result = $controller->handle();
$emploi = $result['emploi'] ?? [];
$salle = $result['salle'] ?? null;
$error = $result['error'] ?? '';

$jours = ['LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI', 'SAMEDI'];
$creneaux = [];
foreach ($emploi as $e) {
    $jour = $e['JOUR'];
    $creneaux[$jour][] = $e;
}

$allHeures = [];
foreach ($emploi as $e) {
    $key = $e['HEUREDEBUT'] . '-' . $e['HEUREFIN'];
    $allHeures[$key] = ['debut' => $e['HEUREDEBUT'], 'fin' => $e['HEUREFIN']];
}
usort($allHeures, fn($a, $b) => strcmp($a['debut'], $b['debut']));
$allHeures = array_values($allHeures);

$joursShort = ['LUNDI' => 'Lun', 'MARDI' => 'Mar', 'MERCREDI' => 'Mer', 'JEUDI' => 'Jeu', 'VENDREDI' => 'Ven', 'SAMEDI' => 'Sam'];
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/slidebar.php';
?>
<style>
    .schedule-card { border: 0; border-radius: 16px; box-shadow: 0 4px 20px rgba(15,23,42,.06); overflow: hidden; }
    .schedule-header { background: linear-gradient(135deg, #0f766e, #0e7490); color: #fff; padding: 1.5rem; }
    .time-slot { border: 1px solid #e2e8f0; border-radius: 10px; padding: .6rem; text-align: center; min-height: 70px; display: flex; flex-direction: column; justify-content: center; transition: transform .15s, box-shadow .15s; }
    .time-slot:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(15,23,42,.08); }
    .time-slot.filled { background: linear-gradient(135deg, #0d9488, #14b8a6); color: #fff; border-color: transparent; }
    .time-slot.empty { background: #f8fafc; color: #cbd5e1; }
    .time-label { font-size: .75rem; font-weight: 600; color: #64748b; }
    .heure-col { background: #f0fdfa; border-radius: 10px; padding: .5rem .75rem; text-align: center; border: 1px solid #ccfbf1; }
    .heure-col .debut { font-weight: 700; color: #0f766e; font-size: .95rem; }
    .heure-col .fin { font-size: .75rem; color: #94a3b8; }
    .jour-header { font-weight: 700; font-size: .85rem; text-transform: uppercase; letter-spacing: .05em; }
    .legend-dot { width: 12px; height: 12px; border-radius: 3px; display: inline-block; }
    @media (max-width: 767.98px) {
        .schedule-mobile { font-size: .8rem; }
        .time-slot { min-height: 55px; padding: .4rem; }
        .time-slot .small { font-size: .65rem; }
    }
</style>

<main class="main-content">
    <?php include __DIR__ . '/../includes/topbar.php'; ?>
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="fa-solid fa-calendar-days text-success me-2"></i>Emploi du temps</h4>
            <small class="text-muted">
                <?php if ($salle): ?>
                    Classe : <strong><?= htmlspecialchars($salle['NOMSALLE'], ENT_QUOTES, 'UTF-8') ?></strong>
                <?php else: ?>
                    Emploi du temps de <strong><?= htmlspecialchars($enfant_actif['PRENOM_ELEVE'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
                <?php endif; ?>
            </small>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2 small">
                <span class="legend-dot" style="background: linear-gradient(135deg, #0d9488, #14b8a6);"></span> Cours
                <span class="legend-dot bg-light border" style="margin-left: .5rem;"></span> Libre
            </div>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="text-center py-5">
            <i class="fa-solid fa-calendar-xmark text-muted" style="font-size: 3rem;"></i>
            <h5 class="text-muted mt-3"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></h5>
            <p class="text-muted">L'emploi du temps sera disponible une fois l'élève assigné à une classe.</p>
        </div>

    <?php elseif (empty($emploi)): ?>
        <div class="text-center py-5">
            <i class="fa-solid fa-calendar-xmark text-muted" style="font-size: 3rem;"></i>
            <h5 class="text-muted mt-3">Aucun emploi du temps</h5>
            <p class="text-muted">Aucun cours programmé pour cette classe.</p>
        </div>

    <?php else: ?>

        <!-- ═══ VERSION DESKTOP : Tableau ═══ -->
        <div class="d-none d-lg-block">
            <div class="card schedule-card">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0 align-middle text-center" style="min-width: 850px;">
                        <thead>
                            <tr>
                                <th style="width: 11%; background: #f0fdfa;" class="text-center">
                                    <span class="jour-header text-success">Horaire</span>
                                </th>
                                <?php foreach ($jours as $j): ?>
                                    <th style="width: 14.8%; background: #f0fdfa;" class="text-center">
                                        <span class="jour-header text-success"><?= $joursShort[$j] ?></span>
                                        <br><small class="text-muted fw-normal"><?= ucfirst(strtolower($j)) ?></small>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($allHeures as $h): ?>
                                <tr>
                                    <td>
                                        <div class="heure-col">
                                            <div class="debut"><?= htmlspecialchars($h['debut'], ENT_QUOTES, 'UTF-8') ?></div>
                                            <div class="fin"><?= htmlspecialchars($h['fin'], ENT_QUOTES, 'UTF-8') ?></div>
                                        </div>
                                    </td>
                                    <?php foreach ($jours as $j): ?>
                                        <td class="px-1">
                                            <?php
                                            $found = null;
                                            foreach ($creneaux[$j] ?? [] as $c) {
                                                if ($c['HEUREDEBUT'] === $h['debut'] && $c['HEUREFIN'] === $h['fin']) {
                                                    $found = $c;
                                                    break;
                                                }
                                            }
                                            ?>
                                            <?php if ($found): ?>
                                                <div class="time-slot filled">
                                                    <div class="fw-bold mb-1"><?= htmlspecialchars($found['NOM_MATIERE'], ENT_QUOTES, 'UTF-8') ?></div>
                                                    <div class="small opacity-90">
                                                        <i class="fa-solid fa-user-tie me-1"></i>
                                                        <?= htmlspecialchars($found['PROFESSEUR'], ENT_QUOTES, 'UTF-8') ?>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="time-slot empty">
                                                    <i class="fa-solid fa-minus"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ═══ VERSION MOBILE : Cards par jour ═══ -->
        <div class="d-lg-none schedule-mobile">
            <?php foreach ($jours as $j): ?>
                <div class="card schedule-card mb-3">
                    <div class="schedule-header py-2 px-3">
                        <h6 class="mb-0 fw-bold"><?= ucfirst(strtolower($j)) ?></h6>
                    </div>
                    <div class="card-body p-2">
                        <?php $slots = $creneaux[$j] ?? []; ?>
                        <?php if (empty($slots)): ?>
                            <div class="text-center text-muted py-3 small">Aucun cours</div>
                        <?php else: ?>
                            <?php foreach ($slots as $s): ?>
                                <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background: #f0fdfa;">
                                    <div class="heure-col flex-shrink-0" style="min-width: 65px;">
                                        <div class="debut" style="font-size: .8rem;"><?= htmlspecialchars($s['HEUREDEBUT'], ENT_QUOTES, 'UTF-8') ?></div>
                                        <div class="fin" style="font-size: .65rem;"><?= htmlspecialchars($s['HEUREFIN'], ENT_QUOTES, 'UTF-8') ?></div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small text-success"><?= htmlspecialchars($s['NOM_MATIERE'], ENT_QUOTES, 'UTF-8') ?></div>
                                        <div class="text-muted" style="font-size: .75rem;">
                                            <i class="fa-solid fa-user-tie me-1"></i>
                                            <?= htmlspecialchars($s['PROFESSEUR'], ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
