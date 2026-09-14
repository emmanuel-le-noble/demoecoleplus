<?php
/**
 * Service d'invitation parent pour le module normal/back-office.
 * Inclut les classes MailService et InvitationService depuis portail-parent.
 */

require_once __DIR__ . '/../../portail-parent/services/MailService.php';
require_once __DIR__ . '/../../portail-parent/services/InvitationService.php';

/**
 * Crée une invitation et envoie l'e-mail au parent.
 *
 * @param PDO    $pdo
 * @param int    $eleveId
 * @param string $emailTuteur
 * @param string $telTuteur
 * @param string $prenomEleve
 * @param string $nomEleve
 * @return array ['ok' => bool, 'message' => string]
 */
function envoyerInvitationParent(PDO $pdo, int $eleveId, string $emailTuteur, string $telTuteur, string $prenomEleve, string $nomEleve): array
{
    if ($emailTuteur === '') {
        return ['ok' => false, 'message' => 'Aucune adresse e-mail tuteur renseignée.'];
    }

    try {
        $invService = new InvitationService($pdo);
        $result = $invService->create($eleveId, $emailTuteur, $telTuteur);

        $mailService = new MailService();
        $parentName = $prenomEleve . ' ' . $nomEleve;
        $sent = $mailService->sendInvitationEmail($emailTuteur, $parentName, $prenomEleve . ' ' . $nomEleve, $result['token']);

        if ($sent) {
            return ['ok' => true, 'message' => 'Invitation envoyée avec succès à ' . $emailTuteur . '.'];
        } else {
            return ['ok' => false, 'message' => 'Invitation créée mais l\'envoi d\'e-mail a échoué. Vérifiez la configuration SMTP.'];
        }
    } catch (\Throwable $e) {
        error_log('[InvitationParent] Erreur : ' . $e->getMessage());
        return ['ok' => false, 'message' => 'Erreur lors de l\'envoi de l\'invitation : ' . $e->getMessage()];
    }
}
