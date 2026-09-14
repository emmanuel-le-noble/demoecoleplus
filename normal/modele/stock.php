<?php

function CreateArticle($idCategorie, $nom, $prixUnitaire, $qteDispo, $statut = 1,$pdo) {
	
	$id = verifUniciteArticle($idCategorie, $nom, $pdo);
	if($id == 0)
	{
		$sql = "INSERT INTO article (IDCATEGORIE, NOM, PRIXUNITAIRE, QTEDISPO, STATUT) 
				VALUES (:idCategorie, :nom, :prixUnitaire, :qteDispo, :statut)";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			'idCategorie' => $idCategorie,
			'nom' => $nom,
			'prixUnitaire' => $prixUnitaire,
			'qteDispo' => $qteDispo,
			'statut' => $statut
		]);
		$id = $pdo->lastInsertId();
		$success="Enregistrement effectu&eacute;e avec succ&egrave;s";
		$error="";
	}
	else
	{
		$success="";
		$error="Cette op&eacute;ration existe d&eacute;j&agrave;";
	}
	return $id.'*'.$success.'*'.$error;
}

function verifUniciteArticle($idcategorie, $nom, $pdo) {
	
	$sql = "SELECT article.id FROM article WHERE IDCATEGORIE = :idcategorie AND NOM = :nom";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['idcategorie' => $idcategorie, 'nom' => $nom]);
	$resultat ="";
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['id'];
	}
	return $resultat;
}

function getAllArticle() {
	
	$sql = "SELECT * FROM article";
	$stmt = $pdo->query($sql);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getByIdArticle($id, $pdo) {
	
	$sql = "SELECT * FROM article WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id' => $id]);
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function UpdateArticle($id, $idCategorie, $nom, $prixUnitaire, $qteDispo, $statut, $pdo) {
	
	$sql = "UPDATE article 
			SET IDCATEGORIE = :idCategorie, NOM = :nom, PRIXUNITAIRE = :prixUnitaire, 
				QTEDISPO = :qteDispo, STATUT = :statut 
			WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	return $stmt->execute([
		'idCategorie' => $idCategorie,
		'nom' => $nom,
		'prixUnitaire' => $prixUnitaire,
		'qteDispo' => $qteDispo,
		'statut' => $statut,
		'id' => $id
	]);
}

function DeleteArticle($id,$pdo) {
	
	$sql = "DELETE FROM article WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	return $stmt->execute(['id' => $id]);
}

function CreateArticleEntree($numLiv, $dateLiv, $statut = 1, $MotifEntree, $iduser, $pdo) {
	
	$id = verifUniciteArticleEntree($numLiv, $dateLiv, $pdo);
	if($id==0)
	{
		$sql = "INSERT INTO articleentree (NUM_LIV, DATE_LIV, STATUT, LIBELLE, ID_USER) 
				VALUES (:num_liv, :date_liv, :statut, :libelle, :iduser)";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			'num_liv' => $numLiv,
			'date_liv' => $dateLiv,
			'statut' => $statut,
			'libelle' => $MotifEntree,
			'iduser' => $iduser
		]);
		$id = $pdo->lastInsertId();
	}
	return $id;
}

function verifUniciteArticleEntree($numLiv, $dateLiv, $pdo) {
	
	$sql = "SELECT articleentree.id FROM articleentree WHERE NUM_LIV = :numLiv AND DATE_LIV = :dateLiv";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['numLiv' => $numLiv, 'dateLiv' => $dateLiv]);
	$resultat ="";
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['id'];
	}
	return $resultat;
}

function getAll() {
	
	$sql = "SELECT * FROM articleentree";
	$stmt = $pdo->query($sql);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getByIdArticleEntree($id, $pdo) {
	
	$sql = "SELECT * FROM articleentree WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id' => $id]);
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function UpdateArticleEntree($id, $numLiv, $dateLiv, $statut, $pdo) {
	
	$sql = "UPDATE articleentree 
			SET NUM_LIV = :num_liv, DATE_LIV = :date_liv, STATUT = :statut 
			WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	return $stmt->execute([
		'num_liv' => $numLiv,
		'date_liv' => $dateLiv,
		'statut' => $statut,
		'id' => $id
	]);
}

 function deleteArticleEntree($id, $pdo) {
	 
	$sql = "DELETE FROM articleentree WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id' => $id]);
	
	$sql = "DELETE FROM articleentree_article WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id' => $id]);
}

function CreateArticleEntreeArticle($idEntree, $idArticle, $qteLivree, $montant, $pdo) {
	
	$id = verifUniciteArticleEntreeArticle($idEntree, $idArticle, $pdo);
	if($id == 0)
	{
		$sql = "INSERT INTO articleentree_article (IDARTICLE_ENTREE, IDARTICLE, QTE_LIV, MONTANT)
				VALUES (:id_entree, :id_article, :qte_liv, :montant)";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			'id_entree' => $idEntree,
			'id_article' => $idArticle,
			'qte_liv' => $qteLivree,
			'montant' => $montant
		]);
		$id = $pdo->lastInsertId();
	}
	return $id;
}

function verifUniciteArticleEntreeArticle($idEntree, $idArticle, $pdo) {
	
	$sql = "SELECT articleentree_article.id FROM articleentree_article WHERE IDARTICLE_ENTREE = :id_entree AND IDARTICLE = :id_article";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id_entree' => $idEntree, 'id_article' => $idArticle]);
	$resultat ="";
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['id'];
	}
	return $resultat;
}

 function getAllArticleEntreeArticle() {
	 
	$sql = "SELECT * FROM articleentree_article";
	$stmt = $pdo->query($sql);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getByIdArticleEntreeArticle($id,$pdo) {
	
	$sql = "SELECT * FROM articleentree_article WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id' => $id]);
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getByEntreeIdArticleEntreeArticle($idEntree,$pdo) {
	
	$sql = "SELECT * FROM articleentree_article WHERE IDARTICLE_ENTREE = :id_entree";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id_entree' => $idEntree]);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function UpdateArticleEntreeArticle($id, $idEntree, $idArticle, $qteLivree,$pdo) {
	
	$sql = "UPDATE articleentree_article 
			SET IDARTICLE_ENTREE = :id_entree, IDARTICLE = :id_article, QTE_LIV = :qte_liv 
			WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	return $stmt->execute([
		'id_entree' => $idEntree,
		'id_article' => $idArticle,
		'qte_liv' => $qteLivree,
		'id' => $id
	]);
}

function DeleteArticleEntreeArticle($id,$pdo) {
	
	$sql = "DELETE FROM articleentree_article WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	return $stmt->execute(['id' => $id]);
}

function CreateArticleSortie($numRecu, $dateSortie, $statut = 1, $ideleve, $iduser, $pdo) {
	
	$id = verifUniciteArticleSortie($ideleve, $dateSortie, $pdo);
	if($id == "")
	{
		$sql = "INSERT INTO articlesortie (NUM_RECU, DATE_SORTIE, STATUT, id_eleve, ID_USER) 
				VALUES (:num_recu, :date_sortie, :statut, :id_eleve, :id_user)";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			'num_recu' => $numRecu,
			'date_sortie' => $dateSortie,
			'statut' => $statut,
			'id_eleve' => $ideleve,
			'id_user' => $iduser
		]);
		$id = $pdo->lastInsertId();
	}
	return $id;
}

