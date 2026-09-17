-- ==========================================================================
-- DONNÉES TEST COMPLÈTES — Ecole Plus Portail Parent v1.3.0
--
-- À exécuter APRÈS le dump ecole_plus.sql et la migration.sql
-- Compatible : MySQL 8.0+ / MariaDB 10.11+
--
-- Mot de passe parent par défaut : Test@1234
-- ==========================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ##########################################################################
-- 0. CORRECTIONS DES ÉLÈVES EXISTANTS
-- ##########################################################################

UPDATE `eleve` SET `ETAT_ELEVE` = 'Actif' WHERE `ID_ELEVE` IN (1, 2);


-- ##########################################################################
-- 1. COMPTES PARENTS
-- ##########################################################################
-- Parent 1 : KOUASSI Jean-Pierre — père actif, 2 enfants (1ère D + CE2)
-- Parent 2 : AGBENOUGLO Marie — mère active, 1 enfant (CE2)
-- Parent 3 : TOKO Philippe — père INACTIF (test blocage connexion), 2 enfants

INSERT INTO `parents`
    (`NOM_PARENT`, `PRENOM_PARENT`, `SEXE_PARENT`, `TEL_PARENT`, `MAIL_PARENT`,
     `LOGIN_PARENT`, `MTPASS_PARENT`, `STATUT_PARENT`, `DATE_CREATION`)
VALUES
    ('KOUASSI', 'Jean-Pierre', 'Masculin', '(228) 90123456', 'kouassi.jp@gmail.com',
     'kouassi', '$2y$10$O9Nb2rTUofQIMFVGvj3OUeoNQduNP3GfzI4Q3NWRwEtSWhu71c5ce', 1, '2025-09-01 08:00:00'),

    ('AGBENOUGLO', 'Marie', 'Féminin', '(228) 91234567', 'agbenouglo.m@gmail.com',
     'agbenouglo', '$2y$10$O9Nb2rTUofQIMFVGvj3OUeoNQduNP3GfzI4Q3NWRwEtSWhu71c5ce', 1, '2025-09-01 09:00:00'),

    ('TOKO', 'Philippe', 'Masculin', '(228) 92345678', 'toko.p@gmail.com',
     'toko', '$2y$10$O9Nb2rTUofQIMFVGvj3OUeoNQduNP3GfzI4Q3NWRwEtSWhu71c5ce', 0, '2025-09-02 10:00:00');


-- ##########################################################################
-- 2. LIENS PARENTS ↔ ÉLÈVES
-- ##########################################################################
-- Parent 1 (KOUASSI) → Élève 1 (KOWOUVI, 1ère D) + Élève 2 (AHOLOU, CE2)
-- Parent 2 (AGBENOUGLO) → Élève 2 (AHOLOU, CE2)
-- Parent 3 (TOKO)     → Élève 1 + Élève 2 (beau-père / tuteur)

INSERT INTO `parent_eleve` (`ID_PARENT`, `ID_ELEVE`)
VALUES
    (1, 1),
    (1, 2),
    (2, 2),
    (3, 1),
    (3, 2);


-- ##########################################################################
-- 3. ÉLÈVES ANNÉE SCOLAIRE (inscription annuelle)
-- ##########################################################################

INSERT INTO `eleveanneescolaire` (`IDELEVE`, `IDANNEESCOLAIRE`, `IDCLASSE`, `ETAT`, `INSCRIT`, `STATUT`)
VALUES
    (1, 1, 16, 1, 1, 1),
    (2, 1, 7, 1, 1, 1);


-- ##########################################################################
-- 4. NOTES — Élève 1 : KOWOUVI (1ère D, IDSALLE=15)
-- ##########################################################################
-- Matières 1ère D : FR(16), MATHS(6), ANGLAIS(3), HISTO-GEO(5), PCT(7), SVT(8), PHILO(37)

-- ── TRIMESTRE 1 (IDPOSITION=1) ──
-- Matière   | NoteINT | NoteDS | NoteDN | MoyClass | MoyTrimes | Coef | Rang
-- FRANÇAIS  |   14    |   12   |   13   |    11    |   13.0    |  2   |  3
-- MATHS     |   16    |   15   |   14   |    12    |   15.0    |  3   |  2
-- ANGLAIS   |   12    |   13   |   11   |    10    |   12.0    |  1   |  5
-- HISTO-GEO |   11    |   10   |   12   |    11    |   11.0    |  1   |  6
-- PCT       |   15    |   14   |   13   |    11    |   14.0    |  1   |  1
-- SVT       |   13    |   12   |   14   |    10    |   13.0    |  1   |  4
-- PHILO     |   10    |   11   |   12   |    10    |   11.0    |  2   |  7
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

-- ── TRIMESTRE 2 (IDPOSITION=2) ──
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

-- ── TRIMESTRE 3 (IDPOSITION=3) ──
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


-- ##########################################################################
-- 5. NOTES — Élève 2 : AHOLOU (CE2, IDSALLE=7)
-- ##########################################################################
-- Matières CE2 : FR(16), MATHS(6), ECriture(17), DICTEE(42), CALCUL(45)

-- ── TRIMESTRE 1 ──
INSERT INTO `note`
    (`NOTEINT`, `NOTEDS`, `NOTEDN`, `MOYCLASS`, `NOTECOMP`, `MOYENTRIMES`, `COEF`,
     `MOYENPONDERE`, `RANG`, `IDPROFESSEUR`, `OBSERVATION`, `IDELEVE`, `IDMATIERE`,
     `IDSALLE`, `IDPOSITION`, `IDANNEESCOLAIRE`, `EST_PUBLIE`)
