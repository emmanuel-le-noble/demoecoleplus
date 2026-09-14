<?php
	include("../modele/connexion.php");
    include("../modele/notification.php");
	
	$idsalle = $_REQUEST['val_sel'];
	if($idsalle=="0" OR $idsalle=="-1")
	{
		?><input type="text" name="numero" required="required" style="font-size:12px;" class="form-control" autocomplete="off"/><?php
	}
	else
	{
		?><input type="text" disabled name="numero" required="required" style="font-size:12px;" class="form-control" autocomplete="off"/><?php
	}
?>