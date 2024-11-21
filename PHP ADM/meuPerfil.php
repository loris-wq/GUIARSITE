<?php
// Inicie a sessão, se ainda não estiver iniciada
session_start();

// Verifica se o ID da empresa está definido na sessão
if (!isset($_SESSION['company_id'])) {
    die("Empresa não identificada. Faça login novamente.");
}

// Verifica se o administrador está logado
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

// Função de logout
if (isset($_GET['logout'])) {
    unset($_SESSION['nome_usuario']); // Remove o nome do administrador
    header("Location: escolherAdm.php"); // Redireciona para a página de escolher adm
    exit();
}

$company_id = $_SESSION['company_id']; 
$nomeAdmin = $_SESSION['nome_usuario'];

// Conectar ao banco de dados e buscar as informações do administrador
require '../config.php';

try {
    $sql = "SELECT * FROM administrador WHERE nome_usuario = :nome_usuario AND FK_EMPRESA_id_empresa = :company_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_usuario', $nomeAdmin, PDO::PARAM_STR);
    $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$admin) {
        die("Administrador não encontrado.");
    }

    if (isset($_SESSION['company_name'])) {
        $nome_empresa = $_SESSION['company_name'];
    } else {
        // Caso o nome da empresa não esteja na sessão, você pode buscar do banco
        $stmtEmpresa = $pdo->prepare("SELECT nome_empresa FROM empresa WHERE id_empresa = :company_id");
        $stmtEmpresa->bindParam(':company_id', $company_id, PDO::PARAM_INT);
        $stmtEmpresa->execute();
        $empresa = $stmtEmpresa->fetch(PDO::FETCH_ASSOC);
        $nome_empresa = $empresa['nome_empresa'];
    }

    // Recuperar a foto do administrador
    $fotoAdmin = htmlspecialchars($admin['nome_foto']);
    $caminho_foto = 'admin_fotos/' . htmlspecialchars($nome_empresa) . '/' . $fotoAdmin;
    
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil | Administrador</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Garante que a altura da tela ocupe toda a viewport */
            margin: 0;
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
            flex: 0.9;
        }
        
        .main {
            margin-left: 250px;
            padding: 15px;
        }

        /* Estilo do card */
        .card {
            max-width: 500px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(153, 87, 34, 0.15);
            text-align: center;
            transition: box-shadow 0.3s;
        }

        .card:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 400px;
            height: 400px;
            border-radius: 50%;
            margin-bottom: 20px;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card h1 {
            font-family: 'Brice-Bold';
            font-size: 40px;
            color: black;
            margin-bottom: 10px;
            -webkit-text-stroke-width: 1px;
      -webkit-text-stroke-color: #131646;
      -webkit-text-fill-color: #ff9a52;
        }

        .card p {
            font-size: 20px;
            color: #666;
            margin: 5px 0;
        }

        .card button {
            padding: 10px 20px;
            font-size: 19px;
            color: #fff;
            background-color: #fc8835;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.5s;
        }

        .card button:hover {
            background-color: #ff7b00;
            transform: scale(1.05);
            border-bottom-right-radius: 0px;
            border-top-left-radius: 0px;
        }

        /* Estilo para posicionar o botão no canto superior direito */
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
            transition: 0.5s;
        }

        .logout-btn:hover {
            background-color: #ff7b00;
            transform: scale(1.05);
            border-bottom-right-radius: 0px;
            border-top-left-radius: 0px;
        }
        
         /* Estilos do Modal */
         .modal {
            display: none; /* Inicialmente escondido */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); /* Cor de fundo semitransparente */
            padding-top: 60px;
        }

        /* Modal Conteúdo */
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        /* Botões do Modal */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-input {
            width: 99%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .modal-button {
            padding: 10px 20px;
            background-color: #fc8835;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: 0.5s;
        }

        .modal-button:hover {
            background-color: #ff7b00;
            transform: scale(1.05);
            border-bottom-right-radius: 0px;
            border-top-left-radius: 0px;
        }

   
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="../PHP ADM/indexAdm.php">Início</a>
        <a href="../PHP ADM/pedidos.php">Pedidos</a>
        <a href="../PHP ADM/entregadores.php">Entregadores</a>
        <a href="../PHP ADM/pedidosEntregues.php">Pedidos Entregues</a>
        <div class="spacer"></div>
        <a href="">Meu perfil</a>
    </div>

    <a href="indexAdm.php?logout=true" class="logout-btn">Logout</a>

    <div class="main">
    <div class="card">
        <img src="<?php echo htmlspecialchars($caminho_foto); ?>" alt="Foto do Administrador">
        <h1><?php echo htmlspecialchars($admin['nome_adm']); ?></h1>
        <p>Usuário: <?php echo htmlspecialchars($admin['nome_usuario']); ?></p>
        <p>Senha: <?php echo str_repeat('*', strlen($admin['senha'])); ?></p><br> <!-- Senha mascarada -->
        <button onclick="openModal()">Editar</button>
    </div>
</div>

    <!-- Modal para Edição -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Editar Perfil</h2>
            <form method="POST" action="modais/editarPerfil.php">
                <label for="nome_adm">Nome:</label>
                <input type="text" id="nome_adm" name="nome_adm" class="modal-input" value="<?php echo htmlspecialchars($admin['nome_adm']); ?>" required>

                <label for="nome_usuario">Usuário:</label>
                <input type="text" id="nome_usuario" name="nome_usuario" class="modal-input" value="<?php echo htmlspecialchars($admin['nome_usuario']); ?>" required>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" class="modal-input" value="<?php echo htmlspecialchars($admin['senha']); ?>" required>

                <button type="submit" class="modal-button">Salvar alterações</button>
            </form>
        </div>
    </div>

    <script>
        // Função para abrir o modal
        function openModal() {
            document.getElementById("editModal").style.display = "block";
        }

        // Função para fechar o modal
        function closeModal() {
            document.getElementById("editModal").style.display = "none";
        }

        // Fechar o modal se o usuário clicar fora da área do modal
        window.onclick = function(event) {
            if (event.target == document.getElementById("editModal")) {
                closeModal();
            }
        }
    </script>
</body>
</html>