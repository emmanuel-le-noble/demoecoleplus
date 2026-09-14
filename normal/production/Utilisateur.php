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
	if(isset($_POST["EnregistrerUser"]))
	{ 
		$typeutilisateur = trim($_REQUEST["typeutilisateur"]);
		if($typeutilisateur=="Oui")
		{
			$idprof = trim($_REQUEST["idprof"]);
			$tab = explode('*',$idprof);
			$idprofesseur = $tab[0];
			$nomprofesseur = $tab[1];
			$login_user = trim($_REQUEST["login_user"]);
			$mtpass_user = trim($_REQUEST["mtpass_user"]);
			$profil = trim($_REQUEST["profil"]);
			$tab = explode('*',CreateUser($nomprofesseur,"",$login_user,$mtpass_user,$profil,$idprofesseur,$typeutilisateur,$pdo));
			$success = $tab[0];
			$error = $tab[1];
		}
		else
		{
			$nom_user = trim($_REQUEST["nom_user"]);
			$prenom_user = trim($_REQUEST["prenom_user"]);
			$login_user = trim($_REQUEST["login_user"]);
			$mtpass_user = trim($_REQUEST["mtpass_user"]);
			$profil = trim($_REQUEST["profil"]);
			$idutilisateur = time();
			$tab = explode('*',CreateUser($nom_user,$prenom_user,$login_user,$mtpass_user,$profil,$idutilisateur,$typeutilisateur,$pdo));
			$success = $tab[0];
			$error = $tab[1];
		}
	}
	elseif(isset($_POST["ModifierUser"]))
	{ 
		$typeutilisateur = trim($_REQUEST["typeutilisateur"]);
		if($typeutilisateur=="Oui")
		{
			$id_user = trim($_REQUEST["id_user"]);
			$idprof = trim($_REQUEST["idprof"]);
			$tab = explode('*',$idprof);
			$idprofesseur = $tab[0];
			$nomprofesseur = $tab[1];
			$login_user = trim($_REQUEST["login_user"]);
			$mtpass_user = trim($_REQUEST["mtpass_user"]);
			$profil = trim($_REQUEST["profil"]);
			UpdateUser($nomprofesseur,"",$login_user,$mtpass_user,$profil,$id_user,$typeutilisateur,$pdo);
			$success="Cet utilisateur est modifi&eacute; avec succ&egrave;s";
			$error="";
		}
		else
		{
			$id_user = trim($_REQUEST["id_user"]);
			$nom_user = trim($_REQUEST["nom_user"]);
			$prenom_user = trim($_REQUEST["prenom_user"]);
			$login_user = trim($_REQUEST["login_user"]);
			$mtpass_user = trim($_REQUEST["mtpass_user"]);
			$profil = trim($_REQUEST["profil"]);
			UpdateUser($nom_user,$prenom_user,$login_user,$mtpass_user,$profil,$id_user,$typeutilisateur,$pdo);
			$success="Cet utilisateur est modifi&eacute; avec succ&egrave;s";
			$error="";
		}	
	}
	elseif(isset($_POST["Supprimer"]))
	{ 
		$nbre_user = trim($_REQUEST["nbre_user"]);
		for($i=1;$i<=$nbre_user;$i++)
		{		
			if(isset($_REQUEST["id_user".$i]))
			{
				$id_user = trim($_REQUEST["id_user".$i]);	
				DeleteUser($id_user,$pdo);			
			}
		}
		$success="Op&eacute;ration de desactivation de compte effectu&eacute;es avec succ&egrave;s";
		$error="";
	}
	elseif(isset($_POST["Activer"]))
	{ 
		$nbre_user = trim($_REQUEST["nbre_user"]);
		for($i=1;$i<=$nbre_user;$i++)
		{		
			if(isset($_REQUEST["id_user".$i]))
			{
				$id_user = trim($_REQUEST["id_user".$i]);	
				ActiveUser($id_user,$pdo);			
			}
		}
		$success="Op&eacute;ration d'activation de compte effectu&eacute;es avec succ&egrave;s";
		$error="";
	}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
		<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
		<link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet"/>
		<link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet"/>
		<link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet"/>
		<link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet"/>
		<link href="../vendors/starrr/dist/starrr.css" rel="stylesheet"/>
		<link href="../build/css/custom.min.css" rel="stylesheet"/>
				<link href="modern.css" rel="stylesheet"/>
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
						    if(isset($_REQUEST["ChangerMotPasse"]))
							{
								$UtilisateurConnecte = $_REQUEST["UtilisateurConnecte"];
								include('ChangerMotPasse.php');
							}
							elseif(isset($_REQUEST["Modifier"]))
							{
							    $nbre_user = trim($_REQUEST["nbre_user"]);
							    $statut=0;
								for($i=1;$i<=$nbre_user;$i++)
								{		
									if(isset($_REQUEST["id_user".$i]))
									{
										$id_user = trim($_REQUEST["id_user".$i]);
										$statut=1;	
										break;		
									}
								}
								if($statut!=0)
								{
								    include('UtilisateurModifier.php');
								}
								else
								{
								    $error="Veuiller s&eacute;lectionner l'utilisateur.";
									include('UtilisateurContenu.php');
								}								
							}								
							elseif(isset($_REQUEST["Ajouter"]))
							{
								include('UtilisateurAjouter.php');
							}
							else
							{
								include('UtilisateurContenu.php');
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