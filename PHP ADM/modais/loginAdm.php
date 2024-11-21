<?php
session_start();

require '../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminUsername = $_POST['adminUsername'];
    $adminPassword = $_POST['adminPassword'];

    try {
        // Consulta para verificar o login do administrador
        $sql = "SELECT * FROM administrador WHERE nome_usuario = :adminUsername AND senha = :adminPassword";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':adminUsername', $adminUsername, PDO::PARAM_STR);
        $stmt->bindParam(':adminPassword', $adminPassword, PDO::PARAM_STR);
        $stmt->execute();

        // Verifica se a consulta retornou algum resultado
        if ($stmt->rowCount() > 0) {
            // Obtém os dados do administrador
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            // Inicia a sessão do administrador
            $_SESSION['id_adm'] = $admin['id_adm']; // Armazena o ID do administrador
            $_SESSION['nome_usuario'] = $admin['nome_usuario']; // Armazena o nome do administrador
     
            // Retorna sucesso
            echo "success";
        } else {
            echo "error";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>