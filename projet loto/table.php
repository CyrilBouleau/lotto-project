<?php
//connexion à la base de donnée
require 'db.php';

//création de la table si elle n'existe pas
$sql = "CREATE TABLE IF NOT EXISTS `loto` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`tirage` varchar(261) NULL,
		`ip` varchar(40) NOT NULL,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

//prépare la requête
$stmt = $dbh->prepare($sql);

//exécute la requête
$stmt->execute();