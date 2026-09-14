<?php
	include("../modele/connexion.php");
	include("../modele/function.php");
	
	$id=$_REQUEST['val_sel'];
?>
<div class="item form-group">
	<label class="control-label col-md-4 col-sm-4 col-xs-12" for="name" style="text-align:left;">Droit d'inscription<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
	<div class="col-md-6 col-sm-6 col-xs-12">
		<input type="text" readonly="yes" name="montantdroit" value="<?php echo number_format(getDroitInscription($id,$pdo),0,""," ");?>" class="form-control"/>
	</div>
</div>