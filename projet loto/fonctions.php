<?php
//connexion à la base de donnée
require 'db.php';


//Cette fonction est appelée lors du chargement de la page
function init_loto()
{
	//appel de la fonction recup_ip
	$ip = recup_ip();

	//appel de la fonction info_ip
	$donnee = info_ip($ip); //on récupére les infos de l'utilisateur
	
	var_dump($donnee);

	if ($donnee == false) { //si l'ip utilisateur n'est pas connue
		
		//il n'y a donc pas eu de tirage
		$tab = array();
		aff_grid($tab); //affichage du tableau des numéros de 1 à 90

		//appel de la fonction 'insert'
		insert($ip); //enregistrement de l'ip du nouvel utilisateur

	}else{ //si l'utilisateur est déjà connu 'user' vaut '1'
		//appel de la fonction explode_num
		$tab = explode_num($donnee['tirage']); //on récupére un tableau avec les num sortis

		aff_grid($tab);//affichage des num
	}
}


/*** fonction permettant de récupérer l'ip de l'utilisateur ***/
function recup_ip(){
	//on récupére l'adresse ip de l'utilisateur
	$ip = $_SERVER["REMOTE_ADDR"];

	return $ip;
}

/*** fonction permettant de récupérer les infos liés à cette ip si connue ***/
/*** retourn false sinon ***/
function info_ip($ip){
	//variable global
	global $dbh;

	//on récupére l'adresse ip de l'utilisateur
	$ip = recup_ip();
	
	//requéte permettant de récupérer la ligne de l'utilisateur(ip) en base
	$sql = "SELECT tirage, ip FROM loto WHERE ip = :ip";
	$stmt = $dbh->prepare($sql);  //envoie la requéte à MySQL

	$stmt->bindValue(":ip", $ip); //on lui donne la valeur

	//éxécution
	$stmt->execute();
	$ligne = $stmt->fetch();

	return $ligne;
}


/*** fonction permettant d'afficher un tableau html contenant les num de 1 à 90 ***/
/*** les num qui sont sortis s'affiche avec un fond rouge ***/
function aff_grid($tab)
{	
	var_dump($tab);
	//permet de trier le tableau pas odre croissant et clé également
	sort($tab);
	?>
	<table border="1">
		<thead>
			<tr><th colspan=10>Numéros</th></tr>
		</thead>
		<tbody>
			<?php
			$k=0;
			$l=0;
			$m=1;
			for ($i=0; $i<9; $i++)
			{ ?>
				<tr><?php
					for ($j=$k; $j<$k+10; $j++)
					{
						if (!empty($tab) && $l < count($tab))
						{
							if ($tab[$l] == $m)
							{ 
								?><td class="select"><?php echo $m; ?></td><?php 
								$l++;
							 	$m++;
							} else {
							 	?><td><?php echo $m; ?></td><?php
								$m++;
							}
						} else {
							?><td><?php echo $m; ?></td><?php
							$m++;
						}
					} ?>
				</tr>
			<?php $k=$k+10;
			} ?>
    	</tbody>
	</table><?php
}


/*** fonction permettant de convertir un tableau contenant des num en
chaine avec le séparateur "," ***/
function implode_num($tab){
	//convertion du tableau en chaine avec le séparateur ","
	$chaine = implode(",", $tab);
	return $chaine;
}


/*** fonction permettant de convertir une chaine de numéros,
en un tableau de numéros sans le séparateur ***/
function explode_num($chaine){
	//convertion de la chaine en tableau (on retire le séparateur ",")
	$tab = explode(",", $chaine);

	//on retire le dernier élément du tableau
	array_pop($tab);
	return $tab;
}


/*** fonction permettant de choisir aléatoirement un chiffre entre 1 et 90 ***/
function random_num(){
	$val = mt_rand(1,90);
	return $val;
}


/***  ***/
function tirage()
{
	//appel de la fonction recup_ip
	$ip = recup_ip();

	//appel de la fonction info_ip
	$donnee = info_ip($ip); //on récupére les infos de l'utilisateur

	//appel de la fonction explode_num
	$tab = explode_num($donnee['tirage']);

	if (count($tab) < 90) //si 90 tous les chiffres sont sortis
	{
		$check = array();
		do{ 
		 	$find = 0;

			do{
				$f = 0;
				$val = random_num();
				echo "tirage =".$val;
				foreach ($check as $value)
				{
					if ($val == $value)
					{
						$f = 1;
						break;  // on stoppe le foreach car le chiffre a déjà été tiré au sort
					}
				}
				if ($f == 0) {
					array_push($check, $val);
					var_dump($check);
				}
			} while ($f == 1);

			echo $val.'<br>';
			foreach ($tab as $value)
			{
				echo "chiff =".$val."  value =".$value."<br>";
				if ($val == $value)
				{
					$find = 1;
					echo "oui <br>";
					break;  // on stoppe le foreach car le chiffre est déjà en base
				}

			}
		} while ($find == 1);
		
		echo $val;

		$val = $val.',';

		//appel de la fonction de mise à jour
		//envoie du num sorti et de l'ip utilisateur */
		update($val, $ip);
	}
}


/*** fonction permettant d'insérer un nouvel utilisateur en base ***/
function insert($ip)
{
	global $dbh;

	//requête pour l'insertion dans la table loto
	$sql = "INSERT INTO loto (ip)
			VALUES (:ip)";

	$stmt = $dbh->prepare($sql);  //envoie la requére à MySQL

	$stmt->bindValue(":ip", $ip); //on lui donne la valeur

	//éxécution
	$stmt->execute();
}


