<?php 
    session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/finance.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		
		header ('location:index.php'); 
	}
	$error="";
	$success="";
	if(isset($_POST["Supprimer"]))
	{
		$nbrelignecalculpaie = trim($_REQUEST["nbrelignecalculpaie"]);
		for($i=1;$i<=$nbrelignecalculpaie;$i++)
		{		
			if(isset($_REQUEST["id".$i]))
			{
				$id = trim($_REQUEST["id".$i]);	
				$tab = explode('*',$id);
				$idmois = $tab[0];
				$idanneescolaire = $tab[1];
				$corps = $tab[2];
				$statut = $tab[3];
				$debutmois = $tab[4];
				$finmois = $tab[5];
				SupprimerMoisPaie($idanneescolaire,$idmois,$corps,$pdo);
			}
		}
		$success ="Op&eacute;ration de suppression effectu&eacute;e avec succ&egraves";
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
		<title>EcolePlus|</title>
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
				<form class="form-horizontal form-label-left" method="post" action="">
					<?php 
					    include("Menu.php");
					    include("Entete.php");
					?>
					<div class="left_col" role="main">
						<?php 
							if(isset($_POST["Modifier"]))
							{
								$id="";
								$nbrelignecalculpaie = trim($_REQUEST["nbrelignecalculpaie"]);
								for($i=1;$i<=$nbrelignecalculpaie;$i++)
								{		
									if(isset($_REQUEST["id".$i]))
									{
										$id = trim($_REQUEST["id".$i]);	
										$tab = explode('*',$id);
										$idmois = $tab[0];
										$idanneescolaire = $tab[1];
										$corps = $tab[2];
										$statut = $tab[3];
										$debutmois = $tab[4];
										$finmois = $tab[5];
										$idpers = $tab[6];
										$libelleanneescolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
				                    }
								}
								if($id!="")
								{
									include('EditionFichePaieModifier.php');
								}
								else
								{
									$error="Veuiller s&eacute;lectionner le mois de paie &agrave; modifier.";
									include('EditionFichePaieContenu.php');
								}							
							}
							elseif(isset($_POST["AjouterEditionFichePaie"]))
							{
								$corps = 3;
								$debutmois = trim($_REQUEST["debutmois"]);
								$finmois = trim($_REQUEST["finmois"]);
								$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
								$libelleanneescolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
								$salairebase = str_replace(" ","",trim($_REQUEST["salairebase"]));
								if($salairebase=="")
								{
									$salairebase=0;
								}
								$anciennete = str_replace(" ","",trim($_REQUEST["anciennete"]));
								if($anciennete=="")
								{
									$anciennete=0;
								}
								$sursalaire = str_replace(" ","",trim($_REQUEST["sursalaire"]));
								if($sursalaire=="")
								{
									$sursalaire=0;
								}
								$indemnitefonction = str_replace(" ","",trim($_REQUEST["indemnitefonction"]));
								if($indemnitefonction=="")
								{
									$indemnitefonction=0;
								}
								$primesujetion = str_replace(" ","",trim($_REQUEST["primesujetion"]));
								if($primesujetion=="")
								{
									$primesujetion=0;
								}
								$primeinterim = str_replace(" ","",trim($_REQUEST["primeinterim"]));
								if($primeinterim=="")
								{
									$primeinterim=0;
								}
								$indemnitelogement = str_replace(" ","",trim($_REQUEST["indemnitelogement"]));
								if($indemnitelogement=="")
								{
									$indemnitelogement=0;
								}
								$indemnitetransport = str_replace(" ","",trim($_REQUEST["indemnitetransport"]));
								if($indemnitetransport=="")
								{
									$indemnitetransport=0;
								}
								$primecaisse = str_replace(" ","",trim($_REQUEST["primecaisse"]));
								if($primecaisse=="")
								{
									$primecaisse=0;
								}
								$allocationfami = str_replace(" ","",trim($_REQUEST["allocationfami"]));
								if($allocationfami=="")
								{
									$allocationfami=0;
								}
								$salairebrute = str_replace(" ","",trim($_REQUEST["salairebrute"]));
								if($salairebrute=="")
								{
									$salairebrute=0;
								}
								$cnss = str_replace(" ","",trim($_REQUEST["cnss"]));
								if($cnss=="")
								{
									$cnss=0;
								}
								$inam=0;
								$crt=0;
								$tcs=0;
								$irpp = str_replace(" ","",trim($_REQUEST["irpp"]));
								if($irpp=="")
								{
									$irpp=0;
								}
								$assurance=0;
								$remboursement = str_replace(" ","",trim($_REQUEST["remboursement"]));
								if($remboursement=="")
								{
									$remboursement=0;
								}
								$autresretenues=0;
								$mutuelle=0;
								$totalretenues=0;
								$primeinstallation=0;
								$rappel=0;
								$salairenet = str_replace(" ","",trim($_REQUEST["salairenet"]));
								if($salairenet=="")
								{
									$salairenet=0;
								}
								$idmois = trim($_REQUEST["idmois"]);
								$idpers = trim($_REQUEST["idpers"]);
								
								DeleteMoisPaieByPers($idanneescolaire,$idmois,$idpers,$pdo);		
								CreateFichePaie($debutmois,$finmois,$idpers,$salairebase,"",$sursalaire,$indemnitefonction,$primesujetion,$primeinterim,$indemnitelogement,
													$indemnitetransport,$primecaisse,$allocationfami,$salairebrute,$cnss,($salairebase*0.175),"","","",$irpp,"",$remboursement,"","","","","",
													$salairenet,"","",$idanneescolaire,$_SESSION['iduser'],$corps,0,$pdo);		
											
								include('EditionFichePaieReussi.php');
							}
							elseif(isset($_POST["suivantEditionFichePaie"]))
							{
								$debutmois = trim($_REQUEST["debutmois"]);
								$finmois = trim($_REQUEST["finmois"]);
								$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
								$libelleanneescolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
								
								include('EditionFichePaieSuivant.php');									
							}							
							elseif(isset($_POST["Ajouter"]))
							{
								include('EditionFichePaieAjouter.php');									
							}
							else
							{
								include('EditionFichePaieContenu.php');
							}
						?>
					</div>    
				</form>
            </div>
        </div>
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
			  placeholder: "Personnel",
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
		<script>
			$('#myDatepicker').datetimepicker();
			
			$('#myDatepicker17').datetimepicker({
				format: 'DD/MM/YYYY'
			});
			
			$('#myDatepicker18').datetimepicker({
				format: 'DD/MM/YYYY'
			});
		</script>
    </body>
</html>