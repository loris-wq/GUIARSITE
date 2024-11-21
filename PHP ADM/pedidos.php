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

try {
    // Consultar pedidos da empresa logada, incluindo o campo do entregador
    $sql = "
        SELECT p.id_pedido, p.nome_cliente, p.preco, p.endereco, p.bairro, p.descricao, p.status, e.nome_completo AS nome_entregador
        FROM pedido p
        LEFT JOIN entregador e ON p.id_entregador = e.id_entregador
        WHERE p.id_empresa = :company_id
        AND p.status != 'entregue'
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Consulta SQL para selecionar entregadores da empresa logada
    $sqlEntregadores = "SELECT id_entregador, nome_completo FROM entregador WHERE FK_EMPRESA_id_empresa = :company_id";
    $stmtEntregadores = $pdo->prepare($sqlEntregadores);
    $stmtEntregadores->bindParam(':company_id', $company_id, PDO::PARAM_INT);
    $stmtEntregadores->execute();
    $resultEntregadores = $stmtEntregadores->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Pedidos</title>
    <link rel="stylesheet" href="../CSSadm/pedidos.css"> 
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
        /* cards */
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
            font-family: 'Brice-SemiBoldSemi';
        }

        .card-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .card input[type="checkbox"] {
            position: absolute;
            top: 10px;
            right: 10px;
            border-radius: 5px;
        }

        .status {
            font-weight: bold;
            color: #ff7b00;
        }

        .fixed-buttons {
            background-color: #fc8835;
            font-family: 'BasisGrotesque-Regular';
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: 0.5s;
        }

        .fixed-buttons button {
            font-family: 'BasisGrotesque-Regular';
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #fc8835;
            color: white;
            border: none;
            border-radius: 5px;
            transition: 0.5s;
        }

        .fixed-buttons button:hover {
            background-color: #ff7b00;
            transform: scale(1.05);
            border-bottom-right-radius: 0px;
            border-top-left-radius: 0px;
        }

        /* Estilo para o formulário dentro do modal */
        #sendOrdersToDeliveryForm {
            font-family: 'BasisGrotesque-Regular';
            display: flex;
            flex-direction: column;
        }

        #entregadoresContainer {
            font-family: 'BasisGrotesque-Regular';
            margin-bottom: 15px;
        }

        select {
            font-family: 'BasisGrotesque-Regular';
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }

        button {
            font-family: 'BasisGrotesque-Regular';
            background-color: #fc8835;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #ff7b00;
            transform: scale(1.05);
            border-bottom-right-radius: 0px;
            border-top-left-radius: 0px;
        }

        /* Estilo para campos ocultos */
        input[type="hidden"] {
            display: none;
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
        }

        .logout-btn:hover {
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
        <a href="../PHP ADM/meuPerfil.php">Meu perfil</a>
    </div>

      <!-- Botão de logout -->
      <a href="pedidos.php?logout=true" class="logout-btn">Logout</a>

    <div class="main">
        <form id="sendOrdersForm" method="POST" action="modais/enviarPedidos.php">
            <div class="container">
                <?php
                if (count($result) > 0) {
                    // Exibir dados de cada linha em um card
                    foreach ($result as $row) {
                        echo "<div class='card'>";
                        echo "<input type='checkbox' name='pedido_ids[]' value='" . htmlspecialchars($row["id_pedido"]) . "'>";
                        echo "<h3>" . htmlspecialchars($row["nome_cliente"]) . "</h3>";
                        echo "<p><strong>Preço:</strong> R$" . htmlspecialchars($row["preco"]) . "</p>";
                        echo "<p><strong>Endereço:</strong> " . htmlspecialchars($row["endereco"]) . "</p>";
                        echo "<p><strong>Bairro:</strong> " . htmlspecialchars($row["bairro"]) . "</p>";
                        echo "<p><strong>Descrição:</strong> " . htmlspecialchars($row["descricao"]) . "</p>";
                        echo "<p class='status'><strong>Status:</strong> " . htmlspecialchars($row["status"]) . "</p>";
                        // Exibir o nome do entregador, se houver
                        if (!empty($row["nome_entregador"])) {
                            echo "<p><strong>Entregador:</strong> " . htmlspecialchars($row["nome_entregador"]) . "</p>";
                        } else {
                            echo "<p><strong>Entregador:</strong> Não atribuído</p>";
                        }
                        echo "<div class='card-actions'>";
                        echo "<button type='button' class='btn-edit' data-id='" . htmlspecialchars($row["id_pedido"]) . "'>Editar</button>";
                        echo "<button type='button' class='btn-delete' data-id='" . htmlspecialchars($row["id_pedido"]) . "'>Excluir</button>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Nenhum pedido encontrado</p>";
                }
                ?>
            </div>


            <!-- Botões fixos no canto inferior direito -->
            <div class="fixed-buttons">
                <button type="button" id="openNewOrderModal">Adicionar Novo Pedido</button>
                <button type="button" id="openSendOrdersModal">Enviar Pedidos Selecionados</button>
            </div>
        </form>
    </div>

    <!-- Modal de Novo Pedido -->
    <div id="newOrderModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeNewOrderModal">&times;</span>
            <h2>Adicionar Novo Pedido</h2>
            <form id="newOrderForm" method="POST" action="modais/adicionarPedido.php">
                <div class="form-group">
                    <label for="nome_cliente">Nome do Cliente:</label>
                    <input type="text" id="nome_cliente" name="nome_cliente" required>
                </div>
                <div class="form-group">
                    <label for="preco">Preço:</label>
                    <input type="number" id="preco" name="preco" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" required>
                </div>
                <div class="form-group">
                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" name="bairro" required>
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao" required></textarea>
                </div>
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">

                <div class="form-group">
                    <button type="button" onclick="geocodeAddress()">Salvar Pedido</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Enviar Pedidos -->
    <div id="sendOrdersModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeSendOrdersModal">&times;</span>
            <h2>Enviar Pedidos Selecionados</h2>
            <form id="sendOrdersToDeliveryForm" method="POST" action="modais/enviarPedidos.php">
                <div id="entregadoresContainer">
                    <?php
                    if (count($resultEntregadores) > 0) {
                        echo "<select id='entregadorSelect' name='entregador_id' required>";
                        echo "<option value=''>Selecione um Entregador</option>";
                        foreach ($resultEntregadores as $entregador) {
                            echo "<option value='" . htmlspecialchars($entregador["id_entregador"]) . "'>" . htmlspecialchars($entregador["nome_completo"]) . "</option>";
                        }
                        echo "</select>";
                    } else {
                        echo "<p>Nenhum entregador encontrado</p>";
                    }
                    ?>
                    </div>

                <input type="hidden" id="selected_pedido_ids" name="pedido_ids">
                <button type="submit">Enviar Pedidos</button>
            </form>
        </div>
    </div>

  <!-- Modal de Edição de Pedido -->
<div id="editOrderModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEditOrderModal">&times;</span>
        <h2>Editar Pedido</h2>
        <form id="editOrderForm" method="POST" action="editarPedido.php">
            <input type="hidden" id="edit_id_pedido" name="id_pedido">
            <div class="form-group">
                <label for="edit_nome_cliente">Nome do Cliente:</label>
                <input type="text" id="edit_nome_cliente" name="nome_cliente" required>
            </div>
            <div class="form-group">
                <label for="edit_preco">Preço:</label>
                <input type="number" id="edit_preco" name="preco" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="edit_endereco">Endereço:</label>
                <input type="text" id="edit_endereco" name="endereco" required>
            </div>
            <div class="form-group">
                <label for="edit_bairro">Bairro:</label>
                <input type="text" id="edit_bairro" name="bairro" required>
            </div>
            <div class="form-group">
                <label for="edit_descricao">Descrição:</label>
                <textarea id="edit_descricao" name="descricao" required></textarea>
            </div>
            <input type="hidden" id="edit_latitude" name="latitude">
            <input type="hidden" id="edit_longitude" name="longitude">

            <div class="form-group">
            <button type="button" onclick="geocodeAddressEdit()">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

   
    <!-- Modal de Exclusão -->
    <div id="deleteOrderModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeDeleteOrderModal">&times;</span>
            <h2>Excluir Pedido</h2>
            <p>Tem certeza de que deseja cancelar este pedido?</p>
            <form id="deleteOrderForm" method="POST" action="modais/excluirPedido.php">
                <input type="hidden" id="delete_id_pedido" name="id_pedido">
                <div class="form-group">
                    <button type="submit">Sim, cancelar</button>
                    <button type="button" id="cancelDelete">não</button>
                </div>
            </form>
        </div>
    </div>
</div>  

    

<script>
// Função para abrir o modal de novo pedido
document.getElementById('openNewOrderModal').addEventListener('click', function() {
    document.getElementById('newOrderModal').style.display = 'block';
});

// Função para fechar o modal de novo pedido
document.getElementById('closeNewOrderModal').addEventListener('click', function() {
    document.getElementById('newOrderModal').style.display = 'none';
});

// Função para abrir o modal de envio de pedidos
document.getElementById('openSendOrdersModal').addEventListener('click', function() {
            var selectedOrders = document.querySelectorAll('input[name="pedido_ids[]"]:checked');
            if (selectedOrders.length === 0) {
                alert('Selecione pelo menos um pedido.');
                return;
            }
            // Coleta os IDs dos pedidos selecionados
            var selectedPedidoIds = Array.from(selectedOrders).map(order => order.value).join(',');

            // Define os IDs dos pedidos selecionados no campo oculto
            document.getElementById('selected_pedido_ids').value = selectedPedidoIds;

            // Exibe o modal
            document.getElementById('sendOrdersModal').style.display = 'block';
        });
// Função para fechar o modal de envio de pedidos
document.getElementById('closeSendOrdersModal').addEventListener('click', function() {
    document.getElementById('sendOrdersModal').style.display = 'none';
});

// Função para abrir o modal de edição e preencher os campos com os dados atuais
document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function() {
        const pedidoId = this.getAttribute('data-id');
        const card = this.closest('.card');

        const nomeCliente = card.querySelector('h3').innerText;
        const preco = card.querySelector('p:nth-of-type(1)').innerText.replace('Preço: R$', '').trim();
        const endereco = card.querySelector('p:nth-of-type(2)').innerText.replace('Endereço:', '').trim();
        const bairro = card.querySelector('p:nth-of-type(3)').innerText.replace('Bairro:', '').trim();
        const descricao = card.querySelector('p:nth-of-type(4)').innerText.replace('Descrição:', '').trim();

        document.getElementById('edit_id_pedido').value = pedidoId;
        document.getElementById('edit_nome_cliente').value = nomeCliente;
        document.getElementById('edit_preco').value = preco;
        document.getElementById('edit_endereco').value = endereco;
        document.getElementById('edit_bairro').value = bairro;
        document.getElementById('edit_descricao').value = descricao;

        document.getElementById('editOrderModal').style.display = 'block';
    });
});

