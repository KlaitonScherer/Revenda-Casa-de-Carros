<?php

    session_start();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $conn = new mysqli('localhost', 'root', '', 'casadecarros');

        if ($conn->connect_error) {
            die("Falha na conexão: ". $conn->connect_error);
        }

        $sql = "SELECT * FROM usuarios WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $_SESSION['username'] = $username;
            header("Location: admin.php");
        } else {
            echo "Usuário ou senha inválidos.";
        }
    
    }
    ?>

<!DOCTYPE html>
<html lang= "pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Login - Casa de Carros</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <h2 class="mt-5">Login</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Usuário:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </form>
                <a href="index.php" class="btn btn-primary mt-3">Voltar à Página Inicial</a> 
        </div>
    </body>
</html>


