<?php
// Inicia a sessão
session_start();

require '../config.php';

// Verifica se o ID da empresa está definido na sessão
if (!isset($_SESSION['company_id'])) {
  echo '<!DOCTYPE html>
  <html lang="pt-BR">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Erro de Acesso</title>
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
          <h1>Empresa não identificada</h1>
          <p>Faça login novamente.</p>
          <p><a href="../ADM/loginEmpresa.php">Clique aqui para voltar ao login</a></p>
      </div>
  </body>
  </html>';
  exit();
}

if (isset($_GET['logout'])) {
  session_destroy(); // Destroi a sessão
  header("Location: ../ADM/loginEmpresa.php"); // Redireciona para a página de login
  exit();
}

// ID da empresa logada
$company_id = $_SESSION['company_id'];

try {
  // Consulta SQL para selecionar o nome da empresa
  $sql_empresa = "SELECT nome_empresa FROM empresa WHERE id_empresa = :company_id";
  $stmt_empresa = $pdo->prepare($sql_empresa);
  $stmt_empresa->bindParam(':company_id', $company_id, PDO::PARAM_INT);
  $stmt_empresa->execute();
  $empresa = $stmt_empresa->fetch();
  $nome_empresa = $empresa['nome_empresa'];

  // Consulta SQL para selecionar administradores da empresa logada
  $sql = "SELECT administrador.* FROM administrador WHERE administrador.FK_EMPRESA_id_empresa = :company_id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo 'Erro: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <title>Perfis de Administradores</title>
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

    .container {
      margin-top: 50px;
    }

    .container h1{
      font-family: 'Brice-Bold';
    }

    .container h1 spam {
      -webkit-text-stroke-width: 1px;
      -webkit-text-stroke-color: #131646;
      -webkit-text-fill-color: #ff9a52;
    }

    .card {
      margin: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .card-body {
      padding: 20px;
    }

    .card-title {
      font-size: 1.5rem;
      margin-bottom: 15px;
      font-family: 'Brice-SemiBoldSemi';
    }

    .btn {
      background-color: #fc8835;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: 0.5s;
    }

    .btn:hover {
      color: white;
      background-color: #ff7b00;
      transform: scale(1.05);
      border-bottom-right-radius: 0px;
      border-top-left-radius: 0px;
    }

    .fixed-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1000;
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
      color: white;
      background-color: #ff7b00;
      transform: scale(1.05);
      border-bottom-right-radius: 0px;
      border-top-left-radius: 0px;
    }
    #error-message {
              color: #d8000c;
              text-decoration: none;
              font-weight: bold;
          }


  </style>
</head>

<body>
  <!-- Botão de logout -->
  <a href="escolherAdm.php?logout=true" class="logout-btn">Logout</a>

  <div class="container">
    <h1>Administradores da Empresa: <spam><?php echo htmlspecialchars($nome_empresa); ?></spam></h1>

    <div class="row">
      <?php
      if (count($result) > 0) {
        foreach ($result as $row) {
          // Caminho da imagem com o nome da empresa e o nome do arquivo da foto
          $imagem_path = "admin_fotos/" . htmlspecialchars($nome_empresa) . "/" . htmlspecialchars($row["nome_foto"]);
      ?>
            <div class="col-md-4">
            <div class="card">
              <!-- Exibe a foto do administrador com tamanho padrão -->
              <img src="<?php echo $imagem_path; ?>" class="card-img-top" alt="Foto do Administrador" style="width: 100%; height: 300px; object-fit: cover;">
              <div class="card-body">
                <center>
                <h5 class="card-title"><?php echo htmlspecialchars($row["nome_adm"]); ?></h5>
                <button class="btn" data-toggle="modal" data-target="#loginModal" data-username="<?php echo htmlspecialchars($row["nome_usuario"]); ?>">Entrar</button>
                </center>
              </div>
            </div>
          </div>
      <?php
        }
      } else {
        echo "<p>Nenhum administrador encontrado.</p>";
      }
      ?>
    </div>

  <!-- Modal de Login -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Login do Administrador</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="modais/loginAdm.php" method="post" id="adminLoginForm">
          <div class="modal-body">
            <div class="form-group">
              <label for="adminUsername">Nome de Usuário</label>
              <input type="text" class="form-control" id="adminUsername" name="adminUsername" readonly>
            </div>
            <div class="form-group">
              <label for="adminPassword">Senha</label>
              <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
            </div>
          </div>
          <div class="modal-footer">
            <!-- Exibir mensagem de erro se houver COMMIT TESTE-->

            <p id="error-message" class="text-danger" style="display: none;"></p>

            <script>
              // Função para remover o parâmetro 'erro' da URL após carregar a página
              window.onload = function() {
                const url = new URL(window.location);

                // Verifica se o parâmetro 'erro' está presente
                if (url.searchParams.has('erro')) {
                  // Remove o parâmetro 'erro'
                  url.searchParams.delete('erro');

                  // Atualiza a URL sem recarregar a página
                  window.history.replaceState({}, document.title, url);
                }
              };
            </script>
            <?php if (isset($_GET['erro2'])): ?>
              <p class="error-message"><?php echo htmlspecialchars($_GET['erro2']); ?></p>
            <?php endif ?>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Entrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    $('#loginModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget);
      var username = button.data('username');
      var modal = $(this);
      modal.find('.modal-body #adminUsername').val(username);
    });

    $('#adminLoginForm').submit(function(event) {
      event.preventDefault(); // Impede o envio padrão do formulário

      var form = $(this);
      $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize(),
        success: function(response) {
          console.log(response); // Para ver a resposta no console do navegador
          response = response.trim();
          if (response === 'success') {
            window.location.href = 'indexAdm.php';
          } else {
            $('#error-message').text('Senha incorreta. Tente novamente.').show();
            var senha = document.getElementById('adminPassword')
            senha.value = ""
          }
        }
      });
    });
  </script>

  <!-- Botão fixo "Adicionar Administrador" -->
  <button class="btn fixed-button" data-toggle="modal" data-target="#addAdminModal"><spam> + </spam> Adicionar Administrador</button>

  <!-- Modal de Adicionar Administrador -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addAdminModalLabel">Adicionar Administrador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="adicionarAdm.php" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <label for="adminNome">Nome Completo</label>
            <input type="text" class="form-control" id="adminNome" name="adminNome" required>
          </div>
          <div class="form-group">
            <label for="adminUsername">Nome de Usuário</label>
            <input type="text" class="form-control" id="adminUsername" name="adminUsername" required>
          </div>
          <div class="form-group">
            <label for="adminPassword">Senha</label>
            <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
          </div>
          <div class="form-group">
            <label for="adminFoto">Foto do Administrador</label>
            <input type="file" class="form-control-file" id="adminFoto" name="adminFoto" accept="image/*" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

  <script>
    // Adiciona o ID da empresa ao formulário antes de enviar commit2
    $('#addAdminModal form').on('submit', function() {
      $('<input>').attr({
        type: 'hidden',
        name: 'company_id',
        value: '<?php echo $company_id; ?>'
      }).appendTo(this);
    });
  </script>

</body>

</html>