<?php
require '../../config.php';
session_start();

// Verifica se o ID da empresa e do entregador estão definidos na sessão
if (!isset($_SESSION['company_id'], $_POST['entregador_id'], $_POST['pedido_ids'])) {
    die("Dados insuficientes para o envio. Faça login novamente.");
}

// Dados da sessão e da requisição
$entregador_id = $_POST['entregador_id'];
$pedido_ids_array = explode(',', $_POST['pedido_ids']);

try {
    // Inicia transação
    $pdo->beginTransaction();

    // Atualiza o status e atribui o entregador aos pedidos selecionados
    $stmt = $pdo->prepare("UPDATE pedido SET id_entregador = :entregador_id, status = 'Enviado' WHERE id_pedido = :pedido_id");

    foreach ($pedido_ids_array as $pedido_id) {
        $stmt->execute([
            ':entregador_id' => $entregador_id,
            ':pedido_id' => intval($pedido_id),
        ]);
    }

    // Confirma transação
    $pdo->commit();
} catch (PDOException $e) {
    // Reverte transação em caso de erro
    $pdo->rollBack();
    die("Erro ao atualizar os pedidos: " . $e->getMessage());
}

// Redireciona de volta para a página de pedidos
header("Location: ../pedidos.php");
exit();
?>