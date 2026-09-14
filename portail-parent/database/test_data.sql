-- ==========================================================================
-- DONNÉES TEST — Ecole Plus Portail Parent v1.2.0
--
-- À exécuter APRÈS le dump ecole_plus.sql et la migration.sql
-- Mot de passe par défaut pour tous les parents : Test@1234
-- Compatible : MySQL 8.0+ / MariaDB 10.11+
-- ==========================================================================

-- ##########################################################################
-- 0. CORRECTIONS DES ÉLÈVES EXISTANTS (ETAT_ELEVE = 'Actif')
-- ##########################################################################

UPDATE `eleve` SET `ETAT_ELEVE` = 'Actif' WHERE `ID_ELEVE` IN (1, 2);

-- ##########################################################################
-- 1. COMPTES PARENTS (3 comptes de test)
-- ##########################################################################

INSERT INTO `parents`
    (`NOM_PARENT`, `PRENOM_PARENT`, `SEXE_PARENT`, `TEL_PARENT`, `MAIL_PARENT`,
     `LOGIN_PARENT`, `MTPASS_PARENT`, `STATUT_PARENT`, `DATE_CREATION`)
VALUES
    ('KOUASSI', 'Jean-Pierre', 'Masculin', '(228) 90123456', 'kouassi.jp@gmail.com',
     'kouassi', '$2y$10$O9Nb2rTUofQIMFVGvj3OUeoNQduNP3GfzI4Q3NWRwEtSWhu71c5ce', 1, NOW()),

    ('AGBENOUGLO', 'Marie', 'Féminin', '(228) 91234567', 'agbenouglo.m@gmail.com',
     'agbenouglo', '$2y$10$O9Nb2rTUofQIMFVGvj3OUeoNQduNP3GfzI4Q3NWRwEtSWhu71c5ce', 1, NOW()),

    ('TOKO', 'Philippe', 'Masculin', '(228) 92345678', 'toko.p@gmail.com',
     'toko', '$2y$10$O9Nb2rTUofQIMFVGvj3OUeoNQduNP3GfzI4Q3NWRwEtSWhu71c5ce', 0, NOW());


-- ##########################################################################
-- 2. LIENS PARENTS ↔ ÉLÈVES
-- ##########################################################################
-- Parent 1 (KOUASSI) → Élève 1 (KOWOUVI Emmanuel, 1ère D)
-- Parent 2 (AGBENOUGLO) → Élève 2 (AHOLOU Jacques, CE2)
-- Parent 3 (TOKO) → Élève 1 + Élève 2 (beau-père / tuteur)

INSERT INTO `parent_eleve` (`ID_PARENT`, `ID_ELEVE`)
VALUES
    (1, 1),
    (2, 2),
    (3, 1),
    (3, 2);


-- ##########################################################################
-- 3. NOTES (élèves 1 et 2, trimestres 1 à 3)
-- ##########################################################################

-- --- Élève 1 : KOWOUVI Emmanuel (ID_ELEVE=1, IDSALLE=15, IDCLASSE=16 = 1ère D) ---
-- Matières: FR(16), MATHS(6), ANGLAIS(3), HISTO-GEO(5), PCT(7), SVT(8), PHILO(37)

-- TRIMESTRE 1 (IDPOSITION=1)
INSERT INTO `note`
    (`NOTEINT`, `NOTEDS`, `NOTEDN`, `MOYCLASS`, `NOTECOMP`, `MOYENTRIMES`, `COEF`,
     `MOYENPONDERE`, `RANG`, `IDPROFESSEUR`, `OBSERVATION`, `IDELEVE`, `IDMATIERE`,
     `IDSALLE`, `IDPOSITION`, `IDANNEESCOLAIRE`, `EST_PUBLIE`)
VALUES
    ('14', '12', '13', '11', '0', 13.0, 2, 26.0, '3', 1, 'Très bien', 1, 16, 15, 1, 1, 1),
    ('16', '15', '14', '12', '0', 15.0, 3, 45.0, '2', 1, 'Excellent', 1, 6, 15, 1, 1, 1),
    ('12', '13', '11', '10', '0', 12.0, 1, 12.0, '5', 1, 'Bien', 1, 3, 15, 1, 1, 1),
    ('11', '10', '12', '11', '0', 11.0, 1, 11.0, '6', 1, 'Assez bien', 1, 5, 15, 1, 1, 1),
    ('15', '14', '13', '11', '0', 14.0, 1, 14.0, '1', 1, 'Très bien', 1, 7, 15, 1, 1, 1),
    ('13', '12', '14', '10', '0', 13.0, 1, 13.0, '4', 1, 'Bien', 1, 8, 15, 1, 1, 1),
    ('10', '11', '12', '10', '0', 11.0, 2, 22.0, '7', 1, 'Passable', 1, 37, 15, 1, 1, 1);

