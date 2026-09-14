<?php
	session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
    include("../modele/notification.php");
	
	$position = $_REQUEST['val_sel'];
	$tab = explode("*",trim($position));
	$idposition = $tab[0];
	$notification = $tab[1];
	if($notification==1)
	{
		ListBulletinSend($idposition,$_SESSION['idanneescolaire'],$pdo);
	}
	elseif($notification==2)
	{
		ListAbsenceSend($idposition,$_SESSION['idanneescolaire'],$pdo);
	}
	elseif($notification==3)
	{
		?>
		<div>
			<span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
				Choisissez le destinataire et saisissez l'objet d'envoi du SMS :::
			</span></div><br/>
		<table width="100%">
			<tr>
				<td width="50%">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Destinataire(s)<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<?php getAllClasse($pdo);?>
						</div>
					</div>
				</td>
				<td width="50%">
					<div class="form-group">
						<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:right;">Num&eacute;ro(s)<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
						<div class="col-md-8 col-sm-8 col-xs-12" id="numerospecifique">
							<input type="text" name="numero" required="required" style="font-size:12px;" class="form-control" autocomplete="off"/>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="left">
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-12" style="text-align:left;">Objet d'envoi<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
						<div class="col-md-10 col-sm-10 col-xs-12">
							<input type="text" name="objetenvoisms" required="required" style="font-size:12px;" class="form-control" autocomplete="off"/>
						</div>
					</div>
				</td>
			</tr>
		</table>
		<input type="hidden" name="idposition" value="<?php echo $idposition;?>"/>
		<input type="hidden" name="notification" value="<?php echo $notification;?>"/>
		<hr style="border:1px dotted #000;"/>
		<div align="center" class="col-md-6 col-md-offset-3">
			<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='EnvoiSms.php'"/>
			<input type="submit" class="btn btn-round btn-success" name="EnregistrerEnvoirSms" value="Enregistrer"/> 
		</div>
		<?php
	}
?>