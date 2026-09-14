<!DOCTYPE html>
<html lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title>EcolePlus</title>
		<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
		<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
		<link href="../build/css/custom.min.css" rel="stylesheet"/>
				<link href="modern.css" rel="stylesheet"/>
		<link rel="icon" type="image/x-icon" href="images/OmegaNet.ico"/>
		<link href="design.css" rel="stylesheet"/>
		<style>
			/* Champ avec icône œil à droite */
			.pwd-with-eye {
			  background: url('data:image/svg+xml;utf8,<svg fill="gray" height="18" viewBox="0 0 24 24" width="18" xmlns="http://www.w3.org/2000/svg"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 13c-3.03 0-5.5-2.47-5.5-5.5S8.97 6.5 12 6.5s5.5 2.47 5.5 5.5S15.03 17.5 12 17.5z"/><circle cx="12" cy="12" r="2.5"/></svg>') 
						  no-repeat right 12px center;
			  background-size: 18px;
			  padding-right: 40px;
			  cursor: pointer;
			}

			td img {
			  max-width: 100%;
			  height: auto;
			  display: block;
			}
		</style>
  </head>
  <body class="login">
    <div>
        <a class="hiddenanchor" id="signup"></a>
        <a class="hiddenanchor" id="signin"></a>
        <div class="login_wrapper">
			<div class="animate form login_form">
				<section class="login_content" style="background:#fff;">
				    <img src="images/logo_.jpg" alt="Logo AUGUST LE GRAND" style="height:120px; margin-bottom:20px;">
					<table width="80%" align="center">
						<tr>
							<td>
								<form method="post" action="index.php">
									<h1 style="color:#000;font-weight:bold;">&nbsp;&nbsp; Connectez-vous &nbsp;&nbsp;</h1>

									  <div>
										<input type="text" class="form-control" placeholder="Identifiant" required name="identifiant" autocomplete="off"/>
									  </div>
									  <div>
										<input type="password" id="motpasse" class="form-control pwd-with-eye" placeholder="Mot de passe" required name="motpasse" autocomplete="off"/>
									  </div>
									  <div>
										<?php getAllAnneeScolaire($pdo); ?>
									  </div>
									  <br/><br/><br/>
									<div>
										<button id="send" type="submit" class="btn btn-round btn-primary" name="connexion">
											<b>Connexion</b>
										</button>
									</div>
								<div style="margin-top:15px;">
									<a href="../../portail-parent/auth/login.php" style="color:#73879C;font-size:14px;text-decoration:none;">
										<i class="fa fa-users"></i> Espace Parent
									</a>
								</div>
								<div class="clearfix"></div>
								<div class="separator">
									<div class="clearfix"></div>
									<div>
									<p>
										Copyright 2024-2026 Tous droits r&eacute;serv&eacute;s.<br/> 
										<span style="color:#4f46e5;font-weight:bold;">Ecole <sup>+</sup></span> 
										: Gestion des &eacute;tablissements scolaires.
									</p>
									</div>
								</div>
									<?php if($error!="") { ?>
									<div class="alert alert-danger alert-dismissible fade in" role="alert" 
										 style="width:100%;padding:5px;font-size:13px;border:1px solid #fff;">
									  <?php echo $error; ?>
									</div>
								  <?php } ?>
								</form>
							</td>
						</tr>
					</table>
				</section>
			</div>
        </div>
    </div>
	<script>
    const pwdInput = document.getElementById('motpasse');
    let isVisible = false;
    pwdInput.addEventListener('click', (e) => {
      const iconZone = pwdInput.offsetWidth - 40;
      if (e.offsetX > iconZone) {
        isVisible = !isVisible;
        pwdInput.type = isVisible ? 'text' : 'password';
      }
    });
   </script>
  </body>
</html>