-- TRIMESTRE 2 (IDPOSITION=2)
INSERT INTO `note`
    (`NOTEINT`, `NOTEDS`, `NOTEDN`, `MOYCLASS`, `NOTECOMP`, `MOYENTRIMES`, `COEF`,
     `MOYENPONDERE`, `RANG`, `IDPROFESSEUR`, `OBSERVATION`, `IDELEVE`, `IDMATIERE`,
     `IDSALLE`, `IDPOSITION`, `IDANNEESCOLAIRE`, `EST_PUBLIE`)
VALUES
    ('15', '14', '16', '12', '0', 15.0, 2, 30.0, '2', 1, 'Très bien', 1, 16, 15, 2, 1, 1),
    ('17', '16', '15', '12', '0', 16.0, 3, 48.0, '1', 1, 'Excellent', 1, 6, 15, 2, 1, 1),
    ('13', '14', '12', '10', '0', 13.0, 1, 13.0, '4', 1, 'Bien', 1, 3, 15, 2, 1, 1),
    ('12', '11', '13', '11', '0', 12.0, 1, 12.0, '5', 1, 'Assez bien', 1, 5, 15, 2, 1, 1),
    ('16', '15', '14', '11', '0', 15.0, 1, 15.0, '1', 1, 'Très bien', 1, 7, 15, 2, 1, 1),
    ('14', '13', '15', '10', '0', 14.0, 1, 14.0, '3', 1, 'Bien', 1, 8, 15, 2, 1, 1),
    ('12', '13', '14', '10', '0', 13.0, 2, 26.0, '4', 1, 'Assez bien', 1, 37, 15, 2, 1, 1);

-- TRIMESTRE 3 (IDPOSITION=3)
INSERT INTO `note`
    (`NOTEINT`, `NOTEDS`, `NOTEDN`, `MOYCLASS`, `NOTECOMP`, `MOYENTRIMES`, `COEF`,
     `MOYENPONDERE`, `RANG`, `IDPROFESSEUR`, `OBSERVATION`, `IDELEVE`, `IDMATIERE`,
     `IDSALLE`, `IDPOSITION`, `IDANNEESCOLAIRE`, `EST_PUBLIE`)
VALUES
    ('16', '15', '17', '12', '0', 16.0, 2, 32.0, '1', 1, 'Très bien', 1, 16, 15, 3, 1, 1),
    ('18', '17', '16', '13', '0', 17.0, 3, 51.0, '1', 1, 'Excellent', 1, 6, 15, 3, 1, 1),
    ('14', '15', '13', '10', '0', 14.0, 1, 14.0, '3', 1, 'Bien', 1, 3, 15, 3, 1, 1),
    ('13', '12', '14', '11', '0', 13.0, 1, 13.0, '4', 1, 'Bien', 1, 5, 15, 3, 1, 1),
    ('17', '16', '15', '12', '0', 16.0, 1, 16.0, '1', 1, 'Très bien', 1, 7, 15, 3, 1, 1),
    ('15', '14', '16', '11', '0', 15.0, 1, 15.0, '2', 1, 'Très bien', 1, 8, 15, 3, 1, 1),
    ('13', '14', '15', '10', '0', 14.0, 2, 28.0, '3', 1, 'Assez bien', 1, 37, 15, 3, 1, 1);


-- --- Élève 2 : AHOLOU Jacques (ID_ELEVE=2, IDSALLE=7, IDCLASSE=7 = CE2) ---
-- Matières: FR(16), MATHS(6), ECriture(17), DICTEE(42), CALCUL(45)

-- TRIMESTRE 1
INSERT INTO `note`
    (`NOTEINT`, `NOTEDS`, `NOTEDN`, `MOYCLASS`, `NOTECOMP`, `MOYENTRIMES`, `COEF`,
     `MOYENPONDERE`, `RANG`, `IDPROFESSEUR`, `OBSERVATION`, `IDELEVE`, `IDMATIERE`,
     `IDSALLE`, `IDPOSITION`, `IDANNEESCOLAIRE`, `EST_PUBLIE`)