VALUES
    ('12', '11', '13', '10', '0', 12.0, 2, 24.0, '5', 1, 'Assez bien', 2, 16, 7, 1, 1, 1),
    ('14', '15', '13', '11', '0', 14.0, 2, 28.0, '3', 1, 'Bien', 2, 6, 7, 1, 1, 1),
    ('10', '11', '12', '10', '0', 11.0, 2, 22.0, '7', 1, 'Passable', 2, 17, 7, 1, 1, 1),
    ('13', '12', '14', '10', '0', 13.0, 1, 13.0, '4', 1, 'Bien', 2, 42, 7, 1, 1, 1),
    ('15', '14', '16', '11', '0', 15.0, 1, 15.0, '2', 1, 'Très bien', 2, 45, 7, 1, 1, 1);

-- ── TRIMESTRE 2 ──
INSERT INTO `note`
    (`NOTEINT`, `NOTEDS`, `NOTEDN`, `MOYCLASS`, `NOTECOMP`, `MOYENTRIMES`, `COEF`,
     `MOYENPONDERE`, `RANG`, `IDPROFESSEUR`, `OBSERVATION`, `IDELEVE`, `IDMATIERE`,
     `IDSALLE`, `IDPOSITION`, `IDANNEESCOLAIRE`, `EST_PUBLIE`)
VALUES
    ('13', '12', '14', '10', '0', 13.0, 2, 26.0, '4', 1, 'Bien', 2, 16, 7, 2, 1, 1),
    ('15', '16', '14', '11', '0', 15.0, 2, 30.0, '2', 1, 'Très bien', 2, 6, 7, 2, 1, 1),
    ('12', '13', '11', '10', '0', 12.0, 2, 24.0, '5', 1, 'Assez bien', 2, 17, 7, 2, 1, 1),
    ('14', '13', '15', '10', '0', 14.0, 1, 14.0, '3', 1, 'Bien', 2, 42, 7, 2, 1, 1),
    ('16', '15', '17', '11', '0', 16.0, 1, 16.0, '1', 1, 'Très bien', 2, 45, 7, 2, 1, 1);

-- ── TRIMESTRE 3 ──
INSERT INTO `note`
    (`NOTEINT`, `NOTEDS`, `NOTEDN`, `MOYCLASS`, `NOTECOMP`, `MOYENTRIMES`, `COEF`,
     `MOYENPONDERE`, `RANG`, `IDPROFESSEUR`, `OBSERVATION`, `IDELEVE`, `IDMATIERE`,
     `IDSALLE`, `IDPOSITION`, `IDANNEESCOLAIRE`, `EST_PUBLIE`)
VALUES
    ('14', '13', '15', '10', '0', 14.0, 2, 28.0, '3', 1, 'Bien', 2, 16, 7, 3, 1, 1),
    ('16', '17', '15', '12', '0', 16.0, 2, 32.0, '1', 1, 'Très bien', 2, 6, 7, 3, 1, 1),
    ('13', '14', '12', '10', '0', 13.0, 2, 26.0, '4', 1, 'Bien', 2, 17, 7, 3, 1, 1),
    ('15', '14', '16', '11', '0', 15.0, 1, 15.0, '2', 1, 'Très bien', 2, 42, 7, 3, 1, 1),
    ('17', '16', '18', '12', '0', 17.0, 1, 17.0, '1', 1, 'Excellent', 2, 45, 7, 3, 1, 1);


-- ##########################################################################
-- 6. ABSENCES
-- ##########################################################################
-- Élève 1 (IDSALLE=15) :
--   T1 : 1 absence non justifiée + 2 jours absence non justifiée + 1 retard justifié
--   T2 : 1 absence non justifiée
-- Élève 2 (IDSALLE=7) :
--   T1 : 1 absence non justifiée
--   T2 : 1 retard justifié + 1 absence non justifiée

-- ── Élève 1 : KOWOUVI (IDSALLE=15) ──
-- [T1] 15 oct 2025 : 1 jour absence, non justifiée, non autorisée
-- [T1] 20 nov 2025 : 2 jours absence, non justifiée
-- [T1] 10 déc 2025 : 1 retard, justifié (Maladie, STATUT_PERMISSION=1)
-- [T2] 22 jan 2026 : 1 jour absence, non justifiée
INSERT INTO `absences`
    (`IDELEVESALLE`, `IDSALLE`, `IDANNEESCOLAIRE`, `IDPOSITION`, `IDMATIERE`, `IDPROF`,
     `NBREABSENCE`, `DATEENREG`, `IDUSERCREATE`, `TYPEABSENCE`, `DATE_DEMANDE`,
     `DATE_DEBUT`, `DATE_FIN`, `MOTIF_PERMISSION`, `STATUT_PERMISSION`)
VALUES
    (1, 15, 1, 1, NULL, NULL, 1, '2025-10-15', 103, 'Absence', '2025-10-15', '2025-10-15', NULL, NULL, NULL),
    (1, 15, 1, 1, NULL, NULL, 2, '2025-11-20', 103, 'Absence', '2025-11-20', '2025-11-20', NULL, NULL, NULL),
    (1, 15, 1, 1, NULL, NULL, 1, '2025-12-10', 103, 'Retard', '2025-12-10', '2025-12-10', NULL, 'Maladie', 1),
    (1, 15, 1, 2, NULL, NULL, 1, '2026-01-22', 103, 'Absence', '2026-01-22', '2026-01-22', NULL, NULL, NULL);

