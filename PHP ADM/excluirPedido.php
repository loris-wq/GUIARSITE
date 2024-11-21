<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root"; // substitua pelo seu usuário
$password = "";   // substitua pela sua senha
$dbname = "guiartcc";

// Criar uma conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar se houve erro na conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verificar se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pedido = $_POST["id_pedido"];

    // Excluir o pedido da tabela 'pedido'
    $sql = "DELETE FROM pedido WHERE id_pedido = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_pedido);

    if ($stmt->execute()) {
        echo "Pedido excluído com sucesso";
        header("Location: pedidos.php"); // Redireciona para a página principal
        exit();
    } else {
        echo "Erro ao excluir pedido: " . $stmt->error;
    }

    // Fechar o statement
    $stmt->close();
}

// Fechar a conexão
$conn->close();
?>