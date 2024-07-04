<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'];
    $valor = $_POST['valor_disponivel'];

    // Verificar se o cliente está cadastrado
    $conn = new mysqli('localhost', 'root', '', 'casadecarros');
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM clientes WHERE cpf = ?");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['cpf'] = $cpf;
        $_SESSION['valor_disponivel'] = $valor;
        header("Location: carros_disponivel.php");
        exit();
    } else {
        echo "CPF não encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login do Cliente</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Login do Cliente</h2>
    <form action="login_cliente.php" method="POST">
        <div class="form-group">
            <label for="cpf">CPF:</label>
            <input type="text" class="form-control" id="cpf" name="cpf" required>
        </div>
            <div class="form-group">
                <label for="valor_disponivel">Valor Disponível:</label>
                <input type="number" class="form-control" id="valor_disponivel" name="valor_disponivel" required>
        </div>
        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
</div>
</body>
</html>