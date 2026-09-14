<?php 
    session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/inscription.php");
	 
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		
		header ('location:index.php'); 
	}
	
	$error="";
	$success="";
	if(isset($_REQUEST["EnregistrerInscription"]))
	{ 
		$image = $_FILES["image"];
		$image_name = basename($_FILES['image']['name']);
		$image_tmp_name = $_FILES['image']['tmp_name'];
		$emailtuteur = trim($_REQUEST["emailtuteur"]);
		$teltuteur = trim($_REQUEST["teltuteur"]);
		$idelevestatutetablissement = trim($_REQUEST["idelevestatutetablissement"]);
		$idclasse = trim($_REQUEST["idclasse"]);
		$nom_eleve = trim($_REQUEST["nom_eleve"]);
		$prenom_eleve = trim($_REQUEST["prenom_eleve"]);
		$date_naissance = trim($_REQUEST["date_naissance"]);
		$lieu_naissance = trim($_REQUEST["lieu_naissance"]);
		$sexe = trim($_REQUEST["sexe"]);
		$idelevestatutclasse = trim($_REQUEST["idelevestatutclasse"]);
		$matricule = trim($_REQUEST["num_matricule"]);
		$remise_frais = trim($_REQUEST["remise_frais"]);
		if($remise_frais=="Partielle")
		{
			$boursier = trim($_REQUEST["remise"]);
		}
		elseif($remise_frais=="Gratuite")
		{
			$idpaiementtype = 1;
			$response = getMontantPaiementTypeClasse($idpaiementtype,$idclasse,1,1,$pdo);
			$tab = explode("*",trim($response));
			$montantpaiementtypeclasse = $tab[0];
			$idpaiementtypeclasse = $tab[1];
			$boursier = $montantpaiementtypeclasse;
		}
		else
		{
			$boursier = "";
		}
		$ideleveanneescolaire = CreateInscription($image_name,$matricule,$emailtuteur,$teltuteur,$nom_eleve,$prenom_eleve,$sexe,$idelevestatutclasse,$date_naissance,$lieu_naissance,$idclasse,$_SESSION['idanneescolaire'],$idelevestatutetablissement,"","",$boursier,$remise_frais,$pdo);
		if($ideleveanneescolaire!=0)
		{
			$nbrepiece = trim($_REQUEST["nbrepiece"]);
			for($i=1;$i<=$nbrepiece;$i++)
			{		
				if(isset($_REQUEST["idpiece".$i]))
				{
					$idpiece = $_REQUEST["idpiece".$i];
					$nomFichier = $_FILES["fichier".$i]["name"];
					$nomTemporaire = $_FILES["fichier".$i]["tmp_name"];
					CreatePieceEleveanneescolaireInscription($ideleveanneescolaire,$idpiece,$nomFichier,$pdo);
					move_uploaded_file($nomTemporaire, "dossierinscription/" . basename($nomFichier));
				}
			}
			$dossier = 'elevephoto/';
			$fichier = basename($_FILES['image']['name']);
			move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichier);
			$success="Inscription r&eacute;ussie avec succ&egrave;s";
			$error="";
		}
		else
		{
			$success="";
			$error="Echec d'enregistrement. Inscription &eacute;chou&eacute;e";
		}
	}
	elseif(isset($_REQUEST["ModifierInscription"]))
	{
		$ideleveanneescolaire = trim($_REQUEST["ideleveanneescolaire"]);
		$ideleve = trim($_REQUEST["ideleve"]);
		$image = $_FILES["image"];
		$image_name = basename($_FILES['image']['name']);
		$image_tmp_name = $_FILES['image']['tmp_name'];
		if($image_name=="")
		{
			$image_name = trim($_REQUEST["photoold"]);
		}
		$matricule = trim($_REQUEST["num_matricule"]);
		$emailtuteur = trim($_REQUEST["emailtuteur"]);
		$teltuteur = trim($_REQUEST["teltuteur"]);
		$idelevestatutetablissement = trim($_REQUEST["idelevestatutetablissement"]);
		$idclasse = trim($_REQUEST["idclasse"]);
		$nom_eleve = trim($_REQUEST["nom_eleve"]);
		$prenom_eleve = trim($_REQUEST["prenom_eleve"]);
		$date_naissance = trim($_REQUEST["date_naissance"]);
		$lieu_naissance = trim($_REQUEST["lieu_naissance"]);
		$sexe = trim($_REQUEST["sexe"]);
		$idelevestatutclasse = trim($_REQUEST["idelevestatutclasse"]);
		$remise_frais = trim($_REQUEST["remise_frais"]);
		if($remise_frais=="Partielle")
		{
			$boursier = trim($_REQUEST["remise"]);
		}
		elseif($remise_frais=="Gratuite")
		{
			$idpaiementtype = 1;
			$response = getMontantPaiementTypeClasse($idpaiementtype,$idclasse,1,1,$pdo);
			$tab = explode("*",trim($response));
			$montantpaiementtypeclasse = $tab[0];
			$idpaiementtypeclasse = $tab[1];
			$boursier = $montantpaiementtypeclasse;
		}
		else
		{
			$boursier = "";
		}
		UpdateInscription($image_name,$matricule,$emailtuteur,$teltuteur,$ideleve,$nom_eleve,$prenom_eleve,$sexe,$date_naissance,$lieu_naissance,$idclasse,$idelevestatutclasse,$idelevestatutetablissement,$ideleveanneescolaire,"","",$boursier,$remise_frais,$pdo);
		$nbrepiece = trim($_REQUEST["nbrepiece"]);
		for($i=1;$i<=$nbrepiece;$i++)
		{		
			if(isset($_REQUEST["idpiece".$i]))
			{
				$idpiece = $_REQUEST["idpiece".$i];
				$nomFichier = $_FILES["fichier".$i]["name"];
				$nomTemporaire = $_FILES["fichier".$i]["tmp_name"];
				if($nomFichier!="")
				{
					DeletePieceEleveanneescolaireInscription($ideleveanneescolaire,$idpiece,$pdo);
					CreatePieceEleveanneescolaireInscription($ideleveanneescolaire,$idpiece,$nomTemporaire,$pdo);
					move_uploaded_file($nomTemporaire, "dossierinscription/" . basename($nomFichier));
				}
			}
		}

		if($image_name!="")
		{
			$dossier = 'elevephoto/';
			$fichier = basename($_FILES['image']['name']);
			move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichier);
		}
		
		$success="Mise &agrave; jour r&eacute;ussie avec succ&egrave;s";
		$error="";
	}
	elseif(isset($_REQUEST["Supprimer"]))
	{ 
		$nbreinscrit = trim($_REQUEST["nbreinscrit"]);
		for($i=1;$i<=$nbreinscrit;$i++)
		{
			if(isset($_REQUEST["id".$i]))
			{
				$tab = explode("*",trim($_REQUEST["id".$i]));
				$ideleveanneescolaire = $tab[0];
				$ideleve = $tab[1];
				
				DeleteInscription($ideleveanneescolaire,$ideleve,$pdo);
			}
		}
		$success="Inscription supprim&eacute;e avec succ&egrave;s.";
		$error="";
	}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title>EcolePlus</title>
		<link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
		<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
		<link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
		<link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
		<link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
		<link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
		<link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
		<link href="../build/css/custom.min.css" rel="stylesheet">
				<link href="modern.css" rel="stylesheet"/>
		<script src="js/jquery-1.11.2.min.js"></script>
		<link rel="icon" type="image/x-icon" href="images/OmegaNet.ico"/>
		<script>
			$(function () 
			{
				$('#my_form').find('input[name="image"]').on('change', function (e) {
					var files = $(this)[0].files;

					if (files.length > 0) {

						var file = files[0],
							$image_preview = $('#image_preview');

						$image_preview.find('.thumbnail').removeClass('hidden');
						$image_preview.find('img').attr('src', window.URL.createObjectURL(file));
						$image_preview.find('h4').html(file.name);
						$image_preview.find('.caption p:first').html(file.size +' bytes');
					}
				});

				$('#image_preview').find('button[type="button"]').on('click', function (e) {
					e.preventDefault();

					$('#my_form').find('input[name="image"]').val('');
					$('#image_preview').find('.thumbnail').addClass('hidden');
				});
			});
			
			$(document).ready(function() {
			$(":input").inputmask();
		  });
		</script>
    </head>
    <body class="nav-md">
		<div class="container body">
			<div class="main_container">
				<form id="my_form" class="form-horizontal form-label-left" method="post" action="" enctype="multipart/form-data">
					<?php 
						include("Menu.php");
					    include("Entete.php");
					?>
					<div class="left_col" role="main">
						<?php  
							if(isset($_POST["Modifier"]))
							{
								$id="";
								$nbreinscrit = trim($_REQUEST["nbreinscrit"]);
								for($i=1;$i<=$nbreinscrit;$i++)
								{		
									if(isset($_REQUEST["id".$i]))
									{
										$id = $_REQUEST["id".$i];
										$tab = explode("*",$id);
										$ideleveanneescolaire = $tab[0];
										$ideleve = $tab[1];
				                    }
								}
								if($id!="")
								{
									include('InscriptionModifier.php');
								}
								else
								{
									$error="Veuiller s&eacute;lectionner l'&eacute;l&egrave;ve.";
									include('InscriptionContenu.php');
								}
							}
							elseif(isset($_POST["Ajouter"]))
							{
								$dernierNumero = getLastMatricule($pdo);
								if($dernierNumero=="")
								{
									$dernierNumero="1138-25";
								}
								$matricule = genererNumero($dernierNumero);
								include('InscriptionAjouter.php');								
							}
							else
							{
								include('InscriptionContenu.php');
							}
						?>
					</div>    
				</form>  
			</div>
		</div>
		<script src="js/submit.js"></script>
		<script src="js/besoin.js"></script>
		<script src="../vendors/jquery/dist/jquery.min.js"></script>
		<script src="../vendors/iCheck/icheck.min.js"></script>
		<script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="../vendors/fastclick/lib/fastclick.js"></script>
		<script src="../vendors/nprogress/nprogress.js"></script>
		<script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
		<script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
		<script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
		<script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
		<script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
		<script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
		<script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
		<script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
		<script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
		<script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
		<script src="../vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
		<script src="../vendors/jszip/dist/jszip.min.js"></script>
		<script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
		<script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
		<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
		<script src="../build/js/custom.min.js"></script>
		<script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
		<script>
		  $(document).ready(function() {
			var handleDataTableButtons = function() {
			  if ($("#datatable-buttons").length) {
				$("#datatable-buttons").DataTable({
				  dom: "Bfrtip",
				  buttons: [
					{
					  extend: "copy",
					  className: "btn-sm"
					},
					{
					  extend: "csv",
					  className: "btn-sm"
					},
					{
					  extend: "excel",
					  className: "btn-sm"
					},
					{
					  extend: "pdfHtml5",
					  className: "btn-sm"
					},
					{
					  extend: "print",
					  className: "btn-sm"
					},
				  ],
				  responsive: true
				});
			  }
			};

			TableManageButtons = function() {
			  "use strict";
			  return {
				init: function() {
				  handleDataTableButtons();
				}
			  };
			}();

			$('#datatable').dataTable();
			$('#datatable-keytable').DataTable({
			  keys: true
			});

			$('#datatable-responsive').DataTable();

			$('#datatable-scroller').DataTable({
			  ajax: "js/datatables/json/scroller-demo.json",
			  deferRender: true,
			  scrollY: 380,
			  scrollCollapse: true,
			  scroller: true
			});

			var table = $('#datatable-fixed-header').DataTable({
			  fixedHeader: true
			});

			TableManageButtons.init();
		  });
		  $.extend(true, $.fn.dataTable.defaults, {
				language: {
					url: '../vendors/fr-FR.json'
				},
			});
		</script>
		<script>
			$(document).ready(function() {
				$(":input").inputmask();
			});
		</script>
    </body>
</html>