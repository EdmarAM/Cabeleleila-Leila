<?php
require_once("../Configuration/conecta.php");

if(isset($_POST['cliente_id']) && isset($_POST['agendamento_id']) && isset($_POST['dia']) && isset($_POST['horario']) && isset($_POST['servico_id'])) {
    $cliente_id = $_POST['cliente_id'];
    $agendamento_id = $_POST['agendamento_id'];
    $dia = $_POST['dia'];
    $horario = $_POST['horario'];
    $servico_id = $_POST['servico_id'];
    
    // Atualizar o status na tabela pedidos_Alteracao para 'Finalizado'
    $update_status_query = "UPDATE pedidos_Alteracao SET status = 'Finalizado' WHERE cliente_id = $cliente_id AND agendamento_id = $agendamento_id";
    if ($conn->query($update_status_query) === TRUE) {
        // Atualizar os detalhes do agendamento na tabela agendamento
        $update_agendamento_query = "UPDATE agendamento SET dia = '$dia', horario = '$horario', servico_id = $servico_id WHERE cliente_id = $cliente_id AND id = $agendamento_id";
        
        if ($conn->query($update_agendamento_query) === TRUE) {
            // Redirecionar de volta para a página de pedidos de alteração após a atualização
            header("Location: ../View/pedidosDeAlteracoes.php");
            exit();
        } else {
            echo "Erro ao atualizar o agendamento: " . $conn->error;
        }
    } else {
        echo "Erro ao atualizar o status do pedido de alteração: " . $conn->error;
    }
} else {
    echo "Todos os campos devem ser preenchidos.";
}
$conn->close();
?>
