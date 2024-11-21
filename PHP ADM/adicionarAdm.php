<?php
require '../config.php';

session_start();

if (!isset($_SESSION['company_id'])) {
    die("Empresa não identificada. Faça login novamente.");
}

$company_id = $_SESSION['company_id'];

// Receber dados do formulário
$nome_adm = $_POST['adminNome'];
$nome_usuario = $_POST['adminUsername'];
$senha = $_POST['adminPassword'];
// Para criptografar: $senha = password_hash($_POST['adminPassword'], PASSWORD_BCRYPT);

// Obter o nome da empresa a partir do company_id
try {
    $sqlEmpresa = "SELECT nome_empresa FROM empresa WHERE id_empresa = :company_id";
    $stmtEmpresa = $pdo->prepare($sqlEmpresa);
    $stmtEmpresa->bindParam(':company_id', $company_id, PDO::PARAM_INT);
    $stmtEmpresa->execute();
    $empresa = $stmtEmpresa->fetch(PDO::FETCH_ASSOC);

    if ($empresa) {
        $nome_empresa = $empresa['nome_empresa'];

        // Verificar se um arquivo de foto foi enviado
        if (isset($_FILES['adminFoto']) && $_FILES['adminFoto']['error'] == 0) {
            // Definir o diretório onde a imagem será salva
            $diretorioDestino = 'admin_fotos/' . $nome_empresa . '/';

            // Crie o diretório da empresa se ele não existir
            if (!is_dir($diretorioDestino)) {
                mkdir($diretorioDestino, 0777, true);
            }

            // Obter informações do arquivo de imagem
            $fotoNome = basename($_FILES['adminFoto']['name']);
            $extensaoArquivo = pathinfo($fotoNome, PATHINFO_EXTENSION);

            // Gera um nome único para a imagem
            $fotoNomeUnico = uniqid() . '.' . $extensaoArquivo;
            $fotoDestino = $diretorioDestino . $fotoNomeUnico;

            // Mover o arquivo para o destino final
            if (move_uploaded_file($_FILES['adminFoto']['tmp_name'], $fotoDestino)) {
                // Imagem movida com sucesso
                try {
                    // Inserir os dados do administrador, incluindo o nome da imagem
                    $sql = "INSERT INTO administrador (nome_adm, nome_usuario, nome_foto, senha, FK_EMPRESA_id_empresa) 
                            VALUES (:nome_adm, :nome_usuario,:foto, :senha, :company_id)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':nome_adm', $nome_adm, PDO::PARAM_STR);
                    $stmt->bindParam(':nome_usuario', $nome_usuario, PDO::PARAM_STR);
                    $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
                    $stmt->bindParam(':foto', $fotoNomeUnico, PDO::PARAM_STR); // Salvando apenas o nome da imagem
                    $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        // Redirecionar após sucesso
                        echo "Administrador adicionado com sucesso.";
                        header("Location: escolherAdm.php");
                        exit();
                    } else {
                        echo "Erro ao adicionar administrador.";
                    }
                } catch (PDOException $e) {
                    echo "Erro: " . $e->getMessage();
                }
            } else {
                // Exibir erro se a imagem não for movida corretamente
                echo "Erro ao fazer upload da foto.";
            }
        } else {
            // Caso não haja upload de imagem ou erro no envio
            echo "Nenhuma foto foi enviada ou ocorreu um erro ao enviar a foto.";
        }
    } else {
        echo "Empresa não encontrada.";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>