<?php
session_start(); // Inicia a sessão

require_once("../Configuration/conecta.php");

if(isset($_SESSION['cliente_id']) && 
    isset($_POST['alteracao']) && 
    isset($_POST['motivo']) &&
    isset($_POST['dia'])) {
        
    $cliente_id = $_SESSION['cliente_id'];
    $alteracao = $_POST['alteracao'];
    $motivo = $_POST['motivo'];
    $dia = $_POST['dia'];

    // Consulta SQL para obter o ID do agendamento correspondente ao dia selecionado
    $query = "SELECT id FROM agendamento WHERE dia = '$dia'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Extrai o ID do agendamento
        $row = $result->fetch_assoc();
        $agendamento_id = $row['id'];

        // Insere o pedido de alteração no banco de dados
        $sql = "INSERT INTO pedidos_Alteracao(cliente_id, alteracao, motivo, agendamento_id)
                VALUES ('$cliente_id', '$alteracao', '$motivo', '$agendamento_id')";

        if ($conn->query($sql) === TRUE) {
            header("location: ../View/horariosAgendados.php");
            exit();
        } else {
            echo "Erro ao inserir pedido de alteração: " . $conn->error;
        }
    } else {
        echo "Nenhum agendamento encontrado para o dia selecionado.";
    }
} else {
    echo "Todos os campos devem ser preenchidos.";
}
$conn->close();
?>
