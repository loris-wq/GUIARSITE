<?php
require '../config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM entregador WHERE email = '$email' and senha = '$senha'";
    $res = $pdo->query($sql);
    $data = $res->fetchAll();

    if($data){
        session_start();
        foreach($data as $entregador){
            $_SESSION['entregadorID'] = $entregador['id_entregador'];
        }
        header('Location: ../mapa/mapa.php');
    }

}

?>