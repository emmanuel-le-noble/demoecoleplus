<?php 
    session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/permission.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		
		header ('location:index.php'); 
	}
	
	$error="";
	$success="";
	if (isset($_POST['EnregistrerPermission'])) 
	{
		$idprod = $_POST['idprof'];
		$datedebut = $_POST['datedebut'];
		$datefin = $_POST['datefin'];
		$motif = trim($_POST['motif']);
		$datedemande = $_POST['datedemande'];
		$statut = "En attente";
		$idusercreate = $_SESSION['iduser'] ?? null;

		//Traitement du fichier
		$fichier_nom = "";
		if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) 
		{
			$uploads_dir = "decisiondocument/";
			if (!is_dir($uploads_dir)) {
				mkdir($uploads_dir, 0777, true);
			}
			$fichier_tmp = $_FILES['fichier']['tmp_name'];
			$fichier_nom = time() . "_" . basename($_FILES['fichier']['name']);
			move_uploaded_file($fichier_tmp, $uploads_dir . $fichier_nom);
		}
		$tab = explode('*',trim(createPermission($idprod, $datedemande, $datedebut, $datefin, $motif, $statut, $fichier_nom, $idusercreate, $pdo)));
		$success = $tab[0];
		$error = $tab[1];
	}
	elseif (isset($_POST['ModifierPermission'])) 
	{
		$id = $_POST['id'];
		$idprod = $_POST['idprof'];
		$datedebut = $_POST['datedebut'];
		$datefin = $_POST['datefin'];
		$motif = trim($_POST['motif']);
		$statut = "En attente";
		// Récupération de l'ancien fichier (si besoin de le supprimer)
		$stmtOld = $pdo->prepare("SELECT FICHIER FROM permission WHERE ID = :id");
		$stmtOld->execute(['id' => $id]);
		$oldData = $stmtOld->fetch(PDO::FETCH_ASSOC);
		$ancienFichier = $oldData['FICHIER'] ?? "";
		// Upload d’un nouveau fichier si fourni
		$fichier_nom = $ancienFichier;
		if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) 
		{
			$uploads_dir = "decisiondocument/";
			if (!is_dir($uploads_dir)) {
				mkdir($uploads_dir, 0777, true);
			}
			// Supprimer l'ancien fichier s'il existe
			if (!empty($ancienFichier) && file_exists($uploads_dir . $ancienFichier)) {
				unlink($uploads_dir . $ancienFichier);
			}

			$fichier_tmp = $_FILES['fichier']['tmp_name'];
			$fichier_nom = time() . "_" . basename($_FILES['fichier']['name']);
			move_uploaded_file($fichier_tmp, $uploads_dir . $fichier_nom);
		 }

		 // Mise à jour
		 $tab = explode('*',trim(updatePermission($id, $idprod, $datedebut, $datefin, $motif, $statut, $fichier_nom, $pdo)));
		 $success = $tab[0];
		 $error = $tab[1];
	}
	elseif(isset($_POST["Supprimer"]))
	{
		$nbrepermission = $_REQUEST['nbrepermission'];
		for($i=1;$i<=$nbrepermission;$i++)
		{		
			if(isset($_REQUEST["id".$i]))
			{
				$id = trim($_REQUEST["id".$i]);	
                deletePermission($id, $pdo);				
			}
		}
		$success="Op&eacute;ration de suppression effectu&eacute;es avec succ&egrave;s";
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
		<link rel="icon" type="image/x-icon" href="images/OmegaNet.ico"/>
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
								$nbrepermission = $_REQUEST['nbrepermission'];
								$idligne="";
								for($i=1;$i<=$nbrepermission;$i++)
								{		
									if(isset($_REQUEST["id".$i]))
									{
										$id = trim($_REQUEST["id".$i]);
										$idligne="1";
										break;									
									}
								}
								//
								if($idligne!="")
								{
									include('PermissionModifier.php');
								}
								else
								{
									$error="Veuillez s&eacute;lectionner la permission.";
									include('PermissionContenu.php');
								}									
							}							
							elseif(isset($_POST["Ajouter"]))
							{
								include('PermissionAjouter.php');									
							}
							else
							{
								include('PermissionContenu.php');
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
			  placeholder: "Liste des professeurs",
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