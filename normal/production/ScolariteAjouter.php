<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un frais de scolarité</title>
    <style>
        .form-control-grand {
            height: 46px;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 8px;
        }

        label {
            font-weight: bold;
            font-size: 14px;
        }

        .x_panel {
            
            box-shadow: 8px 8px 0px #aaa;
            padding: 20px;
        }

        h2 {
            font-family: 'Comic Sans MS', cursive;
            font-weight: bold;
        }

        .info-text {
            color: #000;
            font-weight: bold;
            font-size: 16px;
            font-family: 'Comic Sans MS', cursive;
        }

        hr.dotted {
            border: 1px dotted orange;
        }
    </style>
</head>
<body class="nav-md">
    <div class="right_col" role="main">
        <div class="">
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
							<h1 style="font-family:comic sans ms;font-weight:bold;color:#000;">
						        Ajouter un nouveau frais de scolarit&eacute;
						    </h1>
							<div class="clearfix"></div>
                        </div>
					    <div class="x_content">
						<div class="clearfix"></div>
							<div><span style="color:#000;font-weight:bold;font-size:16px;font-family:comic sans ms">
							[ Veuillez renseigner les information(s) ]</span></div>
							<hr style="border:1px dotted orange;"/>
							<table width="100%">
								<tr>	
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<?php getAllAnneeScolaire($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Classe</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getAllClasse($pdo);?>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Frais</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
											    <?php getListTypePaiement($pdo);?>
											</div>
										</div>
									</td>
									<td>
										<div class="form-group">
											<label class="control-label col-md-5 col-sm-5 col-xs-12" style="text-align:left;">Montant</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" class="form-control form-control-grand" name="montant" id="montant" style="border-radius:6px;"/>
											</div>
										</div>
									</td>
								</tr>
							</table>
							<table width="100%">
								<tr>	
									<td>
										<div class="form-group">
											<?php ListTranche($pdo);?>
										</div>
									</td>
								</tr>
							</table>
							<hr style="border:1px dotted orange;"/>
							<div align="center" class="col-md-6 col-md-offset-3">
								<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Scolarite.php'"/>
								<input id="send" type="submit" class="btn btn-round btn-success" name="EnregistrerScolarite" value="Enregistrer" onclick=""/> 
							</div>
					    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>