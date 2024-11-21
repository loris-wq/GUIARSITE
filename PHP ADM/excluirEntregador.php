<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "guiartcc";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET["id"];

    $sql = "DELETE FROM entregador WHERE id_entregador=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Registro excluído com sucesso";
    } else {
        echo "Erro ao excluir registro: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
header("Location: entregadores.php");
exit();
?>