<?php 
    session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/decisionconseil.php");
	include("../modele/droit.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		
		header ('location:index.php'); 
	}
	$error="";
	$success="";
	if(isset($_REQUEST["EnregistrerDecision"]))
	{ 
		$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
		$idposition = trim($_REQUEST["idposition"]);
		$idclasse = trim($_REQUEST["idclasse"]);
		$moyenne = trim($_REQUEST["moyenne"]);
		$dateconseil = trim($_REQUEST["dateconseil"]);
		$tab = explode("/",trim($dateconseil));
		$dateconseil = $tab[2]."-".$tab[1]."-".$tab[0];
		$fichier = $_FILES["image"];
		$dossier = 'decisiondocument/';
		$fichier = basename($_FILES['image']['name']);
		if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichier))
		{
			//'Upload effectué avec succès!';
		}
		else
		{
			//'Echec de l'upload!';
		}
		$tab = explode('*',CreateDecision($idposition,$idanneescolaire,$dateconseil,$idclasse,$moyenne,$fichier,$pdo));
		$success = $tab[0];
		$error = $tab[1];
	}
	elseif(isset($_REQUEST["ModifierDecision"]))
	{
		$fichierold = trim($_REQUEST["fichierold"]);
		$iddecision = trim($_REQUEST["iddecision"]);
		$idanneescolaire = trim($_REQUEST["idanneescolaire"]);
		$idposition = trim($_REQUEST["idposition"]);
		$idclasse = trim($_REQUEST["idclasse"]);
		$moyenne = trim($_REQUEST["moyenne"]);
		$dateconseil = trim($_REQUEST["dateconseil"]);
		$tab = explode("/",trim($dateconseil));
		$dateconseil = $tab[2]."-".$tab[1]."-".$tab[0];
		$fichier = $_FILES["image"];
		$dossier = 'decisiondocument/';
		$fichier = basename($_FILES['image']['name']);
		if($fichierold!=$fichier)
		{
			if(move_uploaded_file($_FILES['image']['tmp_name'], $dossier . $fichier))
			{
				//'Upload effectué avec succès!';
			}
			else
			{
				//'Echec de l'upload!';
			}
		}
		else
		{
			$fichier=$fichierold;
		}
		$tab = explode('*',UpdateDecision($iddecision,$idposition,$idanneescolaire,$dateconseil,$idclasse,$moyenne,$fichier,$pdo));
		$success = $tab[0];
		$error = $tab[1];
	}
	elseif(isset($_POST["Supprimer"]))
	{
		$nbredecision=trim($_REQUEST["nbredecision"]);
		$iddecision=0;
		$reponse=0;
		for($i=1;$i<=$nbredecision;$i++)
		{		
			if(isset($_REQUEST["iddecision".$i]))
			{
				$id=trim($_REQUEST["iddecision".$i]);
				$tab=explode('*',$id);
				$iddecision=$tab[0];
				$idclasse=$tab[1];
				$classepriorite=$tab[2];
				$idanneescolaire=$tab[3];
				$moyenne=$tab[4];
				$passageclassesup=$tab[5];
				if($passageclassesup=="")
				{
					$reponse=1;
					DeleteDecision($iddecision,$pdo);
				}
			}
		}
		if($reponse==1)
		{
			$error="";
			$success="Suppression r&eacute;ussie avec succ&egrave;s";
		}
		else
		{
			$error="Echec de suppression";
			$success="";
		}
	}
	/*elseif(isset($_POST["ExecuterDecision"]))
	{
		$executetransfert=0;
		$idnouvelleanneescolaire=getidAnneeScolaire($pdo);
		$nbredecision=trim($_REQUEST["nbredecision"]);
		$iddecision=0;
		for($i=1;$i<=$nbredecision;$i++)
		{		
			if(isset($_REQUEST["iddecision".$i]))
			{
				$id=trim($_REQUEST["iddecision".$i]);
				$tab=explode('*',$id);
				$iddecision=$tab[0];
				$idclasse=$tab[1];
				$classepriorite=$tab[2];
				$idanneescolaire=$tab[3];
				$moyenne=$tab[4];
				$passageclassesup=$tab[5];
				$idposition=$tab[6];
				if($idnouvelleanneescolaire>$idanneescolaire)
				{
					$executetransfert=1;
					TransfertClassSuperieur($idanneescolaire,$idnouvelleanneescolaire,$idclasse,$idposition,$classepriorite,$moyenne,$pdo);
				}
				else
				{
					break;
				}
			}
		}
		if($executetransfert==1)
		{
			$error="";
			$success="Transfert r&eacute;ussi avec succ&egrave;s";
		}
		else
		{
			$error="Echec de transfert. Veuillez cr&eacute;er une nouvelle ann&eacute;e scolaire";
			$success="";
		}
	}*/
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title>EcolePlus</title>
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
		<link href="../build/css/custom.min.css" rel="stylesheet"/>
				<link href="modern.css" rel="stylesheet"/>
		<link href="../build/css/select.css" rel="stylesheet"/>
		<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
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
							if(isset($_POST["ExecuterDecision"]))
							{
								$id="";
								$nbredecision = trim($_REQUEST["nbredecision"]);
								for($i=1;$i<=$nbredecision;$i++)
								{		
									if(isset($_REQUEST["iddecision".$i]))
									{
										$id=trim($_REQUEST["iddecision".$i]);
										$tab=explode('*',$id);
										$iddecision=$tab[0];
										$idclasse=$tab[1];
										$classepriorite=$tab[2];
										$idanneescolaire=$tab[3];
										$passageclassesup=$tab[5];
				                    }
								}
								if($id!="")
								{
									if($passageclassesup!="")
									{
										$error="Impossible de modifier. D&eacute;cision d&eacute;j&agrave; modifier.";
										include('DecisionConseilContenu.php');
									}
									else
									{
										include('DecisionConseilPassageEnClasseSuperieur.php');
									}
								}
								else
								{
									$error="Veuiller s&eacute;lectionner la d&eacute;cision &agrave; modifier.";
									include('DecisionConseilContenu.php');
								}							
							}
							elseif(isset($_POST["Modifier"]))
							{
								$id="";
								$nbredecision = trim($_REQUEST["nbredecision"]);
								for($i=1;$i<=$nbredecision;$i++)
								{		
									if(isset($_REQUEST["iddecision".$i]))
									{
										$id=trim($_REQUEST["iddecision".$i]);
										$tab=explode('*',$id);
										$iddecision=$tab[0];
										$idclasse=$tab[1];
										$classepriorite=$tab[2];
										$idanneescolaire=$tab[3];
										$passageclassesup=$tab[5];
				                    }
								}
								if($id!="")
								{
									if($passageclassesup!="")
									{
										$error="Impossible de modifier. D&eacute;cision d&eacute;j&agrave; modifier.";
										include('DecisionConseilContenu.php');
									}
									else
									{
										include('DecisionConseilModifier.php');
									}
								}
								else
								{
									$error="Veuiller s&eacute;lectionner la d&eacute;cision &agrave; modifier.";
									include('DecisionConseilContenu.php');
								}							
							}
							elseif(isset($_POST["Ajouter"]))
							{
								include('DecisionConseilAjouter.php');								
							}
							else
							{
								include('DecisionConseilContenu.php');
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