<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigo_inserido = $_POST['codigo_verificacao'] ?? '';

    if ($codigo_inserido == $_SESSION['codigo_verificacao']) {
        // Código correto, registrar a empresa no banco de dados
        require '../config.php';

        try {
            // Prepara a query para inserir os dados
            $sql = "INSERT INTO empresa (nome_empresa, cnpj, nome_usuario, email, senha, nome_arquivo) 
                    VALUES (:nome_empresa, :cnpj, :nome_usuario, :email, :senha, :nome_arquivo)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome_empresa', $_SESSION['nome_empresa']);
            $stmt->bindParam(':cnpj', $_SESSION['cnpj']);
            $stmt->bindParam(':nome_usuario', $_SESSION['nome_usuario']);
            $stmt->bindParam(':email', $_SESSION['email']);
            $stmt->bindParam(':senha', $_SESSION['senha']);
            $stmt->bindParam(':nome_arquivo', $_SESSION['nome_imagem']);

            // Executa a query
            if ($stmt->execute()) {
                // Sucesso no cadastro
                echo 'Cadastro realizado com sucesso!';
                session_unset(); // Limpar a sessão após o sucesso
                header("Location: ../ADM/loginEmpresa.php");
                exit();
            } else {
                echo 'Erro ao cadastrar a empresa.';
            }
        } catch (PDOException $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    } else {
        echo 'Código de verificação incorreto.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Código</title>
    <link
    rel="Shortcut Icon" 
    type="image/png"
    href="../img/G.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            background-color: #fff;
            padding: 40px 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            color: #555;
            text-align: center;
        }
        input[type="text"] {
            width: 80%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }
        button {
            background-color: #e06c00;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #f3bd0a;
        }
    </style>
</head>
<body>
    <h2>Insira o código de verificação</h2>
    <form method="post" action="">
        <label for="codigo_verificacao">Código de Verificação:</label>
        <input type="text" id="codigo_verificacao" name="codigo_verificacao" required>
        <button type="submit">Verificar</button>
    </form>
</body>
</html>