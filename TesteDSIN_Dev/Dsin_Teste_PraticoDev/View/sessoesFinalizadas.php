<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sessoes Finalizadas - Cabeleleila leila</title>
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
        <form>
            <?php
            SessoesFinalizadas();
            ?>

        </form>
    </center>
</body>

</html>