<?php
require '../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET["id"];

    // Prepare o SQL usando PDO
    $sql = "DELETE FROM entregador WHERE id_entregador = :id_entregador";
    $stmt = $pdo->prepare($sql);

    // Vincule o parâmetro
    $stmt->bindParam(':id_entregador', $id, PDO::PARAM_INT);

    // Execute a declaração e verifique o sucesso
    if ($stmt->execute()) {
        echo "Registro excluído com sucesso";
    } else {
        // Obtenha informações detalhadas sobre o erro
        echo "Erro ao excluir registro: " . implode(", ", $stmt->errorInfo());
    }
}

// Redireciona para a página de entregadores
header("Location: ../entregadores.php");
exit();
?>