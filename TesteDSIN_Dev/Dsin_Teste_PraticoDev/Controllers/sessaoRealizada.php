<?php
session_start(); // Inicia a sessão

require_once("../Configuration/conecta.php");

if(isset($_SESSION['cliente_id']) && isset($_GET['id_agendamento'])) {
    $cliente_id = $_SESSION['cliente_id'];
    $id_agendamento = $_GET['id_agendamento'];

    // Verifica se o usuário tem nível de acesso 1
    $verifica_nivel = "SELECT nivelAcesso FROM cliente WHERE id = $cliente_id";
    $resultado_nivel = $conn->query($verifica_nivel);

    if ($resultado_nivel->num_rows > 0) {
        $row_nivel = $resultado_nivel->fetch_assoc();
        $nivel_acesso = $row_nivel["nivelAcesso"];

        if ($nivel_acesso == 1) {
            // Usuário tem nível de acesso 1, pode confirmar a sessão como finalizada
            $sql = "UPDATE agendamento SET status = 'Finalizada' WHERE id = $id_agendamento";

            if ($conn->query($sql) === TRUE) {
                // Agendamento marcado como finalizado, redireciona de volta para a página de horários agendados
                header("Location: ../View/horariosAgendados.php");
                exit();
            } else {
                echo "Erro ao marcar a sessão como finalizada: " . $conn->error;
            }
        } else {
            // Usuário não tem permissão de nível 1
            echo "Você não tem permissão para marcar esta sessão como finalizada.";
        }
    } else {
        echo "Erro ao verificar o nível de acesso do usuário.";
    }
} else {
    echo "ID de cliente inválido ou sessão expirada.";
}

$conn->close();
?>
