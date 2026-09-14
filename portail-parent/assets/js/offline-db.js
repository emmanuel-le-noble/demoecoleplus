/**
 * offline-db.js — Gestionnaire IndexedDB pour le mode hors-ligne
 *
 * Stocke localement les données essentielles (notes, absences, annonces,
 * profil enfant) pour affichage hors-ligne via la PWA.
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */

class EcolePlusDB {
    constructor() {
        this.dbName = 'ecoleplus_offline';
        this.dbVersion = 1;
        this.db = null;
    }

    /**
     * Ouvrir/créer la base IndexedDB
     */
    async open() {
        if (this.db) return this.db;

        // Vérifier la disponibilité d'IndexedDB
        if (!window.indexedDB) {
            console.warn('[DB] IndexedDB non disponible');
            return null;
        }

        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);

            request.onupgradeneeded = (event) => {
                const db = event.target.result;

                // Store : profil enfant (key: ID_ELEVE)
                if (!db.objectStoreNames.contains('profil')) {
                    db.createObjectStore('profil', { keyPath: 'ID_ELEVE' });
                }

                // Store : notes par matière
                if (!db.objectStoreNames.contains('notes')) {
                    const notesStore = db.createObjectStore('notes', { keyPath: ['eleve_id', 'matiere', 'periode'] });
                    notesStore.createIndex('eleve_id', 'eleve_id', { unique: false });
                }

                // Store : absences
                if (!db.objectStoreNames.contains('absences')) {
                    db.createObjectStore('absences', { keyPath: 'id' });
                }

                // Store : annonces
                if (!db.objectStoreNames.contains('annonces')) {
                    db.createObjectStore('annonces', { keyPath: 'ID' });
                }

                // Store : cahier de textes
                if (!db.objectStoreNames.contains('cahier_texte')) {
                    db.createObjectStore('cahier_texte', { keyPath: 'id' });
                }

                // Store : métadonnées (dernière sync, etc.)
                if (!db.objectStoreNames.contains('meta')) {
                    db.createObjectStore('meta', { keyPath: 'key' });
                }
            };

            request.onsuccess = (event) => {
                this.db = event.target.result;
                resolve(this.db);
            };

