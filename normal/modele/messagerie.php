<?php

function getConversations($idUser, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT c.ID, c.TITRE, c.TYPE_CONV, c.DATE_CREATION,
               (SELECT CONTENU FROM msg_messages WHERE ID_CONVERSATION = c.ID ORDER BY DATE_ENVOI DESC LIMIT 1) as dernier_message,
               (SELECT COUNT(*) FROM msg_messages WHERE ID_CONVERSATION = c.ID) as nb_messages,
               (SELECT COUNT(*) FROM msg_messages m 
                LEFT JOIN msg_statuts_lecture sl ON m.ID = sl.ID_MESSAGE AND sl.LECTEUR_TYPE = 'STAFF' AND sl.ID_LECTEUR = ?
                WHERE m.ID_CONVERSATION = c.ID AND sl.ID_MESSAGE IS NULL AND m.EXPEDITEUR_TYPE != 'STAFF') as non_lus
        FROM msg_conversations c
        INNER JOIN msg_participants p ON c.ID = p.ID_CONVERSATION
        WHERE p.USER_TYPE = 'STAFF' AND p.ID_USER = ?
        ORDER BY c.DATE_CREATION DESC
    ");
    $stmt->execute([$idUser, $idUser]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMessages($idConversation, $idUser, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT m.ID, m.EXPEDITEUR_TYPE, m.ID_EXPEDITEUR, m.CONTENU, m.DATE_ENVOI,
               CASE 
                   WHEN m.EXPEDITEUR_TYPE = 'STAFF' THEN u.NOM_USER
                   WHEN m.EXPEDITEUR_TYPE = 'PARENT' THEN CONCAT(p.NOM_PARENT, ' ', p.PRENOM_PARENT)
               END as expediteur_nom
        FROM msg_messages m
        LEFT JOIN utilisateur u ON m.EXPEDITEUR_TYPE = 'STAFF' AND m.ID_EXPEDITEUR = u.ID
        LEFT JOIN parents p ON m.EXPEDITEUR_TYPE = 'PARENT' AND m.ID_EXPEDITEUR = p.ID_PARENT
        WHERE m.ID_CONVERSATION = ?
        ORDER BY m.DATE_ENVOI ASC
    ");
    $stmt->execute([$idConversation]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function marquerConversationLue($idConversation, $idUser, $pdo)
{
    $stmt = $pdo->prepare("
        UPDATE msg_statuts_lecture sl
        INNER JOIN msg_messages m ON sl.ID_MESSAGE = m.ID
        SET sl.DATE_LECTURE = NOW()
        WHERE m.ID_CONVERSATION = ? AND sl.LECTEUR_TYPE = 'STAFF' AND sl.ID_LECTEUR = ? AND sl.DATE_LECTURE IS NULL
    ");
    $stmt->execute([$idConversation, $idUser]);
}

function creerConversation($titre, $typeConv, $idCreateur, $pdo)
{
    $stmt = $pdo->prepare("INSERT INTO msg_conversations (TITRE, TYPE_CONV, DATE_CREATION) VALUES (?, ?, NOW())");
    $stmt->execute([$titre, $typeConv]);
    $idConv = (int)$pdo->lastInsertId();

    $stmt2 = $pdo->prepare("INSERT INTO msg_participants (ID_CONVERSATION, USER_TYPE, ID_USER) VALUES (?, 'STAFF', ?)");
    $stmt2->execute([$idConv, $idCreateur]);

    return $idConv;
}

function ajouterParticipant($idConversation, $userType, $idUser, $pdo)
{
    $stmt = $pdo->prepare("INSERT IGNORE INTO msg_participants (ID_CONVERSATION, USER_TYPE, ID_USER) VALUES (?, ?, ?)");
    $stmt->execute([$idConversation, $userType, $idUser]);
}

function envoyerMessage($idConversation, $expediteurType, $idExpediteur, $contenu, $pdo)
{
    $stmt = $pdo->prepare("INSERT INTO msg_messages (ID_CONVERSATION, EXPEDITEUR_TYPE, ID_EXPEDITEUR, CONTENU, DATE_ENVOI) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$idConversation, $expediteurType, $idExpediteur, $contenu]);
    return (int)$pdo->lastInsertId();
}

function getParents($pdo)
{
    $stmt = $pdo->query("SELECT ID_PARENT, NOM_PARENT, PRENOM_PARENT, MAIL_PARENT FROM parents ORDER BY NOM_PARENT ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getConversationWithParent($idParent, $idUser, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT c.ID FROM msg_conversations c
        INNER JOIN msg_participants p1 ON c.ID = p1.ID_CONVERSATION AND p1.USER_TYPE = 'STAFF' AND p1.ID_USER = ?
        INNER JOIN msg_participants p2 ON c.ID = p2.ID_CONVERSATION AND p2.USER_TYPE = 'PARENT' AND p2.ID_USER = ?
        WHERE c.TYPE_CONV = 'PRIVEE' LIMIT 1
    ");
    $stmt->execute([$idUser, $idParent]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (int)$row['ID'] : 0;
}

function getConversationInfo($idConversation, $pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM msg_conversations WHERE ID = ?");
    $stmt->execute([$idConversation]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
