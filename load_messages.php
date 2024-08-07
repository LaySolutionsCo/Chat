<?php
session_start();

//*Connexion à la BDD
$host = "localhost";
$id = "u362597761_sacha3";
$mdp = "Sachounette2609$";
$database = "u362597761_bdd3";
$conn = mysqli_connect($host, $id, $mdp, $database);

//*Récuperation de mon id et celui du destinataire depuis la session
$id_dest = $_SESSION['actual_friend'];
$id_recip = $_SESSION['id_recip'];
//*Deux types de messages qui doivent s'afficher dans la conversation : de moi à l'autre utilisateur ou de l'autre utilisateur à moi
$id_recip_dest1 = $id_recip . "_" . $id_dest;
$id_recip_dest2 = $id_dest . "_" . $id_recip;

//*Récuperations des messages de la BDD
$query_select = "SELECT name, messages, date FROM `table1` WHERE id_recip_dest = ? OR id_recip_dest = ? ORDER BY `table1`.`id` DESC";
$result = $conn->prepare($query_select);
$result->bind_param("ss", $id_recip_dest1, $id_recip_dest2);
$result->execute();
$result_set = $result->get_result();

//*Affichage des messages dans un boucles
foreach ($result_set as $row) {
    if ($_SESSION['name']==$row['name']) {
        $div_name_side = "right_message";
    }else{
        $div_name_side = "left_message";
    }
    echo '<div class="message_case">';
    echo "<div class='$div_name_side'>";
    echo '<strong><p style="font-size: 15px;">' . $row['name'] . '<p></strong>';
    echo '<p style="font-size: 15px;">' . $row['date'] . '<p>';
    echo '<br>';
    echo '<p style="font-size: 20px;">' . $row['messages'];
    echo '</div>';
    echo '</div>';
    echo '<br>';
}