VALUES
    ('12', '11', '13', '10', '0', 12.0, 2, 24.0, '5', 1, 'Assez bien', 2, 16, 7, 1, 1, 1),
    ('14', '15', '13', '11', '0', 14.0, 20, 280.0, '3', 1, 'Bien', 2, 6, 7, 1, 1, 1),
    ('10', '11', '12', '10', '0', 11.0, 20, 220.0, '7', 1, 'Passable', 2, 17, 7, 1, 1, 1),
    ('13', '12', '14', '10', '0', 13.0, 10, 130.0, '4', 1, 'Bien', 2, 42, 7, 1, 1, 1),
    ('15', '14', '16', '11', '0', 15.0, 10, 150.0, '2', 1, 'Très bien', 2, 45, 7, 1, 1, 1);

-- TRIMESTRE 2
INSERT INTO `note`
    (`NOTEINT`, `NOTEDS`, `NOTEDN`, `MOYCLASS`, `NOTECOMP`, `MOYENTRIMES`, `COEF`,
     `MOYENPONDERE`, `RANG`, `IDPROFESSEUR`, `OBSERVATION`, `IDELEVE`, `IDMATIERE`,
     `IDSALLE`, `IDPOSITION`, `IDANNEESCOLAIRE`, `EST_PUBLIE`)
VALUES
    ('13', '12', '14', '10', '0', 13.0, 2, 26.0, '4', 1, 'Bien', 2, 16, 7, 2, 1, 1),
    ('15', '16', '14', '11', '0', 15.0, 20, 300.0, '2', 1, 'Très bien', 2, 6, 7, 2, 1, 1),
    ('12', '13', '11', '10', '0', 12.0, 20, 240.0, '5', 1, 'Assez bien', 2, 17, 7, 2, 1, 1),
    ('14', '13', '15', '10', '0', 14.0, 10, 140.0, '3', 1, 'Bien', 2, 42, 7, 2, 1, 1),
    ('16', '15', '17', '11', '0', 16.0, 10, 160.0, '1', 1, 'Très bien', 2, 45, 7, 2, 1, 1);

-- TRIMESTRE 3
INSERT INTO `note`
    (`NOTEINT`, `NOTEDS`, `NOTEDN`, `MOYCLASS`, `NOTECOMP`, `MOYENTRIMES`, `COEF`,
     `MOYENPONDERE`, `RANG`, `IDPROFESSEUR`, `OBSERVATION`, `IDELEVE`, `IDMATIERE`,
     `IDSALLE`, `IDPOSITION`, `IDANNEESCOLAIRE`, `EST_PUBLIE`)
VALUES
    ('14', '13', '15', '10', '0', 14.0, 2, 28.0, '3', 1, 'Bien', 2, 16, 7, 3, 1, 1),
    ('16', '17', '15', '12', '0', 16.0, 20, 320.0, '1', 1, 'Très bien', 2, 6, 7, 3, 1, 1),
    ('13', '14', '12', '10', '0', 13.0, 20, 260.0, '4', 1, 'Bien', 2, 17, 7, 3, 1, 1),
    ('15', '14', '16', '11', '0', 15.0, 10, 150.0, '2', 1, 'Très bien', 2, 42, 7, 3, 1, 1),
    ('17', '16', '18', '12', '0', 17.0, 10, 170.0, '1', 1, 'Excellent', 2, 45, 7, 3, 1, 1);


-- ##########################################################################
-- 4. ABSENCES
-- ##########################################################################

-- Élève 1 : 3 absences en T1, 1 absence justifiée en T2
INSERT INTO `absences`
    (`IDELEVESALLE`, `IDSALLE`, `IDANNEESCOLAIRE`, `IDPOSITION`, `IDMATIERE`, `IDPROF`,
     `NBREABSENCE`, `DATEENREG`, `IDUSERCREATE`, `TYPEABSENCE`, `DATE_DEMANDE`,
     `DATE_DEBUT`, `DATE_FIN`, `MOTIF_PERMISSION`, `STATUT_PERMISSION`)
VALUES
    (1, 15, 1, 1, NULL, NULL, 1, '2025-10-15', 103, 'Absence', '2025-10-15', '2025-10-15', NULL, NULL, NULL),
    (1, 15, 1, 1, NULL, NULL, 2, '2025-11-20', 103, 'Absence', '2025-11-20', '2025-11-20', NULL, NULL, NULL),
    (1, 15, 1, 1, NULL, NULL, 1, '2025-12-10', 103, 'Retard', '2025-12-10', '2025-12-10', NULL, 'Maladie', 1),
    (1, 15, 1, 2, NULL, NULL, 1, '2026-01-22', 103, 'Absence', '2026-01-22', '2026-01-22', NULL, NULL, NULL);

