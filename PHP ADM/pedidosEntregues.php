<?php
// Inicia a sessão
session_start();
require '../config.php';

// Verifica se o ID da empresa está definido na sessão
if (!isset($_SESSION['company_id'])) {
    die("Empresa não identificada. Faça login novamente.");
}

// ID da empresa logada
$company_id = $_SESSION['company_id'];

// Verifica se o administrador está logado
if (!isset($_SESSION['nome_usuario'])) {
    if (!isset($_SESSION['nome_usuario'])) {
        echo '<!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Erro de Acesso</title>
            <link
    rel="Shortcut Icon" 
    type="image/png"
    href="../img/G.png">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f8f9fa;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .error-message {
                    background-color: #ffdddd;
                    color: #d8000c;
                    border: 1px solid #d8000c;
                    padding: 20px;
                    border-radius: 5px;
                    text-align: center;
                    max-width: 400px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }
                .error-message h1 {
                    margin: 0;
                    font-size: 18px;
                }
                .error-message p {
                    margin-top: 10px;
                    font-size: 16px;
                }
                .error-message a {
                    color: #d8000c;
                    text-decoration: none;
                    font-weight: bold;
                }
            </style>
        </head>
        <body>
            <div class="error-message">
                <h1>Administrador não identificado</h1>
                <p>Faça login novamente.</p>
                <p><a href="escolherAdm.php">Clique aqui para voltar ao login</a></p>
            </div>
        </body>
        </html>';
        exit();
    }
}



// Função de logout
if (isset($_GET['logout'])) {
    unset($_SESSION['nome_usuario']); // Remove o nome do administrador
    header("Location: escolherAdm.php"); // Redireciona para a página de escolher adm
    exit();
}

$nomeAdmin = $_SESSION['nome_usuario'];

// Verifica se o botão "Finalizar Turno" foi clicado
if (isset($_POST['finalizar_turno'])) {
    try {
        $sqlDelete = "DELETE FROM pedido WHERE status = 'entregue' AND id_empresa = :company_id";
        $stmtDelete = $pdo->prepare($sqlDelete);
        $stmtDelete->bindParam(':company_id', $company_id, PDO::PARAM_INT);
        $stmtDelete->execute();
        echo "<script>alert('Turno finalizado! Todos os pedidos entregues foram removidos.');</script>";
        header("Refresh:0"); // Recarrega a página para atualizar a lista de pedidos
        exit();
    } catch (PDOException $e) {
        echo "Erro ao finalizar turno: " . $e->getMessage();
    }
}

try {
    // Consulta para buscar pedidos com status "entregue" e o nome do entregador na tabela "pedido_entregue"
    $sql = "
    SELECT pedido.id_pedido, pedido.nome_cliente, pedido.preco, pedido.endereco, pedido.bairro, pedido.descricao, pedido.id_entregador, pedido.status, entregador.nome_completo AS 'nome_entregador' FROM pedido
    JOIN entregador
    ON pedido.id_entregador = entregador.id_entregador
    WHERE pedido.status = 'entregue' AND pedido.id_empresa = :company_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
    $stmt->execute();
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos Entregues</title>
    <link
    rel="Shortcut Icon" 
    type="image/png"
    href="../img/G.png">
    <style>
        @font-face {
            font-family: 'Brice-Bold';
            src: url('../fonts/Brice-BoldSemiCondensed.ttf') format('truetype');
        }

        @font-face {
            font-family: 'BasisGrotesque-Regular';
            src: url('../fonts/BasisGrotesqueArabicPro-Regular.ttf') format('truetype');
        }

        @font-face {
            font-family: 'Brice-SemiBoldSemi';
            src: url('../fonts/Brice-SemiBoldSemiCondensed.ttf');
        }

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            background-color: #fefaf1 !important;
            font-family: 'BasisGrotesque-Regular';
        }

        .sidebar {
    height: 100vh;
    width: 250px;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #111;
    padding-top: 20px;
    display: flex;
    flex-direction: column;
}

