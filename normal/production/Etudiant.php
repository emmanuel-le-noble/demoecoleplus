<?php 
    session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/liste.php");
	include("../modele/droit.php");
	include("../modele/function.php");
	
	if(isset($_REQUEST["enregistrer_etudiant"]))
	{ 
		$salle = trim($_REQUEST["salle"]);
		$nom_eleve = trim($_REQUEST["nom_eleve"]);
		$prenom_eleve = trim($_REQUEST["prenom_eleve"]);
		$date_naissance = trim($_REQUEST["date_naissance"]);

		$sexe = trim($_REQUEST["sexe"]);
		//
		CreateEtudiant($nom_eleve,$prenom_eleve,$sexe,$date_naissance,$salle,$pdo);
	}
	elseif(isset($_REQUEST["modifier_etudiant"]))
	{ 
	    $id_eleve = trim($_REQUEST["id_eleve"]);
		$salle = trim($_REQUEST["salle"]);
		$nom_eleve = trim($_REQUEST["nom_eleve"]);
		$date_naissance = trim($_REQUEST["date_naissance"]);

		$sexe = trim($_REQUEST["sexe"]);
		$statut=0;

		UpdateEtudiant($id_eleve,$nom_eleve,$sexe,$date_naissance,$salle,$statut,$pdo);
	}
	elseif(isset($_REQUEST["desactiver_inscription"]))
	{ 
		$nbre_eleve = trim($_REQUEST["nbre_eleve"]);
		for($i=1;$i<=$nbre_eleve;$i++)
		{		
			if(isset($_REQUEST["id_eleve".$i]))
			{
				$id_eleve = trim($_REQUEST["id_eleve".$i]);	
				$statut=1;
				UpdateEtudiant_($id_eleve,$statut,$pdo);
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
		<title>ECOLEPLUS!|</title>
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
						include("menu.php");
					    include("entete.php");
					?>
					<div class="left_col" role="main">
						<?php  
							if(isset($_POST["modifier_etudiant2"]))
							{
							    $nbre_eleve = trim($_REQUEST["nbre_eleve"]);
								for($i=1;$i<=$nbre_eleve;$i++)
								{		
									if(isset($_REQUEST["id_eleve".$i]))
									{
										$id_eleve = trim($_REQUEST["id_eleve".$i]);	
										break;
									}
								}
								include('ModifierEtudiant.php');
							}
							elseif(isset($_POST["ajouter_inscription"]))
							{
								include('AjouterEtudiant.php');								
							}
							else
							{
								include('EtudiantContenu.php');
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
		</script>
		<script>
			$(document).ready(function() {
				$(":input").inputmask();
			});
		</script>
    </body>
</html>