/*** fonction permettant de mettre à jour la partie de l'utilisateur ***/
function update($val, $ip)
{
	global $dbh;

	//requête pour l'insertion dans la table loto
	$sql = "UPDATE loto 
			SET tirage = CONCAT(tirage, :val) 
			WHERE ip = :ip";

	$stmt = $dbh->prepare($sql);  //envoie la requére à MySQL

	$stmt->bindValue(":val", $val); //on lui donne la valeur
	$stmt->bindValue(":ip", $ip);

	//éxécution
	$stmt->execute();
}




/*** fonction permettant de générer un carton aléatoirement ***/
function rand_carton()
{
	// tableau contenant le carton final
	$carton = array(
	    1 => array(),
	    2 => array(),
	    3 => array()
	);

	?><pre><?php
	print_r($carton);?></pre><?php

	// colonnes pleines
	$full_cols = array_fill(0, 9, 0); //à retirer

	?><pre><?php
	print_r($full_cols);?></pre><?php
	echo "<br>";

	// tableau qui servira à garder les numéros choisis
	$tirage = array();

	$placed = 0;
	$row = 1;

	//tant que 15 chiffres ne sont pas placés
	while ($placed < 15)
	{	
		// tant que le chiffre sorti ne correspond pas
		do {
			$y = 0;

			// on tire un numéro au hazard
			$num = random_num();
			echo 'num choisi ='.$num.'<br>';

			foreach ($tirage as $value)
			{
				if ($num == $value)
				{
					$y = 1;
					echo "déjà sorti <br>";
					break;  // on stoppe le foreach car le chiffre a déjà été tiré au sort
				}
			}

			// si le chiffre n'est pas déjà sorti on continue
			if ($y == 0)
			{
				//recherche la position du chiffre sorti
				if ($num == 90)
			    {
			        $col = 8;
			    }
			    elseif ($num < 10)
			    {
			    	$col = 0;
			    }
			    else
			    {
			    	$col = substr($num, 0, 1);
			    }

			    echo "colonne ->".$col."<br>";
		    	echo "row = ".$row."<br>";

		    	// si on est sur la ligne 1 du carton
		    	if($row == 1)
		    	{	// vérifie si un chiffre n'est pas déja à l'emplacement 
		    		if(!empty($carton[$row][$col]))
			    	{
			    		$y = 1;
		    			echo "il y a déjà 1 chiffre dans cet case, ligne = ".$row."<br>";
			    	}
		    	}
		    	elseif ($row ==2) // si on est sur la ligne 2 du carton
		    	{	// vérifie si un chiffre n'est pas déja à l'emplacement
		    		if(!empty($carton[$row][$col]))
			    	{
			    		$y = 1;
		    			echo "il y a déjà 1 chiffre dans cet case, ligne = ".$row."<br>";
			    	}
		    	}
		    	elseif ($row == 3) // si on est sur la ligne 3 du carton
		    	{	// vérifie si un chiffre n'est pas déja à l'emplacement
		    		if(!empty($carton[$row][$col]))
			    	{
			    		$y = 1;
		    			echo "il y a déjà 1 chiffre dans cet case, ligne = ".$row."<br>";
			    	}
		    		elseif((!empty($carton[$row-1][$col])) && (!empty($carton[$row-2][$col])))
			    	{ // vérifie si l'emplacement à la ligne 1 et 2 n'est pas déjà occupé
			    	  // afin d'empécher d'avoir plus de 2 chiffres par colonne
			    		$y = 1;
		    			echo "il y a déjà 2 chiffres dans cet colonne<br>";
			    	}
		    	}
		    }	

		} while ($y == 1);

    	$carton[$row][$col] = $num;
    	array_push($tirage, $num);

    	var_dump($tirage);

    	$full_cols[$col]++; // à retirer
    	$placed++;

    	?><pre><?php
		print_r($carton);?></pre><?php
		?><pre><?php
		print_r($full_cols);?></pre><?php // à retirer

		// si il y a déjà 5 chiffres sur la ligne, on passe à la suivante
		if (count($carton[$row]) == 5)
		{
			$row++;
		}
	}

	//appel de la fonction permettant l'affichage du carton aléatoire
	aff_carton($carton);

	echo '<button id="choice">Je choisi ce carton</button>';
}



function aff_carton($carton)
{
	var_dump($carton);

	// génération du tableau
	echo '<table border="1">';
	foreach($carton as $row => $nums)
	{
   		echo '<tr>';
	    for($i = 0; $i < 9; ++$i)
	    {
	        echo '<td width="20" align="center">', ((isset($nums[$i])) ? $nums[$i] : '&nbsp;') , '</td>';
	    }
	    echo '</tr>';
	}
	echo '</table>';
}


/*** Vérification de la valeur de "ajax" en GET ***/
//si "ajax" vaut 1
if($_GET['ajax'] == 1)
{ 
	print_r($_GET);

	echo "chouette !!!";
	//initialisation du loto
	init_loto();
}

//si "ajax" vaut 2
if($_GET['ajax'] == 2)
{
	// $chaine = ',1,2,3,4,5,6,7,8,9,';
	// $tab = explode_num($chaine);
	// for ($i=0; $i <= count($tab); $i++) { 
	// 	if ($tab[$i] == '')
	// 	{
	// 		unset($tab[$i]);
	// 	}
	// }
	// sort($tab);

 	tirage();
	init_loto();
 	
}

//si "ajax" vaut 3
if($_GET['ajax'] == 3)
{
	rand_carton();


}

//si "ajax" vaut 4
if($_GET['ajax'] == 4)
{
	echo "sfnrg hegsgrgggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg";
}