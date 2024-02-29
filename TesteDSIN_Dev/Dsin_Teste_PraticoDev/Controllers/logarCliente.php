<?php
session_start(); // Inicia a sessão

require_once("../Configuration/conecta.php");

$email = $_POST['email'];
$senha = $_POST['senha'];

// Consulta SQL para obter o usuário com o email fornecido
$sql = "SELECT * FROM cliente WHERE email ='$email'";
$result = $conn->query($sql);

if($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $senha_hash = $row['senha']; // Obtém a senha criptografada armazenada no banco de dados

    // Verifica se a senha fornecida corresponde à senha criptografada no banco de dados
    if (password_verify($senha, $senha_hash)) {
        // Define as variáveis de sessão
        $_SESSION['cliente_id'] = $row['id'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['nivelAcesso'] = $row['nivelAcesso'];

        header("Location: ../View/horariosAgendados.php");
        exit();
    } else {
        header("Location: ../View/login.html");
        exit();
    }
} else {
    header("Location: ../View/login.html");
    exit();
}

$conn->close();
?>
