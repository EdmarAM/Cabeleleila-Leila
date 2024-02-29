<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Alteração - Cabeleleila leila</title>
    <link rel="stylesheet" href="../View/Css/padrao.css">
</head>

<body>
    <header>
        <a href="../View/horariosAgendados.php">Horários agendados</a>
        <?php
        require("../Controllers/funcoes.php");
        headerNivel();
        ?>
        <a href="../View/servicos.php">Serviços do salão</a>
        <form class="logout" action="../Controllers/logout.php" method="post"><button class="perfil-btn" type="submit">Sair</button></form>
    </header>
    <center>
        <form action="../Controllers/pedirAlteracao.php" method="post">
            <h2>Pedido de Alteração</h2>
            <label for="dia">Selecione o Dia do Agendamento:</label><br>
                <?php
                solicitarAlteracao();
                ?>
            </select><br><br>
            <label for="alteracao">Alteração Desejada:</label><br>
            <input type="text" id="alteracao" name="alteracao" required><br><br>
            <label for="motivo">Motivo da Alteração:</label><br>
            <textarea id="motivo" name="motivo" rows="3" cols="30" required></textarea><br><br>
            <button type="submit" class="botao">Enviar</button>
        </form>
    </center>
</body>

</html>