<?php
    session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/function.php");
	
	$idclasse = $_REQUEST['val_sel'];
	$id_exercice = $_SESSION['id_exercice'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title></title>
		<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="../build/css/custom.min.css" rel="stylesheet">
				<link href="modern.css" rel="stylesheet"/>
    </head>
    <body class="nav-md">
	<div style="margin:10px;">
		<span style="color:red;"><i>::: Liste des Cartes d'Etudiants :::</i></span>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<button type="button" name="imprimer_carte" class="btn btn-danger btn-xs"><i class="fa fa-folder"></i>&nbsp;Imprimer Toutes les Cartes</a></button>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<button type="button" class="btn btn-info btn-xs" onclick="document.location='Etudiant.php'"><i class="fa fa-folder"></i>&nbsp;Fermer</a></button>
	</div><hr/>
	<div class="row">
	<?php
		$req=(' SELECT  
						eleve.id_eleve,
						eleve.num_eleve,
						eleve.photo_eleve,
						eleve.nom_eleve,
						eleve.prenom_eleve,
						eleve.datenaissance_eleve,
						eleve.lieunaissance_eleve,
						eleve.tel_eleve,
						eleve.commentaire_eleve,
						eleve.sexe_eleve,
						eleve.adresse_eleve,
						exercice_eleve.droit_inscription,
						exercice_eleve.date_inscrit,
						classe.codeclasse,
						exercice_eleve.id						
								
				FROM eleve,exercice_eleve,classe
				WHERE
				eleve.id_eleve=exercice_eleve.id_eleve
				AND
				exercice_eleve.id_exercice=:id_exercice
				AND
				classe.idclasse=exercice_eleve.idclasse
				AND
				classe.idclasse=:idclasse');
				
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idclasse', $idclasse, PDO::PARAM_INT);
		$stmt->bindParam(':id_exercice', $id_exercice, PDO::PARAM_INT);
		$stmt->execute();
		$nbre = 0;
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$num_eleve = $donnees['num_eleve'];
			$id_eleve = $donnees['id_eleve'];
			$nom_eleve = $donnees['nom_eleve'];
			$photo_eleve = $donnees['photo_eleve'];
			$prenom_eleve = $donnees['prenom_eleve'];
			$sexe_eleve = $donnees['sexe_eleve'];
			$datenaissance_eleve = $donnees['datenaissance_eleve'];
			$tab = explode("-",$datenaissance_eleve);
			$datenaissance_eleve = $tab[2]." ".$tab[1]." ".$tab[0];
			
			$lieunaissance_eleve = $donnees['lieunaissance_eleve'];
			$tel_eleve = $donnees['tel_eleve'];
			$commentaire_eleve = $donnees['commentaire_eleve'];
			$droit_inscription = $donnees['droit_inscription'];
			$date_inscrit = $donnees['date_inscrit'];
			$tabinscrit = explode("-",$date_inscrit);
			$date_inscrit = $tabinscrit[2]." ".$tabinscrit[1]." ".$tabinscrit[0];
			
			$codeclasse = $donnees['codeclasse'];
			$adresse_eleve = $donnees['adresse_eleve'];
			
			?>
			<div class="col-md-4 col-sm-4 col-xs-12 profile_details">
				<div class="well profile_view">
					<div class="col-sm-12">
						<h4 class="brief"><i>Carte d'&eacute;tudiant</i></h4>
						<div class="left col-xs-7">
							<h2><?php echo $nom_eleve." ".$prenom_eleve;?></h2>
							<p><strong> N&eacute; le : </strong><?php echo $datenaissance_eleve;?></p>
							<p><strong> Mention : </strong><?php echo $codeclasse;?></p>
							<ul class="list-unstyled">
								<li><i class="fa fa-building"></i> Address : <?php echo $adresse_eleve;?></li>
								<li><i class="fa fa-phone"></i> T&eacute;l&eacute;phone : <?php echo $tel_eleve;?></li>
							</ul>
						</div>
						<div class="right col-xs-5 text-center">
							<img src="photo/<?php echo $photo_eleve;?>" alt="" class="img-circle img-responsive">
						</div>
					</div>
					<div class="col-xs-12 bottom text-center">
						<div class="col-xs-12 col-sm-6 emphasis">
							<p class="ratings">
								<a>4.0</a>
								<a href="#"><span class="fa fa-star"></span></a>
								<a href="#"><span class="fa fa-star"></span></a>
								<a href="#"><span class="fa fa-star"></span></a>
								<a href="#"><span class="fa fa-star"></span></a>
								<a href="#"><span class="fa fa-star-o"></span></a>
							</p>
						</div>
						<div class="col-xs-12 col-sm-6 emphasis">
							<button type="button" class="btn btn-success btn-xs"> <i class="fa fa-user">
							</i> <i class="fa fa-comments-o"></i> </button>
							<button type="button" class="btn btn-primary btn-xs">
								<i class="fa fa-user"> </i> View Profile
							</button>
						</div>
					</div>
				</div>
			</div><?php
		}	
		$stmt->closeCursor();
		$stmt=NULL;	
	?>
	</div>
    <script src="../vendors/jquery/dist/jquery.min.js"></script>
    <script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../vendors/fastclick/lib/fastclick.js"></script>
    <script src="../vendors/nprogress/nprogress.js"></script>
    <script src="../build/js/custom.min.js"></script>
	</body>
</html>