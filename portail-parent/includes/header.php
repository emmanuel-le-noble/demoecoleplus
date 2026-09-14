<?php
/**
 * header.php — En-tête global du portail (v1.2.0)
 * 
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

// Injection des headers de sécurité AVANT tout output HTML
require_once __DIR__ . '/../config/csp_headers.php';

// Session + données enfant
require_once __DIR__ . '/session.php';

// Récupérer le nonce CSP généré dans csp_headers.php
$csp_nonce = (string)($GLOBALS['csp_nonce'] ?? '');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecole Plus - Portail Parent</title>
    <link rel="manifest" href="../manifest.json">
    <meta name="theme-color" id="theme-color" content="#0f766e">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Ecole Plus">
    <script nonce="<?= htmlspecialchars($csp_nonce, ENT_QUOTES, 'UTF-8') ?>">
        try {
            if (localStorage.getItem('ecoleplus-theme') === 'dark') {
                document.documentElement.dataset.theme = 'dark';
            }
        } catch (e) {}
    </script>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <!-- PWA : Service Worker avec nonce CSP -->
    <script nonce="<?= htmlspecialchars($csp_nonce, ENT_QUOTES, 'UTF-8') ?>">
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('../sw.js')
                    .then(function(reg) { console.log('[PWA] Service Worker enregistré:', reg.scope); })
                    .catch(function(err) { console.warn('[PWA] Erreur Service Worker:', err); });
            });
        }
    </script>
    <!-- PWA : IndexedDB + bannière hors-ligne -->
    <script src="../assets/js/offline-db.js" nonce="<?= htmlspecialchars($csp_nonce, ENT_QUOTES, 'UTF-8') ?>"></script>
    <script src="../assets/js/offline-banner.js" nonce="<?= htmlspecialchars($csp_nonce, ENT_QUOTES, 'UTF-8') ?>"></script>
</head>
<body>
<div class="app-container">
