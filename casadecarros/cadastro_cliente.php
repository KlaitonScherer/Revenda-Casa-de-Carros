<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "casadecarros";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $valor = $_POST['valor_disponivel'];

    // Verificar se o cliente já está cadastrado
    $sql = "SELECT * FROM clientes WHERE cpf='$cpf'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Cliente com este CPF já está cadastrado.";
    } else {
        // Insira o novo cliente
        $sql = "INSERT INTO clientes (nome, cpf, endereco, valor_disponivel) VALUES ('$nome', '$cpf', '$endereco', '$valor')";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['cpf'] = $cpf; // Define a sessão com o CPF do cliente recém-cadastrado
            header("Location: carros_disponivel.php"); // Redireciona para a página de carros disponíveis
            exit(); // Encerra o script após o redirecionamento
        } else {
            echo "Erro: " . $conn->error;
        }
    }
}
        
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Cadastro de Cliente</h2>
    <form action="cadastro_cliente.php" method="POST">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="cpf">CPF:</label>
            <input type="text" class="form-control" id="cpf" name="cpf" required>
        </div>
        <div class="form-group">
            <label for="endereco">Endereço:</label>
            <input type="text" class="form-control" id="endereco" name="endereco" required>
        </div>
        <div class="form-group">
            <label for="valor_disponivel">Valor Disponível:</label>
            <input type="number" class="form-control" id="valor_disponivel" name="valor_disponivel" required>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
</div>
</body>
</html>