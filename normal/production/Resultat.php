<?php
	include("../modele/connexion.php");
    include("../modele/function.php");
	include("../modele/liste.php");
	include("../modele/droit.php");
	
	$matiere = $_REQUEST['val_sel'];
	$tab = explode("*",trim($matiere));
	$idmatiere = $tab[0];
	$idsalle = $tab[1];
	$idanneescolaire = $tab[2];
	$coefficient = $tab[3];
	$idposition = $tab[4];
	$etat = $tab[5];
	$statutanneescolaire = getstatutAnneeScolairee($idanneescolaire,$pdo);

	$InfoProfesseur = getMatProf($idanneescolaire,$statutanneescolaire,$idsalle,$idmatiere,$pdo);
	$tab_ = explode("*",trim($InfoProfesseur));
	$nomProfesseur = $tab_[0];
	$idProfesseur = $tab_[1];
?>
<div><span style="font-family:comic sans ms;color:#000;font-weight:bold;">&nbsp;&nbsp;
	<font color="red">*</font>
	Information(s) sur le Coefficient et le professeur enseignant la mati&egrave;re sélectionn&eacute;e :::</span></div>
<table width="100%">
	<tr>
		<td width="33%"> 
			<div class="item form-group">
				<label class="control-label col-md-6 col-sm-6 col-xs-12" style="text-align:left;">&nbsp;
					Coef. : &nbsp;&nbsp;&nbsp;<?php echo $coefficient;?></label>
			<input type="hidden" name="coef" value="<?php echo $coefficient;?>"/>
			<input type="hidden" name="etat" value="<?php echo $etat;?>"/>
			<input type="hidden" name="idmatiere" value="<?php echo $idmatiere;?>"/>
			</div>
		</td>
		<td width="33%" colspan="2"> 
			<div class="item form-group">
				<label class="control-label col-md-8 col-sm-8 col-xs-12" style="text-align:left;">Prof. : <?php echo $nomProfesseur;?></label>
				<input type="hidden" class="form-control" readonly="yes" name="nomprofesseur" value="<?php echo $nomProfesseur;?>"/>
				<input type="hidden" class="form-control" readonly="yes" name="idprofesseur" value="<?php echo $idProfesseur;?>"/>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="left">
			<div><span style="font-family:comic sans ms;color:#000;font-weight:bold;">&nbsp;
			[ Liste des &eacute;l&egrave;ves de la classe ]</span></div>
			<?php 
			if($idposition>=6)
			{
				ListEleveSalle_($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			}
			elseif($idposition==4 OR $idposition==5)
			{
				ListEleveSalleCycleSup($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			}
			elseif($idposition<=3)
			{
				ListEleveSalle($idsalle,$idposition,$idanneescolaire,$idmatiere,$pdo);
			}
			?>
		</td>
	</tr>
</table>
<?php 
	if($idposition>=6)
	{
		?>
		<hr style="border:1px dotted #000;"/>
		<div align="center" class="col-md-6 col-md-offset-3">
			<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Notes.php'"/>
			<input type="submit" class="btn btn-round btn-success" name="AjouterNoteEleve" value="Enregistrer"/> 
		</div>
		<?php 
	}
	elseif($idposition==4 OR $idposition==5)
	{
		?>
		<hr style="border:1px dotted #000;"/>
		<div align="center" class="col-md-6 col-md-offset-3">
			<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='NotesCycleSup.php'"/>
			<input type="submit" class="btn btn-round btn-success" name="AjouterNoteEleve" value="Enregistrer"/> 
		</div>
		<?php
	}
	elseif($idposition<=3)
	{
		?>
		<hr style="border:1px dotted #000;"/>
		<div align="center" class="col-md-6 col-md-offset-3">
			<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Notes.php'"/>
			<input type="submit" class="btn btn-round btn-success" name="AjouterNoteEleve" value="Enregistrer"/> 
		</div>
		<?php
	}
?>