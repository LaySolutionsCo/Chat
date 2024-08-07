<?php
session_start();
//*Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['name'])) {
    header("Location: /login.php");
}

//*Initialisation du message d'erreur
$error_message = NULL;

//*Connexion à la BDD
$host = "localhost";
$id = "u362597761_sacha3";
$mdp = "Sachounette2609$";
$database = "u362597761_bdd3";
$conn = mysqli_connect($host, $id, $mdp, $database);

//*Verification de la connexion
if (!$conn) {
    echo "Connexion impossible : " . mysqli_connect_error();
} else {
    if (isset($_POST['submit'])) {
        $id_input = $_POST['id_input'];
        $link = "https://chat.laysolutions.fr/index.php?friend=" . $id_input;

        //*Récuperation de l'utilisateur demandé dans l'input
        $query = "SELECT id_recip FROM userbdd WHERE id_recip = ?";
        $result = $conn->prepare($query);
        $result->bind_param("s", $id_input);
        $result->execute();
        $result->store_result();

        //*Verification de l'existance de l'utilisateurs
        if ($result->num_rows > 0) {
            header("Location: $link");
        } else {
            $error_message = "Utilisateur inexistant";
        }
    }
}
?>
<html>

<head>
    <title>Friends</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.7" />
    <style>
        * {
            font-family: Arial;
        }

        header {
            padding-top: 20px;
            padding-left: 10px;
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-color: black;
        }

        form {
            display: flex;
        }

        form input[type="submit"] {
            color: white;
            padding: 10px;
            background-color: #254996;
            border: 2px solid #254996;
            font-size: 20px;
            border-radius: 10px;
            margin-left: 10px;
        }

        form input[type="submit"]:hover {
            color: #254996;
            padding: 10px;
            background-color: black;
            border: 2px solid #254996;
            font-size: 20px;
            border-radius: 10px;
        }


        form input[type="text"] {
            padding: 10px;
            border: 2px solid #254996;
            font-size: 20px;
            border-radius: 10px;
        }

        form input[type="password"] {
            padding: 10px;
            border: 2px solid #254996;
            font-size: 20px;
            border-radius: 10px;
        }

        .button {
            color: white;
            padding: 10px;
            background-color: red;
            border: 2px solid red;
            font-size: 20px;
            border-radius: 10px;
            text-decoration: nonE;
            margin-top: 20px;
        }

        .button:hover {
            color: red;
            padding: 10px;
            background-color: black;
            border: 2px solid red;
            font-size: 20px;
            border-radius: 10px;
            text-decoration: none;
        }
        
        .bloc1 {
            padding-left: 10px;
            padding-right: 10px;
            padding-bottom: 80px;
        }
        
        .bloc2 {
            padding-left: 10px;
            padding-right: 10px;
        }

        @media (max-width: 768px) {
            .friend {
            padding-top: 30px;
            height: 200px;
            display: flex;
            justify-content: space-between;
            flex-direction: column;
        }
        }
        .friend {
            padding-top: 30px;
            height: 200px;
            display: flex;
            justify-content: space-between;
        }

        .friend h1 {
            font-size: 40px;
            color: white;
            margin-bottom: 10px;
            margin-top: 0px;
        }

        .friend h2 {
            font-size: 2Opx;
            color: white;
            margin: 0px;
        }

        .error_message {
            color: red;
        }
        
        .conv_name {
            color: white;
            font-size: 25px;
        }
        
        .button_conv_name {
            color: white;
        }
    </style>
</head>
<header>
    <a class="button" href="/logout.php">
        Déconnexion
    </a>
</header>
<div class="friend">
    <div class="bloc1">
        <h1>Ajouter un(e) ami(e)</h1>
        <form method="POST">
            <input type="text" name="id_input" placeholder="Entrez l'ID de votre ami(e)">
            <input type="submit" name="submit">
        </form <?php
                echo '<p class="error_message">' . $error_message . '</p>';
                ?> <br>
        <h2>
            <?php
            //*Affichage de mon ID
            echo "Mon ID : " . $_SESSION['id_recip'];
            ?>
        </h2>
    </div>
    <div class="bloc2">
        <h1>Mes ami(es)</h1>
        <?php
        //*Récuperations de toutes les conversations contenant mon id
        $query2 = "SELECT * FROM conv WHERE user1 = ? OR user2 = ?";
        $result2 = $conn->prepare($query2);
        $result2->bind_param("ss", $_SESSION['id_recip'], $_SESSION['id_recip']);
        $result2->execute();
        $result2_final = $result2->get_result();

        //*Parcourir les conversatrions pour connaitre l'ID du destinataire et ensuite chercher son nom dans la BDD, redirection vers la conversation 
        while ($row = $result2_final->fetch_assoc()) {
            //*On deduit grace aux  conditions if quelle est l'ID du destinataire
            if ($row['user1'] == $_SESSION['id_recip']) {
                $id_dest = $row['user2'];
                
                //*Rechercher le nom dans la BDD a partir de l'ID
                $query3 = "SELECT * FROM userbdd WHERE id_recip = ?";
                $result3 = $conn->prepare($query3);
                $result3->bind_param("s", $id_dest);
                $result3->execute();
                $result3_final = $result3->get_result();

                while ($row1 = $result3_final->fetch_assoc()) {
                    //*Affichage du bouton avec le nom
                    echo '
                    <a class="button_conv_name" href="https://chat.laysolutions.fr/index.php?friend=' . $id_dest . '">
                    <h3 class="conv_name">' . $row1['name'] . '</h3>
                    </a>
                    ';
                }
            //*On deduit grace aux  conditions if quelle est l'ID du destinataire
            } else if ($row['user2'] == $_SESSION['id_recip']) {
                $id_dest = $row['user1'];
                
                //*Rechercher le nom dans la BDD a partir de l'ID
                $query4 = "SELECT * FROM userbdd WHERE id_recip = ?";
                $result4 = $conn->prepare($query4);
                $result4->bind_param("s", $id_dest);
                $result4->execute();
                $result4_final = $result4->get_result();

                while ($row2 = $result4_final->fetch_assoc()) {
                    //*Affichage du bouton avec le nom
                    echo '
                    <a class="button_conv_name" href="https://chat.laysolutions.fr/index.php?friend=' . $id_dest . '">
                    <h3 class="conv_name">' . $row2['name'] . '</h3>
                    </a>
                    ';
                }
            }
        }
        ?>
    </div>
</div>

</html>
