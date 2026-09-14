<?php 
    include("../modele/connexion.php");
    include("../modele/droit.php");
	include("../modele/function.php");
	
	$error="";
	$success="";
	if(isset($_REQUEST["connexion"]))
	{
		$identifiant = (trim($_REQUEST["identifiant"]));
		$motpasse = (trim($_REQUEST["motpasse"]));
		$idanneescolaire = (trim($_REQUEST["idanneescolaire"]));
		$profil = verif_utilisateur($identifiant,$motpasse,$pdo);
		if($profil=="")
		{
			$error="Paramètres de connexion invalides !";
		}
		else
		{
			if($profil=="Professeur")
			{
				if(getverif_Horaire($pdo)==0)
				{
					$error="Les horaires de connexion invalides !";
				}
				else
				{
					session_name("ecoleplus");
					if(session_start())
					{	
						$_SESSION['datecontrole'] = date('Y-m-d H:i:s');
						$_SESSION['date'] = date('Y-m-d');
						$_SESSION['iduser'] = getidutilisateur($identifiant,$motpasse,$pdo);
						$_SESSION['nomuser'] = getnom($_SESSION['iduser'],$pdo);
						$_SESSION['prenom'] = getprenom($_SESSION['iduser'],$pdo);
						$_SESSION['idanneescolaire'] = $idanneescolaire;
						$_SESSION['libelleanneescolaire'] = getLibelleAnneeScolaire($idanneescolaire,$pdo);
						$_SESSION['titreprof'] = gettitreUser($_SESSION['iduser'],$pdo);
						$_SESSION['idprofil'] = getidprofil($_SESSION['iduser'],$pdo);
						$_SESSION['photo'] = "";
						CreateJournalisation($_SESSION['iduser'],"Connecter","Connexion à l'application",$pdo);
						
						header("location:NotesProfesseur.php");
					}
				}
			}
			else
			{
				session_name("ecoleplus");
				if(session_start())
				{	
					$_SESSION['datecontrole'] = date('Y-m-d H:i:s');
					$_SESSION['date'] = date('Y-m-d');
					$_SESSION['iduser'] = getidutilisateur($identifiant,$motpasse,$pdo);
					$_SESSION['nomuser'] = getnom($_SESSION['iduser'],$pdo);
					$_SESSION['prenom'] = getprenom($_SESSION['iduser'],$pdo);
					$_SESSION['idanneescolaire'] = $idanneescolaire;
					$_SESSION['libelleanneescolaire'] = getLibelleAnneeScolaire($idanneescolaire,$pdo);
					$_SESSION['titreprof'] = gettitreUser($_SESSION['iduser'],$pdo);
					$_SESSION['idprofil'] = getidprofil($_SESSION['iduser'],$pdo);
					$_SESSION['photo'] = "";
					CreateJournalisation($_SESSION['iduser'],"Connecter","Connexion à l'application",$pdo);
					
					header("location:Ecoleplus.php");
				}
			}
		}
	}	
	include("Connexion.php");
?>