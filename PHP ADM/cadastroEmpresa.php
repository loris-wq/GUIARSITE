<?php
require '../config.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receber os dados do formulário
    $nome_empresa = $_POST['nome_empresa'] ?? '';
    $cnpj = $_POST['cnpj'] ?? '';
    $nome_usuario = $_POST['nome_usuario'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Lógica para o upload da imagem
    $diretorio = 'logo_empresas/';
    
    // Verifica se o diretório existe, se não existir, tenta criar
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    // Lógica para verificar e mover a imagem
    if (isset($_FILES['foto_logo']) && $_FILES['foto_logo']['error'] == 0) {
        $nome_imagem = $_FILES['foto_logo']['name'];
        $caminho_imagem = $diretorio . basename($nome_imagem);
        move_uploaded_file($_FILES['foto_logo']['tmp_name'], $caminho_imagem);
    }

    // Gerar código de verificação (4 números)
    $codigo_verificacao = rand(1000, 9999);

    // Iniciar sessão para salvar temporariamente os dados do cadastro
    session_start();
    $_SESSION['nome_empresa'] = $nome_empresa;
    $_SESSION['cnpj'] = $cnpj;
    $_SESSION['nome_usuario'] = $nome_usuario;
    $_SESSION['email'] = $email;
    $_SESSION['senha'] = $senha;
    $_SESSION['nome_imagem'] = $nome_imagem;
    $_SESSION['codigo_verificacao'] = $codigo_verificacao;

    // Enviar o código de verificação via email
    $mail = new PHPMailer(true);
    try {
        // Configurações do servidor de email
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Seu provedor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'lorismigz.pam@gmail.com'; // Seu e-mail SMTP
        $mail->Password = 'edts pdst pvfj ffkv'; // Sua senha SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configurações de envio
        $mail->setFrom('lorismigz.pam@gmail.com', 'GUIAR');
        $mail->addAddress($email);

        // Conteúdo do email
        $mail->isHTML(true);
        $mail->Subject = 'Codigo de Verificacao';
        $mail->Body = "Seu codigo de verificacao eh: $codigo_verificacao";

        // Enviar email
        $mail->send();

        // Redirecionar para a página de verificação
        header('Location: verificarCodigo.php');
        exit();

    } catch (Exception $e) {
        echo "Erro ao enviar email: {$mail->ErrorInfo}";
    }
}
?>