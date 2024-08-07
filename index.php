<?php
session_start();
//*Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['name'])) {
    header("Location: /login.php");
}

//* Connexion à la BDD
$host = "localhost";
$id = "u362597761_sacha3";
$mdp = "Sachounette2609$";
$database = "u362597761_bdd3";
$conn = mysqli_connect($host, $id, $mdp, $database);

//*Définition des variable de mon ID et celui du destinataire
$id_dest = $_GET['friend'];
$id_recip = $_SESSION['id_recip'];
//*Definition de l'ID dest et recip regroupé (dans les deux configuration possible)
$id_recip_dest = $id_recip . "_" . $id_dest;
$id_recip_dest1 = $id_dest . "_" . $id_recip;

//*Enregistrer dans un variable de session la personne avec qui on chat (notament pour le fichier load_messages)
$_SESSION['actual_friend'] = $id_dest;

//*Requete dans la BDD des conversations
$query = "SELECT id_recip_dest FROM conv WHERE id_recip_dest = ? OR id_recip_dest = ?";
$result = $conn->prepare($query);
$result->bind_param("ss", $id_recip_dest, $id_recip_dest1);
$result->execute();
$result->store_result();
          
//*Verification de l'existance de la conversation dans la BDD
if ($result->num_rows > 0) {
   //*Conversation deja ajouté dans la BDD
} else {
    //*Conversation inexistante dans la BDD, ajout de la conversations dans la BDD
    $query_insert1 = "INSERT INTO conv (id_recip_dest, user1, user2) VALUES (?, ?, ?)";
    $stmt_insert1 = $conn->prepare($query_insert1);
    $stmt_insert1->bind_param("sss", $id_recip_dest, $id_recip, $id_dest);
    $stmt_insert1->execute();
}

//*Definition de la timezone
date_default_timezone_set('Europe/Paris');

if (isset($_POST['submit'])) {
    $name = ($_POST['name']);
    $date = date("Y-m-d H:i:s");
    $message = nl2br(($_POST['message']));

    //*Insertion d'un message dans la BDD
    $query_insert = "INSERT INTO table1 (name, date, messages, id_dest, id_recip, id_recip_dest) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("ssssss", $name, $date, $message, $id_dest, $id_recip, $id_recip_dest);
    $stmt_insert->execute();
}
?>
<html>

<head>
    <title>Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.7" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <style>
        * {
            font-family: Arial;
        }

        body {
            margin-top: 170px;
            background-color: black;
        }

        form input[type="submit"] {
            color: white;
            padding: 10px;
            background-color: #254996;
            border: 2px solid #254996;
            margin-left: 10px;
            font-size: 20px;
            border-radius: 10px;
        }

        form input[type="submit"]:hover {
            color: #254996;
            padding: 10px;
            background-color: black;
            border: 2px solid 254996;
            margin-left: 10px;
            font-size: 20px;
            border-radius: 10px;
        }


        form input[type="text"] {
            padding: 10px;
            border: 1px solid #254996;
            margin-left: 10px;
            font-size: 20px;
            border-radius: 10px;
        }

        .refreshbutton {
            color: white;
            padding: 10px;
            background-color: #254996;
            border: 1px solid #254996;
            margin-left: 10px;
            font-size: 20px;
            border-radius: 10px;
        }

        .refreshbutton:hover {
            color: #254996;
            padding: 10px;
            background-color: black;
            border: 1px solid #254996;
            margin-left: 10px;
            font-size: 20px;
            border-radius: 10px;

        }

        .button {
            color: white;
            padding: 10px;
            background-color: #254996;
            border: 1px solid #254996;
            margin-left: 10px;
            margin-bottom: 20px;
            font-size: 20px;
            text-decoration: none;
            border-radius: 10px;
        }

        .button:hover {
            color: #254996;
            padding: 10px;
            background-color: black;
            border: 1px solid #254996;
            margin-left: 10px;
            font-size: 20px;
            border-radius: 10px;

        }
        
        .message_case {
            max-width: 85%;
            padding-left: 10px;
            display: flex;
         }
        
        .left_message,
        .right_message {
            padding: 15px;
            background-color: #254996;
            border-radius: 15px;
            word-wrap: break-word;
            overflow: hidden;
        }

        .right_message {
            background-color: #1e90ff;
            align-self: flex-end;
        }

        .general_message1 {
            display: inline-block;
            width: 100%;
            
        }

        .general_message {
            width: 100%;
        }
    </style>
</head>
<header style="position: fixed; top: 0; left: 0; width: 100%; background-color: #353535; padding: 10px; z-index: 999; ">
    <form method="POST" action="">
        <input type="hidden" name="name" value="<?php echo $_SESSION['name']; ?>" placeholder="Nom">
        <input type="text" name="message" placeholder="Messages">
        <input type="submit" name="submit" value="Envoyer">
        <button class="refreshbutton" onclick="window.location.reload();">Rafraîchir</button>
    </form>
    <a class="button" href="https://chat.laysolutions.fr/friend.php">
        Retour
    </a>
</header>
<div>
    <br>
    <div class="general_message">
        <div class="general_message1" id="general_message1"></div>
    </div>
    <script>
        //* Actualisation tout les x ms du fichier load_messages.php pour recuperer les messages
        setInterval('load_messages()', 100);

        function load_messages() {
            $('#general_message1').load('load_messages.php');
        }
    </script>

</div>

</html>