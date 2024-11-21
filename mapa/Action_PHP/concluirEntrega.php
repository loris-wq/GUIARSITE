<?php
require '../../config.php';
$data = json_decode(file_get_contents("php://input"), true);

if ($data['id']) {
    $pedido = $data['id'];
    $sql = "UPDATE pedido SET status = 'entregue' WHERE id_pedido = $pedido";

    $stmt = $pdo->query($sql);
    if ($stmt) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
