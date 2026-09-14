/**
 * ws-client.js — Client WebSocket pour la messagerie Ecole Plus
 * 
 * Gère la connexion temps réel, la reconnexion automatique,
 * l'injection dynamique des messages, et le fallback AJAX.
 * 
 * Ecole Plus v1.2.0 — 2026-07-05
 */

class EcolePlusWS {
    constructor(config = {}) {
        this.wsUrl = config.wsUrl || 'ws://localhost:8080';
        this.conversationId = config.conversationId || 0;
        this.parentId = config.parentId || 0;
        this.authToken = config.authToken || '';
        this.containerSelector = config.containerSelector || '#chat-scroller';
        this.reconnectInterval = config.reconnectInterval || 3000;
        this.maxReconnectAttempts = config.maxReconnectAttempts || 10;
        this.pingInterval = config.pingInterval || 30000;

        this.ws = null;
        this.reconnectAttempts = 0;
        this.pingTimer = null;
        this.reconnectTimer = null;
        this.isDisconnecting = false;
        this.isOnline = navigator.onLine;
        this.messageCount = 0;

        this._onMessageCallback = config.onMessage || null;
        this._onStatusCallback = config.onStatus || null;
        this._onFallbackCallback = config.onFallback || null;

        // Réinitialiser la reconnexion dès que le réseau revient
        this._onOnlineHandler = () => {
            if (this.reconnectAttempts >= this.maxReconnectAttempts) {
                this.reconnectAttempts = 0;
                console.log('[WS] Réseau restauré — reprise des tentatives de connexion');
                this._attemptReconnect();
            }
        };
        window.addEventListener('online', this._onOnlineHandler);
    }

    /**
     * Initialiser la connexion WebSocket
     */
    connect() {
        this.isDisconnecting = false;
        if (this.conversationId <= 0 || this.parentId <= 0) {
            console.warn('[WS] Impossible de se connecter : conversation ou parent_id manquant');
            return false;
        }

        const auth = encodeURIComponent(this.authToken || '');
        const url = `${this.wsUrl}?conversation=${this.conversationId}&parent_id=${this.parentId}&user_type=PARENT&auth=${auth}`;
        
        try {
            this.ws = new WebSocket(url);
            this._setupEventListeners();
            return true;
        } catch (e) {
            console.error('[WS] Erreur connexion :', e);
            return false;
        }
    }

