<?php 
    session_name("ecoleplus");
    session_start();
	include("../modele/connexion.php");
	include("../modele/decisionconseil.php");
	$error="";
	$success="";
	if(isset($_REQUEST["EnregistrerDecision"]))
	{ 
		$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
		$idposition = trim($_REQUEST["idposition"]);
		$dateconseil = trim($_REQUEST["dateconseil"]);
		$tab = explode("/",trim($dateconseil));
		$dateconseil = $tab[2]."-".$tab[1]."-".$tab[0];
		$contenurapport = trim($_REQUEST["contenurapport"]);
		$fichier = $_FILES["image"];
		$dossier = 'decisionrapport/';
		$fichier = basename($_FILES['image']['name']);
		if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
		{
			//echo 'Upload effectué avec succès !';
		}
		else //Sinon (la fonction renvoie FALSE).
		{
			//echo 'Echec de l\'upload !';
		}
		$tab = explode('*',CreateDecision($idposition,$idanneescolaire,$dateconseil,$contenurapport,$fichier,$pdo));
		$success = $tab[0];
		$error = $tab[1];
	}
	elseif(isset($_REQUEST["ModifierDecision"]))
	{
		$iddecision = trim($_REQUEST["iddecision"]);
		$iddecisionrapport = trim($_REQUEST["iddecisionrapport"]);
		$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
		$idposition = trim($_REQUEST["idposition"]);
		$dateconseil = trim($_REQUEST["dateconseil"]);
		$tab = explode("/",trim($dateconseil));
		$dateconseil = $tab[2]."-".$tab[1]."-".$tab[0];
		$contenurapport = trim($_REQUEST["contenurapport"]);
		$fichier = $_FILES["image"];
		$dossier = 'decisionrapport/';
		$fichier = basename($_FILES['image']['name']);
		if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
		{
			//echo 'Upload effectué avec succès !';
		}
		else //Sinon (la fonction renvoie FALSE).
		{
			//echo 'Echec de l\'upload !';
		}
		UpdateDecision($idposition,$idanneescolaire,$dateconseil,$iddecision,$iddecisionrapport,$contenurapport,$fichier,$pdo);
		$success="Rapport enregistr&eacute; avec succ&egrave;s";
		$error="";
	}
	elseif(isset($_POST["Supprimer"]))
	{
		$NbreDecisionRapport = trim($_REQUEST["NbreDecisionRapport"]);
		$idecisionrapport = 0;
		for($i=1;$i<=$NbreDecisionRapport;$i++)
		{		
			if(isset($_REQUEST["idecisionrapport".$i]))
			{
				$idecisionrapport = trim($_REQUEST["idecisionrapport".$i]);
				DeleteDecision($idecisionrapport,$pdo);			
			}
		}
		$error="";
		$success="Suppression r&eacute;ussie avec succ&egrave;s";
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
		<script>
			$(function () {
				/*$('#my_form').on('submit', function (e) {
					// On empêche le navigateur de soumettre le formulaire
					e.preventDefault();

					var $form = $(this);
					var formdata = (window.FormData) ? new FormData($form[0]) : null;
					var data = (formdata !== null) ? formdata : $form.serialize();

					$.ajax({
						url: $form.attr('action'),
						type: $form.attr('method'),
						contentType: false, // obligatoire pour de l'upload
						processData: false, // obligatoire pour de l'upload
						dataType: 'json', // selon le retour attendu
						data: data,
						success: function (response) {
							$('#result > pre').html(JSON.stringify(response, undefined, 4));
						}
					});
				});*/

				// A change sélection de fichier
				$('#my_form').find('input[name="image"]').on('change', function (e) {
					var files = $(this)[0].files;

					if (files.length > 0) {
						// On part du principe qu'il n'y qu'un seul fichier
						// étant donné que l'on a pas renseigné l'attribut "multiple"
						var file = files[0],
							$image_preview = $('#image_preview');
                            
						// Ici on injecte les informations recoltées sur le fichier pour l'utilisateur
						$image_preview.find('.thumbnail').removeClass('hidden');
						$image_preview.find('img').attr('src', window.URL.createObjectURL(file));
						$image_preview.find('h4').html(file.name);
						$image_preview.find('.caption p:first').html(file.size +' bytes');
					}
				});

				// Bouton "Annuler"
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
					    include("menu.php");
					    include("entete.php");
					?>
					<div class="left_col" role="main">
						<?php 
							if(isset($_POST["Modifier"]))
							{
								$iddecisionrapport="";
								$NbreDecisionRapport = trim($_REQUEST["NbreDecisionRapport"]);
								for($i=1;$i<=$NbreDecisionRapport;$i++)
								{		
									if(isset($_REQUEST["idecisionrapport".$i]))
									{
										$idecisionrapport = trim($_REQUEST["idecisionrapport".$i]);
										$tab = explode('*',$idecisionrapport);
										$iddecisionrapport = $tab[0];
										$iddecision = $tab[1];
				                    }
								}
								if($iddecisionrapport!="")
								{
									include('DecisionRapportModifier.php');
								}
								else
								{
									include('DecisionRapportContenu.php');
								}							
							}
							elseif(isset($_POST["Ajouter"]))
							{
								include('DecisionRapportAjouter.php');								
							}
							else
							{
								include('DecisionRapportContenu.php');
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
		
		<script>
		  $(document).ready(function() {
			$(".select2_single").select2({
			  placeholder: "Liste du personnel",
			  allowClear: true
			});
			$(".select2_group").select2({});
			$(".select2_multiple").select2({
			  maximumSelectionLength: 4,
			  placeholder: "With Max Selection limit 4",
			  allowClear: true
			});
		  });
		</script>
		
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
		</script>
		<script>
			$(document).ready(function() {
				$(":input").inputmask();
			});
		</script>
    </body>
</html>