-- Élève 2 : 1 absence en T1, 2 absences en T2
INSERT INTO `absences`
    (`IDELEVESALLE`, `IDSALLE`, `IDANNEESCOLAIRE`, `IDPOSITION`, `IDMATIERE`, `IDPROF`,
     `NBREABSENCE`, `DATEENREG`, `IDUSERCREATE`, `TYPEABSENCE`, `DATE_DEMANDE`,
     `DATE_DEBUT`, `DATE_FIN`, `MOTIF_PERMISSION`, `STATUT_PERMISSION`)
VALUES
    (2, 7, 1, 1, NULL, NULL, 1, '2025-10-28', 103, 'Absence', '2025-10-28', '2025-10-28', NULL, NULL, NULL),
    (2, 7, 1, 2, NULL, NULL, 1, '2026-02-05', 103, 'Retard', '2026-02-05', '2026-02-05', NULL, 'Rendez-vous médical', 1),
    (2, 7, 1, 2, NULL, NULL, 1, '2026-03-10', 103, 'Absence', '2026-03-10', '2026-03-10', NULL, NULL, NULL);


-- ##########################################################################
-- 5. BULLETINS (bulletins officiels pour les 2 élèves)
-- ##########################################################################

-- Élève 1 : 3 trimestres
INSERT INTO `bulletin`
    (`IDPOSITION`, `IDELEVE`, `IDANNEESCOLAIRE`, `IDSALLE`, `MOYENNE_GENE`, `RANG`, `MOYEN_ANN`, `RANG_ANN`, `observation`)
VALUES
    (1, 1, 1, 15, 12.57, 3, NULL, NULL, 'Bon trimestre. Continue tes efforts.'),
    (2, 1, 1, 15, 13.43, 2, NULL, NULL, 'Très bon progrès. Résultats satisfaisants.'),
    (3, 1, 1, 15, 15.00, 1, 13.67, 1, 'Excellent trimestre. Classement premier.');

-- Élève 2 : 3 trimestres
INSERT INTO `bulletin`
    (`IDPOSITION`, `IDELEVE`, `IDANNEESCOLAIRE`, `IDSALLE`, `MOYENNE_GENE`, `RANG`, `MOYEN_ANN`, `RANG_ANN`, `observation`)
VALUES
    (1, 2, 1, 7, 13.00, 4, NULL, NULL, 'Bon travail. Peut mieux faire en écriture.'),
    (2, 2, 1, 7, 14.00, 3, NULL, NULL, 'Progrès notable. Résultats encourageants.'),
    (3, 2, 1, 7, 15.00, 2, 14.00, 3, 'Très bon résultat. Félicitations.');


-- ##########################################################################
-- 6. BULLETIN CONTENU (détails par matière pour chaque bulletin)
-- ##########################################################################

-- --- Bulletin 1 (Élève 1, T1, IDBULLETIN=1) ---
INSERT INTO `bulletincontenu`
    (`IDBULLETIN`, `IDMATIERE`, `INTE`, `DS`, `DN`, `MOY_CLASSE`, `NOTES_COMP`,
     `MOY_TRIMES`, `COEF`, `MOY_PONDERE`, `RANG`, `PROFESSEUR`, `APPRECIATION`)
VALUES
    (1, 16, 14, 12, 13, 11, NULL, 13.0, 2, 26.0, 3, 'AGODJI Jacob', 'Très bien'),
    (1, 6,  16, 15, 14, 12, NULL, 15.0, 3, 45.0, 2, 'AGODJI Jacob', 'Excellent'),
    (1, 3,  12, 13, 11, 10, NULL, 12.0, 1, 12.0, 5, 'AGODJI Jacob', 'Bien'),
    (1, 5,  11, 10, 12, 11, NULL, 11.0, 1, 11.0, 6, 'AGODJI Jacob', 'Assez bien'),
    (1, 7,  15, 14, 13, 11, NULL, 14.0, 1, 14.0, 1, 'AGODJI Jacob', 'Très bien'),
    (1, 8,  13, 12, 14, 10, NULL, 13.0, 1, 13.0, 4, 'AGODJI Jacob', 'Bien'),
    (1, 37, 10, 11, 12, 10, NULL, 11.0, 2, 22.0, 7, 'AGODJI Jacob', 'Passable');

