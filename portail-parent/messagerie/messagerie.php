<?php
declare(strict_types=1);

// ==========================================================================
// 1. INCLUSION DE LA SESSION & UTILS
// ==========================================================================
require_once __DIR__ . '/../includes/session.php';

$parent_id = $_SESSION['id_parent'];
$enfant_actif_id = $_SESSION['active_eleve_id'];

require_once __DIR__ . '/MessagerieRepository.php';
$repo = new MessagerieRepository($pdo);

// Récupération de l'année scolaire active
$annee_active = $repo->getAnneeActive();
$id_annee = $annee_active['ID'] ?? 1;

// ==========================================================================
// 2. CONTEXTUALISATION : INFOS ENFANT SÉLECTIONNÉ & CLASSE
// ==========================================================================
// Récupérer les infos de l'enfant actif
$enfant_actif = [];
if ($enfant_actif_id > 0) {
    $enfant_actif = $repo->getEnfantInfo($enfant_actif_id) ?: [];
}
$enfant_prenom = $enfant_actif['PRENOM_ELEVE'] ?? 'votre enfant';
$id_salle = $enfant_actif['ID_SALLE'] ?? 0;

// Récupération des professeurs
$professeurs = [];
if ($id_salle > 0) {
    $professeurs = $repo->getProfesseursBySalle($id_salle);
}

$services = [
    'administration' => 'Secrétariat / Administration',
    'vie_scolaire'   => 'Bureau de la Vie Scolaire',
    'comptabilite'   => 'Service Comptabilité'
];

// ==========================================================================
// 3. IDENTIFICATION DE LA CONVERSATION ACTIVE
// ==========================================================================
$active_type = $_GET['type'] ?? null;
$active_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$id_conversation = null;

// Une conversation privée ne peut être ouverte qu'avec un professeur de la
// classe de l'enfant actif. Cela évite de créer des conversations invalides
// via des paramètres d'URL forgés.
$typesServices = ['administration', 'vie_scolaire', 'comptabilite'];
if ($active_type !== null && !in_array($active_type, $typesServices, true)) {
    $professeurAutorise = false;
    foreach ($professeurs as $professeur) {
        if ((int)($professeur['ID_PROFESSEUR'] ?? 0) === $active_id) {
            $professeurAutorise = true;
            break;
        }
    }

    if ($active_type !== 'professeur' || !$professeurAutorise) {
        $active_type = null;
        $active_id = 0;
    }
}

if ($active_type) {
    if (in_array($active_type, $typesServices, true)) {
        $id_conversation = $repo->findDepartementConversation($active_type, $parent_id);
    } else {
        $id_conversation = $repo->findPriveeConversation($parent_id, $active_id);
    }
}

// Le serveur WebSocket ne reçoit pas la session PHP : il exige donc un jeton
// HMAC à durée courte. Sans secret configuré, le client passe automatiquement
// au polling AJAX plutôt que d'ouvrir une connexion non authentifiée.
$wsAuthToken = '';
$wsUrl = (string)(getenv('ECOLEPLUS_WS_URL') ?: '');
if ($id_conversation) {
    $wsSecret = getenv('ECOLEPLUS_WS_SECRET') ?: '';
    if ($wsSecret !== '') {
        $wsExpiry = time() + 300;
        $wsPayload = $parent_id . ':' . $id_conversation . ':' . $wsExpiry;
        $wsAuthToken = $wsExpiry . '.' . hash_hmac('sha256', $wsPayload, $wsSecret);
    }
}

