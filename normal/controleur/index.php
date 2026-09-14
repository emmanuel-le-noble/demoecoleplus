<?php 
    include("modele/connexion.php");
    include("modele/droit.php");

if(isset($_REQUEST["connexion"]))
{
	$identifiant = mysql_real_escape_string(trim($_REQUEST["identifiant"]));
	$motpasse = mysql_real_escape_string(trim($_REQUEST["motpasse"]));
	$verif_user = verif_utilisateur($identifiant,$motpasse,$pdo);
	if($verif_user==0)
	{
		$mess="Vous n'&ecirc;tes pas un utilisateur pour cette application! Veuillez contacter l'administrateur du syst&egrave;me.";
	}
	else
	{
		$date = date('Y-m-d H:i:s');
		$d = date('Y-m-d');
		$iduser = getidutilisateur($identifiant,$motpasse, $pdo);
		$nomuser = getnom($iduser,$pdo);
		$prenomuser = getprenom($iduser,$pdo);
		$idprofil = getidprofil($iduser,$pdo);
		$photo = getphoto($iduser,$pdo);

		if($nomuser!="" AND $prenomuser!="" AND $iduser!="" AND $idprofil!="") 
		{
			if(session_start())
			{
				$_SESSION['datecontrole'] = $d;
				$_SESSION['date'] = $date; 
				$_SESSION['iduser']  = $iduser; 
				$_SESSION['nomuser'] = $nomuser; 
				$_SESSION['prenom']  = $prenomuser;				
				$_SESSION['idprofil']   =  $idprofil; 
				$_SESSION['photo']   =  $photo;
				
				header("location:production/index.php");			
			}
		}												
	}
}
else
{
	$mess="";	
    include("production/connexion.php");
}
?>
