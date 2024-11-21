<?php
require '../config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    session_start();

    $nome_cliente = $_POST['nome_cliente'];
    $preco = $_POST['preco'];
    $endereco = $_POST['endereco'];
    $bairro = $_POST['bairro'];
    $descricao = $_POST['descricao'];
    $latitude = $_POST['latitude'];
    $longitude =$_POST['longitude'];
    $id_empresa = $_SESSION['company_id'];

    $sql = "INSERT INTO pedido(id_pedido,nome_cliente,preco,endereco,bairro,descricao,latitude,longitude,id_entregador,id_empresa)
    VALUES(NULL, '$nome_cliente','$preco','$endereco','$bairro','$descricao',$latitude,$longitude,NULL,$id_empresa)";

    $res = $pdo->query($sql);

    header('Location: pedidos.php');
}


?>