function geocodeAddressEdit() {
    var endereco = document.getElementById('edit_endereco').value;
    var bairro = document.getElementById('edit_bairro').value;
    var address = endereco + ' ' + bairro;

    var url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&addressdetails=1`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                var location = data[0];
                document.getElementById('edit_latitude').value = location.lat;
                document.getElementById('edit_longitude').value = location.lon;
                document.getElementById('editOrderForm').submit();
            } else {
                alert('Nenhum resultado encontrado');
            }
        })
        .catch(error => alert('Erro na solicitação:', error));
}

// Função para fechar o modal de edição
document.getElementById('closeEditOrderModal').addEventListener('click', function() {
    document.getElementById('editOrderModal').style.display = 'none';
});

// Função para abrir o modal de exclusão
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function() {
        const pedidoId = this.getAttribute('data-id');
        document.getElementById('delete_id_pedido').value = pedidoId;
        document.getElementById('deleteOrderModal').style.display = 'block';
    });
});

// Função para fechar o modal de exclusão
document.getElementById('closeDeleteOrderModal').addEventListener('click', function() {
    document.getElementById('deleteOrderModal').style.display = 'none';
});

// Função para cancelar a exclusão
document.getElementById('cancelDelete').addEventListener('click', function() {
    document.getElementById('deleteOrderModal').style.display = 'none';
});

function geocodeAddress() {
            var endereco = document.getElementById('endereco').value;
            var bairro = document.getElementById('bairro').value;
            var address = endereco + ' ' + bairro;

            var url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json&addressdetails=1`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        var location = data[0];
                        document.getElementById('latitude').value = location.lat;
                        document.getElementById('longitude').value = location.lon;
                        document.getElementById('newOrderForm').submit();
                    } else {
                        alert('Nenhum resultado encontrado');
                    }
                })
                .catch(error => alert('Erro na solicitação:', error));
        }

</script>
</body>

</html>