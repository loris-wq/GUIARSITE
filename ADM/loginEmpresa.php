<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Empresa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="../CssnavbarRodape.css" rel="stylesheet" />
  <link
    rel="Shortcut Icon" 
    type="image/png"
    href="../img/G.png">

  <style>
@font-face {
  font-family: 'Brice-BoldSemi';
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

    .formulario {
      border-top: 3px solid #fc8835 !important;
      width: 50%;
      padding: 7%;
      margin: 5%;
    }

    .formulario h2{
      font-family: 'Brice-SemiBoldSemi';
    }

    #form {
      border-top: 3px solid #fc8835 !important;
      border: 1px black;
      border-radius: 5px;
      box-shadow: 1px 1px 10px -5px black !important;
    }

    #botao {
      width: 70%;
      background-color: #fc8835;
      border: 0px solid;
      letter-spacing: 1px;
      transition: 0.5s;
    }

    #botao:hover {
      transform: scale(1.05);
      border-bottom-right-radius: 0px;
      border-top-left-radius: 0px;
    }

    #esqueceusenha{
      color: #ff9a52;
    }

    #cadastro {
      color: #000;
      text-decoration: none;
      
    }

    #cadastro spam {
      color: #ff9a52;
    }

    .error-message {
      color: red;
      font-weight: bold;
    }

    @media (max-width: 880px) {
    .formulario {
      width: 70%;
    }
    }
  </style>

</head>

<body>
<nav class="navbar navbar-expand-lg custom-navbar" id="gblur">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.html"><img style= "height: 90px;" src="../img/Guiar.png" alt="LOGO"></img></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="../index.html">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="ativado" href="loginEmpresa.php">Empresa</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../ENTREGADOR/loginEntregador.php">Entregador</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row align-items-center">
      <center>
        <div class="formulario" id="form">
          <h2>Login | Empresa</h2>
          <hr color="black" size="2px">
          <br>

          <!-- Exibir mensagem de erro se houver COMMIT TESTEeeeeee-->
          <?php if (isset($_GET['erro'])): ?>
            <p class="error-message"><?php echo htmlspecialchars($_GET['erro']); ?></p>
          <?php endif ?>

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

          <form method="post" action="../PHP ADM/loginEmpresa.php">
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Nome de Usuário</label>
              <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="username" required>
            </div>
            <div class="mb-3">
              <label for="exampleInputPassword1" class="form-label">Senha</label>
              <input type="password" class="form-control" id="exampleInputPassword1" name="password" required>
              <a id="esqueceusenha" href="EsqueceuSenha/esqueceuSenha.php">Esqueceu sua senha?</a>
            </div>
            <br>
            <input type="submit" class="btn btn-primary" id="botao" value="Entrar"><br>
            <a id="cadastro" href="cadastroEmpresa.html">Não tem uma conta? <spam>Faça cadastro</spam></a>
          </form>
        </div>
      </center>
    </div>
  </div>

  <footer class="footer bg-dark text-white text-center">
    <div class="container p-3">
      <p>&copy; 2024 GUIAR. Todos os direitos reservados.</p>
      <ul class="list-unstyled">
        <li><a href="#" class="text-white">Política de Privacidade</a></li>
        <li><a href="#" class="text-white">Termos de Serviço</a></li>
      </ul>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous">
  </script>
</body>

</html>
