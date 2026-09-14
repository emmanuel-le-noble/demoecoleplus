<?php 
    session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/demande_permission.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		header ('location:index.php'); 
		exit;
	}

	$error = "";
	$success = "";
	
	// Traiter une demande
	if(isset($_REQUEST["TraiterDemande"]))
	{
		$idDemande = (int)trim($_REQUEST["id_demande"]);
		$nouveauStatut = trim($_REQUEST["nouveau_statut"]);
		
		if($idDemande > 0 && in_array($nouveauStatut, ['1', '2', '3']))
		{
			traiterDemande($idDemande, $nouveauStatut, $pdo);
			$success = "Demande traitée avec succès.";
		}
		else
		{
			$error = "Paramètres invalides.";
		}
	}

	$demandes = getDemandesPermission($pdo);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title>EcolePlus - Demandes de Permission</title>
		<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
		<link href="../build/css/custom.min.css" rel="stylesheet">
				<link href="modern.css" rel="stylesheet"/>
		<link rel="icon" type="image/x-icon" href="images/OmegaNet.ico"/>
    </head>
    <body class="nav-md">
		<div class="container body">
			<div class="main_container">
				<?php include("Menu.php"); include("Entete.php"); ?>
				<div class="left_col" role="main">
					<div class="right_col" role="main">
						<div class="x_panel">
							<div class="x_title">
								<h2><i class="fa fa-envelope-open"></i> Demandes de Permission des Parents</h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<?php if($error != ""): ?>
									<div class="alert alert-danger"><?php echo $error; ?></div>
								<?php endif; ?>
								<?php if($success != ""): ?>
									<div class="alert alert-success"><?php echo $success; ?></div>
								<?php endif; ?>
								
								<table id="datatable" class="table table-striped table-bordered" width="100%">
									<thead>
										<tr style="background-color:#eee;">
											<th style="width:5%;">#</th>
											<th style="width:12%;">Date demande</th>
											<th style="width:12%;">Élève</th>
											<th style="width:10%;">Classe</th>
											<th style="width:12%;">Parent</th>
											<th style="width:10%;">Date début</th>
											<th style="width:10%;">Date fin</th>
											<th style="width:15%;">Motif</th>
											<th style="width:8%;">Statut</th>
											<th style="width:6%;">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php $ligne = 0; foreach($demandes as $d): $ligne++; ?>
											<tr>
												<td><?php echo $ligne; ?></td>
												<td><?php echo $d['DATE_DEMANDE'] ? date('d/m/Y', strtotime($d['DATE_DEMANDE'])) : '-'; ?></td>
												<td>
													<strong><?php echo htmlspecialchars($d['NOM_ELEVE']); ?></strong>
													<br/><small><?php echo htmlspecialchars($d['PRENOM_ELEVE']); ?></small>
												</td>
												<td><span class="label label-info"><?php echo htmlspecialchars($d['NOMSALLE']); ?></span></td>
												<td>
													<?php echo htmlspecialchars($d['NOM_PARENT']); ?>
													<br/><small class="text-muted"><?php echo htmlspecialchars($d['MAIL_PARENT']); ?></small>
												</td>
												<td><?php echo $d['DATE_DEBUT'] ? date('d/m/Y', strtotime($d['DATE_DEBUT'])) : '-'; ?></td>
												<td><?php echo $d['DATE_FIN'] ? date('d/m/Y', strtotime($d['DATE_FIN'])) : '-'; ?></td>
												<td><small><?php echo htmlspecialchars($d['MOTIF_PERMISSION']); ?></small></td>
												<td>
													<?php
													switch($d['STATUT_PERMISSION'] ?? 0) {
														case 1: echo '<span class="label label-warning"><i class="fa fa-clock-o"></i> En attente</span>'; break;
														case 2: echo '<span class="label label-success"><i class="fa fa-check"></i> Approuvée</span>'; break;
														case 3: echo '<span class="label label-danger"><i class="fa fa-times"></i> Refusée</span>'; break;
														default: echo '<span class="label label-default">Non traitée</span>';
													}
													?>
												</td>
												<td>
													<div class="btn-group btn-group-xs">
														<?php if($d['STATUT_PERMISSION'] != 2): ?>
														<form method="post" action="" style="display:inline;">
															<input type="hidden" name="id_demande" value="<?php echo $d['ID']; ?>"/>
															<input type="hidden" name="nouveau_statut" value="2"/>
															<button type="submit" name="TraiterDemande" class="btn btn-success btn-xs" title="Approuver">
																<i class="fa fa-check"></i>
															</button>
														</form>
														<?php endif; ?>
														<?php if($d['STATUT_PERMISSION'] != 3): ?>
														<form method="post" action="" style="display:inline;">
															<input type="hidden" name="id_demande" value="<?php echo $d['ID']; ?>"/>
															<input type="hidden" name="nouveau_statut" value="3"/>
															<button type="submit" name="TraiterDemande" class="btn btn-danger btn-xs" title="Refuser">
																<i class="fa fa-times"></i>
															</button>
														</form>
														<?php endif; ?>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
										<?php if(empty($demandes)): ?>
											<tr><td colspan="10" class="text-center text-muted">Aucune demande de permission.</td></tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="../vendors/jquery/dist/jquery.min.js"></script>
		<script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
		<script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
		<script src="../build/js/custom.min.js"></script>
		<script>
			$(document).ready(function() {
				$('#datatable').DataTable({ language: { url: '../vendors/fr-FR.json' } });
			});
		</script>
    </body>
</html>
