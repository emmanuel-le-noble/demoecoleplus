/**
 * offline-banner.js — Indicateur de statut réseau et synchronisation
 *
 * Affiche une bannière quand l'utilisateur est hors-ligne,
 * et synchronise les données IndexedDB quand la connexion revient.
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */

try {
(function() {
    'use strict';

    const SYNC_URL = '/demoecoleplus/portail-parent/config/offline_sync.php';
    const SYNC_INTERVAL = 300000; // 5 minutes

    let banner = null;
    let isOnline = navigator.onLine;
    let syncTimer = null;
    let isSyncing = false;

    // ─────────────────────────────────────────────────────────
    // Créer la bannière UI
    // ─────────────────────────────────────────────────────────
    function createBanner() {
        if (banner) return;
        try {
            banner = document.createElement('div');
            banner.id = 'offline-banner';
            banner.style.cssText = [
                'position: fixed; top: 0; left: 0; right: 0; z-index: 9999;',
                'padding: 8px 16px; text-align: center; font-size: 14px; font-weight: 600;',
                'transform: translateY(-100%); transition: transform 0.3s ease;',
                'display: none;'
            ].join(' ');
            document.body.appendChild(banner);
        } catch (e) {
            console.error('[Offline] Erreur création bannière:', e);
        }
    }

    function showBanner(message, type) {
        try {
            createBanner();
            if (!banner) return;
            banner.textContent = message;
            banner.style.display = 'block';

            if (type === 'offline') {
                banner.style.background = '#fef2f2';
                banner.style.color = '#dc2626';
                banner.style.borderBottom = '2px solid #dc2626';
            }

            requestAnimationFrame(function () {
                if (banner) banner.style.transform = 'translateY(0)';
            });

        } catch (e) {
            console.error('[Offline] Erreur affichage bannière:', e);
        }
    }

    function hideBanner() {
        try {
            if (banner) {
                banner.style.transform = 'translateY(-100%)';
                setTimeout(function () { if (banner) banner.style.display = 'none'; }, 300);
            }
        } catch (e) {
            console.error('[Offline] Erreur masquage bannière:', e);
        }
    }

    // ─────────────────────────────────────────────────────────
    // Synchronisation IndexedDB — ISOLÉE : aucun rejet non capturé
    // ─────────────────────────────────────────────────────────
    async function syncData() {
        try {
            if (isSyncing) return;
            if (!navigator.onLine || !window.ecoleplusDB) return;
            
            isSyncing = true;
            try {
                const response = await fetch(SYNC_URL + '?module=all');
                if (!response.ok) throw new Error('Sync failed (HTTP ' + response.status + ')');

                const data = await response.json();
                if (!data.success) throw new Error(data.error || 'success=false');

                const db = window.ecoleplusDB;
                await db.open();

                if (db && data.notes) await db.syncNotes(data.eleve_id, data.notes);
                if (db && data.absences) await db.syncAbsences(data.eleve_id, data.absences);
                if (db && data.annonces) await db.syncAnnonces(data.annonces);
                if (db && data.cahier_texte) await db.syncCahierTexte(data.eleve_id, data.cahier_texte);
                if (db && data.profil) {
                    // Le store IndexedDB utilise ID_ELEVE comme clé alors que
                    // l’API renvoie id_eleve en snake_case.
                    const profil = {
                        ...data.profil,
                        ID_ELEVE: data.profil.ID_ELEVE || data.profil.id_eleve || data.eleve_id
                    };
                    if (profil.ID_ELEVE) await db.put('profil', profil);
                }

                console.log('[Offline] Données synchronisées le', data.timestamp);

            } finally {
                isSyncing = false;
            }
        } catch (err) {
            console.error('[Offline] Échec sync (isolé):', err.message, err.stack);
        }
    }

    // ─────────────────────────────────────────────────────────
    // Gestionnaires d'événements réseau
    // ─────────────────────────────────────────────────────────
    function handleOnline() {
        try {
            isOnline = true;
            // La synchronisation reste silencieuse : elle ne doit pas interrompre
            // la navigation à chaque chargement de page.
            syncData().catch(function (e) {
                console.error('[Offline] sync post-online échouée:', e);
            });
            startSyncTimer();
        } catch (e) {
            console.error('[Offline] Erreur handleOnline:', e);
        }
    }

    function handleOffline() {
        try {
            isOnline = false;
            showBanner('📡 Mode hors-ligne — données en cache', 'offline');
            stopSyncTimer();
        } catch (e) {
            console.error('[Offline] Erreur handleOffline:', e);
        }
    }

    function startSyncTimer() {
        try {
            stopSyncTimer();
            syncTimer = setInterval(function () {
                syncData().catch(function (e) {
                    console.error('[Offline] sync timer échouée:', e);
                });
            }, SYNC_INTERVAL);
        } catch (e) {
            console.error('[Offline] Erreur startSyncTimer:', e);
        }
    }

    function stopSyncTimer() {
        try {
            if (syncTimer) {
                clearInterval(syncTimer);
                syncTimer = null;
            }
        } catch (e) {
            console.error('[Offline] Erreur stopSyncTimer:', e);
        }
    }

    // ─────────────────────────────────────────────────────────
    // Initialisation
    // ─────────────────────────────────────────────────────────
    function init() {
        try {
            window.addEventListener('online', handleOnline);
            window.addEventListener('offline', handleOffline);
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopSyncTimer();
                } else if (navigator.onLine) {
                    startSyncTimer();
                }
            });
            window.addEventListener('beforeunload', stopSyncTimer);

            if (navigator.onLine) {
                syncData().catch(function (e) {
                    console.error('[Offline] sync initiale échouée:', e);
                });
                startSyncTimer();
            } else {
                handleOffline();
            }
        } catch (e) {
            console.error('[Offline] Erreur init:', e);
        }
    }

    // Démarrer quand le DOM est prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Export pour usage externe
    try {
        window.ecoleplusOffline = {
            sync: syncData,
            isOnline: function () { return isOnline; }
        };
    } catch (e) {
        console.error('[Offline] Erreur export:', e);
    }
})();
} catch (e) {
    console.error('[Offline] Échec total du module offline-banner:', e);
}
