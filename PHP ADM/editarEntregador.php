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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nome_completo = $_POST["nome_completo"];
    $CPF = $_POST["CPF"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];

    $sql = "UPDATE entregador SET nome_completo=?, CPF=?, telefone=?, email=? WHERE id_entregador=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nome_completo, $CPF, $telefone, $email, $id);

    if ($stmt->execute()) {
        echo "Registro atualizado com sucesso";
    } else {
        echo "Erro ao atualizar registro: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
header("Location: entregadores.php");
exit();
?>