    /**
     * Configurer les écouteurs d'événements
     */
    _setupEventListeners() {
        this.ws.onopen = () => {
            console.log('[WS] Connecté à', this.wsUrl);
            this.reconnectAttempts = 0;
            this._startPing();
            this._updateStatus('connected');

            // Rejoindre la conversation
            this.send({
                type: 'join',
                conversation: this.conversationId,
            });
        };

        this.ws.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);
                this._handleMessage(data);
            } catch (e) {
                console.error('[WS] Erreur parsing message :', e);
            }
        };

        this.ws.onclose = (event) => {
            console.log('[WS] Déconnecté (code:', event.code, ')');
            this._stopPing();
            this._updateStatus('disconnected');
            this._attemptReconnect();
        };

        this.ws.onerror = (error) => {
            console.error('[WS] Erreur WebSocket :', error);
            this._updateStatus('error');
        };
    }

    /**
     * Traiter un message reçu
     */
    _handleMessage(data) {
        switch (data.type) {
            case 'new_message':
                this._injectMessage(data.message);
                this._playNotificationSound();
                if (this._onMessageCallback) {
                    this._onMessageCallback(data);
                }
                break;

            case 'pong':
                // Réponse au ping — connexion vivante
                break;

            case 'joined':
                console.log('[WS] Rejoint conversation', data.conversation);
                break;

            case 'error':
                console.warn('[WS] Erreur serveur :', data.message);
                break;
        }
    }

    /**
     * Injecter un message dans le chat
     */
    _injectMessage(message) {
        const container = document.querySelector(this.containerSelector);
        if (!container) return;

        const isParent = (message.EXPEDITEUR_TYPE === 'PARENT');
        const messageDate = new Date(message.DATE_ENVOI || Date.now());
        const time = Number.isNaN(messageDate.getTime()) ? '--:--' : messageDate.toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit'
        });

        const bubbleClass = isParent ? 'bubble-sent' : 'bubble-received';
        const senderName = message.PRENOM ? `${message.PRENOM} ${message.NOM}` : (isParent ? 'Vous' : 'Staff');

        const html = `
            <div class="bubble ${bubbleClass}" data-msg-id="${message.ID_MSG}">
                <div class="message-text">${this._escapeHtml(message.MESSAGE || '')}</div>
                <span class="bubble-time">
                    ${time}
                    ${isParent ? '<i class="fa-solid fa-check-double ms-1 text-muted" style="font-size:11px;"></i>' : ''}
                </span>
            </div>
        `;

        // Vérifier si l'utilisateur est en bas du chat
        const wasAtBottom = (container.scrollTop + container.clientHeight >= container.scrollHeight - 50);

        container.insertAdjacentHTML('beforeend', html);

        // Scroller en bas si l'utilisateur y était déjà
        if (wasAtBottom) {
            container.scrollTop = container.scrollHeight;
        }

        this.messageCount++;
    }

    /**
     * Envoyer un message au serveur
     */
    send(data) {
        if (this.ws && this.ws.readyState === WebSocket.OPEN) {
            this.ws.send(JSON.stringify(data));
            return true;
        }
        return false;
    }

    /**
     * Envoyer un message texte
     */
    sendMessage(content) {
        return this.send({
            type: 'message',
            content: content,
        });
    }

    /**
     * Marquer un message comme lu
     */
    markAsRead(messageId) {
        return this.send({
            type: 'read',
            message_id: messageId,
        });
    }

    /**
     * Tentative de reconnexion automatique
     */
    _attemptReconnect() {
        if (this.isDisconnecting || this.reconnectTimer) return;
        if (this.reconnectAttempts >= this.maxReconnectAttempts) {
            console.warn('[WS] Nombre max de reconnexions atteint. Passage en fallback AJAX.');
            this._updateStatus('fallback');
            if (this._onFallbackCallback) {
                this._onFallbackCallback();
            }
            return;
        }

        this.reconnectAttempts++;
        const delay = this.reconnectInterval * Math.min(this.reconnectAttempts, 5);

        console.log(`[WS] Reconnexion dans ${delay}ms (tentative ${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
        this._updateStatus('reconnecting');

        this.reconnectTimer = setTimeout(() => {
            this.reconnectTimer = null;
            if (!this.ws || this.ws.readyState !== WebSocket.OPEN) {
                this.connect();
            }
        }, delay);
    }

    /**
     * Ping périodique pour maintenir la connexion
     */
    _startPing() {
        this._stopPing();
        this.pingTimer = setInterval(() => {
            try {
                this.send({ type: 'ping' });
            } catch (e) {
                console.warn('[WS] Erreur envoi ping:', e);
            }
        }, this.pingInterval);
    }

    _stopPing() {
        if (this.pingTimer) {
            clearInterval(this.pingTimer);
            this.pingTimer = null;
        }
    }

    /**
     * Mettre à jour l'indicateur de statut
     */
    _updateStatus(status) {
        const indicator = document.querySelector('#ws-status');
        if (indicator) {
            indicator.className = `ws-status ws-status-${status}`;
            const labels = {
                connected: 'Connecté',
                disconnected: 'Déconnecté',
                reconnecting: 'Reconnexion...',
                error: 'Erreur',
                fallback: 'Mode dégradé',
            };
            indicator.textContent = labels[status] || status;
        }

        if (this._onStatusCallback) {
            this._onStatusCallback(status);
        }
    }

    /**
     * Jouer un son de notification
     */
    _playNotificationSound() {
        try {
            // Bip court synthétisé via Web Audio API (évite un fichier externe)
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 800;
            gain.gain.value = 0.3;
            osc.start();
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15);
            osc.stop(ctx.currentTime + 0.15);
            osc.addEventListener('ended', () => ctx.close().catch(() => {}), { once: true });
        } catch (e) {
            // Silencieux — le son est optionnel
        }
    }

    /**
     * Échapper le HTML (anti-XSS)
     */
    _escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Déterminer si on doit utiliser le WebSocket
     */
    static isSupported() {
        return 'WebSocket' in window && window.WebSocket.CLOSING === 2;
    }

    /**
     * Déconnecter proprement
     */
    disconnect() {
        this.isDisconnecting = true;
        this._stopPing();
        if (this.reconnectTimer) {
            clearTimeout(this.reconnectTimer);
            this.reconnectTimer = null;
        }
        window.removeEventListener('online', this._onOnlineHandler);
        if (this.ws) {
            this.ws.close(1000, 'Déconnexion volontaire');
        }
    }
}
