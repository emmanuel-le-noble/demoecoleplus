<style>
    /* ==========================================================================
       1. STRUCTURE DU CONTENEUR DE CHAT
       ========================================================================== */
       
    .btn-file-upload {
        position: relative;
        overflow: hidden;
    }
    .btn-file-upload input[type=file] {
        position: absolute;
        top: 0; right: 0; min-width: 100%; min-height: 100%;
        font-size: 100px; text-align: right; filter: alpha(opacity=0); opacity: 0;
        outline: none; background: white; cursor: inherit; display: block;
    }
    .attachment-box {
        margin-top: 8px;
        padding: 6px 10px;
        background: rgba(0,0,0,0.04);
        border-radius: 8px;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .chat-wrapper {
        height: 650px;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
    }

    /* Barre latérale (Liste des contacts) */
    .chat-sidebar-list {
        background: rgba(255, 255, 255, 0.45);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-right: 1px solid var(--border-glass, rgba(0, 0, 0, 0.08));
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    /* Zone principale de discussion */
    .chat-body-area {
        display: flex;
        flex-direction: column;
        background: rgba(243, 244, 246, 0.7);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    /* En-tête de la discussion */
    .chat-header {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border-glass, rgba(0, 0, 0, 0.08));
        z-index: 2;
    }

    /* Conteneur des bulles de messages */
    .chat-messages-container {
        flex-grow: 1;
        overflow-y: auto;
        padding: 1.25rem;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        flex-direction: column;
    }

    /* Pied de page (Champ de saisie) */
    .chat-footer {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-top: 1px solid var(--border-glass, rgba(0, 0, 0, 0.08));
        padding: 0.9rem;
    }

    /* ==========================================================================
       2. ÉLÉMENTS INTERNES (BOUTONS CONTACTS & BULLES)
       ========================================================================== */
    .chat-contact-btn {
        padding: 0.75rem 1rem;
        border-radius: 12px;
        margin: 0.25rem 0.75rem;
        transition: all 0.2s ease-in-out;
        border: 1px solid transparent;
        color: var(--text-main, #374151);
        text-decoration: none;
        display: block;
    }

    .chat-contact-btn:hover {
        background: rgba(0, 0, 0, 0.04);
        color: var(--text-main, #111827);
    }

    .chat-contact-btn.active {
        background: rgba(16, 185, 129, 0.12);
        border-color: rgba(16, 185, 129, 0.25);
        color: #065f46;
        font-weight: 500;
    }

    /* Bulles de message */
    .bubble {
        max-width: 75%;
        padding: 10px 14px;
        border-radius: 16px;
        font-size: 0.88rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        line-height: 1.45;
        word-wrap: break-word;
        margin-bottom: 0.25rem;
    }

    .bubble-sent {
        background: #e6f7ed;
        color: #065f46;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
        border: 1px solid rgba(16, 185, 129, 0.15);
    }

    .bubble-received {
        background: #ffffff;
        color: var(--text-main, #1f2937);
        align-self: flex-start;
        border-bottom-left-radius: 4px;
        border: 1px solid rgba(0, 0, 0, 0.06);
    }

    .bubble-time {
        font-size: 10px;
        opacity: 0.65;
        margin-top: 4px;
        display: block;
        text-align: right;
    }

    /* ==========================================================================
       4. INDICATEUR STATUT WebSocket
       ========================================================================== */
    .ws-status {
        margin-left: auto;
        border-radius: 12px;
        white-space: nowrap;
    }
    .ws-status-connected {
        background: rgba(16, 185, 129, 0.12);
        color: #065f46;
    }
    .ws-status-connecting {
        background: rgba(245, 158, 11, 0.12);
        color: #92400e;
    }
    .ws-status-disconnected {
        background: rgba(239, 68, 68, 0.12);
        color: #991b1b;
    }

    /* ==========================================================================
       3. RESPONSIVE DESIGN (TABLETTES & PHONES)
       ========================================================================== */

    /* TABLETTES (Écrans moyens de 768px à 1024px) */
    @media (min-width: 768px) and (max-width: 1024px) {
        .chat-wrapper {
            height: 580px;
        }
        .chat-sidebar-list {
            flex: 0 0 40% !important;
            width: 40% !important;
        }
        .chat-body-area {
            flex: 0 0 60% !important;
            width: 60% !important;
        }
        .bubble {
            max-width: 85%;
        }
    }

    /* SMARTPHONES (Écrans inférieurs à 768px) */
    @media (max-width: 767.98px) {
        .chat-wrapper {
            height: calc(100vh - 160px); 
            min-height: 450px;
            border-radius: 12px;
        }

        /* Mode d'affichage conditionnel géré par PHP sécurisé */
        <?php if (!empty($active_type)): ?>
            .chat-sidebar-list { 
                display: none !important; 
            }
            .chat-body-area { 
                width: 100% !important; 
                flex: 0 0 100% !important; 
                display: flex !important;
            }
        <?php else: ?>
            .chat-body-area { 
                display: none !important; 
            }
            .chat-sidebar-list { 
                width: 100% !important; 
                flex: 0 0 100% !important; 
                border-right: none;
            }
        <?php endif; ?>

        .chat-messages-container {
            padding: 1rem 0.85rem;
        }

        .bubble {
            max-width: 90%;
            font-size: 0.9rem;
        }

        .chat-contact-btn {
            margin: 0.3rem 0.5rem;
            padding: 0.85rem;
        }

        .chat-footer {
            padding: 0.75rem;
        }
    }

    /* ======================================================================
       MODE SOMBRE — La messagerie possède ses propres surfaces et bulles.
       ====================================================================== */
    html[data-theme="dark"] .chat-wrapper {
        border-color: rgba(194, 232, 228, 0.12);
        box-shadow: 0 18px 42px rgba(0, 0, 0, 0.22) !important;
    }
    html[data-theme="dark"] .chat-sidebar-list {
        background: rgba(18, 52, 62, 0.90);
        border-right-color: rgba(194, 232, 228, 0.10);
    }
    html[data-theme="dark"] .chat-body-area {
        background: linear-gradient(145deg, rgba(14, 45, 56, 0.94), rgba(19, 60, 66, 0.88));
    }
    html[data-theme="dark"] .chat-header,
    html[data-theme="dark"] .chat-footer,
    html[data-theme="dark"] .chat-sidebar-list > .border-bottom {
        background: rgba(25, 65, 75, 0.90) !important;
        border-color: rgba(194, 232, 228, 0.11) !important;
    }
    html[data-theme="dark"] .chat-messages-container {
        background: rgba(7, 30, 40, 0.26);
    }
    html[data-theme="dark"] .chat-contact-btn {
        color: #d8edeb;
    }
    html[data-theme="dark"] .chat-contact-btn:hover {
        color: #f3fffe;
        background: rgba(45, 212, 191, 0.10);
    }
    html[data-theme="dark"] .chat-contact-btn.active {
        background: rgba(45, 212, 191, 0.16);
        border-color: rgba(94, 234, 212, 0.30);
        color: #bafff3;
    }
    html[data-theme="dark"] .chat-sidebar-list .avatar-circle,
    html[data-theme="dark"] .chat-header .avatar-circle {
        background: rgba(255, 255, 255, 0.10) !important;
        color: #a7f3e9;
    }
    html[data-theme="dark"] .bubble-sent {
        background: linear-gradient(135deg, #0f766e, #0b665f);
        border-color: rgba(94, 234, 212, 0.22);
        color: #ecfffc;
    }
    html[data-theme="dark"] .bubble-received {
        background: rgba(255, 255, 255, 0.09);
        border-color: rgba(194, 232, 228, 0.13);
        color: #e5f4f2;
    }
    html[data-theme="dark"] .attachment-box {
        background: rgba(0, 0, 0, 0.20);
        color: #d9efed;
    }
    html[data-theme="dark"] .attachment-box a { color: #c8fff6 !important; }
    html[data-theme="dark"] .ws-status-connected { background: rgba(45, 212, 191, 0.16); color: #8ff8e6; }
    html[data-theme="dark"] .ws-status-disconnected { background: rgba(251, 113, 133, 0.16); color: #fecdd6; }
    html[data-theme="dark"] .chat-wrapper .bg-white,
    html[data-theme="dark"] .chat-wrapper .btn-light,
    html[data-theme="dark"] .chat-wrapper .bg-light {
        background: rgba(255, 255, 255, 0.09) !important;
        border-color: rgba(194, 232, 228, 0.15) !important;
        color: #dcefed !important;
    }
    html[data-theme="dark"] .chat-wrapper .text-dark { color: #e5f4f2 !important; }
    html[data-theme="dark"] #file-preview-badge { background: #214b55 !important; }
    html[data-theme="dark"] .chat-wrapper .form-control { background: rgba(255, 255, 255, 0.08); }
</style>