// ==========================================================================
// 3.1 ENDPOINT AJAX : CHARGEMENT EN TEMPS RÉEL (POLLING OPTIMISÉ)
// ==========================================================================
if (isset($_GET['action']) && $_GET['action'] === 'get_messages' && $active_type) {
    header('Content-Type: application/json');
    $messages_data = [];
    $last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;

    if ($id_conversation) {
        // Marquage automatique comme lu pour les nouveaux messages reçus
        $repo->marquerMessagesLus($id_conversation, $parent_id);

        // Requête optimisée : ne récupère que les messages PLUS RÉCENTS que last_id
        if ($last_id > 0) {
            $new_messages = $repo->getMessages($id_conversation, $last_id);

            // Réponse partielle : renvoyer uniquement les nouveaux messages
            ob_start();
            foreach ($new_messages as $msg) {
                $est_parent = ($msg['EXPEDITEUR_TYPE'] === 'PARENT');
                ?>
                <div class="bubble <?= $est_parent ? 'bubble-sent' : 'bubble-received' ?>">
                    <?php if(!empty($msg['MESSAGE'])): ?>
                        <div class="message-text"><?= nl2br(htmlspecialchars($msg['MESSAGE'], ENT_QUOTES, 'UTF-8')) ?></div>
                    <?php endif; ?>
                    <?php if(!empty($msg['NOM_UNIQUE'])): ?>
                        <div class="attachment-box">
                            <?php if(str_starts_with((string)($msg['TYPE_MIME'] ?? ''), 'image/')): ?>
                                <a href="uploads/<?= rawurlencode((string)$msg['NOM_UNIQUE']) ?>" target="_blank" rel="noopener">
                                    <img src="uploads/<?= rawurlencode((string)$msg['NOM_UNIQUE']) ?>" alt="pj" style="max-width: 150px; max-height: 100px; border-radius:6px; display:block;">
                                </a>
                            <?php else: ?>
                                <i class="fa-solid fa-file-lines text-primary fs-6"></i>
                                <a href="uploads/<?= rawurlencode((string)$msg['NOM_UNIQUE']) ?>" target="_blank" rel="noopener" class="text-decoration-none text-reset fw-bold">
                                    <?= htmlspecialchars((string)($msg['NOM_ORIGINAL'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <span class="bubble-time">
                        <?= date('H:i', strtotime($msg['DATE_ENVOI'])) ?>
                        <?php if ($est_parent): ?>
                            <i class="fa-solid fa-check-double ms-1 <?= $msg['STATUT_LECTURE'] == 1 ? 'text-info' : 'text-muted' ?>" style="font-size:11px;"></i>
                        <?php endif; ?>
                    </span>
                </div>
                <?php
            }
            $html = ob_get_clean();

            // Compteur total pour vérifier la cohérence
            $totalCount = $repo->getMessageCount($id_conversation);

            echo json_encode([
                'html' => $html,
                'count' => intval($totalCount),
                'new_count' => count($new_messages),
                'append' => true,
                'last_id' => !empty($new_messages) ? (int)end($new_messages)['ID_MSG'] : $last_id,
            ]);
            exit;
        }

        // Requête complète (premier chargement ou fallback)
        $messages_data = $repo->getMessages($id_conversation);
    }

    ob_start();
    if (empty($messages_data)) {
        ?>
        <div class="text-center my-auto px-4">
            <span class="badge bg-white text-muted border px-3 py-2 rounded-pill shadow-sm">
                Aucun message. Ouvrez l'échange ci-dessous.
            </span>
        </div>
        <?php
    } else {
        foreach ($messages_data as $msg) {
            $est_parent = ($msg['EXPEDITEUR_TYPE'] === 'PARENT');
            ?>
            <div class="bubble <?= $est_parent ? 'bubble-sent' : 'bubble-received' ?>">
                <?php if(!empty($msg['MESSAGE'])): ?>
                    <div class="message-text"><?= nl2br(htmlspecialchars($msg['MESSAGE'], ENT_QUOTES, 'UTF-8')) ?></div>
                <?php endif; ?>
                <?php if(!empty($msg['NOM_UNIQUE'])): ?>
                    <div class="attachment-box">
                        <?php if(str_starts_with((string)($msg['TYPE_MIME'] ?? ''), 'image/')): ?>
                            <a href="uploads/<?= rawurlencode((string)$msg['NOM_UNIQUE']) ?>" target="_blank" rel="noopener">
                                <img src="uploads/<?= rawurlencode((string)$msg['NOM_UNIQUE']) ?>" alt="pj" style="max-width: 150px; max-height: 100px; border-radius:6px; display:block;">
                            </a>
                        <?php else: ?>
                            <i class="fa-solid fa-file-lines text-primary fs-6"></i>
                            <a href="uploads/<?= rawurlencode((string)$msg['NOM_UNIQUE']) ?>" target="_blank" rel="noopener" class="text-decoration-none text-reset fw-bold">
                                <?= htmlspecialchars((string)($msg['NOM_ORIGINAL'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <span class="bubble-time">
                    <?= date('H:i', strtotime($msg['DATE_ENVOI'])) ?>
                    <?php if ($est_parent): ?>
                        <i class="fa-solid fa-check-double ms-1 <?= $msg['STATUT_LECTURE'] == 1 ? 'text-info' : 'text-muted' ?>" style="font-size:11px;"></i>
                    <?php endif; ?>
                </span>
            </div>
            <?php
        }
    }
    $html = ob_get_clean();
    echo json_encode(['html' => $html, 'count' => count($messages_data)]);
    exit;
}


// ==========================================================================
// 4. MARQUAGE "LU"
// ==========================================================================
if ($id_conversation) {
    $repo->marquerMessagesLus($id_conversation, $parent_id);
}

// ==========================================================================
// 5. TRAITEMENT DU FORMULAIRE : ENVOI D'UN MESSAGE & PIÈCE JOINTE
// ==========================================================================
$erreur_upload = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoyer_message'])) {
    // Vérification du jeton CSRF pour bloquer les attaques cross-site
    if (!isset($_POST['csrf_token']) || !hash_equals((string)($_SESSION['csrf_token'] ?? ''), (string)$_POST['csrf_token'])) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?type=$active_type&id=$active_id&statut=error&msg=" . urlencode("Erreur de sécurité : Jeton CSRF invalide ou expiré."));
        exit;
    }

    $message_contenu = trim((string)($_POST['message_contenu'] ?? ''));
    $file_uploaded = (isset($_FILES['piece_jointe']) && $_FILES['piece_jointe']['error'] === UPLOAD_ERR_OK);
    $action_autorisee = !empty($message_contenu) || $file_uploaded;

    if ($action_autorisee && $active_type) {
        $pdo->beginTransaction();
        try {
            // Initialisation de la conversation si elle n'existe pas encore
            if (!$id_conversation) {
                if (in_array($active_type, ['administration', 'vie_scolaire', 'comptabilite'])) {
                    $id_conversation = $repo->createConversation($active_type, 'DEPARTEMENT');
                    $repo->addParticipant($id_conversation, 'PARENT', $parent_id);
                } else {
                    $id_conversation = $repo->createConversation(null, 'PRIVEE');
                    $repo->addParticipant($id_conversation, 'PARENT', $parent_id);
                    $repo->addParticipant($id_conversation, 'STAFF', $active_id);
                }
            }

            // 1. Insertion du message principal
            $id_message = $repo->insertMessage($id_conversation, 'PARENT', $parent_id, $message_contenu);

            // 2. Traitement de la pièce jointe avec validation MIME réelle via FileInfo
            if ($file_uploaded) {
                $fileTmpPath = $_FILES['piece_jointe']['tmp_name'];
                $fileName = $_FILES['piece_jointe']['name'];

                // Types MIME autorisés (réels, pas ceux déclarés par le navigateur)
                $allowedMimeTypes = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'application/pdf' => 'pdf',
                    'application/msword' => 'doc',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                    'application/vnd.ms-excel' => 'xls',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
                ];

                // Validation du type MIME réel du fichier via l'extension FileInfo
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $realMimeType = $finfo->file($fileTmpPath);

                $maxUploadSize = 10 * 1024 * 1024;
                if (($_FILES['piece_jointe']['size'] ?? 0) > $maxUploadSize) {
                    throw new Exception('Le fichier dépasse la taille maximale autorisée.');
                }

                if (isset($allowedMimeTypes[$realMimeType])) {
                    // L'extension est déduite du MIME vérifié, jamais du nom fourni
                    // par le navigateur : un fichier PHP ne peut donc pas être servi.
                    $newFileName = bin2hex(random_bytes(16)) . '.' . $allowedMimeTypes[$realMimeType];
                    $uploadFileDir = __DIR__ . '/uploads/';
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }
                    $dest_path = $uploadFileDir . $newFileName;

                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $repo->insertPieceJointe($id_message, $newFileName, $fileName, $realMimeType);
                    } else {
                        throw new Exception("Échec du transfert sur le serveur.");
                    }
                } else {
                    throw new Exception("Type de fichier non autorisé (MIME : " . htmlspecialchars($realMimeType) . ").");
                }
            }

            // 3. Notification/Statut de lecture pour les destinataires
            if (in_array($active_type, ['administration', 'vie_scolaire', 'comptabilite'])) {
                // On cherche tous les utilisateurs qui possèdent ce profil textuel
                $staffs = $repo->getStaffByProfile($active_type);

                foreach ($staffs as $staff_id) {
                    $repo->insertStatutLecture($id_message, 'STAFF', $staff_id);
                }
            } else {
                $repo->insertStatutLecture($id_message, 'STAFF', $active_id);
            }

            $pdo->commit();
            header("Location: " . $_SERVER['PHP_SELF'] . "?type=$active_type&id=$active_id&statut=success");
            exit;

        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            // Log interne de l'erreur complète pour le débogage (à lire dans les logs serveur)
            error_log('[EcolePlus Messagerie] Erreur transaction parent_id=' . $parent_id . ' : ' . $e->getMessage());
            // Message convivial pour l'utilisateur sans exposer les détails techniques
            header("Location: " . $_SERVER['PHP_SELF'] . "?type=$active_type&id=$active_id&statut=error&msg=" . urlencode("Une erreur technique est survenue lors de l'envoi. Veuillez réessayer."));
            exit;
        }
    }
}

// ==========================================================================
// 6. COMPTEURS DE MESSAGES NON LUS
// ==========================================================================
$unread_rows = $repo->getNonLus($parent_id);

$badges_non_lus = [];
foreach ($unread_rows as $row) {
    if ($row['TYPE_CONV'] === 'DEPARTEMENT') {
        $key = $row['CONV_TITRE'] . '_0';
        $badges_non_lus[$key] = ($badges_non_lus[$key] ?? 0) + $row['nb_non_lus'];
    } else {
        $key = 'professeur_' . $row['ID_EXPEDITEUR'];
        $badges_non_lus[$key] = ($badges_non_lus[$key] ?? 0) + $row['nb_non_lus'];
    }
}

// ==========================================================================
// 7. CHARGEMENT DES MESSAGES ET RECOUVREMENT DES PIÈCES JOINTES
// ==========================================================================
$messages_actifs = [];
$nom_contact_actif = "";

if ($active_type) {
    if ($id_conversation) {
        $messages_actifs = $repo->getMessages($id_conversation);
    }

    if ($active_type === 'professeur') {
        foreach ($professeurs as $p) {
            if ($p['ID_PROFESSEUR'] == $active_id) {
                $nom_contact_actif = "M./Mme " . $p['NOM_PROF'] . " [" . $p['MATIERE'] . "]";
                break;
            }
        }
    } else {
        $nom_contact_actif = $services[$active_type] ?? 'Administration';
    }
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/slidebar.php';
?>

<?php include __DIR__ . '/css.php'; ?>

<main class="main-content">
    <?php include __DIR__ . '/../includes/topbar.php'; ?>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold m-0" style="color: var(--text-main);">Messagerie</h3>
            <p class="text-muted small m-0">Échanges concernant <span class="text-success fw-bold"><?= htmlspecialchars($enfant_prenom, ENT_QUOTES, 'UTF-8') ?></span></p>
        </div>
        <?php if ($active_type): ?>
            <a href="?" class="btn btn-sm btn-light border d-lg-none"><i class="fa-solid fa-arrow-left me-1"></i> Contacts</a>
        <?php endif; ?>
    </div>

    <!-- Alertes d'erreurs éventuelles de téléversement -->
    <?php if (isset($_GET['statut']) && $_GET['statut'] === 'error'): ?>
        <div class="alert alert-danger font-size:12px;"><?= htmlspecialchars($_GET['msg'] ?? 'Erreur lors du traitement.') ?></div>
    <?php endif; ?>

    <div class="liquid-card chat-wrapper shadow-sm">
        <div class="row g-0 h-100">

            <!-- LISTE DES CONTACTS -->
            <div class="col-lg-4 d-flex flex-column h-100 chat-sidebar-list">
                <div class="p-3 border-bottom d-flex flex-column gap-2" style="background: rgba(255,255,255,0.35);">
                    <div class="fw-bold text-muted small" style="font-size: 11px;">
                        <i class="fa-regular fa-comments me-1"></i> DISCUSSIONS DISPONIBLES
                    </div>
                    <div class="position-relative mt-1">
                        <i class="fa-solid fa-magnifying-glass position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 11px;"></i>
                        <input type="text" id="contact-search" class="form-control ps-4 py-1" style="border-radius: 8px; font-size: 12px; height: 30px;" placeholder="Rechercher un contact ou une matière..." />
                    </div>
                </div>

                <div class="py-2 flex-grow-1" style="overflow-y:auto;">
                    <div class="px-3 py-1 text-muted small fw-semibold opacity-75" style="font-size:10px;">ADMINISTRATION</div>
                    <?php foreach ($services as $key => $label):
                        $key_badge = $key . '_0';
                        $is_active = ($active_type === $key && $active_id == 0);
                    ?>
                        <a href="?type=<?= $key ?>&id=0" class="chat-contact-btn <?= $is_active ? 'active' : '' ?>">
                            <div class="d-flex align-items-center">
                                <div class="avatar-parent me-3" style="width:34px; height:34px; font-size:12px;">
                                    <i class="fa-solid fa-building"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-semibold text-truncate" style="font-size:13px;"><?= $label ?></div>
                                </div>
                                <?php if (!empty($badges_non_lus[$key_badge])): ?>
                                    <span class="badge bg-danger rounded-pill" style="font-size:10px;"><?= $badges_non_lus[$key_badge] ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>

                    <div class="px-3 py-1 text-muted small fw-semibold opacity-75 mt-3" style="font-size:10px;">CORPS ENSEIGNANT</div>
                    <?php if (empty($professeurs)): ?>
                        <div class="px-3 py-2 text-muted small fst-italic">Aucun enseignant assigné.</div>
                    <?php else: ?>
                        <?php foreach ($professeurs as $prof):
                            $key_badge = 'professeur_' . $prof['ID_PROFESSEUR'];
                            $is_active = ($active_type === 'professeur' && $active_id == $prof['ID_PROFESSEUR']);
                            $initiales = strtoupper(substr($prof['NOM_PROF'], 0, 2));
                        ?>
                            <a href="?type=professeur&id=<?= $prof['ID_PROFESSEUR'] ?>" class="chat-contact-btn <?= $is_active ? 'active' : '' ?>">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3" style="width:34px; height:34px; font-size:11px; background: rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:center; border-radius:50%;">
                                        <?= $initiales ?>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-semibold text-truncate" style="font-size:13px;"><?= htmlspecialchars($prof['NOM_PROF'], ENT_QUOTES, 'UTF-8') ?></div>
                                        <div class="text-muted text-truncate" style="font-size:11px;"><?= htmlspecialchars($prof['MATIERE'], ENT_QUOTES, 'UTF-8') ?></div>
                                    </div>
                                    <?php if (!empty($badges_non_lus[$key_badge])): ?>
                                        <span class="badge bg-danger rounded-pill" style="font-size:10px;"><?= $badges_non_lus[$key_badge] ?></span>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ZONE DE CONVERSATION -->
            <div class="col-lg-8 d-flex flex-column h-100 chat-body-area">
                <?php if ($active_type): ?>

                    <div class="p-3 chat-header d-flex align-items-center shadow-sm">
                        <div class="avatar-circle me-3" style="width:38px; height:38px; background: rgba(0, 0, 0, 0.05); display:flex; align-items:center; justify-content:center; border-radius:50%;">
                            <i class="fa-solid <?= $active_type === 'professeur' ? 'fa-user-tie' : 'fa-sliders' ?> fs-6"></i>
                        </div>
                        <div>
                            <h6 class="m-0 fw-bold" style="font-size:14px; color: var(--text-main);"><?= htmlspecialchars($nom_contact_actif, ENT_QUOTES, 'UTF-8') ?></h6>
                            <span class="text-success small" style="font-size: 11px;"><i class="fa-solid fa-lock me-1"></i>Canal direct connecté</span>
                        </div>
                        <div id="ws-status" class="ws-status ws-status-disconnected small text-muted px-3 py-1" style="font-size:10px; display:flex; align-items:center; gap:4px;"></div>
                    </div>

                    <div class="chat-messages-container d-flex flex-column gap-2" id="chat-scroller">
                        <?php if (empty($messages_actifs)): ?>
                            <div class="text-center my-auto px-4">
                                <span class="badge bg-white text-muted border px-3 py-2 rounded-pill shadow-sm">
                                    Aucun message. Ouvrez l'échange ci-dessous.
                                </span>
                            </div>
                        <?php else: ?>
                            <?php foreach ($messages_actifs as $msg):
                                $est_parent = ($msg['EXPEDITEUR_TYPE'] === 'PARENT');
                            ?>
                                <div class="bubble <?= $est_parent ? 'bubble-sent' : 'bubble-received' ?>">
                                    <?php if(!empty($msg['MESSAGE'])): ?>
                                        <div class="message-text"><?= nl2br(htmlspecialchars($msg['MESSAGE'], ENT_QUOTES, 'UTF-8')) ?></div>
                                    <?php endif; ?>

                                    <!-- Traitement d'affichage de la pièce jointe -->
                                    <?php if(!empty($msg['NOM_UNIQUE'])): ?>
                                        <div class="attachment-box">
                                            <?php if(str_starts_with((string)($msg['TYPE_MIME'] ?? ''), 'image/')): ?>
                                                <a href="uploads/<?= rawurlencode((string)$msg['NOM_UNIQUE']) ?>" target="_blank" rel="noopener">
                                                    <img src="uploads/<?= rawurlencode((string)$msg['NOM_UNIQUE']) ?>" alt="pj" style="max-width: 150px; max-height: 100px; border-radius:6px; display:block;">
                                                </a>
                                            <?php else: ?>
                                                <i class="fa-solid fa-file-lines text-primary fs-6"></i>
                                                <a href="uploads/<?= rawurlencode((string)$msg['NOM_UNIQUE']) ?>" target="_blank" rel="noopener" class="text-decoration-none text-reset fw-bold">
                                                    <?= htmlspecialchars((string)($msg['NOM_ORIGINAL'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <span class="bubble-time">
                                        <?= date('H:i', strtotime($msg['DATE_ENVOI'])) ?>
                                        <?php if ($est_parent): ?>
                                            <i class="fa-solid fa-check-double ms-1 <?= $msg['STATUT_LECTURE'] == 1 ? 'text-info' : 'text-muted' ?>" style="font-size:11px;"></i>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- FORMULAIRE -->
                    <div class="p-3 chat-footer">
                        <form method="POST" action="?type=<?= urlencode($active_type) ?>&id=<?= intval($active_id) ?>" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
                            <!-- Jeton de sécurité CSRF -->
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                            <button type="button" class="btn btn-light border btn-file-upload d-flex align-items-center justify-content-center" style="border-radius: 12px; height:38px; width:40px;" title="Joindre un document">
                                <i class="fa-solid fa-paperclip text-muted"></i>
                                <input type="file" name="piece_jointe" id="file-input" />
                            </button>

                            <div class="flex-grow-1 position-relative">
                                <input type="text" name="message_contenu" id="message-input" autocomplete="off" class="form-control px-3" style="border-radius: 12px; font-size:13px; height:38px;" placeholder="Écrire votre message ici..." />
                                <div id="file-preview-badge" class="badge bg-light border text-dark d-none align-items-center gap-2 position-absolute" style="top: -32px; left: 0; border-radius: 8px; padding: 6px 10px; font-size: 11px; z-index: 10;">
                                    <i class="fa-solid fa-paperclip text-success"></i>
                                    <span id="file-preview-name" class="text-truncate" style="max-width: 180px;"></span>
                                    <button type="button" class="btn-close" id="btn-clear-file" style="font-size: 8px;"></button>
                                </div>
                            </div>

                            <button type="submit" name="envoyer_message" class="btn btn-success d-flex align-items-center justify-content-center px-3" style="border-radius: 12px; height:38px;">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>

                <?php else: ?>
                    <div class="d-flex flex-column justify-content-center align-items-center h-100 text-center px-4 bg-white bg-opacity-20">
                        <div class="mb-3" style="background: rgba(16, 185, 129, 0.08); padding: 18px; border-radius: 50%;">
                            <i class="fa-regular fa-comment-dots fs-2 text-success"></i>
                        </div>
                        <h6 class="fw-bold m-0" style="color: var(--text-main);">Vos conversations</h6>
                        <p class="text-muted small mt-1">Sélectionnez un interlocuteur de l'établissement dans le menu pour afficher vos échanges.</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</main>

<script nonce="<?= htmlspecialchars($GLOBALS['csp_nonce'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    function updateFileIndicator() {
        const input = document.getElementById('file-input');
        const badge = document.getElementById('file-preview-badge');
        const nameSpan = document.getElementById('file-preview-name');
        if (input.files.length > 0) {
            nameSpan.innerText = input.files[0].name;
            badge.classList.remove('d-none');
            badge.classList.add('d-flex');
        } else {
            clearFileSelection();
        }
    }

    function clearFileSelection() {
        const input = document.getElementById('file-input');
        const badge = document.getElementById('file-preview-badge');
        input.value = '';
        badge.classList.remove('d-flex');
        badge.classList.add('d-none');
    }

    document.addEventListener("DOMContentLoaded", function() {
        const box = document.getElementById("chat-scroller");
        if (box) { box.scrollTop = box.scrollHeight; }

        // 1. Filtrage des contacts en temps réel
        const searchInput = document.getElementById('contact-search');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().trim();
                const contacts = document.querySelectorAll('.chat-contact-btn');
                contacts.forEach(contact => {
                    const text = contact.textContent.toLowerCase();
                    contact.style.setProperty('display', text.includes(query) ? 'block' : 'none', 'important');
                });
            });
        }

        // 2. WebSocket temps réel avec fallback AJAX
        <?php if ($active_type && $id_conversation): ?>
        const WS_CONFIG = {
            wsUrl: <?= json_encode($wsUrl, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?> || `${window.location.protocol === 'https:' ? 'wss' : 'ws'}://${window.location.hostname}:8080`,
            conversationId: <?= (int)$id_conversation ?>,
            parentId: <?= (int)$parent_id ?>,
            authToken: <?= json_encode($wsAuthToken, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            containerSelector: '#chat-scroller',
            reconnectInterval: 3000,
            maxReconnectAttempts: 10,
        };

        // Fonction de polling AJAX (fallback)
        let messageCount = <?= count($messages_actifs) ?>;
        let lastMessageId = <?= !empty($messages_actifs) ? end($messages_actifs)['ID_MSG'] : 0 ?>;
        const activeType = <?= json_encode($active_type) ?>;
        const activeId = <?= json_encode($active_id) ?>;
        let ajaxPollingActive = false;
        let ajaxPollingTimer = null;

        function startAjaxPolling() {
            if (ajaxPollingActive) return;
            ajaxPollingActive = true;
            console.log('[Chat] Démarrage du polling AJAX toutes les 4s');

            ajaxPollingTimer = setInterval(function() {
                const params = new URLSearchParams({
                    action: 'get_messages',
                    type: activeType,
                    id: activeId,
                    last_id: lastMessageId
                });
                fetch(`messagerie.php?${params}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.html && data.count !== messageCount) {
                            if (data.append && data.new_count > 0) {
                                const wasAtBottom = (box.scrollTop + box.clientHeight >= box.scrollHeight - 50);
                                box.insertAdjacentHTML('beforeend', data.html);
                                if (wasAtBottom) box.scrollTop = box.scrollHeight;
                            } else {
                                const wasAtBottom = (box.scrollTop + box.clientHeight >= box.scrollHeight - 50);
                                box.innerHTML = data.html;
                                if (wasAtBottom) box.scrollTop = box.scrollHeight;
                            }
                            messageCount = data.count;
                            lastMessageId = Number(data.last_id || lastMessageId);
                        }
                    })
                    .catch(err => console.warn('[Chat] Polling error:', err));
            }, 4000);
        }

        window.addEventListener('pagehide', function() {
            if (ajaxPollingTimer) {
                clearInterval(ajaxPollingTimer);
                ajaxPollingTimer = null;
            }
            if (window.chatWS) window.chatWS.disconnect();
        }, { once: true });

        // Envoyer un message (WS ou fallback)
        window.sendWSMessage = function(content) {
            if (window.chatWS && window.chatWS.ws && window.chatWS.ws.readyState === WebSocket.OPEN) {
                window.chatWS.sendMessage(content);
                return true;
            }
            return false;
        };

        if (EcolePlusWS.isSupported() && WS_CONFIG.authToken) {
            console.log('[Chat] WebSocket disponible — connexion temps réel');

            // Ajouter le callback fallback pour démarrer le polling AJAX si WS échoue
            WS_CONFIG.onFallback = function() {
                console.log('[Chat] WS en échec → activation polling AJAX');
                startAjaxPolling();
            };

            window.chatWS = new EcolePlusWS(WS_CONFIG);
            window.chatWS.connect();

            // Intercepter l'envoi du formulaire
            const form = document.querySelector('form[method="POST"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const input = document.getElementById('message-input');
                    const content = input.value.trim();
                    if (!content) return;

                    // Si WS est actif, essayer d'envoyer via WS
                    if (window.sendWSMessage(content)) {
                        e.preventDefault();
                        input.value = '';
                        // L'UI est mise à jour via le callback WS
                    }
                    // Sinon, laisser le formulaire se soumettre normalement (POST PHP)
                });
            }
        } else {
            console.log('[Chat] WebSocket indisponible ou non configuré — fallback AJAX immédiat');
            startAjaxPolling();
        }
        <?php endif; ?>
    });

    try {
        document.getElementById('file-input')?.addEventListener('change', function() {
            if (typeof updateFileIndicator === 'function') updateFileIndicator();
        });
        document.getElementById('btn-clear-file')?.addEventListener('click', function() {
            if (typeof clearFileSelection === 'function') clearFileSelection();
        });
    } catch (e) { console.error('[Messagerie] Erreur setup file handlers:', e); }
</script>

<!-- Client WebSocket -->
<script src="../ws/ws-client.js" nonce="<?= htmlspecialchars($GLOBALS['csp_nonce'] ?? '', ENT_QUOTES, 'UTF-8') ?>"></script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
