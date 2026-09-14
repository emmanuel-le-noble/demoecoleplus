<?php 
    include("../modele/connexion.php");

	$req=(' SELECT id,moyentrimes
			FROM note
			WHERE 
			note.idsalle in (2,3)
			AND 
			note.idmatiere in (16,21)');
			
	$stmt = $pdo->prepare($req);
	$stmt->execute();	
	while($donnees = $stmt->fetch())
	{
	    $moyenne_trimestre_1 = trim(variant_int(round($donnees['moyenpondere'])));
		$id = $donnees['id'];
		
		if($moyenne_trimestre_1=="4")
		{
			$appre = "T.Faible";						
		}
		
		if($moyenne_trimestre_1=="5" OR $moyenne_trimestre_1=="6")
		{
			$appre = "Faible";						
		}		
		
		if($moyenne_trimestre_1=="7")
		{
			$appre = "T.insuffisant";						
		}	
		
		if($moyenne_trimestre_1=="8" OR $moyenne_trimestre_1=="9")
		{
			$appre = "Insuffisant";						
		}				
		
		if($moyenne_trimestre_1=="10" OR $moyenne_trimestre_1=="11")
		{
			$appre = "Passable";						
		}			
		
		if($moyenne_trimestre_1=="12" OR $moyenne_trimestre_1=="13")
		{
			$appre = "Assez-Bien";						
		}
		
		if($moyenne_trimestre_1=="14" OR $moyenne_trimestre_1=="15")
		{
			$appre = "Bien";						
		}	
		
		if($moyenne_trimestre_1=="16" OR $moyenne_trimestre_1=="17")
		{
			$appre = "Tres Bien";						
		}		
		
		if($moyenne_trimestre_1=="18" OR $moyenne_trimestre_1=="19" OR $moyenne_trimestre_1=="20")
		{
			$appre = "Excellent";						
		}
		
		if($moyenne_trimestre_1=="3")
		{
			$appre = "T.Faible";						
		}	

		if($moyenne_trimestre_1=="1")
		{
			$appre = "T.Faible";						
		}

		if($moyenne_trimestre_1=="2")
		{
			$appre = "T.Faible";						
		}	

		if($moyenne_trimestre_1=="0")
		{
			$appre = "T.Faible";						
		}

		if($moyenne_trimestre_1=="")
		{
			$appre = "";						
		}
		
		$requete =' UPDATE note SET note.observation=:observation WHERE note.id=:id';
			
		$stmt_ = $pdo->prepare($requete);
		$stmt_ -> bindParam(':id', $id, PDO::PARAM_INT);
		$stmt_ -> bindParam(':observation', $appre, PDO::PARAM_INT);
		$stmt_ -> execute();			
		$stmt_ -> closeCursor();
		$stmt_ = NULL;
	}
	$stmt->closeCursor();
	$stmt=NULL;
?> 