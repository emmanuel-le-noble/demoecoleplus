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
	
	if(isset($_POST["delete_bulletin_eleve"]))
	{
		$nbre_bulletin = trim($_REQUEST["nbre_bulletin"]);
		for($i=1;$i<=$nbre_bulletin;$i++)
		{		
			if(isset($_REQUEST["id".$i]))
			{
				$idbulletin= trim($_REQUEST["id".$i]);	

				$req='  DELETE FROM bulletincontenu WHERE bulletincontenu.idbulletin=:idbulletin';
				$stmt = $pdo->prepare($req);
				$stmt ->bindParam(':idbulletin', $idbulletin, PDO::PARAM_INT);
				$stmt->execute();			

				$req='  DELETE FROM bulletin WHERE bulletin.id=:idbulletin';
				$stmt = $pdo->prepare($req);
				$stmt ->bindParam(':idbulletin', $idbulletin, PDO::PARAM_INT);
				$stmt->execute();			
				$stmt->closeCursor();
				$stmt=NULL;								
			}
		}
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
						if(isset($_POST["genererBulletins"]))
						{
							$idposition = trim($_REQUEST["idposition"]);
                            $idsalle = trim($_REQUEST["idsalle"]);
							$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
							
							$LibelleAnneeScolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
							$LibellePosition = getLibellePosition($idposition,$pdo);
							$CodeSalle = getCodeSalle($idsalle,$pdo);
							$iddomaine = getDomaineSalle($idsalle,$pdo);
                            
							if($iddomaine==3 OR $iddomaine==6)
							{
								GenererBulletin($idanneescolaire,$idposition,$idsalle,$pdo);
							}
							elseif($iddomaine==7)
							{
								GenererBulletinCycleSup($idanneescolaire,$idposition,$idsalle,$pdo);
							}
							elseif($iddomaine==4 && $iddomaine==5)
							{
								GenererBulletinPrimaire($idanneescolaire,$idposition,$idsalle,$pdo);
							}
							
							include('BulletinsCycleSupGeneration.php');
						}
						elseif(isset($_REQUEST["Consulter"]))
						{ 
							$nbrebulletins = trim($_REQUEST["nbrebulletins"]);
							$valeur=0;
							for($i=1;$i<=$nbrebulletins;$i++)
							{		
								if(isset($_REQUEST["id_".$i]))
								{
									$valeur = trim($_REQUEST["id_".$i]);	
									$tab = explode("-",trim($valeur));
									$idanneescolaire = $tab[0];
									$idposition = $tab[1];
									$idsalle = $tab[2];
									
									$LibelleAnneeScolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
									$LibellePosition = getLibellePosition($idposition,$pdo);
									$CodeSalle = getCodeSalle($idsalle,$pdo);
								}
							}
							if($valeur!=0)
							{
								include('BulletinsCycleSupGeneration.php');
							}
							else
							{
								include('BulletinsCycleSupContenu.php');
							}
						}
						elseif(isset($_POST["Ajouter"]))
						{
							include('BulletinsCycleSupGenerer.php');									
						}
						else
						{
							include('BulletinsCycleSupContenu.php');
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
			  placeholder: "Liste des eleves",
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