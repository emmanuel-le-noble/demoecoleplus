<?php 
    session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/stock.php"); 
	
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
				$tab = explode('*',$id);
				$idArticleSortie = $tab[0];

				deleteArticleSortie($idArticleSortie,$pdo);
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
										$DateSortie_ = $tab[1];
										$NumSortie = $tab[2];
										$MotifSortie = $tab[3];
										$iduser = $_SESSION['iduser'];
										$idligne = "1";
										break;
									}
								}
								if($idligne!="")
								{
									include('SortieArticleAjouterArticle.php');
								}
								else
								{
									$error="Avertissement : Veuillez s&eacute;lectionner la sortie.";
									include('SortieArticleContenu.php');
								}
							}	
							elseif(isset($_POST["supprimerArticle"]))
							{
								$idArticleSortie = $_REQUEST["idArticleSortie"];
								$MotifSortie = $_REQUEST["MotifSortie"];
								$NumSortie = $_REQUEST["NumSortie"];
								$DateSortie_ = trim($_REQUEST["DateSortie"]);
								$tab = explode('/',$DateSortie_);
								$DateSortie = $tab[0].'-'.$tab[1].'-'.$tab[2];
							    $iduser = $_SESSION['iduser'];

								$nbreligne = trim($_REQUEST["nbreligne"]);
								for($i=1;$i<=$nbreligne;$i++)
								{		
									if(isset($_REQUEST["id".$i]))
									{
										$tab = explode('*',trim($_REQUEST["id".$i]));
									    $idArticleSortie = $tab[0];
										$idarticlesortie_article = $tab[1];

										DeleteArticleSortieArticle($idarticlesortie_article, $idArticleSortie, $pdo);
									}
								}
								include('SortieArticleAjouterArticle.php');	
							}
							elseif(isset($_POST["enregistrerDetailBD"]))
							{
								$idArticleSortie = $_REQUEST["idArticleSortie"];
								$MotifSortie = $_REQUEST["MotifSortie"];
								$NumSortie = $_REQUEST["NumSortie"];
								$DateSortie_ = trim($_REQUEST["DateSortie"]);
								$tab = explode('/',$DateSortie_);
								$DateSortie = $tab[2].'-'.$tab[1].'-'.$tab[0];
							    $iduser = $_SESSION['iduser'];
								
								if(!empty($_REQUEST["idarticle"]) && !empty($_REQUEST["Qte"]))
								{
									$tab = explode('*',trim($_REQUEST["idarticle"]));
									$idar = $tab[0];
									$prixunitaire = $tab[2];
									$Qtedispo = $tab[3];
									$Quantite = trim($_REQUEST["Qte"]);
									$Montant = $prixunitaire*$Quantite;

									$idArticleSortie = CreateArticleSortie($NumSortie, $DateSortie, $statut = 1, $MotifSortie, $_SESSION['iduser'], $pdo);
									$idArticleSortieArticle = CreateArticleSortieArticle($idArticleSortie, $idar, $Quantite, $Montant, $pdo);
								}
								else
								{
									$success="";
									$error="Avertissement : ALes champs [ Article(s) ] et [ Quantité de sortie ] sont obligatoires à renseigner !!!";
								}
								include('SortieArticleAjouterArticle.php');	
							}
							elseif(isset($_POST["enregistrerBD"]))
							{
								$idArticleSortie = "";
								$MotifSortie = $_REQUEST["MotifSortie"];
								$NumSortie = $_REQUEST["NumSortie"];
								$DateSortie = trim($_REQUEST["DateSortie"]);
								$tab = explode('-',$DateSortie);
								$DateSortie_ = $tab[2].'/'.$tab[1].'/'.$tab[0];
							    $iduser = $_SESSION['iduser'];

								include('SortieArticleAjouterArticle.php');
							}
							elseif(isset($_POST["Ajouter"]))
							{
								include('SortieArticleAjouter.php');
							}
							else
							{
								include('SortieArticleContenu.php');
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
		  $(document).ready(function() {
			$(".stars").starrr();

			$('.stars-existing').starrr({
			  rating: 4
			});

			$('.stars').on('starrr:change', function (e, value) {
			  $('.stars-count').html(value);
			});

			$('.stars-existing').on('starrr:change', function (e, value) {
			  $('.stars-count-existing').html(value);
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