.sidebar a {
    padding: 15px 25px;
    text-decoration: none;
    font-size: 18px;
    color: white;
    display: block;
    transition: 0.3s;
}

.sidebar a:hover {
    background-color: #575757;
}

.sidebar .spacer {
    flex:  0.9; /* Esta div empurra o terceiro botão para o fundo */
}

.main {
    margin-left: 250px;
    padding: 15px;
}
        /* Estilo dos cards */
        .card-container {
            width: 80%;
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
            border-left: 7px solid #e06c00;
            border-radius: 5px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
            min-width: 35%;
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 20px;
            color: #333;
            font-family: 'Brice-SemiBoldSemi';
        }

        .card p {
        margin: 5px 0;
        color: #555;
        }

        .card p strong {
         font-weight: bold;
        }

        button {
            font-family: 'BasisGrotesque-Regular';
            transition: 0.5s;
            background-color: #fc8835;
        }

        button:hover {
            color: white;
      background-color: #ff7b00;
      transform: scale(1.05);
      border-bottom-right-radius: 0px;
      border-top-left-radius: 0px;
        }

        /* Estilo para posicionar o botão no canto superior direito  comita por favor */
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #fc8835;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .logout-btn:hover {
            color: white;
      background-color: #ff7b00;
      transform: scale(1.05);
      border-bottom-right-radius: 0px;
      border-top-left-radius: 0px;
        }


        /* finalizar turno */
        .finalizar-btn {
        position: fixed;
        bottom: 100px;
        right: 20px; /* Alterado de 'left' para 'right' */
        padding: 10px 20px;
        background-color: #fc8835;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        }

        .finalizar-btn:hover {
            color: white;
      background-color: #ff7b00;
      transform: scale(1.05);
      border-bottom-right-radius: 0px;
      border-top-left-radius: 0px;
        }

    </style>
</head>
<body>
    <div class="sidebar">]
        <a href="../PHP ADM/indexAdm.php">Início</a>
        <a href="../PHP ADM/pedidos.php">Pedidos</a>
        <a href="../PHP ADM/entregadores.php">Entregadores</a>
        <a href="../PHP ADM/pedidosEntregues.php">Pedidos Entregues</a>
        <div class="spacer"></div>
        <a href="../PHP ADM/meuPerfil.php">Meu perfil</a>
    </div>

    <!-- Botão de logout -->
 <a href="pedidosEntregues.php?logout=true" class="logout-btn">Logout</a>

    <div class="main">
        <div class="card-container">
            <?php
            // Exibição dos pedidos em forma de cards
            if ($pedidos) {
                foreach ($pedidos as $pedido) {
                    echo '<div class="card">';
                    echo '<h3>Pedido #' . $pedido['id_pedido'] . '</h3>';
                    echo '<p><strong>Cliente:</strong> ' . htmlspecialchars($pedido['nome_cliente']) . '</p>';
                    echo '<p><strong>Preço:</strong> R$ ' . number_format($pedido['preco'], 2, ',', '.') . '</p>';
                    echo '<p><strong>Endereço:</strong> ' . htmlspecialchars($pedido['endereco']) . '</p>';
                    echo '<p><strong>Bairro:</strong> ' . htmlspecialchars($pedido['bairro']) . '</p>';
                    echo '<p><strong>Descrição:</strong> ' . htmlspecialchars($pedido['descricao']) . '</p>';
                    echo '<p><strong>Entregador:</strong> ' . htmlspecialchars($pedido['nome_entregador']) . '</p>';
                    echo '<p><strong>Status:</strong> ' . htmlspecialchars($pedido['status']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>Nenhum pedido entregue encontrado.</p>';
            }
            ?>
        </div>
    </div>

     <!-- Botão "Finalizar Turno" -->
     <form method="POST">
        <button type="submit" name="finalizar_turno" class="finalizar-btn">Finalizar Turno</button>
    </form>
</body>
</html>