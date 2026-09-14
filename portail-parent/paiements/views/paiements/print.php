<?php
/**
 * Vue : Impression reçu / relevé financier
 *
 * Variables attendues via $data :
 *   type, paiement, eleve, parent, libelle_annee, reference
 *   montant_total, total_paye, total_reste, pourcentage, historique
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if ($data['type'] === 'recu' && $data['paiement']): ?>
    <title>Reçu <?= $data['reference'] ?> - Ecole Plus</title>
    <?php else: ?>
    <title>Relevé financier <?= htmlspecialchars($data['libelle_annee']) ?> - Ecole Plus</title>
    <?php endif; ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* -- Variables & Reset ----------------------- */
        :root {
            --green: #10b981;
            --green-dark: #059669;
            --blue:  #06b6d4;
            --text:  #1e293b;
            --muted: #64748b;
            --bg:    #f8fafc;
            --border:#e2e8f0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
            line-height: 1.6;
        }

        /* -- Boutons de contrôle (masqués à l'impression) -- */
        .print-controls {
            position: fixed;
            bottom: 24px;
            right: 24px;
            display: flex;
            gap: 10px;
            z-index: 999;
        }
        .btn-print {
            background: var(--green);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 22px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 20px rgba(16,185,129,.35);
            transition: transform .15s, box-shadow .15s;
        }
        .btn-print:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(16,185,129,.45); }
        .btn-close-page {
            background: #fff;
            color: var(--muted);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 14px;
            cursor: pointer;
        }

        /* -- Document papier -------------------------- */
        .document {
            max-width: 780px;
            margin: 30px auto;
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0,0,0,.09);
        }

        /* -- En-tête du document ---------------------- */
        .doc-header {
            background: linear-gradient(135deg, var(--green-dark) 0%, var(--green) 60%, #34d399 100%);
            color: #fff;
            padding: 32px 36px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .doc-header h1 { font-size: 22px; font-weight: 800; margin-bottom: 2px; }
        .doc-header .subtitle { font-size: 12px; opacity: .82; font-weight: 400; }

        .badge-doc {
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.35);
            backdrop-filter: blur(6px);
            border-radius: 10px;
            padding: 10px 18px;
            text-align: right;
        }
        .badge-doc .ref { font-size: 18px; font-weight: 800; letter-spacing: .5px; }
        .badge-doc .ref-label { font-size: 10px; opacity: .75; text-transform: uppercase; letter-spacing: 1px; }

        /* -- Section infos ---------------------------- */
        .doc-body { padding: 30px 36px; }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 28px;
        }

        .info-card {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px 18px;
        }
        .info-card-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .info-card-title i { color: var(--green); }
        .info-value { font-weight: 600; font-size: 14px; color: var(--text); }
        .info-sub   { font-size: 12px; color: var(--muted); margin-top: 2px; }

        /* -- Séparateur ------------------------------- */
        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 24px 0;
        }

        /* -- Montant principal (reçu) ----------------- */
        .amount-box {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1.5px solid #a7f3d0;
            border-radius: 14px;
            padding: 24px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
        }
        .amount-label { font-size: 13px; font-weight: 600; color: var(--green-dark); }
        .amount-value { font-size: 32px; font-weight: 800; color: var(--green-dark); line-height: 1; }
        .amount-currency { font-size: 14px; font-weight: 500; color: var(--muted); }
        .amount-icon { font-size: 36px; color: #a7f3d0; }

        /* -- Tableau du relevé ------------------------ */
        .releve-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .releve-table th {
            background: var(--bg);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--muted);
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }
        .releve-table td {
            padding: 11px 14px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
            color: var(--text);
        }
        .releve-table tr:last-child td { border-bottom: none; }
        .releve-table .ref-cell { font-weight: 700; font-family: monospace; color: var(--green-dark); }
        .releve-table .amount-cell { text-align: right; font-weight: 700; color: var(--green-dark); }

        /* -- Résumé financier (relevé) ---------------- */
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 14px;
            margin-bottom: 22px;
        }
        .summary-item {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px;
            text-align: center;
        }
        .summary-item .s-label { font-size: 10px; color: var(--muted); text-transform: uppercase; letter-spacing: .7px; font-weight: 600; }
        .summary-item .s-value { font-size: 18px; font-weight: 800; margin-top: 4px; }
        .s-total  { color: var(--text); }
        .s-paid   { color: var(--green-dark); }
        .s-rest   { color: #f59e0b; }
        .s-rest.ok { color: var(--green-dark); }

        /* -- Barre de progression --------------------- */
        .progress-wrap { margin-bottom: 28px; }
        .progress-bar-track {
            height: 10px;
            background: #e2e8f0;
            border-radius: 99px;
            overflow: hidden;
            margin: 6px 0;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, var(--green-dark), var(--green));
            transition: width .4s ease;
        }
        .progress-labels { display: flex; justify-content: space-between; font-size: 11px; color: var(--muted); }

        /* -- Pied de document ------------------------- */
        .doc-footer {
            background: var(--bg);
            border-top: 1px solid var(--border);
            padding: 18px 36px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: var(--muted);
        }
        .doc-footer .seal {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: var(--green-dark);
            font-size: 12px;
        }
        .doc-footer .seal i { font-size: 18px; }

        /* -- Mentions légales ------------------------- */
        .legal {
            font-size: 10px;
            color: var(--muted);
            text-align: center;
            margin-top: 20px;
            line-height: 1.6;
        }

        /* -- STYLES D'IMPRESSION ---------------------- */
        @media print {
            body { background: #fff; font-size: 12px; }
            .print-controls { display: none !important; }
            .document {
                margin: 0;
                border-radius: 0;
                box-shadow: none;
                max-width: 100%;
            }
            .doc-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .amount-box  { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .progress-bar-fill { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .releve-table,
            .releve-table tr,
            .releve-table td,
            .releve-table th {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .info-card,
            .info-grid > div {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .summary-grid,
            .summary-item {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            @page { margin: 10mm; size: A4 portrait; }
        }

        @media (max-width: 600px) {
            .doc-header  { flex-direction: column; padding: 22px 20px; }
            .doc-body    { padding: 20px; }
            .info-grid   { grid-template-columns: 1fr; }
            .summary-grid{ grid-template-columns: 1fr; }
            .doc-footer  { flex-direction: column; gap: 8px; text-align: center; padding: 16px 20px; }
            .amount-box  { flex-direction: column; gap: 12px; text-align: center; }
            .print-controls { bottom: 12px; right: 12px; }
        }
    </style>
</head>
<body>

<!-- Boutons de contrôle flottants -->
<div class="print-controls">
    <button class="btn-close-page" onclick="window.close()" title="Fermer">
        <i class="fa-solid fa-xmark"></i>
    </button>
    <button class="btn-print" onclick="window.print()">
        <i class="fa-solid fa-print"></i> Imprimer / Enregistrer PDF
    </button>
</div>

<div class="document">

    <!-- == EN-TÊTE == -->
    <div class="doc-header">
        <div>
            <div style="font-size:11px;opacity:.7;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">
                <?= $data['type'] === 'recu' ? 'Reçu de paiement' : 'Relevé financier annuel' ?>
            </div>
            <h1>Ecole Plus</h1>
            <div class="subtitle">Portail Parent &nbsp;·&nbsp; Année scolaire <?= htmlspecialchars($data['libelle_annee']) ?></div>
            <div class="subtitle" style="margin-top:6px;">
                <i class="fa-regular fa-calendar me-1"></i>Émis le <?= date('d/m/Y à H:i') ?>
            </div>
        </div>

        <div class="badge-doc">
            <?php if ($data['type'] === 'recu' && $data['paiement']): ?>
                <div class="ref-label">Référence reçu</div>
                <div class="ref"><?= $data['reference'] ?></div>
            <?php else: ?>
                <div class="ref-label">Type de document</div>
                <div class="ref" style="font-size:14px;">Relevé Annuel</div>
            <?php endif; ?>
            <div style="font-size:10px;opacity:.7;margin-top:6px;">
                <i class="fa-solid fa-shield-check"></i> Document officiel
            </div>
        </div>
    </div>

    <!-- == CORPS == -->
    <div class="doc-body">

        <!-- Grille d'informations : Élève & Parent -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-card-title"><i class="fa-solid fa-user-graduate"></i> Informations de l'élève</div>
                <div class="info-value"><?= htmlspecialchars(($data['eleve']['PRENOM_ELEVE'] ?? '') . ' ' . ($data['eleve']['NOM_ELEVE'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                <div class="info-sub">
                    <?php if (!empty($data['eleve']['MATRICULE'])): ?>
                        Matricule : <?= htmlspecialchars($data['eleve']['MATRICULE']) ?><br>
                    <?php endif; ?>
                    Classe : <?= htmlspecialchars($data['eleve']['NOMSALLE'] ?? 'N/D', ENT_QUOTES, 'UTF-8') ?>
                </div>
            </div>
            <div class="info-card">
                <div class="info-card-title"><i class="fa-solid fa-user-tie"></i> Informations du parent</div>
                <div class="info-value"><?= htmlspecialchars(($data['parent']['PRENOM_PARENT'] ?? '') . ' ' . ($data['parent']['NOM_PARENT'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                <div class="info-sub">
                    <?php if (!empty($data['parent']['TEL_PARENT'])): ?>
                        Tél : <?= htmlspecialchars($data['parent']['TEL_PARENT']) ?><br>
                    <?php endif; ?>
                    <?php if (!empty($data['parent']['MAIL_PARENT'])): ?>
                        Email : <?= htmlspecialchars($data['parent']['MAIL_PARENT']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <hr class="divider">

        <?php if ($data['type'] === 'recu' && $data['paiement']): ?>
        <!-- ==========================
             MODE : REÇU INDIVIDUEL
        ========================== -->

        <!-- Montant versé -->
        <div class="amount-box">
            <div>
                <div class="amount-label">Montant versé</div>
                <div class="amount-value">
                    <?= fcfa($data['paiement']['MONTANT']) ?>
                </div>
                <div style="font-size:12px;color:#059669;margin-top:6px;">
                    <i class="fa-solid fa-circle-check me-1"></i>Paiement validé et enregistré
                </div>
            </div>
            <i class="fa-solid fa-money-bill-wave amount-icon"></i>
        </div>

        <!-- Détails du paiement -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-card-title"><i class="fa-regular fa-calendar"></i> Date du versement</div>
                <div class="info-value"><?= date('d/m/Y', strtotime($data['paiement']['DATE'])) ?></div>
            </div>
            <div class="info-card">
                <div class="info-card-title"><i class="fa-solid fa-user"></i> Payeur enregistré</div>
                <div class="info-value"><?= htmlspecialchars($data['paiement']['NOMPAYEUR'] ?: 'Non renseigné', ENT_QUOTES, 'UTF-8') ?></div>
            </div>
            <div class="info-card">
                <div class="info-card-title"><i class="fa-solid fa-tag"></i> Motif du versement</div>
                <div class="info-value">Frais de scolarité</div>
                <div class="info-sub">Année <?= htmlspecialchars($data['libelle_annee']) ?></div>
            </div>
            <div class="info-card">
                <div class="info-card-title"><i class="fa-solid fa-info-circle"></i> Statut</div>
                <div class="info-value" style="color:var(--green-dark);">
                    <i class="fa-solid fa-circle-check"></i> Validé
                </div>
            </div>
        </div>

        <?php else: ?>
        <!-- ==========================
             MODE : RELEVÉ COMPLET
        ========================== -->

        <!-- Résumé financier -->
        <div class="summary-grid">
            <div class="summary-item">
                <div class="s-label">Total scolarité</div>
                <div class="s-value s-total"><?= fcfa($data['montant_total']) ?></div>
            </div>
            <div class="summary-item">
                <div class="s-label">Montant payé</div>
                <div class="s-value s-paid"><?= fcfa($data['total_paye']) ?></div>
            </div>
            <div class="summary-item">
                <div class="s-label">Reste à payer</div>
                <div class="s-value s-rest <?= $data['total_reste'] == 0 ? 'ok' : '' ?>"><?= fcfa($data['total_reste']) ?></div>
            </div>
        </div>

        <!-- Barre de progression -->
        <div class="progress-wrap">
            <div class="progress-labels">
                <span>Progression du règlement</span>
                <span style="font-weight:700;color:var(--green-dark);"><?= $data['pourcentage'] ?>%</span>
            </div>
            <div class="progress-bar-track">
                <div class="progress-bar-fill" style="width:<?= $data['pourcentage'] ?>%;"></div>
            </div>
        </div>

        <hr class="divider">

        <!-- Tableau historique des versements -->
        <h6 style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:14px;">
            <i class="fa-solid fa-list-check" style="color:var(--green);margin-right:6px;"></i>
            Détail des versements effectués (<?= count($data['historique']) ?> opération<?= count($data['historique']) > 1 ? 's' : '' ?>)
        </h6>

        <?php if (!empty($data['historique'])): ?>
        <table class="releve-table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Date</th>
                    <th>Payeur enregistré</th>
                    <th>Motif</th>
                    <th style="text-align:right;">Montant (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['historique'] as $p): ?>
                <tr>
                    <td class="ref-cell">REC-<?= str_pad((string)$p['ID'], 5, '0', STR_PAD_LEFT) ?></td>
                    <td><?= date('d/m/Y', strtotime($p['DATE'])) ?></td>
                    <td><?= htmlspecialchars($p['NOMPAYEUR'] ?: 'Non renseigné', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>Frais de scolarité</td>
                    <td class="amount-cell"><?= fcfa($p['MONTANT']) ?></td>
                </tr>
                <?php endforeach; ?>
                <!-- Ligne de total -->
                <tr style="background:#f0fdf4;">
                    <td colspan="4" style="font-weight:700;font-size:13px;padding:12px 14px;">TOTAL VERSÉ</td>
                    <td class="amount-cell" style="font-size:15px;"><?= fcfa($data['total_paye']) ?></td>
                </tr>
            </tbody>
        </table>
        <?php else: ?>
        <div style="text-align:center;padding:30px;color:var(--muted);">
            <i class="fa-solid fa-receipt" style="font-size:32px;margin-bottom:10px;display:block;color:#cbd5e1;"></i>
            Aucun versement enregistré pour cette année scolaire.
        </div>
        <?php endif; ?>

        <?php endif; ?>

        <!-- Mentions légales -->
        <div class="legal">
            Ce document est généré automatiquement par le Portail Parent Ecole Plus.<br>
            Il constitue une preuve officielle de paiement. Pour toute contestation, veuillez vous adresser à l'administration.
        </div>
    </div>

    <!-- == PIED DE DOCUMENT == -->
    <div class="doc-footer">
        <div class="seal">
            <i class="fa-solid fa-shield-check"></i>
            Document certifié — Ecole Plus
        </div>
        <div>Imprimé le <?= date('d/m/Y à H:i') ?></div>
    </div>

</div>

</body>
</html>