-- ── Élève 2 : AHOLOU (IDSALLE=7) ──
-- [T1] 28 oct 2025 : 1 jour absence, non justifiée
-- [T2] 05 fév 2026 : 1 retard, justifié (Rendez-vous médical)
-- [T2] 10 mar 2026 : 1 jour absence, non justifiée
INSERT INTO `absences`
    (`IDELEVESALLE`, `IDSALLE`, `IDANNEESCOLAIRE`, `IDPOSITION`, `IDMATIERE`, `IDPROF`,
     `NBREABSENCE`, `DATEENREG`, `IDUSERCREATE`, `TYPEABSENCE`, `DATE_DEMANDE`,
     `DATE_DEBUT`, `DATE_FIN`, `MOTIF_PERMISSION`, `STATUT_PERMISSION`)
VALUES
    (2, 7, 1, 1, NULL, NULL, 1, '2025-10-28', 103, 'Absence', '2025-10-28', '2025-10-28', NULL, NULL, NULL),
    (2, 7, 1, 2, NULL, NULL, 1, '2026-02-05', 103, 'Retard', '2026-02-05', '2026-02-05', NULL, 'Rendez-vous médical', 1),
    (2, 7, 1, 2, NULL, NULL, 1, '2026-03-10', 103, 'Absence', '2026-03-10', '2026-03-10', NULL, NULL, NULL);


-- ##########################################################################
-- 7. BULLETINS OFFICIELS
-- ##########################################################################

-- ── Élève 1 : KOWOUVI (3 trimestres) ──
-- T1 : moy 12.57, rang 3 — Bon trimestre
-- T2 : moy 13.43, rang 2 — Très bon progrès
-- T3 : moy 15.00, rang 1 — Excellent, 1er de la classe
INSERT INTO `bulletin`
    (`IDPOSITION`, `IDELEVE`, `IDANNEESCOLAIRE`, `IDSALLE`, `MOYENNE_GENE`, `RANG`, `MOYEN_ANN`, `RANG_ANN`, `observation`)
VALUES
    (1, 1, 1, 15, 12.57, 3, NULL, NULL, 'Bon trimestre. Continue tes efforts.'),
    (2, 1, 1, 15, 13.43, 2, NULL, NULL, 'Très bon progrès. Résultats satisfaisants.'),
    (3, 1, 1, 15, 15.00, 1, 13.67, 1, 'Excellent trimestre. Classement premier.');

-- ── Élève 2 : AHOLOU (3 trimestres) ──
-- T1 : moy 13.00, rang 4 — Bon travail
-- T2 : moy 14.00, rang 3 — Progrès notable
-- T3 : moy 15.00, rang 2 — Très bon résultat
INSERT INTO `bulletin`
    (`IDPOSITION`, `IDELEVE`, `IDANNEESCOLAIRE`, `IDSALLE`, `MOYENNE_GENE`, `RANG`, `MOYEN_ANN`, `RANG_ANN`, `observation`)
VALUES
    (1, 2, 1, 7, 13.00, 4, NULL, NULL, 'Bon travail. Peut mieux faire en écriture.'),
    (2, 2, 1, 7, 14.00, 3, NULL, NULL, 'Progrès notable. Résultats encourageants.'),
    (3, 2, 1, 7, 15.00, 2, 14.00, 3, 'Très bon résultat. Félicitations.');


-- ##########################################################################
-- 8. BULLETIN CONTENU (détails par matière)
-- ##########################################################################

-- ── Bulletin 1 : Élève 1, T1 (IDBULLETIN=1) ──
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

-- ── Bulletin 2 : Élève 1, T2 (IDBULLETIN=2) ──
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

-- ── Bulletin 3 : Élève 1, T3 (IDBULLETIN=3) ──
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

-- ── Bulletin 4 : Élève 2, T1 (IDBULLETIN=4) ──
INSERT INTO `bulletincontenu`
    (`IDBULLETIN`, `IDMATIERE`, `INTE`, `DS`, `DN`, `MOY_CLASSE`, `NOTES_COMP`,
     `MOY_TRIMES`, `COEF`, `MOY_PONDERE`, `RANG`, `PROFESSEUR`, `APPRECIATION`)
VALUES
    (4, 16, 12, 11, 13, 10, NULL, 12.0, 2, 24.0, 5, 'AGODJI Jacob', 'Assez bien'),
    (4, 6,  14, 15, 13, 11, NULL, 14.0, 2, 28.0, 3, 'AGODJI Jacob', 'Bien'),
    (4, 17, 10, 11, 12, 10, NULL, 11.0, 2, 22.0, 7, 'AGODJI Jacob', 'Passable'),
    (4, 42, 13, 12, 14, 10, NULL, 13.0, 1, 13.0, 4, 'AGODJI Jacob', 'Bien'),
    (4, 45, 15, 14, 16, 11, NULL, 15.0, 1, 15.0, 2, 'AGODJI Jacob', 'Très bien');

