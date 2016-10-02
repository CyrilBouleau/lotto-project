<?php

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

/*** fonction permettant de choisir aléatoirement un chiffre entre 1 et 90 ***/
function random_num(){
	$val = mt_rand(1,90);
	return $val;
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