-- --- Bulletin 2 (Élève 1, T2, IDBULLETIN=2) ---
INSERT INTO `bulletincontenu`
    (`IDBULLETIN`, `IDMATIERE`, `INTE`, `DS`, `DN`, `MOY_CLASSE`, `NOTES_COMP`,
     `MOY_TRIMES`, `COEF`, `MOY_PONDERE`, `RANG`, `PROFESSEUR`, `APPRECIATION`)
VALUES
    (2, 16, 15, 14, 16, 12, NULL, 15.0, 2, 30.0, 2, 'AGODJI Jacob', 'Très bien'),
    (2, 6,  17, 16, 15, 12, NULL, 16.0, 3, 48.0, 1, 'AGODJI Jacob', 'Excellent'),
    (2, 3,  13, 14, 12, 10, NULL, 13.0, 1, 13.0, 4, 'AGODJI Jacob', 'Bien'),
    (2, 5,  12, 11, 13, 11, NULL, 12.0, 1, 12.0, 5, 'AGODJI Jacob', 'Assez bien'),
    (2, 7,  16, 15, 14, 11, NULL, 15.0, 1, 15.0, 1, 'AGODJI Jacob', 'Très bien'),
    (2, 8,  14, 13, 15, 10, NULL, 14.0, 1, 14.0, 3, 'AGODJI Jacob', 'Bien'),
    (2, 37, 12, 13, 14, 10, NULL, 13.0, 2, 26.0, 4, 'AGODJI Jacob', 'Assez bien');

-- --- Bulletin 3 (Élève 1, T3, IDBULLETIN=3) ---
INSERT INTO `bulletincontenu`
    (`IDBULLETIN`, `IDMATIERE`, `INTE`, `DS`, `DN`, `MOY_CLASSE`, `NOTES_COMP`,
     `MOY_TRIMES`, `COEF`, `MOY_PONDERE`, `RANG`, `PROFESSEUR`, `APPRECIATION`)
VALUES
    (3, 16, 16, 15, 17, 12, NULL, 16.0, 2, 32.0, 1, 'AGODJI Jacob', 'Très bien'),
    (3, 6,  18, 17, 16, 13, NULL, 17.0, 3, 51.0, 1, 'AGODJI Jacob', 'Excellent'),
    (3, 3,  14, 15, 13, 10, NULL, 14.0, 1, 14.0, 3, 'AGODJI Jacob', 'Bien'),
    (3, 5,  13, 12, 14, 11, NULL, 13.0, 1, 13.0, 4, 'AGODJI Jacob', 'Bien'),
    (3, 7,  17, 16, 15, 12, NULL, 16.0, 1, 16.0, 1, 'AGODJI Jacob', 'Très bien'),
    (3, 8,  15, 14, 16, 11, NULL, 15.0, 1, 15.0, 2, 'AGODJI Jacob', 'Très bien'),
    (3, 37, 13, 14, 15, 10, NULL, 14.0, 2, 28.0, 3, 'AGODJI Jacob', 'Assez bien');

-- --- Bulletins Élève 2 (IDBULLETIN=4,5,6) ---
INSERT INTO `bulletincontenu`
    (`IDBULLETIN`, `IDMATIERE`, `INTE`, `DS`, `DN`, `MOY_CLASSE`, `NOTES_COMP`,
     `MOY_TRIMES`, `COEF`, `MOY_PONDERE`, `RANG`, `PROFESSEUR`, `APPRECIATION`)
VALUES
    (4, 16, 12, 11, 13, 10, NULL, 12.0, 2, 24.0, 5, 'AGODJI Jacob', 'Assez bien'),
    (4, 6,  14, 15, 13, 11, NULL, 14.0, 20, 280.0, 3, 'AGODJI Jacob', 'Bien'),
    (4, 17, 10, 11, 12, 10, NULL, 11.0, 20, 220.0, 7, 'AGODJI Jacob', 'Passable'),
    (4, 42, 13, 12, 14, 10, NULL, 13.0, 10, 130.0, 4, 'AGODJI Jacob', 'Bien'),
    (4, 45, 15, 14, 16, 11, NULL, 15.0, 10, 150.0, 2, 'AGODJI Jacob', 'Très bien'),
    (5, 16, 13, 12, 14, 10, NULL, 13.0, 2, 26.0, 4, 'AGODJI Jacob', 'Bien'),
    (5, 6,  15, 16, 14, 11, NULL, 15.0, 20, 300.0, 2, 'AGODJI Jacob', 'Très bien'),
    (5, 17, 12, 13, 11, 10, NULL, 12.0, 20, 240.0, 5, 'AGODJI Jacob', 'Assez bien'),
    (5, 42, 14, 13, 15, 10, NULL, 14.0, 10, 140.0, 3, 'AGODJI Jacob', 'Bien'),
    (5, 45, 16, 15, 17, 11, NULL, 16.0, 10, 160.0, 1, 'AGODJI Jacob', 'Très bien'),
    (6, 16, 14, 13, 15, 10, NULL, 14.0, 2, 28.0, 3, 'AGODJI Jacob', 'Bien'),
    (6, 6,  16, 17, 15, 12, NULL, 16.0, 20, 320.0, 1, 'AGODJI Jacob', 'Très bien'),
    (6, 17, 13, 14, 12, 10, NULL, 13.0, 20, 260.0, 4, 'AGODJI Jacob', 'Bien'),
    (6, 42, 15, 14, 16, 11, NULL, 15.0, 10, 150.0, 2, 'AGODJI Jacob', 'Très bien'),
    (6, 45, 17, 16, 18, 12, NULL, 17.0, 10, 170.0, 1, 'AGODJI Jacob', 'Excellent');


