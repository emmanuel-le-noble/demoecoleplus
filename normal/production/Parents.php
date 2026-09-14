<?php 
    session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/parents_gestion.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		header ('location:index.php'); 
		exit;
	}

	$error = "";
	$success = "";
	
	// Activer/Désactiver un parent
	if(isset($_POST["ToggleStatut"]))
	{
		$idParent = (int)trim($_POST["id_parent"]);
		$nouveauStatut = (int)trim($_POST["nouveau_statut"]);
		
		if($idParent > 0 && in_array($nouveauStatut, [0, 1]))
		{
			toggleStatutParent($idParent, $nouveauStatut, $pdo);
			$success = $nouveauStatut == 1 ? "Compte parent activé avec succès." : "Compte parent désactivé.";
		}
	}
	
	// Réinitialiser le mot de passe
	if(isset($_POST["ResetPassword"]))
	{
		$idParent = (int)trim($_POST["id_parent"]);
		$nouveauMdp = trim($_POST["nouveau_mdp"]);
		
		if($idParent > 0 && strlen($nouveauMdp) >= 6)
		{
			resetPasswordParent($idParent, $nouveauMdp, $pdo);
			$success = "Mot de passe réinitialisé avec succès.";
		}
		else
		{
			$error = "Le mot de passe doit contenir au moins 6 caractères.";
		}
	}

	$parents = getAllParents($pdo);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title>EcolePlus - Gestion des Parents</title>
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
								<h2><i class="fa fa-users"></i> Gestion des Comptes Parents</h2>
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
											<th style="width:4%;">#</th>
											<th style="width:14%;">Nom & Prénom</th>
											<th style="width:10%;">Sexe</th>
											<th style="width:12%;">Téléphone</th>
											<th style="width:14%;">Email</th>
											<th style="width:10%;">Login</th>
											<th style="width:18%;">Enfant(s)</th>
											<th style="width:8%;">Statut</th>
											<th style="width:10%;">Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php $ligne = 0; foreach($parents as $p): $ligne++; ?>
											<tr>
												<td><?php echo $ligne; ?></td>
												<td><strong><?php echo htmlspecialchars($p['NOM_PARENT'] . ' ' . $p['PRENOM_PARENT']); ?></strong></td>
												<td><?php echo htmlspecialchars($p['SEXE_PARENT'] ?? '-'); ?></td>
												<td><?php echo htmlspecialchars($p['TEL_PARENT']); ?></td>
												<td><small><?php echo htmlspecialchars($p['MAIL_PARENT']); ?></small></td>
												<td><code><?php echo htmlspecialchars($p['LOGIN_PARENT']); ?></code></td>
												<td><small><?php echo htmlspecialchars($p['ENFANTS'] ?? 'Aucun'); ?></small></td>
												<td>
													<?php if($p['STATUT_PARENT'] == 1): ?>
														<span class="label label-success"><i class="fa fa-check"></i> Actif</span>
													<?php else: ?>
														<span class="label label-warning"><i class="fa fa-clock-o"></i> Inactif</span>
													<?php endif; ?>
												</td>
												<td>
													<div class="btn-group btn-group-xs">
														<?php if($p['STATUT_PARENT'] == 0): ?>
														<form method="post" action="" style="display:inline;">
															<input type="hidden" name="id_parent" value="<?php echo $p['ID_PARENT']; ?>"/>
															<input type="hidden" name="nouveau_statut" value="1"/>
															<button type="submit" name="ToggleStatut" class="btn btn-success btn-xs" title="Activer">
																<i class="fa fa-check"></i>
															</button>
														</form>
														<?php else: ?>
														<form method="post" action="" style="display:inline;">
															<input type="hidden" name="id_parent" value="<?php echo $p['ID_PARENT']; ?>"/>
															<input type="hidden" name="nouveau_statut" value="0"/>
															<button type="submit" name="ToggleStatut" class="btn btn-warning btn-xs" title="Désactiver">
																<i class="fa fa-ban"></i>
															</button>
														</form>
														<?php endif; ?>
														<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#resetModal<?php echo $p['ID_PARENT']; ?>" title="Réinitialiser le mot de passe">
															<i class="fa fa-key"></i>
														</button>
													</div>
													
													<!-- Modal Reset Password -->
													<div class="modal fade" id="resetModal<?php echo $p['ID_PARENT']; ?>" tabindex="-1">
														<div class="modal-dialog modal-sm">
															<div class="modal-content">
																<form method="post" action="">
																	<div class="modal-header" style="background:#17a2b8;color:#fff;">
																		<h5 class="modal-title"><i class="fa fa-key"></i> Reset MDP</h5>
																		<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
																	</div>
																	<div class="modal-body">
																		<p>Parent : <strong><?php echo htmlspecialchars($p['NOM_PARENT'] . ' ' . $p['PRENOM_PARENT']); ?></strong></p>
																		<input type="hidden" name="id_parent" value="<?php echo $p['ID_PARENT']; ?>"/>
																		<div class="form-group">
																			<label>Nouveau mot de passe</label>
																			<input type="text" name="nouveau_mdp" class="form-control" required minlength="6" value="<?php echo substr(bin2hex(random_bytes(4)), 0, 8); ?>"/>
																		</div>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
																		<button type="submit" name="ResetPassword" class="btn btn-info"><i class="fa fa-save"></i> Enregistrer</button>
																	</div>
																</form>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
										<?php if(empty($parents)): ?>
											<tr><td colspan="9" class="text-center text-muted">Aucun compte parent.</td></tr>
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
