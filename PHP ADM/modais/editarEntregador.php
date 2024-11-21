<?php
require '../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nome_completo = $_POST["nome_completo"];
    $CPF = $_POST["CPF"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];

    // Prepare o SQL usando PDO
    $sql = "UPDATE entregador SET nome_completo = :nome_completo, CPF = :CPF, telefone = :telefone, email = :email WHERE id_entregador = :id_entregador";
    $stmt = $pdo->prepare($sql);

    // Vincule os parâmetros
    $stmt->bindParam(':nome_completo', $nome_completo);
    $stmt->bindParam(':CPF', $CPF);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id_entregador', $id, PDO::PARAM_INT);

    // Execute a declaração e verifique o sucesso
    if ($stmt->execute()) {
        echo "Registro atualizado com sucesso";
    } else {
        echo "Erro ao atualizar registro: " . implode(", ", $stmt->errorInfo());
    }
}

// Redireciona para a página de entregadores
header("Location: ../entregadores.php");
exit();
?>