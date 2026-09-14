<?php
	session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/liste.php");
	include("../modele/droit.php");
	include("../modele/function.php");
	
	$idsalle = $_REQUEST['idsalle'];
	$idanneescolaire = $_REQUEST['idanneescolaire'];
	$idposition = $_REQUEST['idposition'];
	$idetat = $_REQUEST['idetat'];
	$libelleetat = $_REQUEST['libelleetat'];
	$moyenne = $_REQUEST['moyenne'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>EcolePlus|</title>
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
	<link href="../build/css/custom.min.css" rel="stylesheet"/>
				<link href="modern.css" rel="stylesheet"/>
	<link href="../build/css/select.css" rel="stylesheet"/>
	<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
	<link rel="icon" type="image/x-icon" href="images/OmegaNet.ico"/>
</head>
    <body style="font-size:11px;background-color:#fff;">
		<div>
			<div>
				<div>
					<div class="x_title">
						<h2 style="font-family:comic sans ms;font-weight:bold;color:#000;">
							<img src="images/bordjo.jpeg" width="50%" height="110px"/>
							<br/><br/>
							<?php echo $libelleetat. ' : '.$moyenne;?>
						</h2>
						<div class="clearfix"></div>
					</div>
					<div>
						<div class="clearfix"></div>
						<?php
							if($idetat==1 OR $idetat==2)
							{
								TauxReussite($idanneescolaire,$idposition,$idsalle,$idetat,$libelleetat,$moyenne,1,$pdo);
							}
							else
							{
								if($idposition==1 OR $idposition==2 OR $idposition==3)
								{
									
									EvaluationAnnuelleCollege($idanneescolaire,$idposition,$idsalle,$pdo);
								}
								else
								{
									EvaluationAnnuelleLycee($idanneescolaire,$idposition,$idsalle,$pdo);
								}
							}
						?>
					</div>
				</div>
			</div>
		</div>
		<script>window.print();</script>
    </body>
</html>