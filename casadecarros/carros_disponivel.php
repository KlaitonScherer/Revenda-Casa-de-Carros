<?php
session_start();

if (!isset($_SESSION['cpf'])) {
    header("Location: login_cliente.php");
    exit();
}

$cpf = $_SESSION['cpf'];

$conn = new mysqli('localhost', 'root', '', 'casadecarros');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT valor_disponivel FROM clientes WHERE cpf = ?");
$stmt->bind_param("s", $cpf);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$valor = $row['valor_disponivel'];
$stmt->close();

$stmt = $conn->prepare("SELECT * FROM carros WHERE valor <= ?");
$stmt->bind_param("d", $valor);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carros Disponíveis - Casa de Carros</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .car-card {
            cursor: pointer;
        }

        .buy-now-button {
            display: none;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .card:hover {
            background-color: #f0f0f0;
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
                <a class="nav-link" href="index.php">Página Inicial</a>
            </li>
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
    <h1 class="text-center">Carros Disponíveis</h1>
    <div class="row mt-5">
        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificação se o cliente está logado
        if (!isset($_SESSION['cpf'])) {
            header("Location: login_cliente.php");
            exit();
        }

        $valor_disponivel = $_SESSION['valor_disponivel'];

        // Conexão com o banco de dados
        $conn = new mysqli('localhost', 'root', '', 'casadecarros');
        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }

        // Consulta SQL para selecionar os carros dentro do valor disponível
        $stmt = $conn->prepare("SELECT * FROM carros WHERE valor <= ?");
        $stmt->bind_param("d", $valor_disponivel);
        $stmt->execute();
        $result = $stmt->get_result();

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
                echo '<button class="btn btn-primary buy-now-button" onclick="comprar()">Comprar Agora</button>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "Nenhum carro encontrado.";
        }
        $stmt->close();
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

// Função para comprar o carro
function comprar() {
    alert('Parabéns pela compra!');
}

// para exibir o botão "Comprar Agora" ao passar o mouse sobre o cartão do carro
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