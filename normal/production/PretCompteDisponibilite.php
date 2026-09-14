<?php
	session_name("ecoleplus");
    session_start();
	include("../modele/connexion.php");
    include("../modele/pret.php");
	
	$idcompte=$_REQUEST['val_sel'];
	$disponibilite=getentreecompte($_SESSION['idanneescolaire'],$idcompte,$pdo)-getsortiecompte($_SESSION['idanneescolaire'],$idcompte,$pdo);
?>
<input type="text" class="form-control" style="font-size:12px;background-color:#A6C700;" name="disponibilite" autocomplete="off" value="<?php echo number_format($disponibilite,0,""," ");?>"/>
