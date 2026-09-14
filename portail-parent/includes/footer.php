<?php
/**
 * footer.php — Pied de page global et scripts applicatifs
 * 
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);
?>
</main> 
</div> 

<?php 
include __DIR__ . '/navbar_mobile.php'; 
$csp_nonce = (string)($GLOBALS['csp_nonce'] ?? '');
?>

<!-- Conteneur global des notifications Toast -->
<div class="toast-portal-container" id="toastPortalContainer"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script nonce="<?= htmlspecialchars($csp_nonce, ENT_QUOTES, 'UTF-8') ?>">
const toastConfig = {
    success: { icon: 'fa-circle-check', title: 'Succès !' },
    error:   { icon: 'fa-circle-xmark', title: 'Erreur' },
    info:    { icon: 'fa-circle-info',  title: 'Information' },
    warning: { icon: 'fa-triangle-exclamation', title: 'Attention' },
};

function showToast(message, type = 'success', duration = 4500) {
    const container = document.getElementById('toastPortalContainer');
    if (!container || !message) return;

    const config = toastConfig[type] || toastConfig.info;
    const toastEl = document.createElement('div');
    toastEl.className = `glass-toast toast-${type}`;
    toastEl.style.animationDuration = `${duration}ms`;

    toastEl.innerHTML = `
        <div class="glass-toast-icon"><i class="fa-solid ${config.icon}"></i></div>
        <div class="glass-toast-body">
            <div class="glass-toast-title">${config.title}</div>
            <div class="glass-toast-message"></div>
        </div>
        <button class="glass-toast-close" onclick="dismissToast(this.parentElement)" aria-label="Fermer">
            <i class="fa-solid fa-xmark"></i>
        </button>
    `;

    toastEl.querySelector('.glass-toast-message').textContent = message;
    container.appendChild(toastEl);

    const timer = setTimeout(() => dismissToast(toastEl), duration);
    toastEl._toastTimer = timer;
}

function dismissToast(toastEl) {
    if (!toastEl || toastEl.classList.contains('toast-hiding')) return;
    clearTimeout(toastEl._toastTimer);
    toastEl.classList.add('toast-hiding');
    setTimeout(() => toastEl.remove(), 300);
}

document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const statut = params.get('statut');
    const msg    = params.get('msg');

    if (statut === 'success') {
        showToast(decodeURIComponent(msg || 'Opération effectuée avec succès.'), 'success');
    } else if (statut === 'error') {
        showToast(decodeURIComponent(msg || 'Une erreur est survenue.'), 'error');
    } else if (statut === 'warning') {
        showToast(decodeURIComponent(msg || 'Attention.'), 'warning');
    } else if (statut === 'info') {
        showToast(decodeURIComponent(msg || ''), 'info');
    }

    if (statut) {
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }
});
</script>

<script nonce="<?= htmlspecialchars($csp_nonce, ENT_QUOTES, 'UTF-8') ?>">
try {
document.addEventListener('DOMContentLoaded', function () {
    try {
        document.querySelectorAll('a[href*="logout.php"]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                try {
                    var targetUrl = link.getAttribute('href') || '../auth/logout.php';

                    if ('caches' in window) {
                        caches.keys().then(function (keys) {
                            return Promise.all(keys.map(function (k) { return caches.delete(k); }));
                        }).catch(function () {});
                    }

                    if (window.indexedDB) {
                        try {
                            var req = indexedDB.deleteDatabase('ecoleplus_offline');
                            req.onsuccess = function () {};
                            req.onerror = function () {};
                            req.onblocked = function () {};
                        } catch (ignore) {}
                    }

                    window.location.href = targetUrl;
                } catch (innerErr) {
                    window.location.href = link.getAttribute('href') || '../auth/logout.php';
                }
            });
        });
    } catch (setupErr) {}
});
} catch (e) {}
</script>
</body>
</html>