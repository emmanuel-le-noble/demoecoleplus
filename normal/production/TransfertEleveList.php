<?php
	include("../modele/connexion.php");
    include("../modele/transferteleve.php");
	
	$idclasse = $_REQUEST['val_sel'];
	$idanneescolaire = getidDerniereAnneeScolaire($pdo);
	$tab = explode('*',$idanneescolaire);
	$idanneescolaire = $tab[0];
	$libelleanneescolaire = $tab[1];
	$moyenne_reussite = getClasseMoyenneReussite($idclasse,$idanneescolaire,$pdo);
?>
<div><h2 style="color:red;font-weight:bold">Moyenne de r&eacute;ussite d&eacute;finie dans la classe : <?php echo $moyenne_reussite;?></h1></div>
<br/>
<table width="100%">
	<tr>
		<td valign="top" style="margin-left:5px;">
			<div class="form-group">
				<?php ListEleveATransferer($moyenne_reussite,$idclasse,$idanneescolaire,$pdo);?>
			</div>
		</td>
	</tr>
</table>
<hr/>
<div align="center" class="col-md-6 col-md-offset-3">
	<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='TransfertEleve.php'"/>
	<input id="send" type="submit" class="btn btn-round btn-success" name="ValiderTransfert" value="Valider le transfert" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir faire ce(s) transfert(s) ?');"/> 
</div>