<?php
require '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    // Verifique se o id_adm está na sessão
    if (!isset($_SESSION['id_adm'])) {
        echo "Erro: Administrador não identificado.";
        exit;
    }

    $id_adm = $_SESSION['id_adm'];
    $nome_cliente = $_POST['nome_cliente'];
    $preco = $_POST['preco'];
    $endereco = $_POST['endereco'];
    $bairro = $_POST['bairro'];
    $descricao = $_POST['descricao'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $id_empresa = $_SESSION['company_id'];
    $status = 'Pendente'; // Valor padrão para o status

    // Query para inserir o pedido no banco de dados
    $sql = "INSERT INTO pedido (id_pedido, nome_cliente, preco, endereco, bairro, descricao, latitude, longitude, id_entregador, id_empresa, id_adm, status)
        VALUES (NULL, :nome_cliente, :preco, :endereco, :bairro, :descricao, :latitude, :longitude, NULL, :id_empresa, :id_adm, :status);";
    // Preparar a query
    $stmt = $pdo->prepare($sql);

    // Executar a query com os parâmetros corretos
    $stmt->execute([
        ':nome_cliente' => $nome_cliente,
        ':preco' => $preco,
        ':endereco' => $endereco,
        ':bairro' => $bairro,
        ':descricao' => $descricao,
        ':latitude' => $latitude,
        ':longitude' => $longitude,
        ':id_empresa' => $id_empresa,
        ':id_adm' => $id_adm,
        ':status' => $status
    ]);

    // Redireciona para a página de pedidos
    header('Location: ../pedidos.php');
}
?>