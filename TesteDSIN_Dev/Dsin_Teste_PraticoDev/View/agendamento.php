<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento - Cabeleleila leila</title>
    <link rel="stylesheet" href="../View/Css/padrao.css">
</head>

<body>
    <header>
        <a href="../View/horariosAgendados.php">Horários agendados</a>
            <?php
                require("../Controllers/funcoes.php");
                headerNivel();
            ?>
        <a href="servicos.php">Serviços do salão</a>
        <form class="logout" action="../Controllers/logout.php" method="post"><button class="perfil-btn" type="submit">Sair</button></form>
    </header>
    <center>
        <form action="../Controllers/agendaSessao.php" method="post">
            <h3>Agendar Sessao</h3>
            <label for="dia">Dia: </label>
            <input type="date" name="dia" id="dia" required>
            <label for="horario">Horario: </label>
            <input type="time" name="horario" id="horario" required>
            <label for="servicos">Serviços: </label>

            <?php
            agendar();
            ?>

            <input type="submit" value="Agendar">
        </form>
    </center>

    <script>
        window.onload = function() {
            // Obter a data atual
            var hoje = new Date();

            // Adicionar dois dias à data atual
            var doisDiasDepois = new Date();
            doisDiasDepois.setDate(hoje.getDate() + 2);

            // Converter a data para o formato yyyy-MM-dd
            var doisDiasDepoisFormatado = doisDiasDepois.toISOString().slice(0, 10);

            // Definir o valor mínimo do campo de data para dois dias depois de hoje
            document.getElementById("dia").min = doisDiasDepoisFormatado;

            // Definir o valor mínimo do campo de horário para 08:00
            document.getElementById("horario").min = "08:00";

            // Definir o valor máximo do campo de horário para 20:00
            document.getElementById("horario").max = "20:00";
        }
    </script>
</body>

</html>
