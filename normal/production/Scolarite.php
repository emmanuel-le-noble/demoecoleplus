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
	if(isset($_REQUEST["EnregistrerScolarite"]))
	{ 

		$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
		$idTypePaiement = trim($_REQUEST["idTypePaiement"]);
		$montant = floatval(trim($_REQUEST["montant"]));
		$idclasse = trim($_REQUEST["idclasse"]);
		$totalligne = intval(trim($_REQUEST["totalligne"]));
		$sommeTranches = 0.0;

		// Calcule la somme des montants de toutes les tranches
		for ($i = 1; $i <= $totalligne; $i++) {
			if (isset($_REQUEST["idpaiementtranche" . $i])) {
				$montantTranche = floatval(trim($_REQUEST["montant" . $i]));
				$sommeTranches += $montantTranche;
			}
		}
		
		// Vérifie que la somme des tranches est égale au montant total
		if (abs($sommeTranches - $montant) > 0.01) {
			// Erreur : la somme des tranches ne correspond pas
			//echo "<div style='color:red;'>Erreur : La somme des montants des tranches ($sommeTranches) est différente du montant total ($montant).</div>";
			$success = "";
			$error = " Erreur : La somme des montants des tranches ($sommeTranches) est différente du montant total ($montant). ";
		} 
		else{

			$tab = explode('*',CreatePaiementTypeClasse($idanneescolaire,$idTypePaiement,$idclasse,$montant,$pdo));
			$success = $tab[0];
			$error = $tab[1];
			$idpaiementtypeclasse = $tab[2];
		
			for($i=1;$i<=$totalligne;$i++)
			{		
				if(isset($_REQUEST["idpaiementtranche".$i]))
				{
					$dateecheance = trim($_REQUEST["dateecheance".$i]);
					$montant = trim($_REQUEST["montant".$i]);
					$idpaiementtranche = trim($_REQUEST["idpaiementtranche".$i]);
					CreatePaiementTypeClasseTranche($idpaiementtypeclasse,$idpaiementtranche,$dateecheance,$montant,$pdo);	
				}
			}
		}
	}
	elseif(isset($_REQUEST["ModifierScolarite"]))
	{ 
	
		$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
		$id = trim($_REQUEST["id"]);
		$idTypePaiement = trim($_REQUEST["idTypePaiement"]);
		$montant = floatval(trim($_REQUEST["montant"]));
		$idclasse = trim($_REQUEST["idclasse"]);
		$totalligne = intval(trim($_REQUEST["totalligne"]));
		$sommeTranches = 0.0;

		// Calcule la somme des montants de toutes les tranches
		for ($i = 1; $i <= $totalligne; $i++) {
			if (isset($_REQUEST["idpaiementtranche" . $i])) {
				$montantTranche = floatval(trim($_REQUEST["montant" . $i]));
				$sommeTranches += $montantTranche;
			}
		}
		
		// Vérifie que la somme des tranches est égale au montant total
		if (abs($sommeTranches - $montant) > 0.01) {
			// Erreur : la somme des tranches ne correspond pas
			//echo "<div style='color:red;'>Erreur : La somme des montants des tranches ($sommeTranches) est différente du montant total ($montant).</div>";
			$success = "";
			$error = " Erreur : La somme des montants des tranches ($sommeTranches) est différente du montant total ($montant). ";
		} 
		else{

			$tab = explode('*',UpdatePaiementTypeClasse($id,$idanneescolaire,$idTypePaiement,$idclasse,$montant,$pdo));
			$success = $tab[0];
			$error = $tab[1];
			$totalligne = trim($_REQUEST["totalligne"]);
			for($i=1;$i<=$totalligne;$i++)
			{		
				if(isset($_REQUEST["idpaiementtranche".$i]))
				{
					$dateecheance = trim($_REQUEST["dateecheance".$i]);
					$montant = trim($_REQUEST["montant".$i]);
					$idpaiementtranche = trim($_REQUEST["idpaiementtranche".$i]);
					UpdatePaiementTypeClasseTranche($id,$idpaiementtranche,$dateecheance,$montant,$pdo);
				}
			}
		}
	}
	elseif(isset($_REQUEST["Supprimer"]))
	{
		$nbrefraisscolarite = trim($_REQUEST["nbrefraisscolarite"]);
		for($i=1;$i<=$nbrefraisscolarite;$i++)
		{		
			if(isset($_REQUEST["id".$i]))
			{
				$id = trim($_REQUEST["id".$i]);
				DeletePaiementTypeClasse($id,$pdo);	
			}
		}
		$success="Suppression effectu&eacute;e avec succ&egrave;s";
		$error="";
	}
	elseif(isset($_REQUEST["Desactiver"]))
	{
		$nbrefraisscolarite = trim($_REQUEST["nbrefraisscolarite"]);
		for($i=1;$i<=$nbrefraisscolarite;$i++)
		{		
			if(isset($_REQUEST["id".$i]))
			{
				$idLigne = trim($_REQUEST["id".$i]);
				$tab = explode('*',$idLigne);
				$id = $tab[0];
				$statut = $tab[1];
				DesactivePaiementTypeClasse($id,$pdo);
			}
		}
		$success="Op&eacute;ration de d&eacute;sactivation effectu&eacute; avec succ&egrave;s !!!";
		$error="";
	}
	elseif(isset($_REQUEST["Reactiver"]))
	{
		$nbrefraisscolarite = trim($_REQUEST["nbrefraisscolarite"]);
		for($i=1;$i<=$nbrefraisscolarite;$i++)
		{		
			if(isset($_REQUEST["id".$i]))
			{
				$idLigne = trim($_REQUEST["id".$i]);
				$tab = explode('*',$idLigne);
				$id = $tab[0];
				$statut = $tab[1];
				ActivePaiementTypeClasse($id,$pdo);
			}
		}
		$success="Op&eacute;ration de r&eacute;activation effectu&eacute;e avec succ&egrave;s !!!";
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
		<link rel="icon" type="image/x-icon" href="images/OmegaNet.ico"/>
		<script>
		function getValidationForm()
		{
			document.getElementById("my_form").addEventListener("submit", function(e){
				const total = parseFloat(document.getElementById("montant").value);
				const nbLignes = parseInt(document.querySelector('input[name="totalligne"]').value);
				let sommeTranches = 0;

				for (let i = 1; i <= nbLignes; i++) {
					const trancheInput = document.getElementById("montant" + i);
					if (trancheInput) {
						const montantTranche = parseFloat(trancheInput.value) || 0;
						sommeTranches += montantTranche;
					}
				}

				const tolerance = 0.01;
				if (Math.abs(sommeTranches - total) > tolerance) {
					e.preventDefault(); // bloque l'envoi du formulaire
					alert("Erreur : La somme des montants des tranches (" + sommeTranches.toFixed(2) + 
						  ") est différente du montant total (" + total.toFixed(2) + ").");
				}
			});
		}
		</script>
    </head>
    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
				<form name="my_form" id="my_form" class="form-horizontal form-label-left" method="post" action="" onsubmit="return getValidationForm();">
					<?php 
					    include("Menu.php");
					    include("Entete.php");
					?>
					<div class="left_col" role="main">
						<?php 
							if(isset($_POST["Modifier"]))
							{
								$idLigne="";
								$nbrefraisscolarite = trim($_REQUEST["nbrefraisscolarite"]);
								for($i=1;$i<=$nbrefraisscolarite;$i++)
								{		
									if(isset($_REQUEST["id".$i]))
									{
										$idLigne = trim($_REQUEST["id".$i]);
										$tab = explode('*',$idLigne);
										$id = $tab[0];
										$statut = $tab[1];
										break;	
				                    }
								}
								if($idLigne!="")
								{
									if($statut==1)
									{
										include('ScolariteModifier.php');
									}
									else
									{
										$error="Impossible de modifier la ligne s&eacute;lectionn&eacute;e.";
										include('ScolariteContenu.php');
									}
								}
								else
								{
									$error="Veuiller s&eacute;lectionner la ligne &agrave; modifier.";
									include('ScolariteContenu.php');
								}
							}
							elseif(isset($_POST["Ajouter"]))
							{
								include('ScolariteAjouter.php');								
							}
							else
							{
								include('ScolariteContenu.php');
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