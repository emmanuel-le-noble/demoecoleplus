<?php
	include("../modele/connexion.php");
    include("../modele/function.php");
	include("../modele/liste.php");
	
	$idclasse = $_REQUEST['val_sel'];
	$montant = getMontantTypePaiement(3,$idclasse,$pdo);
?>
<input type="text" class="form-control"  name="montantinscript" readonly="yes" value="<?php echo $montant;?>"/>