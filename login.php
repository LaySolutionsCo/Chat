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
    echo "Connexion impossibles";
} else {
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $password = $_POST['password'];

        $query = "SELECT id_recip FROM userbdd WHERE name = ? AND password = ?";
        $result = $conn->prepare($query);
        $result->bind_param("ss", $name, $password);
        $result->execute();
        $result->store_result();

        if ($result->num_rows > 0) {
            $result->bind_result($id_recip);
            $result->fetch();

            $_SESSION['name'] = $name;
            $_SESSION['id_recip'] = $id_recip;
            header("Location: /friend.php");
        } else {
            $errorMessage = "Mot de passe ou nom d'utilisateur incorrect!";
        }
    }
}
?>
<html>

<head>
    <title>Connexion</title>
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
        <h1>Connexion</h1>
        <br>
        <br>
        <input type="text" name="name" placeholder="Nom d'utilisateur">
        <br>
        <br>
        <input type="password" name="password" placeholder="Mot de passe">
        <br>
        <br>
        <input type="submit" name="submit" value="Se connecter">
        <br>
        <br>
        <br>
        <a class="button" href="signin.php">
            Crée un compte
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