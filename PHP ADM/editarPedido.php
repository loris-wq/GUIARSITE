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
    $id_pedido = $_POST['id_pedido'];

    $sql = "UPDATE pedido SET nome_cliente = '$nome_cliente', preco = $preco, endereco = '$endereco', bairro = '$bairro', descricao = '$descricao', latitude = $latitude, longitude = $longitude WHERE id_pedido = $id_pedido";
    
    $res = $pdo->query($sql);

    header('Location: pedidos.php');
}


?>