function verifUniciteArticleSortie($ideleve, $dateSortie, $pdo) {
	
	$sql = "SELECT articlesortie.id FROM articlesortie WHERE id_eleve = :id_eleve AND DATE_SORTIE = :datesortie";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id_eleve' => $ideleve, 'datesortie' => $dateSortie]);
	$resultat ="";
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['id'];
	}
	return $resultat;
}

function getAllArticleSortie() {
	
	$sql = "SELECT * FROM articlesortie";
	$stmt = $pdo->query($sql);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getByIdArticleSortie($id) {
	
	$sql = "SELECT * FROM articlesortie WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id' => $id]);
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function UpdateArticleSortie($id, $numRecu, $dateSortie, $statut,$pdo) {
	
	$sql = "UPDATE articlesortie 
			SET NUM_RECU = :num_recu, DATE_SORTIE = :date_sortie, STATUT = :statut 
			WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	return $stmt->execute([
		'num_recu' => $numRecu,
		'date_sortie' => $dateSortie,
		'statut' => $statut,
		'id' => $id
	]);
}

function DeleteArticleSortie($id,$pdo) {
	
	$sql = "DELETE FROM articlesortie WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id' => $id]);
	
	$sql = "DELETE FROM articlesortie_article WHERE ID_ARTICLESORTIE = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id' => $id]);
}

function CreateArticleSortieArticle($idSortie, $idArticle, $qteSortie, $montant, $pdo) {
	
	$id = verifUniciteArticleSortieArticle($idSortie, $idArticle, $pdo);
	if($id == "")
	{
		$sql = "INSERT INTO articlesortie_article (ID_ARTICLESORTIE, IDARTICLE, QTE_SORTIE, MONTANT)
				VALUES (:id_sortie, :id_article, :qte_sortie, :montant)";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			'id_sortie' => $idSortie,
			'id_article' => $idArticle,
			'qte_sortie' => $qteSortie,
			'montant' => $montant
		]);
		$id = $pdo->lastInsertId();
	}
	return $id;
}

function verifUniciteArticleSortieArticle($idSortie, $idArticle, $pdo) {
	
	$sql = "SELECT articlesortie_article.id FROM articlesortie_article WHERE ID_ARTICLESORTIE = :id_articlesortie AND IDARTICLE = :idarticle";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id_articlesortie' => $idSortie, 'idarticle' => $idArticle]);
	$resultat ="";
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['id'];
	}
	return $resultat;
}

