<?php
session_start();
if (isset($_SESSION['name'])) {
    header("Location: /friend.php");
    exit();
}
$host = "localhost";
$id = "u362597761_sacha3";
$mdp = "Sachounette2609$";
$database = "u362597761_bdd3";

$conn = mysqli_connect($host, $id, $mdp, $database);

if (!$conn) {
    echo "Connexion impossible : " . mysqli_connect_error();
} else {
    if (isset($_POST['submit'])) {
        if (!isset($_POST['name']) || empty($_POST['name']) || !isset($_POST['password']) || empty($_POST['password'])) {
            $errorMessage = "Veuillez remplire tout les champs!";
        } else {
            $name = $_POST['name'];
            $password = $_POST['password'];
            $id_recip = rand(10000, 1000000);

            $query = "SELECT * FROM userbdd WHERE name = ?";
            $result_name = $conn->prepare($query);
            $result_name->bind_param("s", $name);
            $result_name->execute();
            $result_name->store_result();

            $query1 = "SELECT * FROM userbdd WHERE id_recip = ?";
            $result_id = $conn->prepare($query1);
            $result_id->bind_param("s", $id_recip);
            $result_id->execute();
            $result_id->store_result();

            if (!$result_name or !$result_id) {
                die("Erreur de requête : " . mysqli_error($conn));
            }

            if ($result_id->num_rows > 0) {
                header('Location: friend.php');
            }

            if ($result_name->num_rows > 0) {
                $errorMessage = "Nom d'utilisateur déjà utilisé!";
            } else {
                $query3 = "INSERT INTO userbdd (name, password, id_recip) VALUES (?, ?, ?)";
                $stmt_insert = $conn->prepare($query3);
                $stmt_insert->bind_param("sss", $name, $password, $id_recip);

                if ($stmt_insert->execute()) {
                    $_SESSION['name'] = $name;
                    $_SESSION['id_recip'] = $id_recip;
                    header("Location: /friend.php");
                } else {
                    $errorMessage = "Erreur lors de l'inscription!";
                }
            }
        }
    }
}
?>
<html>

<head>
    <title>Inscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.7" />
    <style>
        * {
            font-family: Arial;
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: black;
        }

        form input[type="submit"] {
            color: white;
            padding: 10px;
            background-color: #254996;
            border: 2px solid #254996;
            font-size: 20px;
            border-radius: 10px;
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
            background-color: #254996;
            border: 2px solid #254996;
            font-size: 20px;
            border-radius: 10px;
            text-decoration: none;
        }

        .button:hover {
            color: #254996;
            padding: 10px;
            background-color: black;
            border: 2px solid #254996;
            font-size: 20px;
            border-radius: 10px;
            text-decoration: none;
        }

        .connecter {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            width: 300px;
            height: 200px;
        }

        .connecter h1 {
            font-size: 40px;
            color: white;
        }
    </style>
</head>
<div class="connecter">
    <form method="POST" action="">
        <h1>Inscrption</h1>
        <br>
        <br>
        <input type="text" name="name" placeholder="Nom d'utilisateur">
        <br>
        <br>
        <input type="password" name="password" placeholder="Mot de passe">
        <br>
        <br>
        <input type="submit" name="submit" value="S'inscrire">
        <br>
        <br>
        <br>
        <a class="button" href="login.php">
            Se connecter
        </a>
        <br>
        <br>
        <?php
        if (isset($errorMessage)) {
            echo '<p style="color: red;">' . $errorMessage . '</p>';
        }
        ?>
    </form>
</div>

</html>