<?php
	include("../modele/connexion.php");
    include("../modele/absences.php");
	
	$matiere = $_REQUEST['val_sel'];
	$tab = explode("*",trim($matiere));
	$idmatiere = $tab[0];
	$idsalle = $tab[1];
	$idanneescolaire = $tab[2];
	$coefficient = $tab[3];
	$idposition = $tab[4];
	$etat = $tab[5];
?>
<table width="100%">
	<tr>
		<td colspan="3" align="left">
			<hr style="border:1px dotted #000;margin-top:0px;margin-bottom:5px;"/>
			<div><span style="color:#000;font-weight:bold;font-size:14px;font-family:comic sans ms">
			::: Veuillez cocher les &eacute;l&egrave;ves absents au cours :::</span></div><br/>
			<?php ListEleveSalle($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);?>
		</td>
	</tr>
</table>
<hr style="border:1px dotted #000;"/>
<div align="center" class="col-md-6 col-md-offset-3">
	<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='AbsencesMatiere.php'"/>
	<input type="submit" class="btn btn-round btn-success" name="AjouterAbsencesMatiereEleve" value="Enregistrer"/> 
</div>