-- ── Bulletin 5 : Élève 2, T2 (IDBULLETIN=5) ──
INSERT INTO `bulletincontenu`
    (`IDBULLETIN`, `IDMATIERE`, `INTE`, `DS`, `DN`, `MOY_CLASSE`, `NOTES_COMP`,
     `MOY_TRIMES`, `COEF`, `MOY_PONDERE`, `RANG`, `PROFESSEUR`, `APPRECIATION`)
VALUES
    (5, 16, 13, 12, 14, 10, NULL, 13.0, 2, 26.0, 4, 'AGODJI Jacob', 'Bien'),
    (5, 6,  15, 16, 14, 11, NULL, 15.0, 2, 30.0, 2, 'AGODJI Jacob', 'Très bien'),
    (5, 17, 12, 13, 11, 10, NULL, 12.0, 2, 24.0, 5, 'AGODJI Jacob', 'Assez bien'),
    (5, 42, 14, 13, 15, 10, NULL, 14.0, 1, 14.0, 3, 'AGODJI Jacob', 'Bien'),
    (5, 45, 16, 15, 17, 11, NULL, 16.0, 1, 16.0, 1, 'AGODJI Jacob', 'Très bien');

-- ── Bulletin 6 : Élève 2, T3 (IDBULLETIN=6) ──
INSERT INTO `bulletincontenu`
    (`IDBULLETIN`, `IDMATIERE`, `INTE`, `DS`, `DN`, `MOY_CLASSE`, `NOTES_COMP`,
     `MOY_TRIMES`, `COEF`, `MOY_PONDERE`, `RANG`, `PROFESSEUR`, `APPRECIATION`)
VALUES
    (6, 16, 14, 13, 15, 10, NULL, 14.0, 2, 28.0, 3, 'AGODJI Jacob', 'Bien'),
    (6, 6,  16, 17, 15, 12, NULL, 16.0, 2, 32.0, 1, 'AGODJI Jacob', 'Très bien'),
    (6, 17, 13, 14, 12, 10, NULL, 13.0, 2, 26.0, 4, 'AGODJI Jacob', 'Bien'),
    (6, 42, 15, 14, 16, 11, NULL, 15.0, 1, 15.0, 2, 'AGODJI Jacob', 'Très bien'),
    (6, 45, 17, 16, 18, 12, NULL, 17.0, 1, 17.0, 1, 'AGODJI Jacob', 'Excellent');


-- ##########################################################################
-- 9. BULLETIN CALCULÉ (cache gelé)
-- ##########################################################################

INSERT INTO `bulletin_calcule`
    (`IDELEVE`, `IDSALLE`, `IDANNEESCOLAIRE`, `IDPOSITION`,
     `MOYENNE_GENERALE`, `RANG_CLASSE`, `TOTAL_POINTS`, `TOTAL_COEFS`, `NOMBRE_MATIERES`,
     `DATE_CALCUL`, `EST_GELE`)
VALUES
    (1, 15, 1, 1, 12.57, 3, 138.27, 11.00, 7, '2025-12-22 10:00:00', 1),
    (1, 15, 1, 2, 13.43, 2, 147.73, 11.00, 7, '2026-03-22 10:00:00', 1),
    (1, 15, 1, 3, 15.00, 1, 165.00, 11.00, 7, '2026-06-22 10:00:00', 1),
    (2, 7, 1, 1, 13.00, 4, 102.00, 8.00, 5, '2025-12-22 10:00:00', 1),
    (2, 7, 1, 2, 14.00, 3, 114.00, 8.00, 5, '2026-03-22 10:00:00', 1),
    (2, 7, 1, 3, 15.00, 2, 117.00, 8.00, 5, '2026-06-22 10:00:00', 1);


-- ##########################################################################
-- 10. PAIEMENTS — FRAIS DE SCOLARITÉ
-- ##########################################################################

-- ── Type de frais ──
-- MINERAL = scolarité, INSCRIPTION = frais d'inscription
-- (Les paiementtype existent déjà dans le dump de base)

-- ── Montant dû par classe (paiementtypeclasse) ──
-- Les montants pour les classes 16 (1ère D) et 7 (CE2) existent déjà
-- dans le dump de base (ID=8 et ID=9, montant 272000 et 204000).
-- Aucun INSERT supplémentaire nécessaire ici.

-- ── Paiements effectués (paiementfrais) ──
-- ID 1-2 existent déjà dans ecole_plus.sql :
--   ID=1 : KOWOUVI (classe 16), 70 000 FCFA le 2026-02-16
--   ID=2 : AHOLOU (classe 7), 45 000 FCFA le 2026-03-23
-- On ajoute des paiements supplémentaires (ID 3+) :
-- Élève 1 (classe 16, IDPAIEMENTTYPECLASSE=8) : +60 000 + 70 000 → total 200 000 / 272 000
-- Élève 2 (classe 7, IDPAIEMENTTYPECLASSE=19) : +50 000 + 90 000 → total 185 000 / 185 000
INSERT INTO `paiementfrais` (`IDPAIEMENTTYPECLASSE`, `IDELEVEANNEESCOLAIRE`, `IDANNEESCOLAIRE`, `MONTANT`, `DATE`, `STATUT`, `IDUSERAJOUT`, `IDUSERDELETE`, `NOMPAYEUR`, `TELPAYEUR`)
VALUES
    (8,  1, 1, 60000, '2025-10-01', 1, 103, 0, 'KOUASSI Jean-Pierre', '(228) 90123456'),
    (8,  1, 1, 70000, '2026-01-15', 1, 103, 0, 'KOUASSI Jean-Pierre', '(228) 90123456'),
    (19, 2, 1, 50000, '2025-10-05', 1, 103, 0, 'AGBENOUGLO Marie', '(228) 91234567'),
    (19, 2, 1, 90000, '2026-02-10', 1, 103, 0, 'AGBENOUGLO Marie', '(228) 91234567');


