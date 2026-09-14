# Ecole Plus — Portail Parent

> Progressive Web App (PWA) de suivi scolaire pour les parents d'élèves.

**Version :** 1.3.0 | **Stack :** PHP 8.x · MySQL 8.0 · Bootstrap 5 · WebSocket (Ratchet) | **Licence :** Privée

---

## Aperçu

Ecole Plus Portail Parent est une application web qui permet aux parents de suivre la scolarité de leurs enfants en temps réel :

- **Notes & bulletins** — Consultation des notes, moyennes, classements et impression de bulletins
- **Absences & retards** — Registre complet avec motifs et permissions
- **Cahier de textes** — Cours et devoirs avec échéances
- **Messagerie** — Chat temps réel entre parents et administration (WebSocket + fallback AJAX)
- **Professeurs** — Liste des professeurs, matières, classes, et contact direct
- **Paiements** — Suivi des frais de scolarité et impression de reçus
- **Annonces** — Communiqués officiels de l'établissement
- **Mode hors-ligne** — Données en cache (IndexedDB) accessibles sans connexion
- **OTP** — Double authentification par code 6 chiffres (SMS + email) à chaque connexion

---

## Installation

### Prérequis

- PHP 8.1+ (avec extensions : `pdo_mysql`, `json`, `openssl`, `session`)
- MySQL 8.0+ ou MariaDB 10.11+
- Apache 2.4+ (WampServer, XAMPP, ou serveur Linux)
- Composer 2.x
- Node.js (optionnel, pour le développement)

### Étapes

```bash
# 1. Cloner le projet
git clone <url> portail-parent
cd portail-parent

# 2. Installer les dépendances
composer install

# 3. Configurer l'environnement
cp .env.example .env
# Éditer .env avec vos paramètres (DB, clés, etc.)

# 4. Créer la base de données
mysql -u root -p -e "CREATE DATABASE ecole_plus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
mysql -u root -p ecole_plus < database/ecole_plus.sql

# 5. Exécuter la migration (si nécessaire)
mysql -u root -p ecole_plus < database/migration.sql

# 5b. Migration OTP + invitations (v1.3)
mysql -u root -p ecole_plus < database/migration_otp.sql

# 6. Importer les données de test (optionnel)
mysql -u root -p ecole_plus < database/test_data.sql

# 7. Lancer le serveur WebSocket
php bin/server.php
```

### Accès

| Rôle | URL | Identifiants (test) |
|------|-----|---------------------|
| Parent | `http://localhost/portail-parent/auth/login.php` | `kouassi` / `Test@1234` |
| Inscription | `http://localhost/portail-parent/auth/inscription.php` | — |

---

## Configuration

### Variables d'environnement (`.env`)

Toute la configuration passe par le fichier `.env` (exclu du versionnement).

```bash
cp .env.example .env
```

| Variable | Description | Valeur par défaut |
|----------|-------------|-------------------|
| `APP_URL` | URL publique de l'application | `http://localhost/portail-parent` |
| `APP_ENV` | Environnement (`development` / `production`) | `development` |
| `APP_KEY` | Clé secrète (64 caractères hex) | Générée à l'installation |
| `DB_HOST` | Hôte MySQL | `localhost` |
| `DB_NAME` | Nom de la base | `ecole_plus` |
| `DB_USER` | Utilisateur MySQL | `root` |
| `DB_PASS` | Mot de passe MySQL | *(vide)* |
| `ECOLEPLUS_SESSION_KEY` | Clé AES-256-GCM pour les sessions | Générée à l'installation |
| `ECOLEPLUS_WS_URL` | URL du serveur WebSocket | `ws://localhost:8080` |
| `ECOLEPLUS_WS_SECRET` | Secret pour l'endpoint broadcast | Généré à l'installation |
| `GOOGLE_CLIENT_ID` | Client ID OAuth Google | *(vide = désactivé)* |
| `GOOGLE_CLIENT_SECRET` | Client Secret OAuth Google | *(vide = désactivé)* |
| `MAIL_FROM` | Adresse d'expédition des emails | `noreply@ecoleplus.tg` |
| `OTP_ENABLED` | Activer la double authentification (`true`/`false`) | `true` |
| `OTP_LENGTH` | Longueur du code OTP (chiffres) | `6` |
| `OTP_EXPIRATION` | Validité du code (secondes) | `300` (5 min) |
| `OTP_MAX_ATTEMPTS` | Tentatives max avant blocage | `5` |
| `OTP_RESEND_DELAY` | Délai entre envois (secondes) | `60` (1 min) |
| `SMS_ENABLED` | Activer envoi SMS (`true`/`false`) | `false` |
| `SMS_PROVIDER` | Fournisseur (`twilio`, `africastalking`, `bulksms`) | *(vide)* |
| `SMS_API_URL` | URL API du fournisseur | *(vide)* |
| `SMS_API_KEY` | Clé API du fournisseur | *(vide)* |
| `SMS_SENDER` | Nom/numéro expéditeur | `EcolePlus` |

