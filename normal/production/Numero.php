<?php 
    session_name("ecoleplus");
    session_start();
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/numeroevaluation.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		
		header ('location:index.php'); 
	}
	
	$error="";
	$success="";
	if(isset($_POST["CreerNumero"]))
	{
		$idposition = trim($_REQUEST["idposition"]);
		$idsalle = trim($_REQUEST["idsalle"]);
		$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
		$codeposition = getCodePosition($idposition,$pdo);
		$nbreelevesalle = trim($_REQUEST["nbreelevesalle"]);
		for($i=1;$i<=$nbreelevesalle;$i++)
		{		
			if(isset($_REQUEST["id".$i]))
			{
				$idelevesalle = trim($_REQUEST["id".$i]);
				$numerotable = genererNumeroTable($pdo);
				$numeroanonymat = genererNumeroAnonymat($pdo);
				CreateNumeroEvaluation($idsalle,$idelevesalle,$idposition,$idanneescolaire,$numerotable,$numeroanonymat,$_SESSION['iduser'],$pdo);									
			}
		}
		$success="Op&eacute;ration de g&eacute;n&eacute;ration de num&eacute;ros effectu&eacute;e avec succ&egrave;s.";
		$error="";
	}
	elseif(isset($_POST["Supprimer"]))
	{
		$nbrenumerogenere = $_REQUEST['nbrenumerogenere'];
		for($i=1;$i<=$nbrenumerogenere;$i++)
		{		
			if(isset($_REQUEST["id".$i]))
			{
				$valeur = trim($_REQUEST["id".$i]);	
				$tab = explode("*",trim($valeur));
				$idanneescolaire = $tab[0];
				$idposition = $tab[1];
				$idsalle = $tab[2];
				DeleteNumeroEvaluation($idanneescolaire,$idposition,$idsalle,$pdo);			
			}
		}
		$success="Op&eacute;ration de suppression effectu&eacute;e avec succ&egrave;s.";
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
		<title>EcolePlus!|</title>
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
							if(isset($_POST["Consulter"]))
							{
								$id="";
								$nbrenumerogenere = $_REQUEST['nbrenumerogenere'];
								for($i=1;$i<=$nbrenumerogenere;$i++)
								{		
									if(isset($_REQUEST["id".$i]))
									{
										$id = trim($_REQUEST["id".$i]);	
										$tab = explode("*",trim($id));
										$idanneescolaire = $tab[0];
										$idposition = $tab[1];
										$idsalle = $tab[2];
										$LibelleAnneeScolaire = getLibelleAnneeScolaire($idanneescolaire,$pdo);
										$CodeSalle = getCodeSalle($idsalle,$pdo);
										$LibellePosition = getLibellePosition($idposition,$pdo);
										break;				
									}
								}
								if($id!="")
								{
									include('NumeroConsulter.php');
								}
								else
								{
									$error="Veuiller s&eacute;lectionner la fiche de num&eacute;ros.";
									include('NumeroContenu.php');
								}
							}
							if(isset($_REQUEST["Creer"]))
							{
								include('NumeroAjouter.php');
							}
							else
							{
								include('NumeroContenu.php');
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