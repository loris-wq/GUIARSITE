<?php
require '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_pedido = $_POST['id_pedido'];
    $nome_cliente = $_POST['nome_cliente'];
    $preco = $_POST['preco'];
    $endereco = $_POST['endereco'];
    $bairro = $_POST['bairro'];
    $descricao = $_POST['descricao'];

    $sql = "UPDATE pedido 
            SET nome_cliente = '$nome_cliente', preco = $preco, endereco = '$endereco', bairro = '$bairro', descricao = '$descricao' 
            WHERE id_pedido = $id_pedido";

    $pdo->query($sql);

    echo "Pedido atualizado com sucesso";
    header("Location: ../pedidos.php");
    exit();
}
?>