### Génération de clés

```bash
# Générer une clé de 64 caractères hexadécimaux
php -r "echo bin2hex(random_bytes(32));"
```

---

## Architecture

```
portail-parent/
├── auth/                  # Authentification (login, inscription, OTP, profil, OAuth Google)
├── dashboard/             # Tableau de bord
├── enfants/               # Sélection et info des enfants
├── notes/                 # Notes, bulletins, graphiques
├── absences/              # Absences et retards (MVC)
├── cahier_texte/          # Cours et devoirs
├── paiements/             # Frais et reçus
├── messagerie/            # Chat temps réel (WebSocket)
├── professeurs/           # Liste professeurs, matières, contact (MVC)
├── services/              # Services métier
│   ├── OtpService.php     # Génération, vérification, invalidation OTP
│   ├── MailService.php    # Envoi emails (OTP, invitations)
│   ├── SmsService.php     # Envoi SMS (abstraction multi-provider)
│   └── InvitationService.php # Gestion invitations parents
├── config/
│   ├── env.php            # Chargeur .env
│   ├── app.php            # Constantes centralisées
│   ├── db.php             # Connexion PDO
│   ├── csp_headers.php    # Content-Security-Policy
│   ├── offline_sync.php   # Endpoint sync données hors-ligne
│   ├── bulletin_freeze.php # Script gel des moyennes (CLI)
│   └── session_cleanup.php # Nettoyage sessions expirées
├── includes/
│   ├── session_bootstrap.php  # Bootstrap session BDD
│   ├── session.php            # Session centralisée (CSRF, sélection enfant)
│   ├── DatabaseSessionHandler.php # Handler sessions chiffrées (AES-256-GCM)
│   ├── helpers.php            # Fonctions utilitaires
│   ├── header.php / footer.php / slidebar.php / topbar.php
│   └── navbar_mobile.php
├── assets/css/style.css   # Styles globaux (Liquid Glass)
├── assets/js/
│   ├── offline-db.js      # Manager IndexedDB
│   └── offline-banner.js  # Bannière statut réseau
├── ws/
│   ├── ChatComponent.php  # Composant WebSocket Ratchet
│   ├── broadcast.php      # Endpoint HTTP → WS
│   └── ws-client.js       # Client JS temps réel
├── bin/server.php         # Point d'entrée serveur WebSocket
├── sw.js                  # Service Worker (PWA)
├── manifest.json          # Manifest PWA
├── .env                   # Variables d'environnement (non versionné)
├── .env.example           # Template de configuration
└── database/
    ├── ecole_plus.sql     # Schéma complet de la base
    ├── migration.sql      # Migration consolidée
    ├── migration_otp.sql  # Migration OTP + invitations (v1.3)
    └── test_data.sql      # Données de test
```

### Architecture MVC (exemple : absences)

```
absences/
├── AbsencesRepository.php   # Couche données (SQL)
├── AbsencesController.php   # Logique métier + formulaires
├── absences.php             # Point d'entrée (routing mince)
└── views/absences/
    └── index.php            # Vue pure (HTML)
```

### Chaîne de chargement

```
session_bootstrap.php
  ├── config/env.php        → charge .env → putenv()
  ├── config/db.php          → $pdo (via getenv)
  └── config/app.php         → APP_VERSION, APP_URL, is_dev()
```

---

## Fonctionnalités

### Authentification

- Connexion par identifiant/mot de passe
- **Double authentification (OTP)** — Code 6 chiffres par SMS + email à chaque connexion
- Inscription parent avec liaison par matricule élève
- Inscription par invitation (lien token 7 jours)
- Connexion Google OAuth 2.0
- Réinitialisation mot de passe par email (jeton à usage unique)
- Sessions en base de données chiffrées (AES-256-GCM)

