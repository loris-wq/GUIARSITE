<?php
session_start();
require '../../config.php';

// Obter os dados enviados pelo formulário
$novo_nome = $_POST['nome_adm'];
$novo_usuario = $_POST['nome_usuario'];
$nova_senha = $_POST['senha'];

// Verificar se o administrador está logado
if (!isset($_SESSION['nome_usuario']) || !isset($_SESSION['company_id'])) {
    die("Acesso não autorizado.");
}

$usuario_atual = $_SESSION['nome_usuario'];
$empresa_id = $_SESSION['company_id'];

try {
    $sql = "UPDATE administrador 
            SET nome_adm = :nome_adm, nome_usuario = :nome_usuario, senha = :senha
            WHERE nome_usuario = :usuario_atual AND FK_EMPRESA_id_empresa = :empresa_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_adm', $novo_nome);
    $stmt->bindParam(':nome_usuario', $novo_usuario);
    $stmt->bindParam(':senha', $nova_senha);
    $stmt->bindParam(':usuario_atual', $usuario_atual);
    $stmt->bindParam(':empresa_id', $empresa_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Atualizar a variável de sessão com o novo nome de usuário
        $_SESSION['nome_usuario'] = $novo_usuario;
        header("Location: ../meuPerfil.php?status=success");
        exit();
    } else {
        header("Location: ../meuPerfil.php?status=error");
        exit();
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>