-- ##########################################################################
-- 11. CAHIER DE TEXTES (devoirs et cours)
-- ##########################################################################

-- ── Salle 15 (1ère D) — Cours et devoirs ──
-- [1] 13 oct : Maths — Devoir pour le 20 oct
-- [2] 14 oct : Français — Devoir pour le 21 oct
-- [3] 15 oct : Anglais — Devoir pour le 22 oct
-- [4] 16 oct : PCT — Devoir pour le 23 oct
-- [5] 03 nov : Maths (avancé) — Devoir pour le 10 nov
-- [6] 20 jan : Maths — PAS de devoir (cours seul)
INSERT INTO `cahier_texte`
    (`IDSALLE`, `IDMATIERE`, `IDPROF`, `IDANNEESCOLAIRE`, `DATE_COURS`, `CONTENU_COURS`,
     `DEVOIRS_A_FAIRE`, `DATE_ECHEANCE`, `FICHIER_DEVOIR`)
VALUES
    (15, 6,  1, 1, '2025-10-13', 'Équations du second degré — Résolution par la méthode du discriminant.', 'Exercices 3 et 4 page 145', '2025-10-20', NULL),
    (15, 16, 1, 1, '2025-10-14', 'Étude de texte : Le Petit Prince — Analyse des thèmes principaux.', 'Rédiger un paragraphe de 20 lignes sur le thème de l''amitié.', '2025-10-21', NULL),
    (15, 3,  1, 1, '2025-10-15', 'The Simple Past — Exercices de conjugaison.', 'Compléter les exercices du handout distribué en classe.', '2025-10-22', NULL),
    (15, 7,  1, 1, '2025-10-16', 'Les réactions acide-base — Titre de pH.', 'Fiche d''exercices n°12 : dosages et calculs de pH.', '2025-10-23', NULL),
    (15, 6,  1, 1, '2025-11-03', 'Fonctions trigonométriques — sinus, cosinus, tangente.', 'Apprendre les formules et résoudre les exercices 1 à 5.', '2025-11-10', NULL),
    (15, 6,  1, 1, '2026-01-20', 'Nombres complexes — Forme algébrique et trigonométrique.', NULL, NULL, NULL);

-- ── Salle 7 (CE2) — Cours et devoirs ──
-- [7] 13 oct : Maths — Devoir
-- [8] 14 oct : Français — Devoir
-- [9] 15 oct : Calcul — Devoir
INSERT INTO `cahier_texte`
    (`IDSALLE`, `IDMATIERE`, `IDPROF`, `IDANNEESCOLAIRE`, `DATE_COURS`, `CONTENU_COURS`,
     `DEVOIRS_A_FAIRE`, `DATE_ECHEANCE`, `FICHIER_DEVOIR`)
VALUES
    (7, 6,  1, 1, '2025-10-13', 'Les fractions — Addition et soustraction de fractions.', 'Exercices page 67, numéros 1 à 10.', '2025-10-20', NULL),
    (7, 16, 1, 1, '2025-10-14', 'Dictée — Leçon de grammaire sur les accords.', 'Apprendre la leçon et préparer une dictée pour mercredi.', '2025-10-16', NULL),
    (7, 45, 1, 1, '2025-10-15', 'Tables de multiplication — Exercices de calcul mental.', 'Apprendre les tables de 6 à 9.', '2025-10-22', NULL);


-- ##########################################################################
-- 12. EMPLOI DU TEMPS
-- ##########################################################################

-- ── Salle 15 (1ère D) — Semaine type ──
-- Lundi    : Maths 7h30-8h30, Français 8h30-9h30, Anglais 9h30-10h30, PCT 10h30-11h30
-- Mardi    : Histo-Géo 7h30-8h30, SVT 8h30-9h30, Maths 9h30-10h30, Philosophie 10h30-11h30
-- Mercredi : Français 7h30-8h30, Anglais 8h30-9h30, Maths 9h30-10h30
-- Jeudi    : PCT 7h30-8h30, SVT 8h30-9h30, Histo-Géo 9h30-10h30, Philosophie 10h30-11h30
-- Vendredi : Maths 7h30-8h30, Français 8h30-9h30, Anglais 9h30-10h30
-- Samedi   : SVT 7h30-8h30, PCT 8h30-9h30
INSERT INTO `salleemploitemps` (`IDJOUR`, `HEUREDEBUT`, `HEUREFIN`, `IDMATIERE`, `IDPROF`, `IDSALLE`, `IDANNEESCOLAIRE`)
VALUES
    -- LUNDI
    (1, '07:30', '08:30', 6,  1, 15, 1),
    (1, '08:30', '09:30', 16, 1, 15, 1),
    (1, '09:30', '10:30', 3,  1, 15, 1),
    (1, '10:30', '11:30', 7,  1, 15, 1),
    -- MARDI
    (2, '07:30', '08:30', 5,  1, 15, 1),
    (2, '08:30', '09:30', 8,  1, 15, 1),
    (2, '09:30', '10:30', 6,  1, 15, 1),
    (2, '10:30', '11:30', 37, 1, 15, 1),
    -- MERCREDI
    (3, '07:30', '08:30', 16, 1, 15, 1),
    (3, '08:30', '09:30', 3,  1, 15, 1),
    (3, '09:30', '10:30', 6,  1, 15, 1),
    -- JEUDI
    (4, '07:30', '08:30', 7,  1, 15, 1),
    (4, '08:30', '09:30', 8,  1, 15, 1),
    (4, '09:30', '10:30', 5,  1, 15, 1),
    (4, '10:30', '11:30', 37, 1, 15, 1),
    -- VENDREDI
    (5, '07:30', '08:30', 6,  1, 15, 1),
    (5, '08:30', '09:30', 16, 1, 15, 1),
    (5, '09:30', '10:30', 3,  1, 15, 1),
    -- SAMEDI
    (6, '07:30', '08:30', 8,  1, 15, 1),
    (6, '08:30', '09:30', 7,  1, 15, 1);

