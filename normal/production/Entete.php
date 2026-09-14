<?php
	$NbreEntreeSortieSansJustificatif=getNbreEntreeSortieSansJustificatif($_SESSION['idanneescolaire'],$pdo);
	$NbrePretSansJustificatif=getNbrePretSansJustificatif($_SESSION['idanneescolaire'],$pdo);
	$NbreJourneeNonCloture=getNbreJourneeNonCloture($_SESSION['idanneescolaire'],$pdo);
	$EtatCaisse=getEtatCaisse_($_SESSION['idanneescolaire'],2,$pdo);
	$NbreInscritNonPaye=getNbreInscritNonPaye($_SESSION['idanneescolaire'],1,$pdo);
	
	$a=0;
	$b=0;
	$c=0;
	$d=0;
	$e=0;
	
	if($NbreEntreeSortieSansJustificatif!=0)
	{
		$a=1;
	}
	
	if($NbrePretSansJustificatif!=0)
	{
		$b=1;
	}
	
	if($NbreJourneeNonCloture!=0)
	{
		$c=1;
	}
	
	if($EtatCaisse!=0)
	{
		$d=1;
	}
	
	if($NbreInscritNonPaye!=0)
	{
		$e=1;
	}
	
	$nbrenotification = $a+$b+$c+$d+$e;
