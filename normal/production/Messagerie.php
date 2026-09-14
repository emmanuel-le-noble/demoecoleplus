<?php 
    session_name("ecoleplus");
    session_start();
	
	include("../modele/connexion.php");
	include("../modele/droit.php");
	include("../modele/messagerie.php");
	
	if(!isset($_SESSION['iduser']))
	{
		session_unset();  
		session_destroy(); 
		header ('location:index.php'); 
		exit;
	}

	$idUser = $_SESSION['iduser'];
	$error = "";
	$success = "";
	
	// Créer une conversation avec un parent
	if(isset($_REQUEST["CreerConversation"]))
	{
		$idParent = (int)trim($_REQUEST["idparent"]);
		$titre = trim($_REQUEST["titre_conv"]);
		
		if($idParent > 0 && $titre !== "")
		{
			$existingConv = getConversationWithParent($idParent, $idUser, $pdo);
			if($existingConv > 0)
			{
				header('location:Messagerie.php?conv=' . $existingConv);
				exit;
			}
			
			$idConv = creerConversation($titre, 'PRIVEE', $idUser, $pdo);
			ajouterParticipant($idConv, 'PARENT', $idParent, $pdo);
			header('location:Messagerie.php?conv=' . $idConv);
			exit;
		}
		else
		{
			$error = "Veuillez sélectionner un parent et saisir un titre.";
		}
	}

	$idConvActive = isset($_GET['conv']) ? (int)$_GET['conv'] : 0;
	$conversations = getConversations($idUser, $pdo);
	$messages = [];
	$infoConv = [];
	
	if($idConvActive > 0)
	{
		$messages = getMessages($idConvActive, $idUser, $pdo);
		$infoConv = getConversationInfo($idConvActive, $pdo);
		marquerConversationLue($idConvActive, $idUser, $pdo);
	}
	
	$parents = getParents($pdo);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta charset="utf-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<title>EcolePlus - Messagerie</title>
		<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<link href="../build/css/custom.min.css" rel="stylesheet">
				<link href="modern.css" rel="stylesheet"/>
		<link rel="icon" type="image/x-icon" href="images/OmegaNet.ico"/>
		<style>
			.conv-list { max-height: 500px; overflow-y: auto; }
			.conv-item { cursor: pointer; border-left: 3px solid transparent; transition: all 0.2s; }
			.conv-item:hover { background-color: #f5f5f5; }
			.conv-item.active { background-color: #e8f4f8; border-left-color: #17a2b8; }
			.conv-item .badge { font-size: 10px; }
			.msg-box { max-height: 450px; overflow-y: auto; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px; padding: 10px; }
			.msg-bubble { max-width: 75%; padding: 8px 12px; border-radius: 12px; margin-bottom: 8px; font-size: 13px; word-wrap: break-word; }
			.msg-staff { background: #007bff; color: #fff; margin-left: auto; border-bottom-right-radius: 2px; }
			.msg-parent { background: #fff; color: #333; margin-right: auto; border-bottom-left-radius: 2px; border: 1px solid #ddd; }
			.msg-time { font-size: 10px; opacity: 0.7; margin-top: 2px; }
			.msg-sender { font-size: 10px; font-weight: bold; margin-bottom: 2px; }
			.chat-input { position: sticky; bottom: 0; background: #fff; padding: 10px; border-top: 1px solid #ddd; }
		</style>
    </head>
    <body class="nav-md">
		<div class="container body">
			<div class="main_container">
				<?php include("Menu.php"); include("Entete.php"); ?>
				<div class="left_col" role="main">
					<div class="right_col" role="main">
						<div class="x_panel">
							<div class="x_title">
								<h2><i class="fa fa-comments"></i> Messagerie - Administration</h2>
								<div class="clearfix"></div>
							</div>
							<div class="x_content">
								<?php if($error != ""): ?>
									<div class="alert alert-danger"><?php echo $error; ?></div>
								<?php endif; ?>
								
								<div class="row">
									<!-- Liste des conversations -->
									<div class="col-md-4">
										<button class="btn btn-info btn-block mb-2" data-toggle="modal" data-target="#newConvModal">
											<i class="fa fa-plus"></i> Nouvelle conversation
										</button>
										<div class="conv-list">
											<?php foreach($conversations as $conv): ?>
												<a href="Messagerie.php?conv=<?php echo $conv['ID']; ?>" 
												   class="list-group-item conv-item <?php echo $conv['ID'] == $idConvActive ? 'active' : ''; ?>">
													<div class="d-flex justify-content-between align-items-start">
														<div>
															<strong style="font-size:13px;">
																<i class="fa fa-<?php echo $conv['TYPE_CONV'] == 'PRIVEE' ? 'user' : 'users'; ?>"></i>
																<?php echo htmlspecialchars($conv['TITRE']); ?>
															</strong>
															<br/>
															<small class="text-muted" style="font-size:11px;">
																<?php echo htmlspecialchars(mb_strimwidth($conv['dernier_message'] ?? 'Aucun message', 0, 40, '...')); ?>
															</small>
														</div>
														<div class="text-end">
															<small class="text-muted" style="font-size:10px;">
																<?php echo date('d/m H:i', strtotime($conv['DATE_CREATION'])); ?>
															</small>
															<?php if($conv['non_lus'] > 0): ?>
																<br/><span class="badge bg-danger"><?php echo $conv['non_lus']; ?></span>
															<?php endif; ?>
														</div>
													</div>
												</a>
											<?php endforeach; ?>
											<?php if(empty($conversations)): ?>
												<div class="text-center text-muted py-4">
													<i class="fa fa-inbox fa-2x mb-2"></i><br/>
													Aucune conversation
												</div>
											<?php endif; ?>
										</div>
									</div>
									
									<!-- Zone de chat -->
									<div class="col-md-8">
										<?php if($idConvActive > 0 && !empty($infoConv)): ?>
											<div class="panel panel-default">
												<div class="panel-heading" style="background:#17a2b8;color:#fff;">
													<strong><i class="fa fa-comments"></i> <?php echo htmlspecialchars($infoConv['TITRE']); ?></strong>
													<span class="pull-right badge" style="background:#fff;color:#17a2b8;">
														<?php echo $infoConv['TYPE_CONV']; ?>
													</span>
												</div>
												<div class="panel-body msg-box" id="msgBox">
													<?php foreach($messages as $msg): ?>
														<div class="msg-bubble <?php echo $msg['EXPEDITEUR_TYPE'] == 'STAFF' ? 'msg-staff' : 'msg-parent'; ?>" style="<?php echo $msg['EXPEDITEUR_TYPE'] == 'STAFF' ? 'margin-left:auto;' : 'margin-right:auto;'; ?>">
															<?php if($msg['EXPEDITEUR_TYPE'] != 'STAFF'): ?>
																<div class="msg-sender"><?php echo htmlspecialchars($msg['expediteur_nom']); ?></div>
															<?php endif; ?>
															<div><?php echo nl2br(htmlspecialchars($msg['CONTENU'])); ?></div>
															<div class="msg-time"><?php echo date('d/m/Y H:i', strtotime($msg['DATE_ENVOI'])); ?></div>
														</div>
													<?php endforeach; ?>
													<?php if(empty($messages)): ?>
														<div class="text-center text-muted py-3">Aucun message dans cette conversation.</div>
													<?php endif; ?>
												</div>
												<div class="chat-input">
													<form id="chatForm" method="post" action="">
														<input type="hidden" name="id_conversation" value="<?php echo $idConvActive; ?>"/>
														<div class="input-group">
															<textarea name="message" class="form-control" rows="2" placeholder="Votre message..." required id="msgInput"></textarea>
															<span class="input-group-btn">
																<button type="submit" class="btn btn-info"><i class="fa fa-paper-plane"></i> Envoyer</button>
															</span>
														</div>
													</form>
												</div>
											</div>
										<?php else: ?>
											<div class="text-center text-muted py-5">
												<i class="fa fa-comments fa-3x mb-3"></i><br/>
												<h4>Sélectionnez une conversation</h4>
												<p>ou créez une nouvelle conversation avec un parent.</p>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Modal nouvelle conversation -->
		<div class="modal fade" id="newConvModal" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<form method="post" action="">
						<div class="modal-header" style="background:#17a2b8;color:#fff;">
							<h4 class="modal-title"><i class="fa fa-plus"></i> Nouvelle conversation</h4>
							<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label>Parent</label>
								<select name="idparent" class="form-control" required>
									<option value="">-- Sélectionner un parent --</option>
									<?php foreach($parents as $p): ?>
										<option value="<?php echo $p['ID_PARENT']; ?>">
											<?php echo htmlspecialchars($p['NOM_PARENT'] . ' ' . $p['PRENOM_PARENT'] . ' (' . $p['MAIL_PARENT'] . ')'); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="form-group">
								<label>Titre de la conversation</label>
								<input type="text" name="titre_conv" class="form-control" placeholder="Ex: Comportement de l'élève" required/>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
							<button type="submit" name="CreerConversation" class="btn btn-info"><i class="fa fa-check"></i> Créer</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<script src="../vendors/jquery/dist/jquery.min.js"></script>
		<script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="../build/js/custom.min.js"></script>
		<script>
			$(document).ready(function() {
				var msgBox = document.getElementById('msgBox');
				if(msgBox) msgBox.scrollTop = msgBox.scrollHeight;
				
				$('#chatForm').on('submit', function(e) {
					e.preventDefault();
					var form = $(this);
					var msgInput = $('#msgInput');
					var msg = msgInput.val().trim();
					if(msg === '') return;
					
					$.ajax({
						url: 'EnvoyerMessageAjax.php',
						type: 'POST',
						data: form.serialize(),
						dataType: 'json',
						success: function(data) {
							if(data.ok) {
								var isStaff = true;
								var bubbleClass = 'msg-staff';
								var html = '<div class="msg-bubble ' + bubbleClass + '" style="margin-left:auto;">';
								html += '<div>' + $('<div>').text(msg).html().replace(/\n/g, '<br/>') + '</div>';
								html += '<div class="msg-time">' + data.time + '</div>';
								html += '</div>';
								$('#msgBox').append(html);
								msgInput.val('');
								msgBox.scrollTop = msgBox.scrollHeight;
							}
						}
					});
				});
			});
		</script>
    </body>
</html>