            request.onerror = (event) => {
                console.error('[DB] Erreur ouverture IndexedDB:', event.target.error);
                reject(event.target.error);
            };
        });
    }

    /**
     * Sauvegarder des données dans un store
     */
    async put(storeName, data) {
        const db = await this.open();
        if (!db) return;
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            const request = store.put(data);
            request.onsuccess = () => resolve();
            request.onerror = (e) => reject(e.target.error);
        });
    }

    /**
     * Sauvegarder plusieurs éléments
     */
    async putAll(storeName, items) {
        const db = await this.open();
        if (!db || !Array.isArray(items)) return;
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            items.forEach(item => store.put(item));
            tx.oncomplete = () => resolve();
            tx.onerror = (e) => reject(e.target.error);
        });
    }

    /**
     * Lire un élément par clé
     */
    async get(storeName, key) {
        const db = await this.open();
        if (!db) return null;
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const request = store.get(key);
            request.onsuccess = () => resolve(request.result);
            request.onerror = (e) => reject(e.target.error);
        });
    }

    /**
     * Lire tous les éléments d'un store
     */
    async getAll(storeName) {
        const db = await this.open();
        if (!db) return [];
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const request = store.getAll();
            request.onsuccess = () => resolve(request.result);
            request.onerror = (e) => reject(e.target.error);
        });
    }

    /**
     * Lire par index
     */
    async getByIndex(storeName, indexName, value) {
        const db = await this.open();
        if (!db) return [];
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const index = store.index(indexName);
            const request = index.getAll(value);
            request.onsuccess = () => resolve(request.result);
            request.onerror = (e) => reject(e.target.error);
        });
    }

    /**
     * Supprimer un élément
     */
    async delete(storeName, key) {
        const db = await this.open();
        if (!db) return;
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            const request = store.delete(key);
            request.onsuccess = () => resolve();
            request.onerror = (e) => reject(e.target.error);
        });
    }

    /**
     * Vider un store
     */
    async clear(storeName) {
        const db = await this.open();
        if (!db) return;
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            const request = store.clear();
            request.onsuccess = () => resolve();
            request.onerror = (e) => reject(e.target.error);
        });
    }

    /**
     * Enregistrer la date de dernière synchronisation
     */
    async setLastSync(storeName) {
        if (!(await this.open())) return;
        await this.put('meta', {
            key: `last_sync_${storeName}`,
            value: new Date().toISOString()
        });
    }

    /**
     * Récupérer la date de dernière synchronisation
     */
    async getLastSync(storeName) {
        if (!(await this.open())) return null;
        const meta = await this.get('meta', `last_sync_${storeName}`);
        return meta ? meta.value : null;
    }

    // ─────────────────────────────────────────────────────────────
    // MÉTHODES SPÉCIFIQUES PAR MODULE
    // ─────────────────────────────────────────────────────────────

    /**
     * Sync atomique : clear + putAll dans une seule transaction
     */
    async syncStore(storeName, items) {
        const db = await this.open();
        if (!db || !Array.isArray(items)) return;
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            store.clear();
            items.forEach(item => store.put(item));
            tx.oncomplete = () => resolve();
            tx.onerror = (e) => reject(e.target.error);
        });
    }

    /**
     * Synchroniser les notes d'un élève
     */
    async syncNotes(eleveId, notes) {
        if (!Array.isArray(notes)) { console.warn('[DB] syncNotes: notes non tableau'); return; }
        const items = notes.map(n => ({
            eleve_id: eleveId,
            matiere: n.NOM_MATIERE || '',
            periode: n.libposition || '',
            moyenne: parseFloat(n.MOYENTRIMES) || 0,
            note_int: parseFloat(n.NOTEINT) || 0,
            note_ds: parseFloat(n.NOTEDS) || 0,
            note_dn: parseFloat(n.NOTEDN) || 0,
            coef: parseInt(n.COEF) || 1,
            observation: n.OBSERVATION || ''
        }));
        await this.syncStore('notes', items);
        await this.setLastSync('notes');
    }

    /**
     * Synchroniser les absences
     */
    async syncAbsences(eleveId, absences) {
        if (!Array.isArray(absences)) { console.warn('[DB] syncAbsences: absences non tableau'); return; }
        const items = absences.map(a => ({
            ...a,
            eleve_id: eleveId
        }));
        await this.syncStore('absences', items);
        await this.setLastSync('absences');
    }

    /**
     * Synchroniser les annonces
     */
    async syncAnnonces(annonces) {
        if (!Array.isArray(annonces)) { console.warn('[DB] syncAnnonces: annonces non tableau'); return; }
        await this.syncStore('annonces', annonces);
        await this.setLastSync('annonces');
    }

    /**
     * Synchroniser le cahier de textes
     */
    async syncCahierTexte(eleveId, items) {
        if (!Array.isArray(items)) { console.warn('[DB] syncCahierTexte: items non tableau'); return; }
        const data = items.map(c => ({
            ...c,
            eleve_id: eleveId
        }));
        await this.syncStore('cahier_texte', data);
        await this.setLastSync('cahier_texte');
    }

    /**
     * Vérifier si des données sont disponibles hors-ligne
     */
    async hasOfflineData() {
        const db = await this.open();
        if (!db) return false;
        const stores = ['notes', 'absences', 'annonces', 'cahier_texte'];
        for (const storeName of stores) {
            const count = await new Promise((resolve, reject) => {
                const tx = db.transaction(storeName, 'readonly');
                const store = tx.objectStore(storeName);
                const request = store.count();
                request.onsuccess = () => resolve(request.result);
                request.onerror = () => resolve(0);
            });
            if (count > 0) return true;
        }
        return false;
    }
}

// Instance globale
window.ecoleplusDB = new EcolePlusDB();
