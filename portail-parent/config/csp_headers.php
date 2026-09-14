<?php
/**
 * csp_headers.php — Politique Content-Security-Policy (CSP) stricte (v1.2.0)
 * 
 * Utilise des nonces dynamiques pour les scripts inline.
 * À inclure avant tout output HTML.
 * 
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);

if (php_sapi_name() === 'cli' || headers_sent()) {
    error_log("[CSP] ALERTE : en-têtes déjà envoyés avant csp_headers.php, CSP non appliquée");
    return;
}

// ---------------------------------------------------------------------------
// Génération de nonces CSP (uniques par requête, pas par session)
// ---------------------------------------------------------------------------
$csp_nonce = bin2hex(random_bytes(16));
$GLOBALS['csp_nonce'] = $csp_nonce;

// ---------------------------------------------------------------------------
// Domaines autorisés
// ---------------------------------------------------------------------------
$scriptSources = implode(' ', [
    "'self'",
    "https://cdn.jsdelivr.net",
    "https://cdnjs.cloudflare.com",
    "'nonce-{$csp_nonce}'",
]);

$styleSources = implode(' ', [
    "'self'",
    "https://cdn.jsdelivr.net",
    "https://cdnjs.cloudflare.com",
    "https://fonts.googleapis.com",
    "'unsafe-inline'", // Requis pour les styles inline Bootstrap
]);

$fontSources = implode(' ', [
    "'self'",
    "https://cdnjs.cloudflare.com",
    "https://fonts.gstatic.com",
]);

$imageSources = implode(' ', [
    "'self'",
    "data:",
    "blob:",
]);

$connectSources = implode(' ', [
    "'self'",
    // Les navigateurs téléchargent les source maps des bibliothèques CDN dans
    // les outils de développement ; sans ces origines, la CSP génère un bruit
    // inutile dans la console.
    "https://cdn.jsdelivr.net",
    "https://cdnjs.cloudflare.com",
    "http://localhost:3000",
    "https://localhost:3000",
    "ws://localhost:8080",
    "wss:",
]);

// ---------------------------------------------------------------------------
// En-tête CSP principal
// ---------------------------------------------------------------------------
$csp = implode('; ', [
    "default-src 'self'",
    "script-src $scriptSources",
    "style-src $styleSources",
    "font-src $fontSources",
    "img-src $imageSources",
    "connect-src $connectSources",
    "frame-src 'self'",
    "object-src 'none'",
    "base-uri 'self'",
    "form-action 'self'",
    "frame-ancestors 'self'",
]);

header("Content-Security-Policy: $csp");

// ---------------------------------------------------------------------------
// Headers de sécurité complémentaires
// ---------------------------------------------------------------------------
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()");

if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
}
