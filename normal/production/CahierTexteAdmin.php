<?php 
    session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/cahier_texte_gestion.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		header ('location:index.php'); 
		exit;
	}

	$idanneescolaire = $_SESSION['idanneescolaire'] ?? 1;
	$error = "";
	$success = "";
	
	// Supprimer
	if(isset($_POST["SupprimerCahierTexte"]))
	{
		$idCT = (int)trim($_POST["id_cahier_texte"]);
		if($idCT > 0) { deleteCahierTexte($idCT, $pdo); $success = "Entrée supprimée."; }
	}
	
	// Ajouter / Modifier
	if(isset($_POST["EnregistrerCahierTexte"]))
	{
		$idCT = (int)($_POST["id_cahier_texte"] ?? 0);
		$idSalle = (int)trim($_POST["idsalle"]);
		$idMatiere = (int)trim($_POST["idmatiere"]);
		$idProf = (int)trim($_POST["idprof"]);
		$dateCours = trim($_POST["date_cours"]);
		$contenuCours = trim($_POST["contenu_cours"]);
		$devoirs = trim($_POST["devoirs"]);
		$dateEcheance = trim($_POST["date_echeance"]);
		
		if($contenuCours === "") { $error = "Le contenu du cours est obligatoire."; }
		else
		{
			if($idCT > 0) { updateCahierTexte($idCT, $dateCours, $contenuCours, $devoirs, $dateEcheance, $pdo); $success = "Entrée modifiée."; }
			else { createCahierTexte($idSalle, $idMatiere, $idProf, $idanneescolaire, $dateCours, $contenuCours, $devoirs, $dateEcheance, $pdo); $success = "Entrée créée."; }
		}
	}

	$mode = "list";
	$ctData = null;
	if(isset($_GET["action"]))
	{
		if($_GET["action"] == "ajouter") { $mode = "form"; }
		elseif($_GET["action"] == "modifier" && isset($_GET["id"]))
		{
			$mode = "form";
			$ctData = getCahierTexteById((int)$_GET["id"], $pdo);
		}
	}

	$entries = getAllCahierTexte($idanneescolaire, $pdo);
	$matieres = getMatieres($pdo);
	$professeurs = getProfesseurs($pdo);
	$salles = getSalles($idanneescolaire, $pdo);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title>EcolePlus - Cahier de Texte</title>
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
								<h2><i class="fa fa-book-open"></i> Cahier de Texte</h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<?php if($error != ""): ?>
									<div class="alert alert-danger"><?php echo $error; ?></div>
								<?php endif; ?>
								<?php if($success != ""): ?>
									<div class="alert alert-success"><?php echo $success; ?></div>
								<?php endif; ?>
								
								<?php if($mode == "list"): ?>
									<a href="CahierTexteAdmin.php?action=ajouter" class="btn btn-success btn-sm mb-3">
										<i class="fa fa-plus"></i> Ajouter une entrée
									</a>
									<table id="datatable" class="table table-striped table-bordered" width="100%">
										<thead>
											<tr style="background-color:#eee;">
												<th style="width:5%;">#</th>
												<th style="width:10%;">Date cours</th>
												<th style="width:12%;">Classe</th>
												<th style="width:12%;">Matière</th>
												<th style="width:12%;">Professeur</th>
												<th style="width:25%;">Contenu du cours</th>
												<th style="width:15%;">Devoirs</th>
												<th style="width:9%;">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php $ligne = 0; foreach($entries as $e): $ligne++; ?>
												<tr>
													<td><?php echo $ligne; ?></td>
													<td><?php echo $e['DATE_COURS'] ? date('d/m/Y', strtotime($e['DATE_COURS'])) : '-'; ?></td>
													<td><span class="label label-info"><?php echo htmlspecialchars($e['NOMSALLE']); ?></span></td>
													<td><?php echo htmlspecialchars($e['NOM_MATIERE']); ?></td>
													<td><small><?php echo htmlspecialchars($e['PROFESSEUR']); ?></small></td>
													<td><small><?php echo htmlspecialchars(mb_strimwidth($e['CONTENU_COURS'], 0, 80, '...')); ?></small></td>
													<td><small><?php echo htmlspecialchars(mb_strimwidth($e['DEVOIRS_A_FAIRE'] ?? '', 0, 50, '...')); ?></small></td>
													<td>
														<a href="CahierTexteAdmin.php?action=modifier&id=<?php echo $e['ID']; ?>" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
														<form method="post" action="" style="display:inline;" onsubmit="return confirm('Supprimer cette entrée ?');">
															<input type="hidden" name="id_cahier_texte" value="<?php echo $e['ID']; ?>"/>
															<button type="submit" name="SupprimerCahierTexte" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
														</form>
													</td>
												</tr>
											<?php endforeach; ?>
											<?php if(empty($entries)): ?>
												<tr><td colspan="8" class="text-center text-muted">Aucune entrée dans le cahier de texte.</td></tr>
											<?php endif; ?>
										</tbody>
									</table>
								<?php else: ?>
									<h3><?php echo $ctData ? 'Modifier' : 'Ajouter'; ?> une entrée</h3>
									<hr/>
									<form method="post" action="" class="form-horizontal">
										<input type="hidden" name="id_cahier_texte" value="<?php echo $ctData['ID'] ?? 0; ?>"/>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Classe</label>
													<select name="idsalle" class="form-control" required>
														<option value="">-- Sélectionner --</option>
														<?php foreach($salles as $s): ?>
															<option value="<?php echo $s['ID']; ?>" <?php echo ($ctData['IDSALLE'] ?? '') == $s['ID'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($s['NOMSALLE']); ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="form-group">
													<label>Matière</label>
													<select name="idmatiere" class="form-control" required>
														<option value="">-- Sélectionner --</option>
														<?php foreach($matieres as $m): ?>
															<option value="<?php echo $m['ID_MATIERE']; ?>" <?php echo ($ctData['IDMATIERE'] ?? '') == $m['ID_MATIERE'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($m['NOM_MATIERE']); ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="form-group">
													<label>Professeur</label>
													<select name="idprof" class="form-control" required>
														<option value="">-- Sélectionner --</option>
														<?php foreach($professeurs as $pr): ?>
															<option value="<?php echo $pr['ID']; ?>" <?php echo ($ctData['IDPROF'] ?? '') == $pr['ID'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($pr['NOM']); ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Date du cours</label>
													<input type="date" name="date_cours" class="form-control" value="<?php echo $ctData['DATE_COURS'] ?? date('Y-m-d'); ?>" required/>
												</div>
												<div class="form-group">
													<label>Date d'échéance des devoirs</label>
													<input type="date" name="date_echeance" class="form-control" value="<?php echo $ctData['DATE_ECHEANCE'] ?? ''; ?>"/>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Contenu du cours</label>
											<textarea name="contenu_cours" class="form-control" rows="5" required><?php echo htmlspecialchars($ctData['CONTENU_COURS'] ?? ''); ?></textarea>
										</div>
										<div class="form-group">
											<label>Devoirs à faire</label>
											<textarea name="devoirs" class="form-control" rows="3"><?php echo htmlspecialchars($ctData['DEVOIRS_A_FAIRE'] ?? ''); ?></textarea>
										</div>
										<div class="text-center">
											<a href="CahierTexteAdmin.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Retour</a>
											<button type="submit" name="EnregistrerCahierTexte" class="btn btn-success"><i class="fa fa-save"></i> Enregistrer</button>
										</div>
									</form>
								<?php endif; ?>
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
			$(document).ready(function() { $('#datatable').DataTable({ language: { url: '../vendors/fr-FR.json' } }); });
		</script>
    </body>
</html>
