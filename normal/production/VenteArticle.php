<?php 
    session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/stock.php"); 
	
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
		$nbreligne = trim($_REQUEST["nbreligne"]);
		$statut="-1";
		for($i=1;$i<=$nbreligne;$i++)
		{		
			if(isset($_REQUEST["id".$i]))
			{
				$id = trim($_REQUEST["id".$i]);		
				DeleteArticleSortie($id,$pdo);
			}
		}
		$success="Op&eacute;ration de suppression effectu&eacute;es avec succ&egrave;s";
		$error="";								
	}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
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
		<script src="js/jquery-1.11.2.min.js"></script>
		<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
		<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
		<link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet"/>
		<link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet"/>
		<link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet"/>
		<link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet"/>
		<link href="../vendors/starrr/dist/starrr.css" rel="stylesheet"/>
		<link href="../build/css/custom.min.css" rel="stylesheet"/>
				<link href="modern.css" rel="stylesheet"/>
		<link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
		<link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
		<link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
		<link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
		<link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
		<script>
			$(function () {
	
				$('#idarticle').on('change', function (e) {

				    var tab=$('#idarticle').val().split('*');
					var idarticle = tab[0];
					$('#PrixUnitaire').val(tab[2]);
					$('#QteDispo').val(tab[3]);
					
				});
			});
		</script>
    </head>
    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
				<form method="post" action="">
					<?php 
					    include("Menu.php");
					    include("Entete.php");
					?>
					<div class="left_col" role="main">
						<?php 	
							if(isset($_POST["Modifier"]))
							{
							    $nbreligne = trim($_REQUEST["nbreligne"]);
							    $idligne = "";
								for($i=1;$i<=$nbreligne;$i++)
								{		
									if(isset($_REQUEST["id".$i]))
									{
										$id = trim($_REQUEST["id".$i]);	
										$tab = explode('*',$id);
										$idArticleSortie = $tab[0];
										$ideleve = $tab[1];
										$NomPrenomEleve = getNomPrenomEleve($ideleve, $pdo);
										$NumSortie = $tab[2];
										$DateSortie = $tab[3];
										$tab = explode('-',$DateSortie);
										$DateSortie_ = $tab[2].'/'.$tab[1].'/'.$tab[0];
										$iduser = $_SESSION['iduser'];
										$idligne = "1";
										break;
									}
								}
								if($idligne!="")
								{
									include('VenteArticleAjouterArticle.php');
								}
								else
								{
									$error="Veuillez s&eacute;lectionner la vente.";
									include('VenteArticleContenu.php');
								}								
							}
							elseif(isset($_POST["supprimerArticle"]))
							{
								$ideleve = $_REQUEST["ideleve"];
								$NomPrenomEleve = getNomPrenomEleve($ideleve, $pdo);
								$NumSortie = $_REQUEST["NumSortie"];
								$DateSortie = trim($_REQUEST["DateSortie"]);
								$tab = explode('-',$DateSortie);
								$DateSortie_ = $tab[2].'/'.$tab[1].'/'.$tab[0];
							    $iduser = $_SESSION['iduser'];
								$idArticleSortie = $_REQUEST["idArticleSortie"];
								
								$nbrelignearticleajoute = trim($_REQUEST["nbrelignearticleajoute"]);
								for($i=1;$i<=$nbrelignearticleajoute;$i++)
								{		
									if(isset($_REQUEST["idligne".$i]))
									{
										$tab = explode('*',trim($_REQUEST["idligne".$i]));
									    $idArticleSortie = $tab[0];
										$idArticleSortieArticle = $tab[1];
										DeleteArticleSortieArticle($idArticleSortieArticle,$idArticleSortie,$pdo);
									}
								}
								include('VenteArticleAjouterArticle.php');	
							}
							elseif(isset($_POST["enregistrerDetailBD"]))
							{
								$idArticleSortie = $_REQUEST["idArticleSortie"];
								$ideleve = $_REQUEST["ideleve"];
								$NomPrenomEleve = getNomPrenomEleve($ideleve, $pdo);
								$NumSortie = $_REQUEST["NumSortie"];
								$DateSortie = trim($_REQUEST["DateSortie"]);
								$tab = explode('-',$DateSortie);
								$DateSortie_ = $tab[2].'/'.$tab[1].'/'.$tab[0];
							    $iduser = $_SESSION['iduser'];
								$tab = explode('*',trim($_REQUEST["idarticle"]));
								if($tab[0]!="")
								{
									$idar = $tab[0];
									$prixunitaire = $tab[2];
									$Qtedispo = $tab[3];
									$Quantite = trim($_REQUEST["Qte"]);
								
									$Montant = $prixunitaire*$Quantite;
									$idArticleSortie = CreateArticleSortie($NumSortie, $DateSortie, $statut = 1, $ideleve, $_SESSION['iduser'], $pdo);
									$idArticleSortieArticle = CreateArticleSortieArticle($idArticleSortie, $idar, $Quantite, $Montant, $pdo);
								}
								else
								{
									$error="L'article n'a pas été choisi pour être ajouté à la liste ! Veuillez reprendre l'opération";
									$success="";
								}
								include('VenteArticleAjouterArticle.php');	
							}					
							elseif(isset($_POST["enregistrerBD"]))
							{
								$idArticleSortie = "";
								$ideleve = $_REQUEST["ideleve"];
								$NomPrenomEleve = getNomPrenomEleve($ideleve, $pdo);
								$NumSortie = $_REQUEST["NumSortie"];
								$DateSortie = trim($_REQUEST["DateSortie"]);
								$tab = explode('-',$DateSortie);
								$DateSortie_ = $tab[2].'/'.$tab[1].'/'.$tab[0];
							    $iduser = $_SESSION['iduser'];

								include('VenteArticleAjouterArticle.php');
							}
							elseif(isset($_POST["Ajouter"]))
							{
								$DateDujour = date('Y-m-d');
								include('VenteArticleAjouter.php');
							}
							else
							{
								include('VenteArticleContenu.php');
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
			  placeholder: "-- Choisissez --",
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