-- ##########################################################################
-- 7. BULLETIN CALCULÉ (cache gelé)
-- ##########################################################################

INSERT INTO `bulletin_calcule`
    (`IDELEVE`, `IDSALLE`, `IDANNEESCOLAIRE`, `IDPOSITION`,
     `MOYENNE_GENERALE`, `RANG_CLASSE`, `TOTAL_POINTS`, `TOTAL_COEFS`, `NOMBRE_MATIERES`,
     `DATE_CALCUL`, `EST_GELE`)
VALUES
    -- Élève 1 (KOWOUVI) : 3 trimestres
    (1, 15, 1, 1, 12.57, 3, 213.00, 11.00, 7, NOW(), 1),
    (1, 15, 1, 2, 13.43, 2, 228.00, 11.00, 7, NOW(), 1),
    (1, 15, 1, 3, 15.00, 1, 252.00, 11.00, 7, NOW(), 1),
    -- Élève 2 (AHOLOU) : 3 trimestres
    (2, 7, 1, 1, 13.00, 4, 260.00, 20.00, 5, NOW(), 1),
    (2, 7, 1, 2, 14.00, 3, 280.00, 20.00, 5, NOW(), 1),
    (2, 7, 1, 3, 15.00, 2, 300.00, 20.00, 5, NOW(), 1);


-- ##########################################################################
-- 8. ANNONCES ÉCOLE
-- ##########################################################################

INSERT INTO `annonces_ecole` (`TITRE`, `CONTENU`, `DATE_PUBLICATION`, `CIBLE_TYPE`, `ID_CIBLE`, `IDUSERCREATE`)
VALUES
    ('Réunion parents-professeurs',
     'La réunion parents-professeurs aura lieu le samedi 25 octobre 2025 à 9h00 dans l''amphithéâtre. Votre présence est vivement souhaitée.',
     '2025-10-01 08:00:00', 'TOUS', NULL, 103),
    ('Résultats du 1er trimestre',
     'Les résultats du premier trimestre sont disponibles sur le portail parent. Consultez les bulletins de vos enfants.',
     '2025-12-20 14:00:00', 'TOUS', NULL, 103),
    ('Journée portes ouvertes',
     'L''établissement organise sa journée portes ouvertes le 15 janvier 2026 de 8h à 16h. Venez nombreux !',
     '2026-01-10 09:00:00', 'TOUS', NULL, 103),
    ('Frais de scolarité — 2ème tranche',
     'La date limite de paiement de la 2ème tranche des frais de scolarité est fixée au 30 novembre 2025. Merci de vous conformer à cet échéancier.',
     '2025-11-05 10:00:00', 'TOUS', NULL, 103),
    ('Navette scolaire — Nouveau trajet',
     'Un nouveau trajet de navette scolaire est disponible pour les quartiers du Boulevard du 13 Janvier. Inscription au secrétariat.',
     '2026-02-15 11:00:00', 'TOUS', NULL, 103);


-- ##########################################################################
-- 9. CAHIER DE TEXTES
-- ##########################################################################

INSERT INTO `cahier_texte`
    (`IDSALLE`, `IDMATIERE`, `IDPROF`, `IDANNEESCOLAIRE`, `DATE_COURS`, `CONTENU_COURS`,
     `DEVOIRS_A_FAIRE`, `DATE_ECHEANCE`, `FICHIER_DEVOIR`)