-- ── Salle 7 (CE2) — Semaine type ──
INSERT INTO `salleemploitemps` (`IDJOUR`, `HEUREDEBUT`, `HEUREFIN`, `IDMATIERE`, `IDPROF`, `IDSALLE`, `IDANNEESCOLAIRE`)
VALUES
    -- LUNDI
    (1, '07:30', '08:30', 16, 1, 7, 1),
    (1, '08:30', '09:30', 6,  1, 7, 1),
    (1, '09:30', '10:30', 42, 1, 7, 1),
    (1, '10:30', '11:30', 45, 1, 7, 1),
    -- MARDI
    (2, '07:30', '08:30', 6,  1, 7, 1),
    (2, '08:30', '09:30', 17, 1, 7, 1),
    (2, '09:30', '10:30', 16, 1, 7, 1),
    -- MERCREDI
    (3, '07:30', '08:30', 45, 1, 7, 1),
    (3, '08:30', '09:30', 42, 1, 7, 1),
    (3, '09:30', '10:30', 6,  1, 7, 1),
    -- JEUDI
    (4, '07:30', '08:30', 16, 1, 7, 1),
    (4, '08:30', '09:30', 6,  1, 7, 1),
    (4, '09:30', '10:30', 17, 1, 7, 1),
    -- VENDREDI
    (5, '07:30', '08:30', 42, 1, 7, 1),
    (5, '08:30', '09:30', 16, 1, 7, 1),
    -- SAMEDI
    (6, '07:30', '08:30', 6,  1, 7, 1),
    (6, '08:30', '09:30', 45, 1, 7, 1);


-- ##########################################################################
-- 13. PROFESSEURS AFFECTÉS (professeursallemat)
-- ##########################################################################
-- Prof 1 (AGODJI Jacob) → Salle 15 (1ère D) : MATHS
-- Prof 1 (AGODJI Jacob) → Salle 7 (CE2) : MATHS + FRANÇAIS
-- Prof 2 (AGBESSI Yao Christian) → Salle 15 (1ère D) : ANGLAIS (ID=24)
INSERT INTO `professeursallemat` (`ID`, `IDPROF`, `IDSALLE`, `IDMAT`, `IDANNEESCOLAIRE`, `IDTITRE`, `STATUT`)
VALUES
    (1, 1, 1, 6, 1, 4, 1),
    (2, 1, 2, 7, 1, 4, 1),
    (3, 1, 2, 6, 1, 4, 1),
    (4, 2, 15, 24, 1, 1, 1);


-- ##########################################################################
-- 14. ANNONCES ÉCOLE
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
-- 15. MESSAGERIE
-- ##########################################################################

-- ── Conversation 1 : Canal Département MATHÉMATIQUES ──
-- Département : Mathématiques
-- Participants : Parent 1, Parent 2, Staff (Demo/103)
-- 5 messages, tous lus sauf le dernier lu par Parent 2
INSERT INTO `msg_conversations` (`ID`, `TITRE`, `TYPE_CONV`, `DATE_CREATION`)
VALUES (1, 'Mathématiques', 'DEPARTEMENT', '2025-10-01 08:00:00');

INSERT INTO `msg_participants` (`ID_CONVERSATION`, `USER_TYPE`, `ID_USER`)
VALUES
    (1, 'PARENT', 1),
    (1, 'PARENT', 2),
    (1, 'STAFF', 103);

-- Msg 1 : Staff → tous (bienvenue)
-- Msg 2 : Parent 1 → staff (demande soutien)
-- Msg 3 : Staff → Parent 1 (réponse soutien)
-- Msg 4 : Parent 2 → staff (question gratuite)
-- Msg 5 : Staff → tous (réponse)
INSERT INTO `msg_messages` (`ID_CONVERSATION`, `EXPEDITEUR_TYPE`, `ID_EXPEDITEUR`, `CONTENU`, `DATE_ENVOI`)
VALUES
    (1, 'STAFF', 103, 'Bienvenue dans le groupe Mathématiques. Posez vos questions ici.', '2025-10-01 08:05:00'),
    (1, 'PARENT', 1, 'Bonjour, mon fils a des difficultés en algèbre. Y a-t-il des cours de soutien ?', '2025-10-02 14:30:00'),
    (1, 'STAFF', 103, 'Oui, un cours de soutien est disponible le mercredi de 15h à 16h30.', '2025-10-02 16:00:00'),
    (1, 'PARENT', 2, 'Merci pour l''info. Est-ce que c''est gratuit ?', '2025-10-03 09:15:00'),
    (1, 'STAFF', 103, 'Oui, le soutien est gratuit pour tous les élèves de l''établissement.', '2025-10-03 10:00:00');