### Temps réel

- Chat WebSocket (Ratchet) sur le port 8080
- Fallback AJAX automatique si WebSocket indisponible
- Notification sonore pour les nouveaux messages
- Indicateur de statut de connexion

### PWA & Hors-ligne

- Service Worker avec stratégies Network First / Cache First
- IndexedDB pour les données locales (notes, absences, annonces, profil)
- Synchronisation automatique au chargement et toutes les 5 minutes
- Bannière statut réseau (en ligne / hors-ligne / synchronisation)

### Professeurs (v1.3)

- Liste des professeurs par enfant (matières + classes)
- Détail d'un professeur avec toutes ses matières
- Contact direct via la messagerie (création conversation ou accès existant)
- Navigation intégrée dans la sidebar et le menu mobile

### Sécurité

- Content-Security-Policy (CSP) avec nonces dynamiques
- Tokens CSRF sur tous les formulaires
- Sessions chiffrées AES-256-GCM en base
- Headers HTTP sécurisés (X-Frame-Options, HSTS, etc.)
- Validation des entrées et échappement des sorties

---

## Base de données

### Tables principales

| Table | Rôle |
|-------|------|
| `parents` | Comptes parents |
| `parent_eleve` | Association parent ↔ élève |
| `eleve` | Élèves |
| `elevesalle` | Affectation élève ↔ classe |
| `anneescolaire` | Années scolaires |
| `salle` / `classe` | Salles et classes |
| `note` | Notes par matière/période |
| `bulletin` / `bulletincontenu` | Bulletins officiels |
| `bulletin_calcule` | Cache des moyennes gelées |
| `absences` | Absences et retards |
| `paiementfrais` / `paiementtypeclasse` | Paiements |
| `cahier_texte` | Cours et devoirs |
| `matiere` / `professeur` | Matières et enseignants |
| `msg_conversations` / `msg_messages` | Messagerie |
| `annonces_ecole` | Annonces officielles |
| `sessions_parents` | Sessions en base |
| `otp_codes` | Codes OTP pour double authentification (v1.3) |
| `parent_invitations` | Invitations parents (v1.3) |

### Scripts SQL

```bash
# Schéma complet
mysql -u root -p ecole_plus < database/ecole_plus.sql

# Migration consolidée (après modification du schéma)
mysql -u root -p ecole_plus < database/migration.sql

# Migration OTP + invitations (v1.3)
mysql -u root -p ecole_plus < database/migration_otp.sql

# Données de test (3 parents, 2 élèves, notes, absences, bulletins, messagerie)
mysql -u root -p ecole_plus < database/test_data.sql
```

### Gel des bulletins

```bash
# Geler toutes les périodes
php config/bulletin_freeze.php

# Geler un trimestre spécifique
php config/bulletin_freeze.php --position=3

# Déverrouiller (rendre recalculable)
php config/bulletin_freeze.php --unfreeze
```

### Tables utilisées par page

