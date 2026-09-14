<?php 
    session_name("ecoleplus");
    session_start();
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/paiementfrais.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		
		header ('location:index.php'); 
	}
	
	$error="";
	$success="";	
	if(isset($_POST["EnregistrerPaiement"]))
	{
		$datepaiement = trim($_REQUEST["datepaiement"]);
		$nompayeur = trim($_REQUEST["nompayeur"]);
		$tel = trim($_REQUEST["tel"]);
		$tab = explode("/",trim($datepaiement));
		$datepaiement = $tab[2]."-".$tab[1]."-".$tab[0];
		$nbrepaiementtype = trim($_REQUEST["nbrepaiementtype"]);
		for($i=1;$i<=$nbrepaiementtype;$i++)
		{		
			if(isset($_REQUEST["idpaiementtypeclasse".$i]))
			{
				$idpaiementtypeclasse = trim($_REQUEST["idpaiementtypeclasse".$i]);	
				$ideleveanneescolaire = trim($_REQUEST["ideleveanneescolaire".$i]);
				$montant = trim($_REQUEST["montant".$i]);
				
				CreateElevePaiement($ideleveanneescolaire,$montant,$datepaiement,$idpaiementtypeclasse,$nompayeur,$tel,$_SESSION['iduser'],$_SESSION['idanneescolaire'],$pdo);										
			}
		}
		$error="";
		$success="Enregistrement r&eacute;ussi avec succ&egrave;s";
	}
	elseif(isset($_POST["ModifierPaiement"]))
	{
		$montant = trim($_REQUEST["montant"]);
		$idpaiementfrais = trim($_REQUEST["idpaiementfrais"]);
		$datepaiement = trim($_REQUEST["datepaiement"]);
		$nompayeur = trim($_REQUEST["nompayeur"]);
		$telpayeur = trim($_REQUEST["telpayeur"]);
		$tab = explode("/",trim($datepaiement));
		$datepaiement = $tab[2]."-".$tab[1]."-".$tab[0];
		
		UpdateElevePaiement($idpaiementfrais,$montant,$datepaiement,$nompayeur,$telpayeur,$pdo);
		
		$error="";
		$success="Mise &agrave; jour r&eacute;ussie avec succ&egrave;s";
	}
	elseif(isset($_POST["Supprimer"]))
	{
		$nbrepaiement = trim($_REQUEST["nbrepaiement"]);
		$idpaiementfrais = 0;
		for($i=1;$i<=$nbrepaiement;$i++)
		{		
			if(isset($_REQUEST["idpaiementfrais".$i]))
			{
				$idpaiementfrais= trim($_REQUEST["idpaiementfrais".$i]);
                DeleteElevePaiement($idpaiementfrais,$_SESSION['iduser'],$pdo);				
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
		<title>EcolePlus!|</title>
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
		<link href="../vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
		<link rel="icon" type="image/x-icon" href="images/OmegaNet.ico"/>
    </head>
    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
				<form class="form-horizontal form-label-left" method="post" action="" enctype="multipart/form-data">
					<?php 
					    include("Menu.php");
					    include("Entete.php");
					?>
					<div class="left_col" role="main">
						<?php
							if(isset($_POST["Consulter"]))
							{
								$idpaiementfrais=0;
								$nbrepaiement = trim($_REQUEST["nbrepaiement"]);
								for($i=1;$i<=$nbrepaiement;$i++)
								{		
									if(isset($_REQUEST["idpaiementfrais".$i]))
									{
										$idpaiementfrais = trim($_REQUEST["idpaiementfrais".$i]);
				                    }
								}
								if($idpaiementfrais!=0)
								{
									include('PaiementFraisConsulter.php');
								}
								else
								{
									$error="Veuiller s&eacute;lectionner la ligne de paiement &agrave; consulter.";
									include('PaiementFraisContenu.php');
								}
							}
							elseif(isset($_POST["Modifier"]))
							{
								$idpaiementfrais=0;
								$nbrepaiement = trim($_REQUEST["nbrepaiement"]);
								for($i=1;$i<=$nbrepaiement;$i++)
								{		
									if(isset($_REQUEST["idpaiementfrais".$i]))
									{
										$idpaiementfrais = trim($_REQUEST["idpaiementfrais".$i]);
				                    }
								}
								if($idpaiementfrais!=0)
								{
									include('PaiementFraisModifier.php');
								}
								else
								{
									$error="Veuiller s&eacute;lectionner la ligne de paiement &agrave; modifier.";
									include('PaiementFraisContenu.php');
								}
							}
							elseif(isset($_POST["Ajouter"]))
							{
								$toDay = date("d/m/Y"); 
								include('PaiementFraisAjouter.php');								
							}
							else
							{
								include('PaiementFraisContenu.php');
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
		<script>
		  $(document).ready(function() {
			$(".select2_single").select2({
			  placeholder: "Liste des élèves",
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
		<script>
			$('#myDatepicker').datetimepicker();
			$('#myDatepicker13').datetimepicker({
				format: 'DD/MM/YYYY'
			});
		</script>
    </body>
</html>