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
	if(isset($_REQUEST["ValiderImport"]))
	{
		$idsalle = trim($_REQUEST["idsalle"]);
		$idclasse = trim(getIdClasseForSalle($idsalle,$pdo));
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
		$error="- Importation reussie";
		while(!feof($fp))
		{
			$ligne=fgets($fp,4096);
			$liste=explode(",", $ligne);
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
			
			$id_eleve=$liste[0];
			$nom_eleve=$liste[1];
			$prenom_eleve=$liste[2];
			$etat_classe="Nouveau";
			$idetatclasse = getIdEleveStatutClasse($etat_classe,$pdo);
			$etat_etablissement="Nouveau";
			$idetatetablissement = getIdEleveStatutEtablissement($etat_etablissement,$pdo);
			$sexe_eleve="";
			$datenaissance_eleve="";
			$teltuteur="";
			$mailtuteur="";
			$matricule="";
			if($nom_eleve!="" AND $prenom_eleve!="")
			{
				ImportEleve(null,$matricule,$mailtuteur,$teltuteur,$nom_eleve,$prenom_eleve,$sexe_eleve,$idetatclasse,$idetatetablissement,$datenaissance_eleve,$idclasse,$idsalle,$_SESSION['idanneescolaire'],$pdo);
				$cpt++;
			}
		}
		fclose($fp);
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
				<form class="form-horizontal form-label-left" method="post" action="" enctype="multipart/form-data">
					<?php 
					    include("Menu.php");
					    include("Entete.php");
					?>
					<div class="left_col" role="main">
						<?php include('ImportAjouter.php');?>
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