<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'casadecarros');

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $ano = $_POST['ano'];
        $valor = $_POST['valor'];
        $quilometragem = $_POST['quilometragem'];
        
        
        if (!empty($_FILES["foto"]["tmp_name"])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["foto"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            
            $check = getimagesize($_FILES["foto"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                echo "Arquivo não é uma imagem.";
                $uploadOk = 0;
            }
            
            // Check file size
            if ($_FILES["foto"]["size"] > 500000) {
                echo "Desculpe, seu arquivo é muito grande.";
                $uploadOk = 0;
            }
            
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                echo "Desculpe, apenas arquivos JPG, JPEG e PNG são permitidos.";
                $uploadOk = 0;
            }
            
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Desculpe, seu arquivo não foi enviado.";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                    $foto = $target_file;
                } else {
                    echo "Desculpe, ocorreu um erro ao enviar seu arquivo.";
                }
            }
        } else {
            // Mantém a foto atual se nenhuma nova foto for enviada
            $foto = $_POST['foto_atual'];
        }
        
        $sql = "UPDATE carros SET marca='$marca', modelo='$modelo', ano='$ano', valor='$valor', quilometragem='$quilometragem', foto='$foto' WHERE id='$id'";
        
        if ($conn->query($sql) === TRUE) {
            header("Location: admin_list.php");
        } else {
            echo "Erro ao atualizar o registro: " . $conn->error;
        }
    }

    $sql = "SELECT * FROM carros WHERE id='$id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $carro = $result->fetch_assoc();
    } else {
        echo "Carro não encontrado.";
        exit();
    }
} else {
    echo "ID de carro não fornecido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Administração - Editar Carro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Editar Carro</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="marca">Marca:</label>
            <input type="text" class="form-control" id="marca" name="marca" value="<?php echo $carro['marca']; ?>" required>
        </div>
        <div class="form-group">
            <label for="modelo">Modelo:</label>
            <input type="text" class="form-control" id="modelo" name="modelo" value="<?php echo $carro['modelo']; ?>" required>
        </div>
        <div class="form-group">
            <label for="ano">Ano:</label>
            <input type="number" class="form-control" id="ano" name="ano" value="<?php echo $carro['ano']; ?>" required>
        </div>
        <div class="form-group">
            <label for="valor">Valor:</label>
            <input type="number" step="0.01" class="form-control" id="valor" name="valor" value="<?php echo $carro['valor']; ?>" required>
        </div>
        <div class="form-group">
            <label for="quilometragem"> Quilometragem:</label>
            <input type="number" class="form-control" id="quilometragem" name="quilometragem" value="<?php echo $carro['quilometragem']; ?>" required>
        </div>
        <div class="form-group">
            <label for="foto">Foto do Carro:</label>
            <input type="file" class="form-control-file" id="foto" name="foto">
            <input type="hidden" name="foto_atual" value="<?php echo $carro['foto']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Atualizar Carro</button>
    </form>
    <a href="admin_list.php" class="btn btn-secondary mt-3">Voltar</a>
</div>
</body>
</html>