<?php

//inclusion du contenu du fichier 
require 'table.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Projet Loto</title>
	<meta name="description" content="Projet Loto">
	<link href="css/base.css" type="text/css" rel="stylesheet" />
</head>
<body>

    <div id="init">
    </div>


    <div id="card">
    </div>

    <div id="wrapper3">
    </div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/jquery-1.11.3.min.js">\x3C/script>')</script>
    <script>
        $.ajax({
            url: "init.php",
            type: "GET",
            dataType: 'html',
            success: function(response) {
                $("#init").html(response)
            },
            error: function(){ //si le fichier n'est pas trouvé
                console.log("erreur initialisation");
            }
        });

        function card(){
            $.ajax({
                url: "card.php",
                type: "GET",
                dataType: 'html',
                success: function(response) {
                    $("#card").html(response)
                },
                error: function(){ //si le fichier n'est pas trouvé
                    console.log("erreur génération carton");
                }
            });
        }
    
        //  $("#card").click(function() {
        //     $.ajax({
        //         url: "card.php",
        //         type: "GET",
        //         dataType: 'html',
        //         success: function(response) {
        //             $("#init").html(response)
        //         },
        //         error: function(){ //si le fichier n'est pas trouvé
        //             console.log("erreur dans la requête X");
        //         }
        //     });
        // });

// $("#carton").click(function() {
//     $.ajax({
//         url: "fonctions.php",
//         type: "GET",
//         data: {
//             "ajax" : "3"
//         },
//         success: function(response) {
//             $("#wrapper2").html(response)
//         },
//         error: function(){ //si le fichier n'est pas trouvé
//             console.log("erreur dans la requête X");
//         }
//     });
// });

// $("#choice").click(function() {
//     $.ajax({
//         url: "fonctions.php",
//         type: "GET",
//         data: {
//             "ajax" : "4"
//         },
//         success: function(response) {
//             $("#wrapper3").html(response)
//         },
//         error: function(){ //si le fichier n'est pas trouvé
//             console.log("erreur dans la requête X");
//         }
//     });
// });
    </script>
</body>
</html>