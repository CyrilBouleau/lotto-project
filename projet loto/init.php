<?php
//connexion à la base de donnée
require 'db.php';


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
	echo'<a href="javascript:card();"> Générer un carton </a>';

}else{ //si l'utilisateur est déjà connu 'user' vaut '1'
	//appel de la fonction explode_num
	$tab = explode_num($donnee['tirage']); //on récupére un tableau avec les num sortis

	aff_grid($tab);//affichage des num
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