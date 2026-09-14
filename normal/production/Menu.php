<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
	<div class="col-md-3 left_col">
		<div class="left_col scroll-view">
			<div class="navbar nav_title" style="border: 0;">
			    <a href="Ecoleplus.php" class="site_title">
			    	<span>&nbsp;&nbsp;Ecole<sup>+</sup></span></a>
			</div>
			<div class="clearfix"></div>
			<div class="profile">
				<div class="profile_pic">
					<img src="photo/user.png" alt="..." class="img-circle profile_img">
				</div>
				<div class="profile_info">
					<span>Bienvenue,</span>
					<h2><?php echo $_SESSION['prenom']." ".$_SESSION['nomuser'];?></h2>
				</div>
			</div>
			<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
				<div class="menu_section">
				<h3>::: &nbsp;Menu principal&nbsp; :::</h3>
					<ul class="nav side-menu">
					    <?php
							if($_SESSION['idprofil']=="Administrateur")
							{
								?>
								<li><a><i class="fa fa-cogs"></i><b>Configuration syst&egrave;me</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Utilisateur.php">Utilisateur</a></li>
										<li><a href="HoraireSaisieNote.php">Horaire de saisie des notes</a></li>
										<li><a href="#">Journal des &eacute;venements</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-home"></i><b>Informations de base</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a>Pr&eacute;paration Ann&eacute;e Scol.<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="AnneeScolaire.php">Ann&eacute;e Scolaire</a></li>
												<li class="sub_menu"><a href="TransfertEleve.php">Transfert &eacute;l&egrave;ve</a></li>
											</ul>
										</li>
										<li><a href="Domaine.php">Domaine de l'&eacute;tablissement</a></li>
										<li><a href="Niveau.php">Niveau/Option</a></li>
										<li><a href="Classe.php">Classe/Fili&egrave;re</a></li>
										<li><a href="Matiere.php">Mati&egrave;re(s) enseign&eacute;e(s)</a></li>
										<li><a href="MatiereCoefficient.php">Coefficients des mati&egrave;res</a></li>
										<li><a>Corps professeur<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="Professeur.php">Cr&eacute;ation professeur</a></li>
												<li><a href="ProfesseurMatiere.php">Professeur - Mati&egrave;re</a></li>
												<li class="sub_menu"><a href="ProfesseurDonneesPaie.php">Volume Horaire</a></li>
											</ul>
										</li>
										<li><a>Personnel permanent<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="Personnel.php">Personnel</a></li>
												<li class="sub_menu"><a href="PersonnelDonneesPaie.php">Donn&eacute;es de paie</a></li>
											</ul>
										</li>
										<li><a href="Scolarite.php">Param&eacute;trage frais scolarit&eacute;</a></li>
										<li><a href="CreationArticle.php">Cr&eacute;ation d'article(s)</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-edit"></i><b>Scolarit&eacute;</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Import.php">Importation de liste</a></li>
										<li><a href="Inscription.php">Inscription</a></li>
										<li><a href="AffectationSalle.php">Affectation de salle</a></li>
										<li><a href="DossierEleve.php">Dossier des &eacute;l&egrave;ves</a></li>
										<li><a href="EffectifClasse.php">Effectif par classe</a></li>
										<li><a href="EmploiTemps.php">Emploi du temps</a></li>
										<li><a href="CahierTexteAdmin.php"><i class="fa fa-book"></i> Cahier de texte</a></li>
										<li><a href="Parents.php"><i class="fa fa-users"></i> Gestion Parents</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-cc-visa"></i><b>Finance</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a>Facturation scolaire<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="PaiementFrais.php">Paiement des frais</a></li>
												<li><a href="VenteArticle.php">Vente(s) d'article(s)</a></li>
												<li><a href="RecetteJournaliere.php">Recette journali&egrave;re</a></li>
												<li><a href="ClotureJournee.php">Clôture de journ&eacute;e</a></li>
												<li><a href="SituationPaiement.php">Fiche de contrôle</a></li>
											</ul>
										</li>
										<li><a>Paiement salaire<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="#">Paramètre(s) de paie</a></li>
												<li class="sub_menu"><a href="CalculPaie.php">Calcul paie</a></li>
											</ul>
										</li>
										<li><a>Prêts au personnel<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="PretOctroi.php">Octroi</a></li>
												<li><a href="PretRemboursement.php">Remboursement</a></li>
											</ul>
										</li>
										<li><a href="Tresorerie.php">Tr&eacute;sorerie & d&eacute;pense(s)</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-edit"></i><b>Stock d'article(s)</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="EntreeArticle.php">Entr&eacute;e(s) d'article(s)</a></li>
										<li><a href="SortieArticle.php">Sortie(s) d'article(s)</a></li>
										<li><a href="NiveauStock.php">Niveau de stock</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-bolt"></i><b>Evaluation</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a>1er & 2ème Cycle<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="Examen.php">Examen(s)</a></li>
												<li><a href="Numero.php">N° table(s)/anonymat(s)</a></li>
												<li><a href="Absences.php">Absence(s) aux cours</a></li>
												<li><a href="Permission.php">Permission enseignants</a></li>
												<li><a href="Notes.php">Notes</a></li>
												<li><a href="Bulletins.php">Bulletins</a></li>
											</ul>
										</li>
										<li><a>Cycle sup&eacute;rieur<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="NotesCycleSup.php">Notes</a></li>
												<li><a href="BulletinsCycleSup.php">Bulletins</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><a><i class="fa fa-bolt"></i><b>Conseil des prof.</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="DecisionConseil.php">D&eacute;cisions prises</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-phone"></i><b>Notification</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Messagerie.php"><i class="fa fa-comments"></i> Messagerie</a></li>
										<li><a href="DemandesPermission.php"><i class="fa fa-envelope-open"></i> Demandes permission</a></li>
										<li><a href="#">Envoi d'e-mail</a></li>
										<li><a href="#">Envoi sms</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-bar-chart"></i><b>Stat. Pédagogique</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="DeliberationEvaluation.php">D&eacute;lib&eacute;rat. Trim./Sem</a></li>
										<li><a href="MatiereEvaluation.php">D&eacute;lib&eacute;rat. par discipl</a></li>
										<li><a href="EvaluationPerformance.php">Performance par discipl/niveau</a></li>
										<li><a href="EvaluationRapport.php">Synth&egrave;se de la d&eacute;lib&eacute;ration</a></li>
										<li><a href="RapportActivite.php">Rapport d'activit&eacute; trim./sem</a></li>
										<li><a href="NoteComposition.php">Notes de compositions</a></li>
										<li><a href="AnalyseResultat.php">Analyse de r&eacute;sultat</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-bar-chart"></i><b>Rapport Financier</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="StatistiqueEntree.php">Entr&eacute;e(s) uniquement</a></li>
										<li><a href="StatistiqueSortie.php">Sortie(s) uniquement</a></li>
										<li><a href="StatistiqueEntreeSortie.php">Entr&eacute;e(s)/Sortie(s)</a></li>
									</ul>
								</li>
								<?php
							}
							elseif($_SESSION['idprofil']=="Proviseur")
							{
								?>
								<li><a><i class="fa fa-cogs"></i><b>Configuration syst&egrave;me</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Utilisateur.php">Utilisateur</a></li>
										<li><a href="HoraireSaisieNote.php">Horaire de saisie des notes</a></li>
										<li><a href="#">Journal des &eacute;venements</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-home"></i><b>Informations de base</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a>Pr&eacute;paration Ann&eacute;e Scol.<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="AnneeScolaire.php">Ann&eacute;e Scolaire</a></li>
												<li class="sub_menu"><a href="TransfertEleve.php">Transfert &eacute;l&egrave;ve</a></li>
											</ul>
										</li>
										<li><a href="Domaine.php">Domaine de l'&eacute;tablissement</a></li>
										<li><a href="Niveau.php">Niveau/Option</a></li>
										<li><a href="Classe.php">Classe/Fili&egrave;re</a></li>
										<li><a href="Matiere.php">Mati&egrave;re(s) enseign&eacute;e(s)</a></li>
										<li><a href="MatiereCoefficient.php">Coefficients des mati&egrave;res</a></li>
										<li><a>Corps professeur<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="Professeur.php">Cr&eacute;ation professeur</a></li>
												<li><a href="ProfesseurMatiere.php">Professeur - Mati&egrave;re</a></li>
												<li class="sub_menu"><a href="ProfesseurDonneesPaie.php">Volume Horaire</a></li>
											</ul>
										</li>
										<li><a>Personnel permanent<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="Personnel.php">Personnel</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><a><i class="fa fa-edit"></i><b>Scolarit&eacute;</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Import.php">Importation de liste</a></li>
										<li><a href="Inscription.php">Inscription</a></li>
										<li><a href="AffectationSalle.php">Affectation de salle</a></li>
										<li><a href="DossierEleve.php">Dossier des &eacute;l&egrave;ves</a></li>
										<li><a href="EffectifClasse.php">Effectif par classe</a></li>
										<li><a href="EmploiTemps.php">Emploi du temps</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-bolt"></i><b>Evaluation</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Examen.php">Trimestre(s) & Examen(s)</a></li>
										<li><a href="Numero.php">N° table(s) et anonymat(s)</a></li>
										<li><a href="Absences.php">Absence(s) aux cours</a></li>
										<li><a href="Permission.php">Permission des enseignants</a></li>
										<li><a href="Notes.php">Notes</a></li>
										<li><a href="Bulletins.php">Bulletins</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-bolt"></i><b>Conseil des prof.</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="DecisionConseil.php">D&eacute;cisions prises</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-phone"></i><b>Notification</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Messagerie.php"><i class="fa fa-comments"></i> Messagerie</a></li>
										<li><a href="DemandesPermission.php"><i class="fa fa-envelope-open"></i> Demandes permission</a></li>
										<li><a href="#">Envoi d'e-mail</a></li>
										<li><a href="#">Envoi sms</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-bar-chart"></i><b>Tableau de bord</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a>Evaluation<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="DeliberationEvaluation.php">D&eacute;lib&eacute;rat. Trim./Sem</a></li>
												<li><a href="MatiereEvaluation.php">D&eacute;lib&eacute;rat. par discipl</a></li>
												<li><a href="EvaluationPerformance.php">Performance par discipl/niveau</a></li>
												<li><a href="EvaluationRapport.php">Synth&egrave;se de la d&eacute;lib&eacute;ration</a></li>
												<li><a href="RapportActivite.php">Rapport d'activit&eacute; trim./sem</a></li>
												<li><a href="NoteComposition.php">Notes de compositions</a></li>
												<li><a href="AnalyseResultat.php">Analyse de r&eacute;sultat</a></li>
											</ul>
										</li>
									</ul>
								</li><?php
							}
							elseif($_SESSION['idprofil']=="Directeur")
							{
								?>
								<li><a><i class="fa fa-home"></i><b>Informations de base</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Domaine.php">Domaine de l'&eacute;tablissement</a></li>
										<li><a href="Niveau.php">Niveau/Option</a></li>
										<li><a href="Classe.php">Classe/Fili&egrave;re</a></li>
										<li><a href="Matiere.php">Mati&egrave;re(s) enseign&eacute;e(s)</a></li>
										<li><a href="MatiereCoefficient.php">Coefficients des mati&egrave;res</a></li>
										<li><a>Corps professeur<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="Professeur.php">Cr&eacute;ation professeur</a></li>
												<li><a href="ProfesseurMatiere.php">Professeur - Mati&egrave;re</a></li>
												<li class="sub_menu"><a href="ProfesseurDonneesPaie.php">Volume Horaire</a></li>
											</ul>
										</li>
										<li><a>Personnel permanent<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="Personnel.php">Personnel</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><a><i class="fa fa-edit"></i><b>Scolarit&eacute;</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Import.php">Importation de liste</a></li>
										<li><a href="Inscription.php">Inscription</a></li>
										<li><a href="AffectationSalle.php">Affectation de salle</a></li>
										<li><a href="DossierEleve.php">Dossier des &eacute;l&egrave;ves</a></li>
										<li><a href="EffectifClasse.php">Effectif par classe</a></li>
										<li><a href="EmploiTemps.php">Emploi du temps</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-bolt"></i><b>Evaluation</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Examen.php">Trimestre(s) & Examen(s)</a></li>
										<li><a href="Numero.php">N° table(s) et anonymat(s)</a></li>
										<li><a href="Absences.php">Absence(s) aux cours</a></li>
										<li><a href="Permission.php">Permission des enseignants</a></li>
										<li><a href="Notes.php">Notes</a></li>
										<li><a href="Bulletins.php">Bulletins</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-bolt"></i><b>Conseil des prof.</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="DecisionConseil.php">D&eacute;cisions prises</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-phone"></i><b>Notification</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Messagerie.php"><i class="fa fa-comments"></i> Messagerie</a></li>
										<li><a href="DemandesPermission.php"><i class="fa fa-envelope-open"></i> Demandes permission</a></li>
										<li><a href="#">Envoi d'e-mail</a></li>
										<li><a href="#">Envoi sms</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-bar-chart"></i><b>Tableau de bord</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a>Evaluation<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="DeliberationEvaluation.php">D&eacute;lib&eacute;rat. Trim./Sem</a></li>
												<li><a href="MatiereEvaluation.php">D&eacute;lib&eacute;rat. par discipl</a></li>
												<li><a href="EvaluationPerformance.php">Performance par discipl/niveau</a></li>
												<li><a href="EvaluationRapport.php">Synth&egrave;se de la d&eacute;lib&eacute;ration</a></li>
												<li><a href="RapportActivite.php">Rapport d'activit&eacute; trim./sem</a></li>
												<li><a href="NoteComposition.php">Notes de compositions</a></li>
												<li><a href="AnalyseResultat.php">Analyse de r&eacute;sultat</a></li>
											</ul>
										</li>
									</ul>
								</li><?php
							}
							elseif($_SESSION['idprofil']=="Secretaire")
							{
								?>
								<li><a><i class="fa fa-edit"></i><b>Scolarit&eacute;</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Import.php">Importation de liste</a></li>
										<li><a href="Inscription.php">Inscription</a></li>
										<li><a href="AffectationSalle.php">Affectation de salle</a></li>
										<li><a href="DossierEleve.php">Dossier des &eacute;l&egrave;ves</a></li>
										<li><a href="EffectifClasse.php">Effectif par classe</a></li>
										<li><a href="EmploiTemps.php">Emploi du temps</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-cc-visa"></i><b>Finance</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a>Facturation scolaire<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li class="sub_menu"><a href="PaiementFrais.php">Paiement des frais</a></li>
												<li><a href="VenteArticle.php">Vente(s) d'article(s)</a></li>
												<li><a href="RecetteJournaliere.php">Recette journali&egrave;re</a></li>
												<li><a href="ClotureJournee.php">Clôture de journ&eacute;e</a></li>
												<li><a href="SituationPaiement.php">Fiche de contrôle</a></li>
											</ul>
										</li>
										<li><a href="Tresorerie.php">Tr&eacute;sorerie & d&eacute;pense(s)</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-edit"></i><b>Stock d'article(s)</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="EntreeArticle.php">Entr&eacute;e(s) d'article(s)</a></li>
										<li><a href="SortieArticle.php">Sortie(s) d'article(s)</a></li>
										<li><a href="NiveauStock.php">Niveau de stock</a></li>
									</ul>
								</li>
								<li><a><i class="fa fa-bar-chart"></i><b>Tableau de bord</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a>Finance<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="StatistiqueEntree.php">Entr&eacute;e(s) uniquement</a></li>
												<li><a href="StatistiqueSortie.php">Sortie(s) uniquement</a></li>
												<li><a href="StatistiqueEntreeSortie.php">Entr&eacute;e(s)/Sortie(s)</a></li>
											</ul>
										</li>
									</ul>
								</li><?php
							}
							elseif($_SESSION['idprofil']=="Professeur")
							{
								?>
								<li><a><i class="fa fa-bolt"></i><b>Evaluation</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Notes.php">Notes</a></li>
									</ul>
								</li><?php
							}
							elseif($_SESSION['idprofil']=="Surveillant")
							{
								?>
								<li><a><i class="fa fa-bolt"></i><b>Evaluation</b><span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="Absences.php">Absence(s) aux cours</a></li>
									</ul>
								</li><?php
							}
						?>						
						<li><a href="javascript:void(0)"><i class="fa fa-laptop"></i><b>ECOLEPLUS</b> <span class="label label-success pull-right">J'aime</span></a></li>
					</ul>
					<center><img src="images/eleve.gif"/></center>
				</div>
			</div>
			<div class="sidebar-footer hidden-small">
				<a data-toggle="tooltip" data-placement="top" title="Settings">
					<i class="fa fa-cog"></i>
				</a>
				<a data-toggle="tooltip" data-placement="top" title="FullScreen">
					<i class="fa fa-arrows-alt"></i>
				</a>
				<a data-toggle="tooltip" data-placement="top" title="Lock">
					<i class="fa fa-lock"></i>
				</a>
				<a data-toggle="tooltip" data-placement="top" title="Logout">
					<i class="fa fa-sign-out"></i>
				</a>
			</div>
		</div>
	</div>
	</body>
</html>