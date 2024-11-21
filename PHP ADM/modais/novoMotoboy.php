<?php
require '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    // Verifica se o ID da empresa está definido na sessão
    if (!isset($_SESSION['company_id'])) {
        die("Empresa não identificada. Faça login novamente.");
    }

    // ID da empresa logada
    $company_id = $_SESSION['company_id'];

    $nome_completo = $_POST['nome_completo'];
    $CPF = $_POST['CPF'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $nome_usuario = $_POST['nome_usuario'];
    $senha = $_POST['senha'];

    // Verificar e criar diretório para fotos se não existir
    $upload_dir = '../Entregadores/' . $company_id;
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Processar a foto 3x4
    $foto_3x4_name = basename($_FILES['foto_3x4']['name']);
    $foto_3x4_path = $upload_dir . '/' . $foto_3x4_name;
    if (!move_uploaded_file($_FILES['foto_3x4']['tmp_name'], $foto_3x4_path)) {
        die("Erro ao carregar a foto 3x4.");
    }

    // Processar a foto CNH
    $foto_CNH_name = basename($_FILES['foto_CNH']['name']);
    $foto_CNH_path = $upload_dir . '/' . $foto_CNH_name;
    if (!move_uploaded_file($_FILES['foto_CNH']['tmp_name'], $foto_CNH_path)) {
        die("Erro ao carregar a foto CNH.");
    }

    // Inserir novo motoboy no banco de dados usando PDO
    $sql = "INSERT INTO entregador (nome_completo, cpf, telefone, nome_foto3x4, email, nome_usuario, senha, nome_cnh, FK_EMPRESA_id_empresa)
            VALUES ('$nome_completo', '$CPF', '$telefone', '$foto_3x4_name', '$email', '$nome_usuario', '$senha', '$foto_CNH_name', $company_id)";

    $res = $pdo->query($sql);

    header('Location: ../entregadores.php');
    exit();
}
?>