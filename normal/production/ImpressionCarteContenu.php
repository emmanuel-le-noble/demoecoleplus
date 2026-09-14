<!DOCTYPE html>
<html lang="en">
<head></head>
    <body class="nav-md">
        <div class="right_col" role="main">
            <div class="">
                <div class="clearfix"></div>
				<div class="row">
				    <div class="col-md-12">
					    <div class="x_panel" style="box-shadow:8px 8px 0px #aaa;">
					        <div class="x_title">
						        <h2 style="font-family:comic sans ms;font-weight:bold">
						        	N° table(s) et anonymat(s)
						        	<small style="color:#000;font-weight:bold;">
										[Permet de générer et d'historiser les numéros de tables & anonymats des &eacute;l&egrave;ves]
									</small>
						        </h2>
						        <div class="clearfix"></div>
					        </div>
							<div class="x_content">
								<div class="clearfix"></div>
									<div><span style="color:#000;font-weight:bold;font-size:16px;">
									[ Veuillez renseigner les information(s) ]</span></div><br/>
									<table width="100%">
										<tr>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Ann&eacute;e scolaire<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllAnneeScolaire($pdo);?>
													</div>
												</div>
											</td>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Evaluation<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllPosition($pdo);?>
													</div>
												</div>
											</td>
											<td>
												<div class="item form-group">
													<label class="control-label col-md-4 col-sm-4 col-xs-12" style="text-align:left;">Classe<span class="required">&nbsp;&nbsp;<font color="red">*</font></span></label>
													<div class="col-md-6 col-sm-6 col-xs-12">
														<?php getAllSalle($pdo);?>
													</div>
												</div>
											</td>
										</tr>
									</table>
									<hr style="border:1px dotted #000;"/>
									<div><span style="color:#000;font-weight:bold;font-size:16px;">
									[ Liste des &eacute;l&egrave;ves ]</span></div><br/>
									<div id="ListEleve"></div>
									<div align="center" class="col-md-6 col-md-offset-3">
										<input type="button" class="btn btn-round btn-primary" value="Fermer" onclick="document.location='Numero.php'"/>
										<input type="submit" class="btn btn-round btn-success" name="CreerNumero" value="Générer"/> 
									</div>							
							</div>
						</div>
					</div>
				</div>
				<div class="row">
				  <div class="col-md-12">
					<div class="x_panel">
					  <div class="x_content">
						<div class="row">
						  <div class="col-md-12 col-sm-12 col-xs-12 text-center">
							<ul class="pagination pagination-split">
							  <li><a href="#">A</a></li>
							  <li><a href="#">B</a></li>
							  <li><a href="#">C</a></li>
							  <li><a href="#">D</a></li>
							  <li><a href="#">E</a></li>
							  <li>...</li>
							  <li><a href="#">W</a></li>
							  <li><a href="#">X</a></li>
							  <li><a href="#">Y</a></li>
							  <li><a href="#">Z</a></li>
							</ul>
						  </div>

						  <div class="clearfix"></div>

						  <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
							<div class="well profile_view">
							  <div class="col-sm-12">
								<h4 class="brief"><i>Digital Strategist</i></h4>
								<div class="left col-xs-7">
								  <h2>Nicole Pearson</h2>
								  <p><strong>About: </strong> Web Designer / UX / Graphic Artist / Coffee Lover </p>
								  <ul class="list-unstyled">
									<li><i class="fa fa-building"></i> Address: </li>
									<li><i class="fa fa-phone"></i> Phone #: </li>
								  </ul>
								</div>
								<div class="right col-xs-5 text-center">
								  <img src="images/img.jpg" alt="" class="img-circle img-responsive">
								</div>
							  </div>
							  <div class="col-xs-12 bottom text-center">
								<div class="col-xs-12 col-sm-6 emphasis">
								  <p class="ratings">
									<a>4.0</a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star-o"></span></a>
								  </p>
								</div>
								<div class="col-xs-12 col-sm-6 emphasis">
								  <button type="button" class="btn btn-success btn-xs"> <i class="fa fa-user">
									</i> <i class="fa fa-comments-o"></i> </button>
								  <button type="button" class="btn btn-primary btn-xs">
									<i class="fa fa-user"> </i> View Profile
								  </button>
								</div>
							  </div>
							</div>
						  </div>

						  <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
							<div class="well profile_view">
							  <div class="col-sm-12">
								<h4 class="brief"><i>Digital Strategist</i></h4>
								<div class="left col-xs-7">
								  <h2>Nicole Pearson</h2>
								  <p><strong>About: </strong> Web Designer / UI. </p>
								  <ul class="list-unstyled">
									<li><i class="fa fa-building"></i> Address: </li>
									<li><i class="fa fa-phone"></i> Phone #: </li>
								  </ul>
								</div>
								<div class="right col-xs-5 text-center">
								  <img src="images/user.png" alt="" class="img-circle img-responsive">
								</div>
							  </div>
							  <div class="col-xs-12 bottom text-center">
								<div class="col-xs-12 col-sm-6 emphasis">
								  <p class="ratings">
									<a>4.0</a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star-o"></span></a>
								  </p>
								</div>
								<div class="col-xs-12 col-sm-6 emphasis">
								  <button type="button" class="btn btn-success btn-xs"> <i class="fa fa-user">
									</i> <i class="fa fa-comments-o"></i> </button>
								  <button type="button" class="btn btn-primary btn-xs">
									<i class="fa fa-user"> </i> View Profile
								  </button>
								</div>
							  </div>
							</div>
						  </div>

						  <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
							<div class="well profile_view">
							  <div class="col-sm-12">
								<h4 class="brief"><i>Digital Strategist</i></h4>
								<div class="left col-xs-7">
								  <h2>Nicole Pearson</h2>
								  <p><strong>About: </strong> Web Designer / UI. </p>
								  <ul class="list-unstyled">
									<li><i class="fa fa-building"></i> Address: </li>
									<li><i class="fa fa-phone"></i> Phone #: </li>
								  </ul>
								</div>
								<div class="right col-xs-5 text-center">
								  <img src="images/user.png" alt="" class="img-circle img-responsive">
								</div>
							  </div>
							  <div class="col-xs-12 bottom text-center">
								<div class="col-xs-12 col-sm-6 emphasis">
								  <p class="ratings">
									<a>4.0</a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star-o"></span></a>
								  </p>
								</div>
								<div class="col-xs-12 col-sm-6 emphasis">
								  <button type="button" class="btn btn-success btn-xs"> <i class="fa fa-user">
									</i> <i class="fa fa-comments-o"></i> </button>
								  <button type="button" class="btn btn-primary btn-xs">
									<i class="fa fa-user"> </i> View Profile
								  </button>
								</div>
							  </div>
							</div>
						  </div>

						  <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
							<div class="well profile_view">
							  <div class="col-sm-12">
								<h4 class="brief"><i>Digital Strategist</i></h4>
								<div class="left col-xs-7">
								  <h2>Nicole Pearson</h2>
								  <p><strong>About: </strong> Web Designer / UI. </p>
								  <ul class="list-unstyled">
									<li><i class="fa fa-building"></i> Address: </li>
									<li><i class="fa fa-phone"></i> Phone #: </li>
								  </ul>
								</div>
								<div class="right col-xs-5 text-center">
								  <img src="images/user.png" alt="" class="img-circle img-responsive">
								</div>
							  </div>
							  <div class="col-xs-12 bottom text-center">
								<div class="col-xs-12 col-sm-6 emphasis">
								  <p class="ratings">
									<a>4.0</a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star-o"></span></a>
								  </p>
								</div>
								<div class="col-xs-12 col-sm-6 emphasis">
								  <button type="button" class="btn btn-success btn-xs"> <i class="fa fa-user">
									</i> <i class="fa fa-comments-o"></i> </button>
								  <button type="button" class="btn btn-primary btn-xs">
									<i class="fa fa-user"> </i> View Profile
								  </button>
								</div>
							  </div>
							</div>
						  </div>
						  
						  <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
							<div class="well profile_view">
							  <div class="col-sm-12">
								<h4 class="brief"><i>Digital Strategist</i></h4>
								<div class="left col-xs-7">
								  <h2>Nicole Pearson</h2>
								  <p><strong>About: </strong> Web Designer / UX / Graphic Artist / Coffee Lover </p>
								  <ul class="list-unstyled">
									<li><i class="fa fa-building"></i> Address: </li>
									<li><i class="fa fa-phone"></i> Phone #: </li>
								  </ul>
								</div>
								<div class="right col-xs-5 text-center">
								  <img src="images/img.jpg" alt="" class="img-circle img-responsive">
								</div>
							  </div>
							  <div class="col-xs-12 bottom text-center">
								<div class="col-xs-12 col-sm-6 emphasis">
								  <p class="ratings">
									<a>4.0</a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star-o"></span></a>
								  </p>
								</div>
								<div class="col-xs-12 col-sm-6 emphasis">
								  <button type="button" class="btn btn-success btn-xs"> <i class="fa fa-user">
									</i> <i class="fa fa-comments-o"></i> </button>
								  <button type="button" class="btn btn-primary btn-xs">
									<i class="fa fa-user"> </i> View Profile
								  </button>
								</div>
							  </div>
							</div>
						  </div>

						  <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
							<div class="well profile_view">
							  <div class="col-sm-12">
								<h4 class="brief"><i>Digital Strategist</i></h4>
								<div class="left col-xs-7">
								  <h2>Nicole Pearson</h2>
								  <p><strong>About: </strong> Web Designer / UI. </p>
								  <ul class="list-unstyled">
									<li><i class="fa fa-building"></i> Address: </li>
									<li><i class="fa fa-phone"></i> Phone #: </li>
								  </ul>
								</div>
								<div class="right col-xs-5 text-center">
								  <img src="images/user.png" alt="" class="img-circle img-responsive">
								</div>
							  </div>
							  <div class="col-xs-12 bottom text-center">
								<div class="col-xs-12 col-sm-6 emphasis">
								  <p class="ratings">
									<a>4.0</a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star-o"></span></a>
								  </p>
								</div>
								<div class="col-xs-12 col-sm-6 emphasis">
								  <button type="button" class="btn btn-success btn-xs"> <i class="fa fa-user">
									</i> <i class="fa fa-comments-o"></i> </button>
								  <button type="button" class="btn btn-primary btn-xs">
									<i class="fa fa-user"> </i> View Profile
								  </button>
								</div>
							  </div>
							</div>
						  </div>

						  <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
							<div class="well profile_view">
							  <div class="col-sm-12">
								<h4 class="brief"><i>Digital Strategist</i></h4>
								<div class="left col-xs-7">
								  <h2>Nicole Pearson</h2>
								  <p><strong>About: </strong> Web Designer / UI. </p>
								  <ul class="list-unstyled">
									<li><i class="fa fa-building"></i> Address: </li>
									<li><i class="fa fa-phone"></i> Phone #: </li>
								  </ul>
								</div>
								<div class="right col-xs-5 text-center">
								  <img src="images/user.png" alt="" class="img-circle img-responsive">
								</div>
							  </div>
							  <div class="col-xs-12 bottom text-center">
								<div class="col-xs-12 col-sm-6 emphasis">
								  <p class="ratings">
									<a>4.0</a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star-o"></span></a>
								  </p>
								</div>
								<div class="col-xs-12 col-sm-6 emphasis">
								  <button type="button" class="btn btn-success btn-xs"> <i class="fa fa-user">
									</i> <i class="fa fa-comments-o"></i> </button>
								  <button type="button" class="btn btn-primary btn-xs">
									<i class="fa fa-user"> </i> View Profile
								  </button>
								</div>
							  </div>
							</div>
						  </div>

						  <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
							<div class="well profile_view">
							  <div class="col-sm-12">
								<h4 class="brief"><i>Digital Strategist</i></h4>
								<div class="left col-xs-7">
								  <h2>Nicole Pearson</h2>
								  <p><strong>About: </strong> Web Designer / UI. </p>
								  <ul class="list-unstyled">
									<li><i class="fa fa-building"></i> Address: </li>
									<li><i class="fa fa-phone"></i> Phone #: </li>
								  </ul>
								</div>
								<div class="right col-xs-5 text-center">
								  <img src="images/user.png" alt="" class="img-circle img-responsive">
								</div>
							  </div>
							  <div class="col-xs-12 bottom text-center">
								<div class="col-xs-12 col-sm-6 emphasis">
								  <p class="ratings">
									<a>4.0</a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star-o"></span></a>
								  </p>
								</div>
								<div class="col-xs-12 col-sm-6 emphasis">
								  <button type="button" class="btn btn-success btn-xs"> <i class="fa fa-user">
									</i> <i class="fa fa-comments-o"></i> </button>
								  <button type="button" class="btn btn-primary btn-xs">
									<i class="fa fa-user"> </i> View Profile
								  </button>
								</div>
							  </div>
							</div>
						  </div>

						  <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
							<div class="well profile_view">
							  <div class="col-sm-12">
								<h4 class="brief"><i>Digital Strategist</i></h4>
								<div class="left col-xs-7">
								  <h2>Nicole Pearson</h2>
								  <p><strong>About: </strong> Web Designer / UI. </p>
								  <ul class="list-unstyled">
									<li><i class="fa fa-building"></i> Address: </li>
									<li><i class="fa fa-phone"></i> Phone #: </li>
								  </ul>
								</div>
								<div class="right col-xs-5 text-center">
								  <img src="images/user.png" alt="" class="img-circle img-responsive">
								</div>
							  </div>
							  <div class="col-xs-12 bottom text-center">
								<div class="col-xs-12 col-sm-6 emphasis">
								  <p class="ratings">
									<a>4.0</a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star"></span></a>
									<a href="#"><span class="fa fa-star-o"></span></a>
								  </p>
								</div>
								<div class="col-xs-12 col-sm-6 emphasis">
								  <button type="button" class="btn btn-success btn-xs"> <i class="fa fa-user">
									</i> <i class="fa fa-comments-o"></i> </button>
								  <button type="button" class="btn btn-primary btn-xs">
									<i class="fa fa-user"> </i> View Profile
								  </button>
								</div>
							  </div>
							</div>
						  </div>
						</div>
					  </div>
					</div>
				  </div>
			   </div>
			</div>
		</div>
    </body>
</html>