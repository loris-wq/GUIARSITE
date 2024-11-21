<?php
// Inicia a sessão
session_start();

// Verifica se o ID da empresa está definido na sessão
if (!isset($_SESSION['company_id'])) {
    die("Empresa não identificada. Faça login novamente.");
}

// ID da empresa logada
$company_id = $_SESSION['company_id'];
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_completo = $_POST['nome_completo'];
    $CPF = $_POST['CPF'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $nome_usuario = $_POST['nome_usuario'];
    $senha = $_POST['senha'];

    $foto_3x4 = addslashes(file_get_contents($_FILES['foto_3x4']['tmp_name']));
    $foto_CNH = addslashes(file_get_contents($_FILES['foto_CNH']['tmp_name']));

    // Inserir novo motoboy no banco de dados
    $sql = "INSERT INTO entregador (nome_completo, cpf, telefone, foto_3x4, email, nome_usuario, senha, foto_CNH, FK_EMPRESA_id_empresa) 
            VALUES ('$nome_completo', '$CPF', '$telefone', '$foto_3x4', '$email', '$nome_usuario', '$senha', '$foto_CNH', '$company_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Novo motoboy cadastrado com sucesso";
        header("Location: entregadores.php "); // Redirecione para a página principal
        exit();
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>