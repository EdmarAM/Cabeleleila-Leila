<?php
require_once("../Configuration/conecta.php");

$cpf = $_POST['cpf'];
$nomeCompleto = $_POST['nomeCompleto'];
$telefone = $_POST['telefone'];
$email = $_POST['email'];
$senha = $_POST['senha'];

// protegendo a senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$nivelAcesso = 0;

$sql = "INSERT INTO cliente(cpf, nomeCompleto, telefone, email, senha, nivelAcesso)
    VALUE ('$cpf', '$nomeCompleto', '$telefone', '$email', '$senhaHash', '$nivelAcesso')";

if ($conn->query($sql) === TRUE) {
    header("Location: ../View/login.html");
    exit();
} else {
    header("Location: ../View/cadastra.html");
    exit();
}

$conn->close();
