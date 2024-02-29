<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos De Alterações - Cabeleleila leila</title>
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
        <form>
            <h2>Lista de Pedidos de Alteração</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Agendamento ID</th>
                    <th>Alteração</th>
                    <th>Motivo</th>
                    <th>Status</th>
                    <th>Editar</th>
                    <th>Cancelar</th> <!-- Botão Cancelar -->
                </tr>
                <?php 
                pedidosDeAlteracoes();
                ?>
            </table>
        </form>
    </center>
</body>

</html>
