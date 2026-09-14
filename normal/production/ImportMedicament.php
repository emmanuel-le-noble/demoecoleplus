<?php 
    session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/liste.php");
	include("../modele/droit.php");
	include("../modele/function.php");
	//
	$error="";
	$success="";
	//
	if(isset($_REQUEST["ValiderImport"]))
	{ 
		extract(filter_input_array(INPUT_POST));
		$fichier=$_FILES["userfile"]["name"];
		if($fichier)
		{
			$fp=fopen($_FILES["userfile"]["tmp_name"],"r");
		}
		else
		{
			?>
			<p align="center"> - Importation echouee -</p>
			<p align="center"> - Desole, mais vous n'avez pas specifie de chemin valide ...</p>
			<?php
			exit();
		}
		$cpt=0;
		//
		$error="- Importation reussie";
		while(!feof($fp))
		{
			$ligne=fgets($fp,4096);
			$liste=explode(";", $ligne);
			$table=filter_input(INPUT_POST, 'userfile');
			
			$liste[0]=(isset($liste[0]))?$liste[0]:Null;
			$liste[1]=(isset($liste[1]))?$liste[1]:Null;
			$liste[2]=(isset($liste[2]))?$liste[2]:Null;
			$liste[3]=(isset($liste[3]))?$liste[3]:Null;
			$liste[4]=(isset($liste[4]))?$liste[4]:Null;
			$liste[5]=(isset($liste[5]))?$liste[5]:Null;
			$liste[6]=(isset($liste[6]))?$liste[6]:Null;
			$liste[7]=(isset($liste[7]))?$liste[7]:Null;
			$liste[8]=(isset($liste[8]))?$liste[8]:Null;
			$liste[9]=(isset($liste[9]))?$liste[9]:Null;
			$liste[9]=(isset($liste[10]))?$liste[10]:Null;
			$liste[9]=(isset($liste[11]))?$liste[11]:Null;
			$liste[9]=(isset($liste[12]))?$liste[12]:Null;
			$liste[9]=(isset($liste[13]))?$liste[13]:Null;
			$liste[9]=(isset($liste[14]))?$liste[14]:Null;
			$liste[9]=(isset($liste[15]))?$liste[15]:Null;
			//
			$id_medicament = $liste[0];
			$code = $liste[1];
			$nomcommercial = $liste[2];
			$dci = $liste[3];
			$classetherapeutique = $liste[4];
			$forme = $liste[5];
			$dosage = $liste[6];
			$presentation = $liste[7];
			$unite_presentation = $liste[8];
			$typeemballage = $liste[9];
			$typemedicament = $liste[9];
			$ppc = $liste[10];
			$pu_ref = $liste[11];
			$unite = $liste[12];
			$prixbase = $liste[13];
			$statut = $liste[14];
			//
			ImportMedicament($id_medicament,$code,$nomcommercial,$dci,$classetherapeutique,$forme,$dosage,$presentation,$unite_presentation,$typeemballage,$typemedicament,$ppc,$pu_ref,$unite,$pdo);
			$cpt++;
		}
		fclose($fp);
		//
		$success="Fichier import&eacute; avec succ&egrave;s.";
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
    </head>
    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
				<form class="form-horizontal form-label-left" method="post" action="" enctype="multipart/form-data">
					<?php 
					    include("menu.php");
					    include("entete.php");
					?>
					<div class="left_col" role="main">
						<?php include('ImportAjouter.php');?>
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
		
		<script>
		  $(document).ready(function() {
			$(".select2_single").select2({
			  placeholder: "Liste",
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