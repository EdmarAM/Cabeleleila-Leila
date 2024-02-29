<?php
session_start(); // Inicia a sessão

require_once("../Configuration/conecta.php");

if(isset($_SESSION['cliente_id']) && 
    isset($_POST['dia']) && 
    isset($_POST['horario']) && 
    isset($_POST['servicos'])) {
        
    $cliente_id = $_SESSION['cliente_id'];
    $dia = $_POST['dia'];
    $horario = $_POST['horario'];
    $servico = $_POST['servicos'];

    $sql = "INSERT INTO agendamento (cliente_id, dia, horario, servico_id)
            VALUES ('$cliente_id', '$dia', '$horario', '$servico')";

    if ($conn->query($sql) === TRUE) {
        header("location: ../View/horariosAgendados.php");
        exit();
    } else {
        header("location: ../View/agendamento.php"); 
        exit();
    }
} else {
    header("location: ../View/agendamento.php");
    exit();
}
$conn->close();
?>