-- Lecture :
-- Msg 1 : Parent1 lu, Parent2 lu
-- Msg 2 : Staff lu, Parent2 NON lu
-- Msg 3 : Parent1 lu, Parent2 lu
-- Msg 4 : Staff lu, Parent1 NON lu
-- Msg 5 : Parent1 lu, Parent2 lu
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

-- ── Conversation 2 : PRIVÉE — Parent 1 ↔ Staff (relevé de notes) ──
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

-- Msg 6: Staff lu, Parent1 NON lu (dernier message pas encore lu)
-- Msg 7: Parent1 lu, Staff NON lu
-- Msg 8: Staff lu, Parent1 NON lu
INSERT INTO `msg_statuts_lecture` (`ID_MESSAGE`, `LECTEUR_TYPE`, `ID_LECTEUR`, `DATE_LECTURE`)
VALUES
    (6, 'STAFF', 103, '2025-10-05 11:10:00'),
    (6, 'PARENT', 1, NULL),
    (7, 'PARENT', 1, '2025-10-05 14:05:00'),
    (7, 'STAFF', 103, NULL),
    (8, 'STAFF', 103, '2025-10-05 14:20:00'),
    (8, 'PARENT', 1, NULL);

-- ── Conversation 3 : PRIVÉE — Parent 2 ↔ Staff (santé enfant) ──
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
-- 16. INVITATIONS PARENT (inscription par invitation)
-- ##########################################################################
-- Invitation 1 : Valide (expirée 31 déc 2026)
-- Invitation 2 : Expirée (date passée)
-- Invitation 3 : Déjà utilisée

INSERT INTO `parent_invitations` (`eleve_id`, `email`, `telephone`, `token_hash`, `token_plain`, `expires_at`, `used_at`, `created_at`)
VALUES
    (1, 'nouveau.parent@test.com', '(228) 93456789',
     SHA2('invitation_token_valide_abc123', 256),
     'invitation_token_valide_abc123',
     '2026-12-31 23:59:59', NULL, '2025-09-15 10:00:00'),
    (2, 'parent.expire@test.com', '(228) 94567890',
     SHA2('invitation_token_expire_xyz789', 256),
     'invitation_token_expire_xyz789',
     '2025-06-01 00:00:00', NULL, '2025-03-01 10:00:00'),
    (1, 'parent.utilise@test.com', '(228) 95678901',
     SHA2('invitation_token_utilise_def456', 256),
     'invitation_token_utilise_def456',
     '2026-12-31 23:59:59', '2025-09-20 14:30:00', '2025-09-10 10:00:00');


-- ##########################################################################
-- 17. CODES OTP (historique de test)
-- ##########################################################################
-- Code 1 : Code expiré (pour test expiration)
-- Code 2 : Code utilisé (pour test réutilisation)

INSERT INTO `otp_codes` (`parent_id`, `type`, `code_hash`, `email`, `telephone`, `expires_at`, `attempts`, `used_at`, `created_at`, `ip_address`)
VALUES
    (1, 'login', SHA2('111111', 256), 'kouassi.jp@gmail.com', '(228) 90123456', '2025-10-01 00:00:00', 0, NULL, '2025-10-01 09:00:00', '192.168.1.1'),
    (1, 'login', SHA2('222222', 256), 'kouassi.jp@gmail.com', '(228) 90123456', '2026-12-31 23:59:59', 0, '2025-10-02 14:30:00', '2025-10-02 14:25:00', '192.168.1.1');


-- ##########################################################################
-- 18. RÉINITIALISATION MOT DE PASSE (password_resets_parents)
-- ##########################################################################
-- Token 1 : Valide (pour test reset)
-- Token 2 : Expiré

INSERT INTO `password_resets_parents` (`parent_id`, `token_hash`, `expires_at`, `used_at`, `requested_at`)
VALUES
    (1, SHA2('reset_token_valide_xyz789', 256), '2026-12-31 23:59:59', NULL, '2025-10-05 10:00:00'),
    (2, SHA2('reset_token_expire_abc123', 256), '2025-06-01 00:00:00', NULL, '2025-05-30 10:00:00');


-- ##########################################################################
-- 19. JOURNALISATION (traçage des actions)
-- ##########################################################################

