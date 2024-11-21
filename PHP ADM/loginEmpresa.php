<?php
require '../config.php';

// Inicia a sessão
session_start();

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $nome_usuario = $_POST['username'];
    $senha = $_POST['password'];

    try {
        // Prepara a query para verificar o nome de usuário e senha na tabela 'empresa'
        $sql_empresa = "SELECT * FROM empresa WHERE nome_usuario = :nome_usuario AND senha = :senha";
        $stmt_empresa = $pdo->prepare($sql_empresa);
        $stmt_empresa->bindParam(':nome_usuario', $nome_usuario);
        $stmt_empresa->bindParam(':senha', $senha);
        $stmt_empresa->execute();

        // Verifica se o usuário existe na tabela 'empresa'
        if ($stmt_empresa->rowCount() > 0) {
            // Login realizado com sucesso como empresa
            $empresa = $stmt_empresa->fetch();
            
            // Define o ID da empresa na sessão
            $_SESSION['company_id'] = $empresa['id_empresa'];
            
            // Redireciona para a página escolherAdm.php
            header("Location: escolherAdm.php");
            exit;
        } else {
            // Caso o usuário ou senha estejam incorretos, redireciona de volta com mensagem de erro
            $erro = 'Nome de usuário ou senha incorretos';
            header("Location: ../ADM/loginEmpresa.php?erro=" . urlencode($erro));
            exit();
        }
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
    }
}

// Fecha a conexão com o banco de dados
$pdo = null;
?>
