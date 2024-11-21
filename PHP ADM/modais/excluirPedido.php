<?php
require '../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pedido = $_POST["id_pedido"];

    try {
        $stmt = $pdo->prepare("DELETE FROM pedido WHERE id_pedido = :id_pedido");
        $stmt->execute([':id_pedido' => $id_pedido]);
        
        echo "Pedido excluído com sucesso";
        header("Location: ../pedidos.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao excluir pedido: " . $e->getMessage();
    }
}
?>