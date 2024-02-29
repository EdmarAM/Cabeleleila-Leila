<?php
session_start(); // Inicia a sessão

require_once("../Configuration/conecta.php");

if(isset($_SESSION['cliente_id']) && isset($_GET['id_agendamento'])) {
    $cliente_id = $_SESSION['cliente_id'];
    $id_agendamento = $_GET['id_agendamento'];

    // Consulta SQL para verificar se o agendamento pertence ao cliente logado ou se o usuário tem nível de acesso 1
    $query = "SELECT cliente_id FROM agendamento WHERE id = $id_agendamento";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cliente_associado = $row['cliente_id'];

        if ($cliente_id == $cliente_associado || $_SESSION['nivelAcesso'] == 1) {
            // Agendamento pertence ao cliente ou usuário tem nível de acesso 1, então pode ser cancelado
            $sql = "DELETE FROM agendamento WHERE id = $id_agendamento";

            if ($conn->query($sql) === TRUE) {
                // Agendamento cancelado com sucesso, redireciona de volta para a página de horários agendados
                header("Location: ../View/horariosAgendados.php");
                exit();
            } else {
                echo "Erro ao cancelar o agendamento: " . $conn->error;
            }
        } else {
            // O agendamento não pertence ao cliente logado e o usuário não tem permissão de nível 1
            echo "Você não tem permissão para cancelar este agendamento.";
        }
    } else {
        // Não foi encontrado nenhum agendamento com o ID fornecido
        echo "Agendamento não encontrado.";
    }
} else {
    echo "ID de agendamento inválido ou sessão expirada.";
}

$conn->close();
?>