INSERT INTO `journalisation` (`ID`, `IDUSER`, `ACTION`, `VALEUR`, `DATEACTION`)
VALUES
    (1,  102, 'Connecter',    'Connexion à l''application', '2025-09-01 08:00:00'),
    (2,  103, 'Connecter',    'Connexion à l''application', '2025-09-01 08:30:00'),
    (3,  103, 'Connecter',    'Connexion à l''application', '2025-10-01 09:54:00'),
    (4,  103, 'Connecter',    'Connexion à l''application', '2025-10-15 14:00:00'),
    (5,  103, 'Connecter',    'Connexion à l''application', '2025-11-01 10:00:00'),
    (6,  103, 'Connecter',    'Connexion à l''application', '2025-12-20 14:00:00'),
    (7,  1774264514, 'Connecter', 'Connexion à l''application', '2025-12-22 12:00:00'),
    (8,  103, 'Connecter',    'Connexion à l''application', '2026-01-10 09:00:00'),
    (9,  103, 'Connecter',    'Connexion à l''application', '2026-01-20 10:00:00'),
    (10, 103, 'Connecter',    'Connexion à l''application', '2026-02-01 08:00:00'),
    (11, 103, 'Connecter',    'Connexion à l''application', '2026-02-15 11:00:00'),
    (12, 103, 'Connecter',    'Connexion à l''application', '2026-03-01 08:00:00'),
    (13, 103, 'Connecter',    'Connexion à l''application', '2026-03-22 10:00:00'),
    (14, 103, 'Connecter',    'Connexion à l''application', '2026-04-01 08:00:00'),
    (15, 103, 'Connecter',    'Connexion à l''application', '2026-04-15 10:00:00'),
    (16, 103, 'Connecter',    'Connexion à l''application', '2026-05-01 08:00:00'),
    (17, 103, 'Connecter',    'Connexion à l''application', '2026-06-01 22:37:59'),
    (18, 103, 'Connecter',    'Connexion à l''application', '2026-06-01 22:41:21'),
    (19, 103, 'Connecter',    'Connexion à l''application', '2026-06-02 00:03:46'),
    (20, 103, 'Connecter',    'Connexion à l''application', '2026-06-02 06:14:39'),
    (21, 103, 'Connecter',    'Connexion à l''application', '2026-06-02 09:47:02'),
    (22, 103, 'Connecter',    'Connexion à l''application', '2026-06-04 19:53:24'),
    (23, 103, 'Connecter',    'Connexion à l''application', '2026-06-25 12:06:42'),
    (24, 103, 'Connecter',    'Connexion à l''application', '2026-06-25 13:30:35'),
    (25, 103, 'Connecter',    'Connexion à l''application', '2026-06-26 01:06:05'),
    (26, 103, 'Connecter',    'Connexion à l''application', '2026-06-26 01:10:25'),
    (27, 103, 'Connecter',    'Connexion à l''application', '2026-07-23 01:06:09'),
    (28, 103, 'Connecter',    'Connexion à l''application', '2026-07-23 21:56:42'),
    (29, 103, 'Connecter',    'Connexion à l''application', '2026-08-17 21:41:05'),
    (30, 103, 'Connecter',    'Connexion à l''application', '2026-08-18 02:00:06'),
    (31, 103, 'Connecter',    'Connexion à l''application', '2026-08-18 06:28:16'),
    (32, 103, 'Connecter',    'Connexion à l''application', '2026-08-18 19:55:55'),
    (33, 103, 'Connecter',    'Connexion à l''application', '2026-09-04 23:29:35'),
    (34, 103, 'Connecter',    'Connexion à l''application', '2026-09-04 23:33:20'),
    (35, 103, 'Connecter',    'Connexion à l''application', '2026-09-05 14:04:00'),
    (36, 103, 'Connecter',    'Connexion à l''application', '2026-09-07 15:48:35');


-- ##########################################################################
-- 20. MISE À JOUR DES COMPTEURS AUTO_INCREMENT
-- ##########################################################################

ALTER TABLE `parents`             AUTO_INCREMENT = 4;
ALTER TABLE `parent_eleve`        AUTO_INCREMENT = 6;
ALTER TABLE `note`                AUTO_INCREMENT = 36;
ALTER TABLE `bulletin`            AUTO_INCREMENT = 7;
ALTER TABLE `bulletincontenu`     AUTO_INCREMENT = 37;
ALTER TABLE `bulletin_calcule`    AUTO_INCREMENT = 7;
ALTER TABLE `absences`            AUTO_INCREMENT = 23;
ALTER TABLE `annonces_ecole`      AUTO_INCREMENT = 6;
ALTER TABLE `cahier_texte`        AUTO_INCREMENT = 10;
ALTER TABLE `salleemploitemps`    AUTO_INCREMENT = 38;
ALTER TABLE `msg_conversations`   AUTO_INCREMENT = 4;
ALTER TABLE `msg_messages`        AUTO_INCREMENT = 11;
ALTER TABLE `msg_statuts_lecture` AUTO_INCREMENT = 13;
ALTER TABLE `paiementtypeclasse`  AUTO_INCREMENT = 25;
ALTER TABLE `paiementfrais`       AUTO_INCREMENT = 5;


SET FOREIGN_KEY_CHECKS = 1;


-- ##########################################################################
-- RÉCAPITULATIF DES COMPTES TEST
-- ==========================================================================
-- LOGIN          | MOT DE PASSE | STATUT  | ENFANTS
-- ───────────────┼──────────────┼─────────┼──────────────────────────────────
-- kouassi        | Test@1234    | Actif   | KOWOUVI (1ère D) + AHOLOU (CE2)
-- agbenouglo     | Test@1234    | Actif   | AHOLOU (CE2)
-- toko           | Test@1234    | Inactif | KOWOUVI (1ère D) + AHOLOU (CE2)
-- ==========================================================================
-- TRACES : 36 connexions journalisées, 10 messages, 3 conversations,
--          7 notes/élèves/trimestre, 6 bulletins, 7 absences,
--          4 paiements, 9 cours/devoirs, 20 créneaux emploi du temps,
--          2 OTP codes, 2 tokens reset, 3 invitations
-- ##########################################################################
