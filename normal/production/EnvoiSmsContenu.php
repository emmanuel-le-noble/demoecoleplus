<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel" style="background-color:#E9F2DF;box-shadow: 8px 8px 0px #aaa;">
					        <div class="x_title">
						        <h2 style="font-family:comic sans ms;font-weight:bold;color:red;">
						        	Envoi sms
						        	<small style="color:#000;font-weight:bold;">
										[Permet de g&eacute;rer les envois de sms]
									</small>
						        </h2>
						        <div class="clearfix"></div>
					        </div>
						    <div class="x_content">
							    <div id="Envoisms">									
									<div>
										<span style="color:#000;font-weight:bold;font-size:14px;text-shadow: 0px 0px 4px rgba(0,0,0,0.75);font-family:comic sans ms">
										Les types d'op&eacute;rations &agrave; effectuer : </span>
										<input type="submit" style="width:120px;padding:2px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-success" name="EnvoyerSms" value="Envoyer sms"/>
										&nbsp;&nbsp;&nbsp;
										<input type="submit" style="width:120px;padding:2px;box-shadow: 8px 8px 0px #aaa;" class="btn btn-round btn-info" name="ConsulterSms" value="Consulter"/>
									</div>									
									<?php ListSmsSend($pdo);?>
								</div>
							</div>
					    </div>
				    </div>
				</div>
            </div>
        </div>
    </body>
</html>