VALUES
    (15, 6, 1, 1, '2025-10-13', 'Équations du second degré — Résolution par la méthode du discriminant.', 'Exercices 3 et 4 page 145', '2025-10-20', NULL),
    (15, 16, 1, 1, '2025-10-14', 'Étude de texte : Le Petit Prince — Analyse des thèmes principaux.', 'Rédiger un paragraphe de 20 lignes sur le thème de l''amitié.', '2025-10-21', NULL),
    (15, 3, 1, 1, '2025-10-15', 'The Simple Past — Exercices de conjugaison.', 'Compléter les exercices du handout distribué en classe.', '2025-10-22', NULL),
    (15, 7, 1, 1, '2025-10-16', 'Les réactions acide-base — Titre de pH.', 'Fiche d''exercices n°12 : dosages et calculs de pH.', '2025-10-23', NULL),
    (15, 6, 1, 1, '2025-11-03', 'Fonctions trigonométriques — sinus, cosinus, tangente.', 'Apprendre les formules et résoudre les exercices 1 à 5.', '2025-11-10', NULL),
    (7, 6, 1, 1, '2025-10-13', 'Les fractions — Addition et soustraction de fractions.', 'Exercices page 67, numéros 1 à 10.', '2025-10-20', NULL),
    (7, 16, 1, 1, '2025-10-14', 'Dictée — Leçon de grammaire sur les accords.', 'Apprendre la leçon et préparer une dictée pour mercredi.', '2025-10-16', NULL),
    (7, 45, 1, 1, '2025-10-15', 'Tables de multiplication — Exercices de calcul mental.', 'Apprendre les tables de 6 à 9. Exercices-written', '2025-10-22', NULL);


-- ##########################################################################
-- 10. MESSAGERIE (conversations et messages)
-- ##########################################################################

-- --- Conversation département (Mathématiques) ---
INSERT INTO `msg_conversations` (`ID`, `TITRE`, `TYPE_CONV`, `DATE_CREATION`)
VALUES (1, 'Mathématiques', 'DEPARTEMENT', '2025-10-01 08:00:00');

INSERT INTO `msg_participants` (`ID_CONVERSATION`, `USER_TYPE`, `ID_USER`)
VALUES
    (1, 'PARENT', 1),
    (1, 'PARENT', 2),
    (1, 'STAFF', 103);

INSERT INTO `msg_messages` (`ID_CONVERSATION`, `EXPEDITEUR_TYPE`, `ID_EXPEDITEUR`, `CONTENU`, `DATE_ENVOI`)
VALUES
    (1, 'STAFF', 103, 'Bienvenue dans le groupe Mathématiques. Posez vos questions ici.', '2025-10-01 08:05:00'),
    (1, 'PARENT', 1, 'Bonjour, mon fils a des difficultés en algèbre. Y a-t-il des cours de soutien ?', '2025-10-02 14:30:00'),
    (1, 'STAFF', 103, 'Oui, un cours de soutien est disponible le mercredi de 15h à 16h30.', '2025-10-02 16:00:00'),
    (1, 'PARENT', 2, 'Merci pour l''info. Est-ce que c''est gratuit ?', '2025-10-03 09:15:00'),
    (1, 'STAFF', 103, 'Oui, le soutien est gratuit pour tous les élèves de l''établissement.', '2025-10-03 10:00:00');

INSERT INTO `msg_statuts_lecture` (`ID_MESSAGE`, `LECTEUR_TYPE`, `ID_LECTEUR`, `DATE_LECTURE`)
VALUES
    (1, 'PARENT', 1, '2025-10-01 08:10:00'),
    (1, 'PARENT', 2, '2025-10-01 09:00:00'),
    (2, 'STAFF', 103, '2025-10-02 15:00:00'),
    (2, 'PARENT', 2, NULL),
    (3, 'PARENT', 1, '2025-10-02 16:05:00'),
    (3, 'PARENT', 2, '2025-10-03 08:00:00'),
    (4, 'STAFF', 103, '2025-10-03 09:30:00'),
    (4, 'PARENT', 1, NULL),
    (5, 'PARENT', 1, '2025-10-03 10:05:00'),
    (5, 'PARENT', 2, '2025-10-03 10:10:00');

-- --- Conversation privée (Parent 1 ↔ Staff) ---
INSERT INTO `msg_conversations` (`ID`, `TITRE`, `TYPE_CONV`, `DATE_CREATION`)
VALUES (2, NULL, 'PRIVEE', '2025-10-05 11:00:00');

