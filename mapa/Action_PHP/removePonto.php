<?php
// Conectar ao banco de dados
require '../../config.php'; // Inclua aqui seu arquivo de conexão com o banco de dados
session_start();
$id_entregador = $_SESSION['entregadorID'];

// Receber os dados da requisição
$data = json_decode(file_get_contents("php://input"), true);
$latitude = $data['latitude'];
$longitude = $data['longitude'];

// Verificar se as coordenadas foram fornecidas
if (!empty($latitude) && !empty($longitude)) {
    // Comando SQL para remover o ponto
    $sql = "DELETE FROM pedidos WHERE latitude = :latitude , longitude = :longitude , id_entregador = :id_entregador";
    
    // Preparar e executar a query usando PDO
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':latitude', $latitude);
    $stmt->bindParam(':longitude', $longitude);
    $stmt->bindParam(':id_entregador', $id_entregador);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false]);
}
?>
