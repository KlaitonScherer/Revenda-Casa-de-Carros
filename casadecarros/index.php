<?php
// Verifica conexão com o BD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $valor = $_POST['valor_disponivel'];

    $conn = new mysqli('localhost', 'root', '', 'casadecarros');

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }   
// Fim da verificação com o BD


      // Verificar se o cliente já está cadastrado
      $stmt = $conn->prepare("SELECT cpf FROM clientes WHERE cpf = ?");
      $stmt->bind_param("s", $cpf);
      $stmt->execute();
      $stmt->store_result();
      
      if ($stmt->num_rows > 0) {
        echo "Cliente com este CPF já está cadastrado.";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Inserir novo cliente
    $stmt = $conn->prepare("INSERT INTO clientes (nome, cpf, endereco, valor_disponivel) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $nome, $cpf, $endereco, $valor);

    if ($stmt->execute() === TRUE) {
        echo "Cliente cadastrado com sucesso.";

        $stmt->close();

        // Buscar carros dentro do valor disponível
        $stmt = $conn->prepare("SELECT * FROM carros WHERE valor <= ?");
        $stmt->bind_param("d", $valor);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h2>Carros disponíveis:</h2>";
            echo '<div class="row">';
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-4">';
                echo '<div class="card mb-4">';
                echo '<img src="' . htmlspecialchars($row['foto'], ENT_QUOTES, 'UTF-8') . '" class="card-img-top" alt="Foto do carro">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($row['marca'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($row['modelo'], ENT_QUOTES, 'UTF-8') . '</h5>';
                echo '<p class="card-text">Ano: ' . htmlspecialchars($row['ano'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<p class="card-text">Valor: R$' . htmlspecialchars($row['valor'], ENT_QUOTES, 'UTF-8') . '</p>';
                echo '<p class="card-text">Quilometragem: ' . htmlspecialchars($row['quilometragem'], ENT_QUOTES, 'UTF-8') . ' km</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo "Nenhum carro disponível dentro do valor informado.";
        }
        $stmt->close();
    } else {
        echo "Erro: " . $stmt->error;
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Casa de carros - Cadastro e Pesquisa</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos adicionais podem ser adicionados aqui */
        .car-card {
            cursor: pointer; /* Altera o cursor para indicar que a carta é clicável */
        }

        .buy-now-button {
            display: none; /* Botão de compra é oculto inicialmente */
        }

        .card:hover {
            background-color: #f0f0f0; /* Cor de fundo ao passar o mouse */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
<a class="navbar-brand" href="#">Casa de Carros</a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="cadastro_cliente.php">Cadastro</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="login_cliente.php">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="login.php">Admin</a>
        </li> 
    </ul>
</div>
</nav>

<div class="container mt-5">
<h1 class="text-center">Casa de Carros</h1>
<div class="row mt-5">
<?php
    // Conexão com o banco de dados
    $conn = new mysqli('localhost', 'root', '', 'casadecarros');
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Consulta SQL para selecionar os carros
    $sql = "SELECT * FROM carros";
    $result = $conn->query($sql);

    // Verifica se existem registros
    if ($result->num_rows > 0) {
        // Loop através dos registros
        while($row = $result->fetch_assoc()) {
            echo '<div class="col-md-4">';
            echo '<div class="card mb-4 car-card">';
            echo '<img src="' . $row['foto'] . '" class="card-img-top" alt="Foto do carro">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $row['marca'] . ' ' . $row['modelo'] . '</h5>';
            echo '<p class="card-text">Ano: ' . $row['ano'] . '</p>';
            echo '<p class="card-text">Valor: R$' . $row['valor'] . '</p>';
            echo '<p class="card-text">Quilometragem: ' . $row['quilometragem'] . ' km</p>';
            echo '<button class="btn btn-primary buy-now-button" onclick="showLoginModal()">Comprar Agora</button>'; // Botão de compra
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "Nenhum carro encontrado.";
    }
    $conn->close();
    ?>
        
    </div>
</div>

<!-- Modal de Login -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Login do Cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="loginForm">
            <div class="form-group">
                <label for="cpf">CPF:</label>
                <input type="text" class="form-control" id="cpf" name="cpf" required>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="login()">Entrar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
// Função para mostrar o modal de login
function showLoginModal() {
    $('#loginModal').modal('show');
}

// Função para fazer login
function login() {
    
    alert('Parabéns pela compra!');
    $('#loginModal').modal('hide');
}

// Adiciona evento para exibir o botão "Comprar Agora" ao passar o mouse sobre o cartão do carro
document.querySelectorAll('.car-card').forEach(card => {
    card.addEventListener('mouseover', function() {
        this.querySelector('.buy-now-button').style.display = 'block';
    });
    card.addEventListener('mouseout', function() {
        this.querySelector('.buy-now-button').style.display = 'none';
    });
});
</script>
</body>
</html>