function getAllArticleSortieArticle($pdo) {
	
	$sql = "SELECT * FROM articlesortie_article";
	$stmt = $pdo->query($sql);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getByIdArticleSortieArticle($id,$pdo) {
	
	$sql = "SELECT * FROM articlesortie_article WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id' => $id]);
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getByArticleSortieArticle($idSortie,$pdo) {
	
	$sql = "SELECT * FROM articlesortie_article WHERE ID_ARTICLESORTIE = :id_sortie";
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['id_sortie' => $idSortie]);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function UpdateArticleSortieArticle($id, $idSortie, $idArticle, $qteSortie, $pdo) {
	
	$sql = "UPDATE articlesortie_article 
			SET ID_ARTICLESORTIE = :id_sortie, IDARTICLE = :id_article, QTE_SORTIE = :qte_sortie 
			WHERE ID = :id";
	$stmt = $pdo->prepare($sql);
	return $stmt->execute([
		'id_sortie' => $idSortie,
		'id_article' => $idArticle,
		'qte_sortie' => $qteSortie,
		'id' => $id
	]);
}

function DeleteArticleSortieArticle($id, $id_, $pdo) {
	
	$sql = "DELETE FROM articlesortie_article WHERE ID = :id AND id_articlesortie=:id_articlesortie";
	$stmt = $pdo->prepare($sql);
	return $stmt->execute(['id' => $id, 'id_articlesortie' => $id_]);
}

function getNumArticleSortie($libelleanneescolaire,$pdo)
{
    // Séparer l'année scolaire en deux parties
    list($anneeDebut, $anneeFin) = explode('-', $libelleanneescolaire);
    $shortDebut = substr(trim($anneeDebut), -2);
    $shortFin   = substr(trim($anneeFin), -2);
    // Préfixe attendu dans la base (exemple : /25-26)
    $suffixe = '/' . $shortDebut . '-' . $shortFin;
    // Récupérer le dernier numéro existant pour cette année scolaire
    $req=(' SELECT count(distinct articlesortie.id) as nbre
			FROM articlesortie
			WHERE
			articlesortie.statut=1');
	$resultat = "1";		
	$stmt = $pdo->prepare($req);
	$stmt->execute();
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['nbre']+1;
	}
    // Formater le compteur sur 6 chiffres
    $numeroFormate = str_pad($resultat, 6, '0', STR_PAD_LEFT);

    // Retourner le numéro complet
    return 'VENT '. $numeroFormate . $suffixe;
}

function getNumArticleEntree($pdo)
{
	
	$req=(' SELECT count(distinct articleentree.id) as nbre
			FROM articleentree
			WHERE
			articleentree.statut=1');
	$resultat = "";		
	$stmt = $pdo->prepare($req);
	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['nbre']+1;
	}
	return sprintf('%04d',$resultat);

}

function getAllEleve($idanneescolaire,$pdo)
{
	$req=(' SELECT  
					distinct
					eleve.id_eleve as ideleve,
					eleve.nom_eleve as nomeleve,
					eleve.prenom_eleve as prenomeleve,
					eleveanneescolaire.boursier as boursier,
					eleveanneescolaire.idclasse as idclasse,
					eleveanneescolaire.id as ideleveanneescolaire,
					eleveanneescolaire.inscrit as inscrit
					
			FROM    eleve,eleveanneescolaire
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=:idanneescolaire

			ORDER BY  eleve.nom_eleve asc');
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idanneescolaire',$idanneescolaire,PDO::PARAM_INT);
	$stmt->execute();	
	?>	
	<SELECT class="select2_single form-control" tabindex="-1" name="ideleve" id="ideleve" required="required">
	<OPTION></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$ideleve = $donnees['ideleve'];
			$nomeleve = $donnees['nomeleve'].' '.$donnees['prenomeleve'];
			$ideleveanneescolaire = $donnees['ideleveanneescolaire'];
			$boursier = $donnees['boursier'];
			$idclasse = $donnees['idclasse'];
			$inscrit = $donnees['inscrit'];
			
			echo '<OPTION value="'.$ideleve.'*'.$ideleveanneescolaire.'*'.$boursier.'*'.$idclasse.'*'.$idanneescolaire.'*'.$inscrit.'">'.$nomeleve.'</OPTION>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllEleveSelected($ideleveanneescolaireselected,$pdo)
{
	$req=(' SELECT  
					distinct
					eleve.id_eleve as ideleve,
					eleve.nom_eleve as nomeleve,
					eleve.prenom_eleve as prenomeleve,
					eleveanneescolaire.boursier as boursier,
					eleveanneescolaire.idclasse as idclasse,
					eleveanneescolaire.id as ideleveanneescolaire,
					eleveanneescolaire.inscrit as inscrit
					
			FROM    eleve,eleveanneescolaire,anneescolaire
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			
			ORDER BY  eleve.nom_eleve asc');
			
	$stmt = $pdo->prepare($req);
	$stmt->execute();	
	?>	
	<SELECT class="select2_single form-control" tabindex="-1" name="ideleve" id="ideleve" required="required">
	<OPTION></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$ideleve = $donnees['ideleve'];
			$nomeleve = $donnees['nomeleve'].' '.$donnees['prenomeleve'];
			$ideleveanneescolaire = $donnees['ideleveanneescolaire'];
			$boursier = $donnees['boursier'];
			$idclasse = $donnees['idclasse'];
			$inscrit = $donnees['inscrit'];
			
			if($ideleveanneescolaireselected==$ideleveanneescolaire)
			{
				echo '<OPTION value="'.$ideleve.'*'.$ideleveanneescolaire.'*'.$boursier.'*'.$idclasse.'*'.$idanneescolaire.'*'.$inscrit.'" selected="selected">'.$nomeleve.'</OPTION>';
			}
			else
			{
				echo '<OPTION value="'.$ideleve.'*'.$ideleveanneescolaire.'*'.$boursier.'*'.$idclasse.'*'.$idanneescolaire.'*'.$inscrit.'">'.$nomeleve.'</OPTION>';
			}
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllArticleVente($idarticlecategorie,$pdo)
{
	$req=(' SELECT  
				 distinct
				 article.idcategorie AS idcategorie,
				 article.id AS id,
				 article.nom AS nom,
				 article.prixunitaire AS prixunitaire,
				 article.qtedispo AS qtedispo,
				 article.statut AS statut
				
			FROM article
			WHERE
			article.idcategorie=:idcategorie

			ORDER BY  article.nom asc');
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idcategorie',$idarticlecategorie,PDO::PARAM_INT);
	$stmt->execute();	
	?>	
	<SELECT class="select2_single form-control" tabindex="-1" name="idarticle" id="idarticle">
	<OPTION value="*"></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			$prixunitaire = $donnees['prixunitaire'];
			$qtedispo = $donnees['qtedispo'];
			$statut = $donnees['statut'];
			
			echo '<OPTION value="'.$id.'*'.$nom.'*'.$prixunitaire.'*'.$qtedispo.'*'.$statut.'">'.$nom.'</OPTION>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllArticleVenteSelected($idarticlecategorieselected,$idarticleselected,$pdo)
{
	
	$req=(' SELECT  
				 distinct
				 article.idcategorie AS idcategorie,
				 article.id AS id,
				 article.nom AS nom,
				 article.prixunitaire AS prixunitaire,
				 article.qtedispo AS qtedispo,
				 article.statut AS statut
				
			FROM article
			WHERE
			article.idcategorie=:idcategorie

			ORDER BY  article.nom asc');
			
	$stmt = $pdo->prepare($req);
	$stmt ->bindParam(':idcategorie',$idarticlecategorieselected,PDO::PARAM_INT);
	$stmt->execute();	
	?>	
	<SELECT class="select2_single form-control form-control-grand" tabindex="-1" name="idarticle" id="idarticle">
	<OPTION value="*"></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			$prixunitaire = $donnees['prixunitaire'];
			$qtedispo = $donnees['qtedispo'];
			$statut = $donnees['statut'];
			
			if($idarticleselected==$id)
			{
				echo '<OPTION value="'.$id.'*'.$nom.'*'.$prixunitaire.'*'.$qtedispo.'*'.$statut.'" selected="selected">'.$nom.'</OPTION>';	
			}
			else
			{
				echo '<OPTION value="'.$id.'*'.$nom.'*'.$prixunitaire.'*'.$qtedispo.'*'.$statut.'">'.$nom.'</OPTION>';	
			}
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllArticleEntree($pdo)
{
	$req=(' SELECT  
				 distinct
				 article.idcategorie AS idcategorie,
				 article.id AS id,
				 article.nom AS nom,
				 article.prixunitaire AS prixunitaire,
				 article.qtedispo AS qtedispo,
				 article.statut AS statut
				
			FROM article

			ORDER BY  article.nom asc');
			
	$stmt = $pdo->prepare($req);
	$stmt->execute();	
	?>	
	<SELECT class="select2_single form-control" tabindex="-1" name="idarticle" id="idarticle">
	<OPTION></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			$prixunitaire = $donnees['prixunitaire'];
			$qtedispo = $donnees['qtedispo'];
			$statut = $donnees['statut'];
			
			echo '<OPTION value="'.$id.'*'.$nom.'*'.$prixunitaire.'*'.$qtedispo.'*'.$statut.'">'.$nom.'</OPTION>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllArticleEntreeSelected($idarticleselected,$pdo)
{
	$req=(' SELECT  
				 distinct
				 article.idcategorie AS idcategorie,
				 article.id AS id,
				 article.nom AS nom,
				 article.prixunitaire AS prixunitaire,
				 article.qtedispo AS qtedispo,
				 article.statut AS statut
				
			FROM article

			ORDER BY  article.nom asc');
			
	$stmt = $pdo->prepare($req);
	$stmt->execute();	
	?>	
	<SELECT class="select2_single form-control form-control-grand" tabindex="-1" name="idarticle" id="idarticle">
	<OPTION></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$nom = $donnees['nom'];
			$prixunitaire = $donnees['prixunitaire'];
			$qtedispo = $donnees['qtedispo'];
			$statut = $donnees['statut'];
			
			if($idarticleselected==$id)
			{
				echo '<OPTION value="'.$id.'*'.$nom.'*'.$prixunitaire.'*'.$qtedispo.'*'.$statut.'" selected="selected">'.$nom.'</OPTION>';	
			}
			else
			{
				echo '<OPTION value="'.$id.'*'.$nom.'*'.$prixunitaire.'*'.$qtedispo.'*'.$statut.'">'.$nom.'</OPTION>';	
			}
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllArticleCategorieVente_($pdo)
{
	$req=(' SELECT  
				 distinct
				 articlecategorie.id AS id,
				 articlecategorie.libelle AS libelle
				 
			FROM articlecategorie

			ORDER BY articlecategorie.libelle asc');
			
	$stmt = $pdo->prepare($req);
	$stmt->execute();	
	?>	
	<SELECT class="select2_single form-control" tabindex="-1" name="idarticlecategorie" id="idarticlecategorie" onchange="makeRequest('NiveauStockToAllArticle.php','idarticlecategorie','resultat')">
	<OPTION></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			
			echo '<OPTION value="'.$id.'">'.$libelle.'</OPTION>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllArticleCategorieVente($pdo)
{
	$req=(' SELECT  
				 distinct
				 articlecategorie.id AS id,
				 articlecategorie.libelle AS libelle
				 
			FROM articlecategorie

			ORDER BY articlecategorie.libelle asc');
			
	$stmt = $pdo->prepare($req);
	$stmt->execute();	
	?>	
	<SELECT class="select2_single form-control" tabindex="-1" name="idarticlecategorie" style="border-radius: 8px;">
	<OPTION></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			
			echo '<OPTION value="'.$id.'">'.$libelle.'</OPTION>';	
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function getAllArticleCategorieVenteSelected($idarticlecategorieselected,$pdo)
{
	
	$req=(' SELECT  
				 distinct
				 articlecategorie.id AS id,
				 articlecategorie.libelle AS libelle
				 
			FROM articlecategorie

			ORDER BY articlecategorie.libelle asc');
			
	$stmt = $pdo->prepare($req);
	$stmt->execute();	
	?>	
	<SELECT class="select2_single form-control" tabindex="-1" name="idarticlecategorie" id="idarticlecategorie">
	<OPTION></OPTION>
	<?php
		while($donnees = $stmt->fetch())
		{
			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			
			if($id==$idarticlecategorieselected)
			{
				echo '<OPTION value="'.$id.'" selected="selected">'.$libelle.'</OPTION>';
			}
			else
			{
				echo '<OPTION value="'.$id.'">'.$libelle.'</OPTION>';
			}
		}		
	?>
	</SELECT><?php
	$stmt->closeCursor();	
	$stmt=NULL;
}

function ListByVente($articlesortie_id,$pdo)
{
	
	$req=(' SELECT  
				 distinct
				 article.id AS id,
				 article.nom AS nom,
				 article.prixunitaire AS prixunitaire,
				 article.qtedispo AS qtedispo,
				 article.statut AS statut,
				 articlecategorie.libelle AS libellecategorie,
				 articlesortie_article.qte_sortie AS qte_sortie,
				 articlesortie_article.montant AS montant,
				 articlesortie_article.id AS idarticlesortie_article
				
			FROM articlecategorie,article,articlesortie_article
			WHERE
			article.idcategorie=articlecategorie.id
			AND
			article.id=articlesortie_article.idarticle
			AND
			articlesortie_article.id_articlesortie=:id_articlesortie
			
			ORDER BY article.nom asc');
	?>
	<div align="left" style="margin-left: 20px;">
		<input id="send" type="submit" style="width:150px;padding:2px;" class="btn btn-round btn-danger" 
		name="supprimerArticle" value="Suppimer de la liste" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer cet article ?');"/> 
	</div>
	<table class="table table-striped projects" width="100%" style="background-color:#fff;">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;text-align:center;">#</th>
				<th width="13%;text-align:left">Cat&eacute;gorie</th>
				<th width="13%;text-align:left">Article (D&eacute;signation)</th>
				<th width="13%;text-align:left">Prix Unitaire</th>
				<th width="13%;text-align:left">Qt&eacute; (Vendue)</th>
				<th width="13%;text-align:left">Total Vendu (FCFA)</th>			
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':id_articlesortie',$articlesortie_id,PDO::PARAM_INT);
		$stmt -> execute();	
		$ligne=0;
		$Totalligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;

			$id = $donnees['id'];
			$libelle = $donnees['libellecategorie'];
			$nom = $donnees['nom'];
			$prixunitaire = $donnees['prixunitaire'];
			$qtedispo = $donnees['qtedispo'];
			$statut = $donnees['statut'];
			$montant = $donnees['montant'];
			$qtesortie = $donnees['qte_sortie'];
			$idarticlesortie_article = $donnees['idarticlesortie_article'];
			$Totalligne=$Totalligne + $montant;
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="idligne<?php echo $ligne;?>" value="<?php echo $articlesortie_id.'*'.$idarticlesortie_article;?>"/>
				</td>
				<td><a><?php echo $libelle;?></a></td>
				<td><a><?php echo $nom;?></a></td>
				<td><a><?php echo $prixunitaire;?></a></td>
				<td><a><?php echo $qtesortie;?></a></td>
				<td><a><?php echo number_format($montant,0,""," ");?></a></td>		
			</tr><?php
		}
		$stmt -> closeCursor();
		$stmt = NULL;
		?>
		<tr>
			<td><a></a></td>
			<td align="center" colspan="2">
				<b>TOTAL (FCFA)</b>
			</td>
			<td><a></a></td>
			<td><a></a></td>
			<td><b><?php echo number_format($Totalligne,0,""," ");?></b></td>		
		</tr>
	</table>
	<input type="hidden" name="nbrelignearticleajoute" value="<?php echo $ligne;?>"><?php
}

function ListByEntree($articleentree_id,$pdo)
{
	$req=(' SELECT  
				 distinct
				 article.id AS id,
				 article.nom AS nom,
				 article.prixunitaire AS prixunitaire,
				 article.qtedispo AS qtedispo,
				 article.statut AS statut,
				 articlecategorie.libelle AS libellecategorie,
				 articleentree_article.qte_liv AS qte_liv,
				 articleentree_article.montant AS montant,
				 articleentree_article.id AS idarticleentree_article
				 
			FROM articlecategorie,article,articleentree_article
			WHERE
			article.idcategorie=articlecategorie.id
			AND
			article.id=articleentree_article.idarticle
			AND
			articleentree_article.idarticle_entree=:id_articleentree
			
			ORDER BY article.nom asc');
	?>
	<div align="left" style="margin-left: 20px;">
		<input id="send" type="submit" style="width:150px;padding:2px;" class="btn btn-round btn-danger" 
		name="supprimerArticle" value="Suppimer de la liste" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer cet article ?');"/> 
	</div>
	<table class="table table-striped projects" width="100%" style="background-color:#fff;">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;text-align:center;">#</th>
				<th width="13%">Cat&eacute;gorie</th>
				<th width="13%">Article</th>
				<th width="13%">PU</th>
				<th width="13%">Qt&eacute; (Appro)</th>
				<th width="13%">Total vendu (FCFA)</th>			
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':id_articleentree',$articleentree_id,PDO::PARAM_INT);
		$stmt -> execute();	
		$ligne=0;
		$Totalligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;

			$id = $donnees['id'];
			$libelle = $donnees['libellecategorie'];
			$nom = $donnees['nom'];
			$prixunitaire = $donnees['prixunitaire'];
			$qtedispo = $donnees['qtedispo'];
			$statut = $donnees['statut'];
			$montant = $donnees['montant'];
			$qte_liv = $donnees['qte_liv'];
			$idarticleentree_article = $donnees['idarticleentree_article'];
			$Totalligne=$Totalligne + $montant;
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" 
					value="<?php echo $articleentree_id.'*'.$idarticleentree_article;?>"/>
				</td>
				<td><a><?php echo $libelle;?></a></td>
				<td><a><?php echo $nom;?></a></td>
				<td><a><?php echo $prixunitaire;?></a></td>
				<td><a><?php echo $qte_liv;?></a></td>
				<td><a><?php echo number_format($montant,0,""," ");?></a></td>		
			</tr><?php
		}
		$stmt -> closeCursor();
		$stmt = NULL;
		?>
		<tr>
			<td><a></a></td>
			<td align="center" colspan="2">
				<b>TOTAL (FCFA)</b>
			</td>
			<td><a></a></td>
			<td><a></a></td>
			<td><b><?php echo number_format($Totalligne,0,""," ");?></b></td>		
		</tr>
	</table>
	<input type="hidden" name="nbreligne" value="<?php echo $ligne;?>"><?php
}

function ListBySortie($articlesortie_id,$pdo)
{
	$req=(' SELECT  
				 distinct
				 article.id AS id,
				 article.nom AS nom,
				 article.prixunitaire AS prixunitaire,
				 article.qtedispo AS qtedispo,
				 article.statut AS statut,
				 articlecategorie.libelle AS libellecategorie,
				 articlesortie_article.qte_sortie AS qte_sortie,
				 articlesortie_article.montant AS montant,
				 articlesortie_article.id AS idarticlesortie_article
				 
			FROM articlecategorie,article,articlesortie_article
			WHERE
			article.idcategorie=articlecategorie.id
			AND
			article.id=articlesortie_article.idarticle
			AND
			articlesortie_article.id_articlesortie=:id_articlesortie
			
			ORDER BY article.nom asc');
	?>
	<div align="left" style="margin-left: 20px;">
		<input id="send" type="submit" style="width:150px;padding:2px;" class="btn btn-round btn-danger" 
		name="supprimerArticle" value="Suppimer de la liste" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer cet article ?');"/> 
	</div>
	<table class="table table-striped projects" width="100%" style="background-color:#fff;">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;text-align:center;">#</th>
				<th width="31%">Cat&eacute;gorie</th>
				<th width="31%">Article</th>
				<th width="31%">Qt&eacute; (Sortie)</th>		
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':id_articlesortie',$articlesortie_id,PDO::PARAM_INT);
		$stmt -> execute();	
		$ligne=0;
		$Totalligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;

			$id = $donnees['id'];
			$libelle = $donnees['libellecategorie'];
			$nom = $donnees['nom'];
			$prixunitaire = $donnees['prixunitaire'];
			$qtedispo = $donnees['qtedispo'];
			$statut = $donnees['statut'];
			$montant = $donnees['montant'];
			$qte_sortie = $donnees['qte_sortie'];
			$idarticlesortie_article = $donnees['idarticlesortie_article'];
			$Totalligne=$Totalligne + $montant;
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" 
					value="<?php echo $articlesortie_id.'*'.$idarticlesortie_article;?>"/>
				</td>
				<td><a><?php echo $libelle;?></a></td>
				<td><a><?php echo $nom;?></a></td>
				<td><a><?php echo $qte_sortie;?></a></td>	
			</tr><?php
		}
		$stmt -> closeCursor();
		$stmt = NULL;
		?>
		<tr>
			<td><a></a></td>
			<td><b></b></td>
			<td><a></a></td>
			<td><a></a></td>		
		</tr>
	</table>
	<input type="hidden" name="nbreligne" value="<?php echo $ligne;?>"><?php
}

function ListBySortieForPrint($articlesortie_id,$pdo)
{
	$req=(' SELECT  
				 distinct
				 article.id AS id,
				 article.nom AS nom,
				 article.prixunitaire AS prixunitaire,
				 article.qtedispo AS qtedispo,
				 article.statut AS statut,
				 articlecategorie.libelle AS libellecategorie,
				 articlesortie_article.qte_sortie AS qte_sortie,
				 articlesortie_article.montant AS montant,
				 articlesortie_article.id AS idarticlesortie_article,
				 articlesortie_article.idarticle_sortie AS idarticlesortie_article
				
			FROM articlecategorie,article,articlesortie_article
			WHERE
			article.idcategorie=articlecategorie.id
			AND
			article.id=articleentree_article.idarticle
			AND
			articlesortie_article.id_articlesortie=:id_articlesortie
			
			ORDER BY article.nom asc');
	?>
	<div align="left" style="margin-left: 20px;">
		<input id="send" type="submit" style="width:150px;padding:2px;" class="btn btn-round btn-danger" 
		name="supprimerArticle" value="Suppimer de la liste" onclick="return confirm('&ecirc;tes-vous s&ucirc;r de vouloir supprimer cet article ?');"/> 
	</div>
	<table class="table table-striped projects" width="100%" style="background-color:#fff;">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;text-align:center;">#</th>
				<th width="13%">Cat&eacute;gorie</th>
				<th width="13%">Article</th>
				<th width="13%">PU</th>
				<th width="13%">Qt&eacute; (Sortie)</th>
				<th width="13%">Total vendu (FCFA)</th>			
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':id_articlesortie',$articlesortie_id,PDO::PARAM_INT);
		$stmt -> execute();	
		$ligne=0;
		$Totalligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;

			$id = $donnees['id'];
			$libelle = $donnees['libellecategorie'];
			$nom = $donnees['nom'];
			$prixunitaire = $donnees['prixunitaire'];
			$qtedispo = $donnees['qtedispo'];
			$statut = $donnees['statut'];
			$montant = $donnees['montant'];
			$qte_sortie = $donnees['qte_sortie'];
			$idarticlesortie_article = $donnees['idarticlesortie_article'];
			$Totalligne=$Totalligne + $montant;
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" 
					value="<?php echo $articlesortie_id.'*'.$idarticlesortie_article;?>"/>
				</td>
				<td><a><?php echo $libelle;?></a></td>
				<td><a><?php echo $nom;?></a></td>
				<td><a><?php echo $prixunitaire;?></a></td>
				<td><a><?php echo $qte_sortie;?></a></td>
				<td><a><?php echo number_format($montant,0,""," ");?></a></td>		
			</tr><?php
		}
		$stmt -> closeCursor();
		$stmt = NULL;
		?>
		<tr>
			<td><a></a></td>
			<td align="center" colspan="2">
				<b>TOTAL (FCFA)</b>
			</td>
			<td><a></a></td>
			<td><a></a></td>
			<td><b><?php echo number_format($Totalligne,0,""," ");?></b></td>		
		</tr>
	</table>
	<input type="hidden" name="nbreligne" value="<?php echo $ligne;?>"><?php
}

function ListByVenteForPrint($articlesortie_id,$pdo)
{
	
	$req=(' SELECT  
				 distinct
				 article.id AS id,
				 article.nom AS nom,
				 article.prixunitaire AS prixunitaire,
				 article.qtedispo AS qtedispo,
				 article.statut AS statut,
				 articlecategorie.libelle AS libellecategorie,
				 articlesortie_article.qte_sortie AS qte_sortie,
				 articlesortie_article.montant AS montant,
				 articlesortie_article.id AS idarticlesortie_article
				
			FROM articlecategorie,article,articlesortie_article
			WHERE
			article.idcategorie=articlecategorie.id
			AND
			article.id=articlesortie_article.idarticle
			AND
			articlesortie_article.id_articlesortie=:id_articlesortie
			
			ORDER BY article.nom asc');
	?>
	<table class="designnew" width="100%">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;text-align:center">#</th>
				<th width="13%;text-align:left">Cat&eacute;gorie</th>
				<th width="13%;text-align:left">Article (Désignation)</th>
				<th width="13%;text-align:left">Prix Unitaire</th>
				<th width="13%;text-align:left">Qt&eacute; (Vendue)</th>
				<th width="13%;text-align:left">Total Vendu (FCFA)</th>			
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt ->bindParam(':id_articlesortie',$articlesortie_id,PDO::PARAM_INT);
		$stmt -> execute();	
		$ligne=0;
		$Totalligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$id = $donnees['id'];
			$libelle = $donnees['libellecategorie'];
			$nom = $donnees['nom'];
			$prixunitaire = $donnees['prixunitaire'];
			$qtedispo = $donnees['qtedispo'];
			$statut = $donnees['statut'];
			$montant = $donnees['montant'];
			$qtesortie = $donnees['qte_sortie'];
			$idarticlesortie_article = $donnees['idarticlesortie_article'];
			$Totalligne=$Totalligne + $montant;
			?>
			<tr>
				<td style="width:5%;text-align:center;">
					<?php echo $ligne;?>
				</td>
				<td><a>&nbsp;<?php echo $libelle;?></a></td>
				<td><a>&nbsp;<?php echo $nom;?></a></td>
				<td><a>&nbsp;<?php echo $prixunitaire;?></a></td>
				<td><a>&nbsp;<?php echo $qtesortie;?></a></td>
				<td><a>&nbsp;<?php echo number_format($montant,0,""," ");?></a></td>		
			</tr><?php
		}
		$stmt -> closeCursor();
		$stmt = NULL;
		?>
		<tr>
			<td><a></a></td>
			<td align="center" colspan="2">
				<b>TOTAL (FCFA)</b>
			</td>
			<td><a></a></td>
			<td><a></a></td>
			<td><b><?php echo number_format($Totalligne,0,""," ");?></b></td>		
		</tr>
	</table><?php
}

function ListAllVente($pdo)
{
	$req=(' SELECT  
					distinct
					eleve.id_eleve as ideleve,
					eleve.nom_eleve as nomeleve,
					eleve.prenom_eleve as prenomeleve,
					eleveanneescolaire.idclasse as idclasse,
					eleveanneescolaire.id as ideleveanneescolaire,
					articlesortie.id AS idarticlesortie,
					articlesortie.date_sortie AS date_sortie,
					articlesortie.num_recu AS num_recu,
					sum(articlesortie_article.montant) AS montant,
					utilisateur.nom_user AS nomuser,
					utilisateur.prenom_user AS prenomuser
					
			FROM    eleve,eleveanneescolaire,anneescolaire,articlesortie,articlesortie_article,utilisateur
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.idanneescolaire=anneescolaire.id
			AND
			anneescolaire.statut=1
			AND
			eleveanneescolaire.id=articlesortie.id_eleve
			AND
			articlesortie.id=articlesortie_article.id_articlesortie
			AND
			articlesortie.id_user=utilisateur.id

			GROUP BY articlesortie.id
			
			ORDER BY articlesortie.date_sortie DESC');
	?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
		<thead>
			<tr style="background-color:#eee;font-size:15px;">
				<th style="width:5%;font-weight:bold;color:#000;">#</th>
				<th style="width:10%;font-weight:bold;color:#000;">Date</th>
				<th style="width:10%;font-weight:bold;color:#000;">N° vente</th>
				<th style="width:19%;font-weight:bold;color:#000;">Nom & pr&eacute;nom(s) du client</th>
				<th style="width:13%;font-weight:bold;color:#000;">Total vendu (FCFA)</th>
				<th style="width:13%;font-weight:bold;color:#000;">Vente effectu&eacute;e par</th>
				<th style="width:13%;font-weight:bold;color:#000;">Action(s)</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt -> execute();	
		$ligne=0;
		$Totalligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;

			$id = $donnees['ideleve'];
			$nomeleve = $donnees['nomeleve'].' '.$donnees['prenomeleve'];
			$idclasse = $donnees['idclasse'];
			$ideleveanneescolaire = $donnees['ideleveanneescolaire'];
			$idarticlesortie = $donnees['idarticlesortie'];
			$date_sortie = $donnees['date_sortie'];
			$num_recu = $donnees['num_recu'];
			$montant = $donnees['montant'];
			$nomuser = $donnees['nomuser'].' '.$donnees['prenomuser'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idarticlesortie.'*'.$id.'*'.$num_recu.'*'.$date_sortie.'*'.$montant;?>"/>
				</td>
				<td><a><?php echo $date_sortie;?></a></td>
				<td><a><?php echo $num_recu;?></a></td>
				<td><a><?php echo $nomeleve;?></a></td>
				<td><a><?php echo number_format($montant,0,""," ");?></a></td>
				<td><a><?php echo $nomuser;?></a></td>
				<td>
					<div style="float:left;"><a href="#" class="btn btn-info btn-xs" onclick='window.open("ImprimeRecu_1.php?&id=<?php echo $idarticlesortie.'*'.$id.'*'.$num_recu.'*'.$date_sortie.'*'.$montant.'*'.$nomuser;?>","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
						<span class="fa fa-print"></span> Imprimer Recu
					</a></div>
				</td>
			</tr><?php
		}
		$stmt -> closeCursor();
		$stmt = NULL;
		?>
	</table>
	<input type="hidden" name="nbreligne" value="<?php echo $ligne;?>"><?php
}

function ListAllEntree($pdo)
{
	$req=(' SELECT  
					distinct
					articleentree.id AS idarticleentree,
					articleentree.date_liv AS date_liv,
					articleentree.num_liv AS num_liv,
					articleentree.libelle AS libelle,
					utilisateur.nom_user AS nomuser,
					utilisateur.prenom_user AS prenomuser
					
			FROM    articleentree,utilisateur
			WHERE
			articleentree.id_user=utilisateur.id

			ORDER BY articleentree.id DESC');
	?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
		<thead>
			<tr style="background-color:#eee;font-size:15px;">
				<th style="width:5%;text-align:center;">#</th>
				<th style="width:19%;font-weight:bold;color:#000">Date</th>
				<th style="width:19%;font-weight:bold;color:#000">N° entrée</th>
				<th style="width:19%;font-weight:bold;color:#000">Motif concerné</th>
				<th style="width:19%;font-weight:bold;color:#000">Effectuée par</th>
				<th style="width:19%;font-weight:bold;color:#000">Action</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt -> execute();	
		$ligne=0;
		$Totalligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idarticleentree = $donnees['idarticleentree'];
			$date_liv = $donnees['date_liv'];
			$num_liv = $donnees['num_liv'];
			$libelle = $donnees['libelle'];
			$nomuser = $donnees['nomuser'];
			$prenomuser = $donnees['prenomuser'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idarticleentree.'*'.$date_liv.'*'.$num_liv.'*'.$libelle;?>"/>
				</td>
				<td><a><?php echo $date_liv;?></a></td>
				<td><a><?php echo $num_liv;?></a></td>
				<td><a><?php echo $libelle;?></a></td>
				<td><a><?php echo $nomuser.' '.$prenomuser;?></a></td>
				<td>
					<div style="float:left;"><a href="#" class="btn btn-info btn-xs" onclick='window.open("ImprimeBon.php?&id=<?php echo $idarticleentree.'*'.$date_liv.'*'.$num_liv.'*'.$libelle;?>","new","width=1366px, height=700px, top=300, left=500, location=500, directories=0, personnalbar=0, toolbar=no");'>
						---> Imprimer le bon d'entrée.
					</a></div>
				</td>
			</tr><?php
		}
		$stmt -> closeCursor();
		$stmt = NULL;
		?>
	</table>
	<input type="hidden" name="nbreligne" value="<?php echo $ligne;?>"><?php
}

function ListAllSortie($pdo)
{
	$req=(' SELECT  
					distinct
					articlesortie.id AS idarticlesortie,
					DATE_FORMAT(articlesortie.date_sortie, "%d/%m/%Y") AS date_sortie,
					articlesortie.num_recu AS num_recu,
					articlesortie.motif_sortie AS motif_sortie,
					utilisateur.nom_user AS nomuser,
					utilisateur.prenom_user AS prenomuser
					
			FROM    articlesortie,utilisateur
			WHERE
			articlesortie.id_user=utilisateur.id

			ORDER BY articlesortie.id DESC');
	?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;text-align:center;">#</th>
				<th style="width:19%;font-weight:bold;color:#000;font-size:16px">Date</th>
				<th style="width:19%;font-weight:bold;color:#000;font-size:16px">N° sortie</th>
				<th style="width:19%;font-weight:bold;color:#000;font-size:16px">Motif de la sortie</th>
				<th style="width:19%;font-weight:bold;color:#000;font-size:16px">Effectuée par</th>
				<th style="width:19%;font-weight:bold;color:#000;font-size:16px">Action</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt -> execute();	
		$ligne=0;
		$Totalligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$idarticlesortie = $donnees['idarticlesortie'];
			$date_sortie = $donnees['date_sortie'];
			$num_recu = $donnees['num_recu'];
			$motif_sortie = $donnees['motif_sortie'];
			$nomuser = $donnees['nomuser'];
			$prenomuser = $donnees['prenomuser'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $idarticlesortie.'*'.$date_sortie.'*'.$num_recu.'*'.$motif_sortie;?>"/>
				</td>
				<td><a><?php echo $date_sortie;?></a></td>
				<td><a><?php echo $num_recu;?></a></td>
				<td><a><?php echo $motif_sortie;?></a></td>
				<td><a><?php echo $nomuser.' '.$prenomuser;?></a></td>
				<td>
					<div style="float:left;">
						<a href="#" class="btn btn-info btn-xs">
							<span class="fa fa-print"></span> Imprimer le bon de sortie
						</a>
					</div>
				</td>
			</tr><?php
		}
		$stmt -> closeCursor();
		$stmt = NULL;
		?>
	</table>
	<input type="hidden" name="nbreligne" value="<?php echo $ligne;?>"><?php
}

function ListAllArticle($pdo)
{
	
	$req=(' SELECT  
					distinct
					articlecategorie.libelle AS libelle,
					article.id AS id,
					article.nom AS nom,
					article.prixunitaire AS prixunitaire,
					article.qtedispo AS qtedispo
					
			FROM    articlecategorie,article
			WHERE
			articlecategorie.id=article.idcategorie
			
			ORDER BY article.nom ASC');
	?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
		<thead>
			<tr style="background-color:#eee;font-size:15px;">
				<th style="width:5%;text-align:left;">#</th>
				<th style="width:23%;text-align:left;font-weight:bold;color:#000">Cat&eacute;gorie</th>
				<th style="width:23%;text-align:left;font-weight:bold;color:#000">Nom de l'article</th>
				<th style="width:23%;text-align:left;font-weight:bold;color:#000">Prix Unitaire (FCFA)</th>
				<th style="width:23%;text-align:left;font-weight:bold;color:#000">Qt&eacute; Initiale</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt -> execute();	
		$ligne=0;
		$Totalligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;

			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			$nom = $donnees['nom'];
			$prixunitaire = $donnees['prixunitaire'];
			$qtedispo = $donnees['qtedispo'];
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>
				</td>
				<td><a><?php echo $libelle;?></a></td>
				<td><a><?php echo $nom;?></a></td>
				<td><a><?php echo number_format($prixunitaire,0,""," ");?></a></td>
				<td><a><?php echo $qtedispo;?></a></td>
			</tr><?php
		}
		$stmt -> closeCursor();
		$stmt = NULL;
		?>
	</table>
	<input type="hidden" name="nbreligne" value="<?php echo $ligne;?>"><?php
}

function NiveauStockToAllArticle($pdo)
{
	
	$req=(' SELECT  
					distinct
					articlecategorie.libelle AS libelle,
					article.id AS id,
					article.nom AS nom,
					article.prixunitaire AS prixunitaire
					
			FROM    articlecategorie,article
			WHERE
			articlecategorie.id=article.idcategorie
			
			ORDER BY article.nom ASC');
	?>
	<table id="datatable-buttons" class="table table-striped table-bordered" width="100%">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;text-align:center;">#</th>
				<th style="width:23%;font-weight:bold;color:#000;font-size:16px;">Cat&eacute;gorie</th>
				<th style="width:23%;font-weight:bold;color:#000;font-size:16px;">Nom Article</th>
				<th style="width:23%;font-weight:bold;color:#000;font-size:16px;">Prix Unitaire (FCFA)</th>
				<th style="width:23%;font-weight:bold;color:#000;font-size:16px;">Quantit&eacute; Disponible</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt -> execute();	
		$ligne=0;
		$Totalligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;

			$id = $donnees['id'];
			$libelle = $donnees['libelle'];
			$nom = $donnees['nom'];
			$prixunitaire = $donnees['prixunitaire'];
			$qtedispo = getQteDispo($id,$pdo);
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>
				</td>
				<td><a><?php echo $libelle;?></a></td>
				<td><a><?php echo $nom;?></a></td>
				<td><a><?php echo number_format($prixunitaire,0,""," ");?></a></td>
				<td><a><?php echo $qtedispo;?></a></td>
			</tr><?php
		}
		$stmt -> closeCursor();
		$stmt = NULL;
		?>
	</table>
	<input type="hidden" name="nbreligne" value="<?php echo $ligne;?>"><?php
}

function NiveauStockToAllArticle_($articlecategorie_id,$pdo)
{
	
	$req=(' SELECT  
					distinct
					articlecategorie.libelle AS libelle,
					article.id AS id,
					article.nom AS nom,
					article.prixunitaire AS prixunitaire
					
			FROM    articlecategorie,article
			WHERE
			articlecategorie.id=article.idcategorie
			AND
			articlecategorie.id=:articlecategorie_id
			
			ORDER BY article.nom ASC');
	?>
	<table class="table table-striped projects" width="100%">
		<thead>
			<tr style="background-color:#eee;">
				<th style="width:5%;text-align:center;">#</th>
				<th style="width:23%;font-weight:bold;color:#000;font-size:16px;">Cat&eacute;gorie</th>
				<th style="width:23%;font-weight:bold;color:#000;font-size:16px;">Nom Article</th>
				<th style="width:23%;font-weight:bold;color:#000;font-size:16px;">Prix Unitaire (FCFA)</th>
				<th style="width:23%;font-weight:bold;color:#000;font-size:16px;">Quantit&eacute; Disponible</th>
			</tr>
		</thead>
		<?php		
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':articlecategorie_id',$articlecategorie_id,PDO::PARAM_INT);
		$stmt -> execute();	
		$ligne=0;
		$Totalligne=0;
		while($donnees = $stmt->fetch())
		{
			$ligne++;
			$id = $donnees['id'];
			$libelle = ucfirst($donnees['libelle']);
			$nom = ucfirst($donnees['nom']);
			$prixunitaire = $donnees['prixunitaire'];
			$qtedispo = getQteDispo($id,$pdo);
			?>
			<tr>
				<td>
					<input class="flat" type="checkbox" name="id<?php echo $ligne;?>" value="<?php echo $id;?>"/>
				</td>
				<td><a><?php echo $libelle;?></a></td>
				<td><a><?php echo $nom;?></a></td>
				<td><a><?php echo number_format($prixunitaire,0,""," ");?></a></td>
				<td><a><?php echo $qtedispo;?></a></td>
			</tr><?php
		}
		$stmt -> closeCursor();
		$stmt = NULL;
		?>
	</table>
	<input type="hidden" name="nbreligne" value="<?php echo $ligne;?>"><?php
}

function getNomPrenomEleve($ideleveanneescolaire, $pdo) {
	
	$sql = "SELECT  
					distinct
					eleve.id_eleve as ideleve,
					eleve.nom_eleve as nomeleve,
					eleve.prenom_eleve as prenomeleve,
					eleveanneescolaire.idclasse as idclasse,
					eleveanneescolaire.id as ideleveanneescolaire
					
			FROM    eleve,eleveanneescolaire
			WHERE
			eleve.id_eleve=eleveanneescolaire.ideleve
			AND
			eleveanneescolaire.id=:ideleveanneescolaire";
			
	$stmt = $pdo->prepare($sql);
	$stmt->execute(['ideleveanneescolaire' => $ideleveanneescolaire]);
	$resultat ="";
	if($donnees = $stmt->fetch())
	{
		$resultat = $donnees['nomeleve'].' '.$donnees['prenomeleve'];
	}
	return $resultat;
}

function getDirecteur($idanneescolaire,$statutanneescolaire,$pdo)
{
	if($statutanneescolaire==1)
	{
		$req=(' SELECT professeur.nom as nomprof,professeur.id as idprof
				FROM professeur
				WHERE
				professeur.titre=1
				AND
				professeur.statut=1');
			
		$stmt = $pdo->prepare($req);
		$stmt->bindParam(':idsalle',$idsalle,PDO::PARAM_INT);
	}
	else
	{
		$req=(' SELECT professeur.nom as nomprof,professeur.id as idprof
				FROM professeur
				WHERE
				professeur.titre=1
				AND
				professeur.statut=0
				
				ORDER BY professeur.id DESC LIMIT 0,1');
			
		$stmt = $pdo->prepare($req);
	}
	$stmt = $pdo->prepare($req);
  	$stmt->execute();	
	if($donnees = $stmt->fetch())
	{
	    $resultat = $donnees['nomprof'];
	}
	else
	{
	    $resultat="";
	}
	$stmt->closeCursor();
	$stmt=NULL;
	
	return $resultat;
}

function getQteDispo($idarticle,$pdo)
{
	$req=(' SELECT sum(articleentree_article.qte_liv) as qte_liv 
			FROM articleentree_article
			WHERE 
			articleentree_article.idarticle=:idarticle');		
	$stmt = $pdo->prepare($req);
	$stmt->bindParam(':idarticle', $idarticle, PDO::PARAM_STR);
	$stmt->execute();	
	$donnees = $stmt->fetch();
	$stmt->closeCursor();
	$stmt=NULL;
	//
	$req_=(' SELECT sum(articlesortie_article.qte_sortie) as qte_sortie 
			FROM articlesortie_article
			WHERE 
			articlesortie_article.idarticle=:idarticle');		
	$stmt_ = $pdo->prepare($req_);
	$stmt_->bindParam(':idarticle', $idarticle, PDO::PARAM_STR);
	$stmt_->execute();	
	$donnees_ = $stmt_->fetch();
	$stmt_->closeCursor();
	$stmt_=NULL;

	return $donnees['qte_liv']-$donnees_['qte_sortie'];
}
?>