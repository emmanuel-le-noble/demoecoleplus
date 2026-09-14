<?php 
    session_name("ecoleplus");
    session_start();
	include("../modele/connexion.php");
	include("../modele/liste.php");
	include("../modele/droit.php");
	include("../modele/function.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		
		header ('location:index.php'); 
	}
	$error="";
	$success="";
	if(isset($_POST["EnregistrerOperation"]))
	{
		$idSousTypePaiement = trim($_REQUEST["idSousTypePaiement"]);
		$TypeOperation = trim($_REQUEST["TypeOperation"]);
		$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
		$LibelleOperation = trim($_REQUEST["LibelleOperation"]);
		$Montant = str_replace(" ","",trim($_REQUEST["Montant"]));
		$DateOperation = trim($_REQUEST["DateOperation"]);
		$tab = explode("/",trim($DateOperation));
		$DateOperation = $tab[2]."-".$tab[1]."-".$tab[0];
		$idCompte = trim($_REQUEST["idCompte"]);
		$iduser = trim($_REQUEST["iduser"]);
		$fichier = $_FILES["image"];
		$dossier = 'entreesorties/';
		$fichier = basename($_FILES['image']['name']);
		if(file_exists($dossier.$fichier)) 
		{
			$success = "";
			$error = "Echec d'enregistrement. La pi&egrave;ce justificative existe d&eacute;j&agrave;";
		} 
		else 
		{
			if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier.$fichier))
			{
				$tab = explode('*',AjouterEntreeSortie($idSousTypePaiement,$idanneescolaire,$TypeOperation,$LibelleOperation,$Montant,$DateOperation,$idCompte,$iduser,$_SESSION['iduser'],$fichier,$pdo));
				$success = $tab[0];
				$error = $tab[1];
			}
			else
			{
				$success = "";
				$error = "Echec d'enregistrement. La pi&egrave;ce justificative n'a pas &eacute;t&eacute; transf&eacute;r&eacute; sur le serveur";
			}
		}
	}
	elseif(isset($_POST["ModifierOperation"]))
	{
		$idSousTypePaiement = trim($_REQUEST["idSousTypePaiement"]);
		$idEntreeSortie = trim($_REQUEST["idEntreeSortie"]);
		$TypeOperation = trim($_REQUEST["TypeOperation"]);
		$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
		$LibelleOperation = trim($_REQUEST["LibelleOperation"]);
		$Montant = str_replace(" ","",trim($_REQUEST["Montant"]));
		$MontantOld = str_replace(" ","",trim($_REQUEST["MontantOld"]));
		$DateOperation = trim($_REQUEST["DateOperation"]);
		$tab = explode("/",trim($DateOperation));
		$DateOperation = $tab[2]."-".$tab[1]."-".$tab[0];
		$idCompte = trim($_REQUEST["idCompte"]);
		$idUser = trim($_REQUEST["iduser"]);
		$FichierOld = trim($_REQUEST["FichierOld"]);
		$MontantCompte = $MontantOld-$Montant;
		$fichier = $_FILES["image"];
		$dossier = 'entreesorties/';
		$fichier = basename($_FILES['image']['name']);
		if($fichier=="")
		{
			$fichier=$FichierOld;
		}
	
		if(file_exists($dossier.$fichier)) 
		{
			UpdateEntreeSortie($idEntreeSortie,$idSousTypePaiement,$idanneescolaire,$TypeOperation,$LibelleOperation,$Montant,$DateOperation,$idCompte,$idUser,$_SESSION['iduser'],$fichier,$pdo);
			$success ="Mise &agrave; jour effectu&eacute;e avec succ&egrave;s";
			$error="";
		} 
		else 
		{
			if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier.$fichier))
			{
				UpdateEntreeSortie($idEntreeSortie,$idSousTypePaiement,$idanneescolaire,$TypeOperation,$LibelleOperation,$Montant,$DateOperation,$idCompte,$idUser,$_SESSION['iduser'],$fichier,$pdo);
				$success ="Mise &agrave; jour effectu&eacute;e avec succ&egrave;s";
				$error="";
			}
			else
			{
				$success = "";
				$error = "Echec de mise &agrave; jour. La pi&egrave;ce justificative n'a pas &eacute;t&eacute; transf&eacute;r&eacute; sur le serveur";
			}
		}
		
	}
	elseif(isset($_POST["Supprimer"]))
	{
		$nbreEntreeSortie = trim($_REQUEST["nbreEntreeSortie"]);
		for($i=1;$i<=$nbreEntreeSortie;$i++)
		{		
			if(isset($_REQUEST["idEntreeSortie".$i]))
			{
				$idEntreeSortie = trim($_REQUEST["idEntreeSortie".$i]);
				DeleteEntreeSortie($idEntreeSortie,$pdo);		
			}
		}
		$success ="Op&eacute;ration supprim&eacute;e avec succ&egraves";
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
		<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
		<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
		<link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet"/>
		<link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet"/>
		<link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet"/>
		<link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet"/>
		<link href="../vendors/starrr/dist/starrr.css" rel="stylesheet"/>
		<link href="../build/css/custom.min.css" rel="stylesheet"/>
				<link href="modern.css" rel="stylesheet"/>
		<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
		<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
		<link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet"/>
		<link href="../vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet"/>
		<link href="css/maps/jquery-jvectormap-2.0.3.css" rel="stylesheet"/>
		<link href="../build/css/custom.min.css" rel="stylesheet"/>
				<link href="modern.css" rel="stylesheet"/>
		<link href="../build/css/select.css" rel="stylesheet"/>
		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
		<link href="../vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
		<link href="../vendors/pnotify/dist/pnotify.css" rel="stylesheet">
		<link href="../vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
		<link href="../vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
		<link rel="icon" type="image/x-icon" href="images/OmegaNet.ico"/>
		<script>
			$(function () {
			
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
								$idEntreeSortie="";
							    $nbreEntreeSortie = trim($_POST["nbreEntreeSortie"]);
								for($i=1;$i<=$nbreEntreeSortie;$i++)
								{		
									if(isset($_POST["idEntreeSortie".$i]))
									{
										$idEntreeSortie = trim($_POST["idEntreeSortie".$i]);	
										break;
									}
								}
								if($idEntreeSortie!="")
								{
									include('TresorerieModifier.php');
								}
								else
								{
									$error="Veuillez s&eacute;lectionner l'op&eacute;ration &agrave; modifier.";
									include('TresorerieContenu.php');
								}
							}
							elseif(isset($_POST["Ajouter"]))
							{
								include('TresorerieAjouter.php');								
							}
							else
							{
								include('TresorerieContenu.php');
							}
						?>
					</div>    
				</form>
            </div>
        </div>
		<script src="js/submit.js"></script>
		<script src="js/besoin.js"></script>
		<script src="../vendors/jquery/dist/jquery.min.js"></script>
		<script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="../vendors/fastclick/lib/fastclick.js"></script>
		<script src="../vendors/nprogress/nprogress.js"></script>
		<script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
		<script src="../vendors/iCheck/icheck.min.js"></script>
		<script src="js/moment/moment.min.js"></script>
		<script src="js/datepicker/daterangepicker.js"></script>
		<script src="../vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
		<script src="../vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
		<script src="../vendors/google-code-prettify/src/prettify.js"></script>
		<script src="../vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
		<script src="../vendors/switchery/dist/switchery.min.js"></script>
		<script src="../vendors/select2/dist/js/select2.full.min.js"></script>
		<script src="../vendors/parsleyjs/dist/parsley.min.js"></script>
		<script src="../vendors/autosize/dist/autosize.min.js"></script>
		<script src="../vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
		<script src="../vendors/starrr/dist/starrr.js"></script>
		<script src="../build/js/custom.min.js"></script>
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
		<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
		<script src="../vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
		<script src="../vendors/pnotify/dist/pnotify.js"></script>
		<script src="../vendors/pnotify/dist/pnotify.buttons.js"></script>
		<script src="../vendors/pnotify/dist/pnotify.nonblock.js"></script>
		<script>
			$(document).ready(function() 
			{
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
		<script>
			$('#myDatepicker').datetimepicker();
			
			$('#myDatepicker2').datetimepicker({
				format: 'DD/MM/YYYY'
			});
		</script>
    </body>
</html>