| Page / Module | Tables utilisées |
|---------------|------------------|
| **Authentification** (`auth/login.php`) | `parents` |
| **Inscription** (`auth/inscription.php`) | `parents` |
| **Inscription parent** (`auth/inscription.php`) | `parents`, `eleve`, `parent_eleve` |
| **Profil** (`auth/profile.php`) | `parents` |
| **Mot de passe oublié** (`auth/forgot_password.php`) | `parents`, `password_resets_parents` |
| **Réinitialisation** (`auth/reset_password.php`) | `parents`, `password_resets_parents`, `sessions_parents` |
| **Google OAuth** (`auth/google_callback.php`) | `parents` |
| **Session** (`includes/session.php`) | `eleve`, `parent_eleve`, `elevesalle`, `salle` |
| **Dashboard** (`dashboard/`) | `anneescolaire`, `eleve`, `eleveanneescolaire`, `elevesalle`, `salle`, `classe`, `note`, `absences`, `cahier_texte`, `paiementtypeclasse`, `paiementfrais`, `matiere`, `professeur`, `annonces_ecole`, `msg_messages`, `msg_participants` |
| **Notes** (`notes/`) | `anneescolaire`, `position`, `bulletin`, `bulletincontenu`, `bulletin_calcule`, `note`, `matiere`, `eleve`, `salle` |
| **Absences** (`absences/`) | `elevesalle`, `anneescolaire`, `absences` |
| **Cahier de textes** (`cahier_texte/`) | `anneescolaire`, `professeursallemat`, `matiere`, `cahier_texte`, `professeur` |
| **Paiements** (`paiements/`) | `anneescolaire`, `eleveanneescolaire`, `paiementtypeclasse`, `paiementfrais`, `parent_eleve`, `eleve`, `elevesalle`, `salle`, `parents` |
| **Enfants** (`enfants/`) | `anneescolaire`, `elevesalle`, `salle`, `paiementtypeclasse`, `paiementfrais`, `absences`, `note`, `matiere`, `position` |
| **Messagerie** (`messagerie/` + `ws/`) | `anneescolaire`, `eleve`, `elevesalle`, `professeursallemat`, `professeur`, `matiere`, `parents`, `utilisateur`, `msg_conversations`, `msg_participants`, `msg_messages`, `msg_statuts_lecture`, `msg_pieces_jointes` |
| **Sync hors-ligne** (`config/offline_sync.php`) | `parent_eleve`, `anneescolaire`, `note`, `matiere`, `position`, `absences`, `elevesalle`, `annonces_ecole`, `cahier_texte`, `professeur`, `salle`, `eleve` |

### Matrice de dépendance

| Table | Dashboard | Notes | Absences | Cahier texte | Paiements | Enfants | Messagerie | Offline | Auth |
|-------|:---------:|:-----:|:--------:|:------------:|:---------:|:-------:|:----------:|:-------:|:----:|
| `parents` | | | | | x | | x | | x |
| `parent_eleve` | | | | | x | | | x | x |
| `eleve` | x | x | | | x | | x | x | x |
| `elevesalle` | x | | x | | x | x | x | x | |
| `salle` | x | x | | | x | x | | x | |
| `classe` | x | | | | | | | | |
| `anneescolaire` | x | x | x | x | x | x | | x | |
| `note` | x | x | | | | x | | x | |
| `matiere` | x | x | | x | | x | x | x | |
| `position` | | x | | | | x | | x | |
| `professeur` | x | | | x | | | x | x | |
| `absences` | x | | x | | | x | | x | |
| `bulletin` | | x | | | | | | | |
| `bulletincontenu` | | x | | | | | | | |
| `bulletin_calcule` | | x | | | | | | | |
| `cahier_texte` | x | | | x | | | | x | |
| `paiementtypeclasse` | x | | | | x | x | | | |
| `paiementfrais` | x | | | | x | x | | | |
| `annonces_ecole` | x | | | | | | | x | |
| `professeursallemat` | | | | x | | | x | | |
| `eleveanneescolaire` | x | | | | x | | | | |
| `utilisateur` | | | | | | | x | | |
| `msg_conversations` | x | | | | | | x | | |
| `msg_messages` | x | | | | | | x | | |
| `msg_participants` | x | | | | | | x | | |
| `msg_statuts_lecture` | | | | | | | x | | |
| `msg_pieces_jointes` | | | | | | | x | | |
| `password_resets_parents` | | | | | | | | | x |
| `sessions_parents` | | | | | | | | | x |

---

## Commandes utiles

```bash
# Serveur WebSocket (port 8080)
php bin/server.php

# Nettoyage des sessions expirées
php config/session_cleanup.php

# Nettoyage via clé secrète (web)
curl "http://localhost/portail-parent/config/session_cleanup.php?cle=VOTRE_CLE"
```

---

## Technologies

| Couche | Technologies |
|--------|-------------|
| **Backend** | PHP 8.x, PDO, SessionHandlerInterface |
| **Frontend** | Bootstrap 5.3, Font Awesome 6.5, Chart.js 4.4 |
| **Temps réel** | Ratchet (WebSocket), fallback AJAX |
| **Sécurité** | CSP (nonces), AES-256-GCM, CSRF, HSTS |
| **PWA** | Service Worker, manifest.json, IndexedDB |
| **Base** | MySQL 8.0+ / MariaDB 10.11+ |
| **Dépendances** | `cboden/ratchet` (WebSocket) |

---

## License

Projet privé — Tous droits réservés.