?>
<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
		<div class="top_nav">
			<div class="nav_menu">
				<nav role="navigation">
					<div class="nav toggle">
						<a id="menu_toggle"><i class="fa fa-bars"></i></a>
					</div>
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<i class="fa fa-user-circle" style="margin-right:6px;"></i>
								<?php echo $_SESSION['nomuser'].' '.$_SESSION['prenom'];?>
								<span class="fa fa-angle-down"></span>
							</a>
							<ul class="dropdown-menu dropdown-usermenu">
								<li><a href="Utilisateur.php?UtilisateurConnecte=<?php echo $_SESSION['iduser'];?>&ChangerMotPasse=ChangerMotPasse"><i class="fa fa-key" style="margin-right:8px;"></i>Changement de mot de passe</a></li>
								<li><a href="javascript:;"><i class="fa fa-question-circle" style="margin-right:8px;"></i>Acc&egrave;s &agrave; l'aide</a></li>
								<li class="divider"></li>
								<li><a href="logout.php"><i class="fa fa-sign-out" style="margin-right:8px;"></i>D&eacute;connexion</a></li>
							</ul>
						</li>
						
						<?php
						if($nbrenotification>0)
						{
							?>
								<li role="presentation" class="dropdown">
									<a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
										<i class="fa fa-bell-o"></i>
										<span class="badge bg-orange"><?php echo $nbrenotification;?></span>
									</a>
									<ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">								
										<?php
										if($NbreEntreeSortieSansJustificatif!=0)
										{
											?>
											<li>
												<a>
													<span>
														<span style="color:#f59e0b;font-weight:600;">Situation &agrave; r&eacute;gulariser :</span>
													</span>
													<span class="message">
														<?php echo $NbreEntreeSortieSansJustificatif;?> (entr&eacute;e(s)/sortie(s)) sans justif.
													</span>
												</a>
											</li><?php
										}
										
										if($NbrePretSansJustificatif!=0)
										{
											?>
											<li>
												<a>
													<span>
														<span style="color:#f59e0b;font-weight:600;">Situation &agrave; r&eacute;gulariser :</span>
													</span>
													<span class="message">
														<?php echo $NbrePretSansJustificatif;?> pr&ecirc;t(s) sans justif.
													</span>
												</a>
											</li><?php
										}
										
										if($NbreJourneeNonCloture!=0)
										{
											?>
											<li>
												<a>
													<span class="badge bg-orange">
														<?php echo $NbreJourneeNonCloture;?> journ&eacute;e(s) non cl&ocirc;tur&eacute;e(s)
													</span>
												</a>
											</li><?php
										}
										
										if($EtatCaisse>500000)
										{
											?>
											<li>
												<a>
													<span>
														<span style="color:#ef4444;font-weight:600;">Caisse Alerte :</span>
													</span>
													<span class="message">
														Versement bancaire requis
													</span>
												</a>
											</li><?php
										}
										
										if($NbreInscritNonPaye>0)
										{
											?>
											<li>
												<a>
													<span class="badge bg-blue" style="font-size:10px;">
														Aucun paiement pour <?php echo $NbreInscritNonPaye;?> &eacute;l&egrave;ves
													</span>
												</a>
											</li><?php
										}
										?>
									</ul>
								</li>
							<?php
						}
						?>
						<li role="presentation" class="dropdown">
			                <a href="#"><span class="badge bg-green"><i class="fa fa-calendar" style="margin-right:4px;"></i>Ann&eacute;e scolaire <?php echo $_SESSION['libelleanneescolaire'];?></span></a>
			            </li>
			            <li role="presentation" class="dropdown">
			                <a href="#"><span class="badge bg-blue"><i class="fa fa-graduation-cap" style="margin-right:4px;"></i>ECOLEPLUS</span></a>
			            </li>
						<?php 
						if($EtatCaisse!=0)
						{
							?>
							<li role="presentation" class="dropdown">
								<a href="#"><span class="badge bg-red">
									<i class="fa fa-exclamation-triangle" style="margin-right:4px;"></i>
									Caisse : <?php echo number_format($EtatCaisse,0,""," ");?> FCFA
								</a>
							</li>
							<?php
						}
						?>
					</ul>
				</nav>
			</div>
		</div>
						
						<?php
						if($nbrenotification>0)
						{
							?>
								<li role="presentation" class="dropdown">
									<a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
										<i class="fa fa-envelope-o"></i>
										<span class="badge bg-orange"><?php echo $nbrenotification;?></span>
									</a>
									<ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">								
										<?php
										if($NbreEntreeSortieSansJustificatif!=0)
										{
											?>
											<li>
												<a>
													<span>
														<span style="color:orange;font-weight:bold;">Situation &agrave; r&eacute;gulariser :</span>
													</span>
													<span class="message">
														<?php echo $NbreEntreeSortieSansJustificatif;?> (entr&eacute;e(s)/sortie(s)) enregistr&eacute;e(s) sans justif.
													</span>
												</a>
											</li><?php
										}
										
										if($NbrePretSansJustificatif!=0)
										{
											?>
											<li>
												<a>
													<span>
														<span style="color:orange;font-weight:bold;">Situation &agrave; r&eacute;gulariser :</span>
													</span>
													<span class="message">
														<?php echo $NbrePretSansJustificatif;?> prêt(s) enregistr&eacute;(s) sans justif.
													</span>
												</a>
											</li><?php
										}
										
										if($NbreJourneeNonCloture!=0)
										{
											?>
											<li>
												<a>
													<span class="badge bg-orange">
														<?php echo $NbreJourneeNonCloture;?> journ&eacute;e(s) de paiement non clôtur&eacute;e(s)
													</span>
												</a>
											</li><?php
										}
										
										if($EtatCaisse>500000)
										{
											?>
											<li>
												<a>
													<span>
														<span style="color:red;font-weight:bold;">Caisse Alerte !!!:</span>
													</span>
													<span class="message">
														Vous devez aller faire le versement de la caisse
													</span>
												</a>
											</li><?php
										}
										
										if($NbreInscritNonPaye>0)
										{
											?>
											<li>
												<a>
													<span class="badge bg-blue" style="font-size:10px;">
														Aucun paiement n’a été enregistré pour <?php echo $NbreInscritNonPaye;?> &eacute;l&egrave;ves
													</span>
												</a>
											</li><?php
										}
										?>
									</ul>
								</li>
							<?php
						}
						?>
						<li role="presentation" class="dropdown">
			                <a href="#"><span class="badge bg-green" style="padding:10px">Ann&eacute;e scolaire <?php echo $_SESSION['libelleanneescolaire'];?></span></a>
			            </li>
			            <li role="presentation" class="dropdown">
			                <a href="#"><span class="badge bg-blue" style="padding:10px">Bienvenue à ECOLEPLUS</span></a>
			            </li>
						<?php 
						if($EtatCaisse!=0)
						{
							?>
							<li role="presentation" class="dropdown">
								<a href="#"><span class="badge bg-red">
									<span style="font-size:16px;">
										Montant de la caisse : <?php echo number_format($EtatCaisse,0,""," ");?> FCFA. Merci donc d'aller faire le versement &agrave; la banque.
									</span>
								</a>
							</li>
							<?php
						}
						?>
					</ul>
				</nav>
			</div>
		</div>
	</body>
</html>