INSERT INTO `msg_participants` (`ID_CONVERSATION`, `USER_TYPE`, `ID_USER`)
VALUES
    (2, 'PARENT', 1),
    (2, 'STAFF', 103);

INSERT INTO `msg_messages` (`ID_CONVERSATION`, `EXPEDITEUR_TYPE`, `ID_EXPEDITEUR`, `CONTENU`, `DATE_ENVOI`)
VALUES
    (2, 'PARENT', 1, 'Bonjour, je souhaite obtenir un relevé de notes pour mon dossier.', '2025-10-05 11:05:00'),
    (2, 'STAFF', 103, 'Bien sûr, je vais le préparer. Vous pourrez le récupérer au secrétariat demain.', '2025-10-05 14:00:00'),
    (2, 'PARENT', 1, 'Merci beaucoup pour votre réactivité.', '2025-10-05 14:15:00');

INSERT INTO `msg_statuts_lecture` (`ID_MESSAGE`, `LECTEUR_TYPE`, `ID_LECTEUR`, `DATE_LECTURE`)
VALUES
    (6, 'STAFF', 103, '2025-10-05 11:10:00'),
    (6, 'PARENT', 1, NULL),
    (7, 'PARENT', 1, '2025-10-05 14:05:00'),
    (8, 'STAFF', 103, '2025-10-05 14:20:00'),
    (8, 'PARENT', 1, NULL);

-- --- Conversation privée (Parent 2 ↔ Staff) ---
INSERT INTO `msg_conversations` (`ID`, `TITRE`, `TYPE_CONV`, `DATE_CREATION`)
VALUES (3, NULL, 'PRIVEE', '2025-11-10 09:00:00');

INSERT INTO `msg_participants` (`ID_CONVERSATION`, `USER_TYPE`, `ID_USER`)
VALUES
    (3, 'PARENT', 2),
    (3, 'STAFF', 103);

INSERT INTO `msg_messages` (`ID_CONVERSATION`, `EXPEDITEUR_TYPE`, `ID_EXPEDITEUR`, `CONTENU`, `DATE_ENVOI`)
VALUES
    (3, 'PARENT', 2, 'Bonjour, Jacques a souvent mal au ventre en classe. Pouvez-vous le surveiller ?', '2025-11-10 09:05:00'),
    (3, 'STAFF', 103, 'Bien noté, nous allons surveiller la situation. Si ça persiste, il faudra consulter un médecin.', '2025-11-10 11:30:00');

INSERT INTO `msg_statuts_lecture` (`ID_MESSAGE`, `LECTEUR_TYPE`, `ID_LECTEUR`, `DATE_LECTURE`)
VALUES
    (9, 'STAFF', 103, '2025-11-10 09:10:00'),
    (9, 'PARENT', 2, NULL),
    (10, 'PARENT', 2, '2025-11-10 12:00:00'),
    (10, 'STAFF', 103, NULL);


-- ##########################################################################
-- 11. MISE À JOUR DES COMPTEURS AUTO_INCREMENT
-- ##########################################################################

ALTER TABLE `parents` AUTO_INCREMENT = 4;
ALTER TABLE `parent_eleve` AUTO_INCREMENT = 5;
ALTER TABLE `note` AUTO_INCREMENT = 36;
ALTER TABLE `bulletin` AUTO_INCREMENT = 7;
ALTER TABLE `bulletincontenu` AUTO_INCREMENT = 37;
ALTER TABLE `bulletin_calcule` AUTO_INCREMENT = 7;
ALTER TABLE `absences` AUTO_INCREMENT = 23;
ALTER TABLE `annonces_ecole` AUTO_INCREMENT = 6;
ALTER TABLE `cahier_texte` AUTO_INCREMENT = 9;
ALTER TABLE `msg_conversations` AUTO_INCREMENT = 4;
ALTER TABLE `msg_messages` AUTO_INCREMENT = 11;
ALTER TABLE `msg_statuts_lecture` AUTO_INCREMENT = 11;


-- ##########################################################################
-- RÉCAPITULATIF DES COMPTES TEST
-- ==========================================================================
-- LOGIN          | MOT DE PASSE | STATUT  | ÉLÈVES RATTACHÉS
-- kouassi        | Test@1234    | Actif   | KOWOUVI Emmanuel (1ère D)
-- agbenouglo     | Test@1234    | Actif   | AHOLOU Jacques (CE2)
-- toko           | Test@1234    | Inactif | KOWOUVI Emmanuel + AHOLOU Jacques
-- ##########################################################################
