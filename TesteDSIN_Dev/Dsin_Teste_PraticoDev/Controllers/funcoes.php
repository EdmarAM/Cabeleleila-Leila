<?php
session_start();
require_once("../Configuration/conecta.php");


function headerNivel()
{

    global $conn;
    if (isset($_SESSION['cliente_id'])) {
        $idUser = $_SESSION['cliente_id']; // Obtém o ID do cliente da sessão

        $verifica = "SELECT nivelAcesso FROM cliente WHERE id = $idUser";
        $resultado = $conn->query($verifica);

        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $nivelAcesso = $row["nivelAcesso"];
            if ($nivelAcesso == 1) {
                echo "<a href='../View/pedidosDeAlteracoes.php'>Pedidos de alterações</a>";
                echo "<a href='../View/sessoesFinalizadas.php'>Sessoes Finalizadas</a>";
            } else {
                echo "<a href='../View/agendamento.php'>Marcar um horário</a>";
                echo "<a href='../View/solicitarAlteracao.php'>Solicitar Alteração</a>";
            }
        } else {
            echo "<h2>Nível de acesso não encontrado</h2>";
        }
    }
}


function pedidosDeAlteracoes()
{
    global $conn;

    // Verifica se a sessão está definida e se há um ID de cliente armazenado
    if (isset($_SESSION['cliente_id'])) {

        $sql = "SELECT pedidos_Alteracao.id, cliente.nomeCompleto, pedidos_Alteracao.agendamento_id, 
                            pedidos_Alteracao.alteracao, pedidos_Alteracao.motivo, pedidos_Alteracao.status, pedidos_Alteracao.cliente_id
                            FROM pedidos_Alteracao
                            INNER JOIN cliente ON pedidos_Alteracao.cliente_id = cliente.id
                            WHERE pedidos_Alteracao.status = 'Em Processo'"; // Adicionando a cláusula WHERE para filtrar por status 'Em Processo'
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                            <td>" . $row["id"] . "</td>
                            <td>" . $row["nomeCompleto"] . "</td>
                            <td>" . $row["agendamento_id"] . "</td>
                            <td>" . $row["alteracao"] . "</td>
                            <td>" . $row["motivo"] . "</td>
                            <td>" . $row["status"] . "</td>
                            <td><a href='../View/editarSessao.php?id_cliente={$row["cliente_id"]}&id_agendamento={$row["agendamento_id"]}' class='editar'>Editar</a></td>
                            <td><a href='../Controllers/cancelarPedido.php?id_pedido={$row["id"]}' class='cancelar'>Cancelar</a></td> <!-- Link para cancelar o pedido -->
                            </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Nenhum pedido de alteração encontrado em processo.</td></tr>";
        }
    } else {
        header("Location: ../View/login.html");
    }
}


function agendar()
{

    global $conn;

    // Verifica se a sessão está definida e se há um ID de cliente armazenado
    if (isset($_SESSION['cliente_id'])) {

        echo "<select name='servicos' id='servicos' required>";

        // Consulta para selecionar todos os serviços disponíveis
        $selecionar_servicos = mysqli_query($conn, "SELECT * FROM servicos");

        // Mostra os serviços já existentes no banco
        while ($servico = mysqli_fetch_array($selecionar_servicos)) {
            echo '<option value="' . $servico[0] . '">' . $servico[1] . '</option>';
        }

        echo "</select>";
    } else {
        // Se a sessão não estiver definida ou o ID do cliente não estiver presente, redirecionar para a página de login
        header("Location: ../View/login.html");
        exit();
        // Fecha a conexão com o banco de dados
    }
}


function tabelaDeServicos()
{
    global $conn;

    if (isset($_SESSION['cliente_id'])) {

        $visualiza = "SELECT * FROM servicos";

        $resultado = $conn->query($visualiza);

        if ($resultado->num_rows > 0) {
            echo "<div class='container'>";
            while ($row = $resultado->fetch_assoc()) {
                echo "<div class='container-card'>
                            <a href='../View/agendamento.php'>        
                            <img src='{$row['imagem']}' alt='img' width='65%' height='55%'>
                            <h2>{$row['nomeServico']}</h2>
                            <p><strong>Valor: {$row['valor']}</strong></p>
                            <p>Descrição: {$row['descricaoServico']}</p>
                            </div></a>";
            }
            echo "</div>";
        } else {
            echo "<center><div class='container'><h2><strong>Nenhum serviço disponível no momento</strong></h2></div></center>";
        }
    } else {
        header("Location: ../View/login.html");
        exit();
    }
}


function editarSessao()
{
    global $conn;
    if (isset($_GET['id_cliente']) && isset($_GET['id_agendamento'])) {
        $idCliente = $_GET['id_cliente'];
        $idAgendamento = $_GET['id_agendamento'];

        $sqlAgendamento = "SELECT * FROM agendamento WHERE cliente_id = $idCliente AND id = $idAgendamento";
        $resultAgendamento = $conn->query($sqlAgendamento);

        if ($resultAgendamento->num_rows > 0) {
            $rowAgendamento = $resultAgendamento->fetch_assoc();
            $dia = $rowAgendamento["dia"];
            $horario = $rowAgendamento["horario"];
            $servico_id = $rowAgendamento["servico_id"];

            echo "<form action='../Controllers/processarAlteracao.php' method='post'>
                <input type='hidden' name='cliente_id' value='$idCliente'>
                <input type='hidden' name='agendamento_id' value='$idAgendamento'>

                <label for='dia'>Dia:</label><br>
                <input type='date' id='dia' name='dia' value='$dia' required><br><br>
                <label for='horario'>Horário:</label><br>
                <input type='time' id='horario' name='horario' value='$horario' required><br><br>
                <label for='servico_id'>Serviço:</label><br>
                <select id='servico_id' name='servico_id' required>";

            // Consulta para obter todos os serviços disponíveis
            $sqlServicos = "SELECT * FROM servicos";
            $resultServicos = $conn->query($sqlServicos);

            // Loop para criar uma opção para cada serviço
            if ($resultServicos->num_rows > 0) {
                while ($rowServico = $resultServicos->fetch_assoc()) {
                    $idServico = $rowServico['id'];
                    $nomeServico = $rowServico['nomeServico'];
                    echo "<option value='$idServico'";
                    if ($servico_id == $idServico) echo " selected";
                    echo ">$nomeServico</option>";
                }
            }

            echo "</select><br><br>
                <button type='submit' class='botao'>Salvar Alterações</button>
            </form>";
        } else {
            echo "Nenhum agendamento encontrado para este cliente.";
        }
    } else {
        echo "ID do cliente ou ID do agendamento não especificado.";
    }
}


function solicitarAlteracao()
{
    global $conn;

    $cliente_id = isset($_SESSION['cliente_id']) ? $_SESSION['cliente_id'] : null;

    if ($cliente_id) {
        $sql = "SELECT dia,status FROM agendamento WHERE cliente_id = $cliente_id AND agendamento.status = 'Aberto'";
        $result = $conn->query($sql);
        echo "<select id='dia' name='dia' class = 'dia' required>";
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dia = $row["dia"];
                echo "<option value='$dia'>$dia</option>";
            }
        } else {
            echo "<option value=''disabled>Nenhum dia de agendamento encontrado para este cliente</option>";
        }
    } else {
        echo "<option value='' disabled>Cliente não identificado</option>";
    }
}


function mostrarAgendamento()
{
    global $conn;
    if (isset($_SESSION['cliente_id'])) {
        $idUser = $_SESSION['cliente_id']; // Obtém o ID do cliente da sessão

        $verifica = "SELECT nivelAcesso FROM cliente WHERE id = $idUser";
        $resultado = $conn->query($verifica);

        if ($resultado && $resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $nivelAcesso = $row["nivelAcesso"];

            if ($nivelAcesso == 1) {
                // Se o usuário tiver nível de acesso 1, exibir todos os agendamentos
                $sql = "SELECT agendamento.*, cliente.nomeCompleto, servicos.nomeServico 
                        FROM agendamento
                        INNER JOIN cliente ON agendamento.cliente_id = cliente.id
                        INNER JOIN servicos ON agendamento.servico_id = servicos.id
                        WHERE agendamento.status = 'Aberto'";
            } else {
                // Se o usuário tiver nível de acesso 0, exibir apenas os agendamentos relacionados ao seu ID
                $sql = "SELECT agendamento.*, cliente.nomeCompleto, servicos.nomeServico 
                        FROM agendamento
                        INNER JOIN cliente ON agendamento.cliente_id = cliente.id
                        INNER JOIN servicos ON agendamento.servico_id = servicos.id
                        WHERE agendamento.cliente_id = $idUser AND agendamento.status = 'Aberto'";
            }

            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                echo "<h3>Horários Agendados</h3>
                <table>
                <tr>
                    <th>Nome</th>
                    <th>Dia</th>
                    <th>Horário</th>
                    <th>Serviço</th>
                    <th>Status</th>"; // Adicionado o cabeçalho para exibir o status

                if ($nivelAcesso == 1) {
                    echo "<th>Editar</th>"; /* Acesso Nível 1 ver o editar*/
                    echo "<th>Confirmar</th>"; /* Acesso Nível 1 ver o confirmar */
                } else {
                    echo "<th>Alterar Horário</th>";
                }
                echo "<th>Cancelar</th>
                </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['nomeCompleto']}</td>
                        <td>{$row['dia']}</td>
                        <td>{$row['horario']}</td>
                        <td>{$row['nomeServico']}</td>
                        <td>{$row['status']}</td>"; // Exibindo o status

                    if ($nivelAcesso == 1) {
                        echo "<td><a href='../View/pedidosDeAlteracoes.php' class='Editar'>Editar</td>";
                        // Link para confirmar a sessão com o ID do agendamento correto
                        echo "<td><a href='../Controllers/sessaoRealizada.php?id_agendamento={$row['id']}' class='Confirmar'>Confirmar</td>";
                    } else {
                        echo "<td><a href='../View/solicitarAlteracao.php?id={$row['id']}'>Solicitar Alteração</td>";
                    }
                    // Aqui, o link de cancelar envia o id_cliente do agendamento
                    echo "<td><a href='../Controllers/cancelarSessao.php?id_agendamento={$row['id']}' class='Cancelar'>Cancelar</td></tr>";
                }
                echo "</table>";
            } elseif ($nivelAcesso == 0) {
                echo "<h2>Agende sua Sessão</h2>";
            } else {
                echo "<h2>Sem Sessões Agendadas</h2>";
            }
        } else {
            echo "<h2>Nível de acesso não encontrado</h2>";
        }
    } else {
        // Se a sessão não estiver definida ou o ID do cliente não estiver presente, redirecionar para a página de login
        header("Location: ../View/login.html");
        exit();
    }
}


function SessoesFinalizadas()
{
    global $conn;

    if (isset($_SESSION['cliente_id'])) {
        $idUser = $_SESSION['cliente_id']; // Obtém o ID do cliente da sessão

        $verifica = "SELECT nivelAcesso FROM cliente WHERE id = $idUser";
        $resultado = $conn->query($verifica);

        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $nivelAcesso = $row["nivelAcesso"];

            if ($nivelAcesso == 1) {
                // Se o usuário tiver nível de acesso 1, exibir todos os agendamentos com status "Finalizada" dentro do mês corrente
                $mesAtual = date('m'); // Obtém o número do mês corrente
                $anoAtual = date('Y'); // Obtém o ano corrente
                $sql = "SELECT * FROM agendamento
                    INNER JOIN cliente ON agendamento.cliente_id = cliente.id
                    INNER JOIN servicos ON agendamento.servico_id = servicos.id
                    WHERE agendamento.status = 'Finalizada' AND MONTH(agendamento.dia) = $mesAtual AND YEAR(agendamento.dia) = $anoAtual";
            } else {
                // Se o usuário tiver nível de acesso 0, redireciona para login
                header("Location: ../View/login.html");
                exit();
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<h3>Serviços do mes finalizados ({$mesAtual}/{$anoAtual})</h3>
                <table>
                <tr>
                    <th>Nome</th>
                    <th>Dia</th>
                    <th>Horário</th>
                    <th>Serviço</th>
                    <th>Valor</th>
                    <th>Status</th>"; // Adicionado o cabeçalho para exibir o status

                echo "</tr>";

                $totalValor = 0; // Inicializa a variável para armazenar o total de valores

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['nomeCompleto']}</td>
                        <td>{$row['dia']}</td>
                        <td>{$row['horario']}</td>
                        <td>{$row['nomeServico']}</td>
                        <td>{$row['valor']}</td>
                        <td>{$row['status']}</td>"; // Exibindo o status

                    // Adiciona o valor do serviço ao total
                    $totalValor += $row['valor'];
                }
                $totalFormatado = number_format($totalValor, 2, ',', '.');
                // Exibe a linha com o total de valores
                echo "</table>";
                echo "<div><table>";
                echo "<tr><th colspan='5' style='text-align: right;'>Total:</th></tr>
                <tr><td colspan='5' style='text-align: right;'>R$ $totalFormatado</td></tr>"; // Adicionando o prefixo 'R$' para indicar que é uma quantia monetária
                echo "</table><div>";
            } else {
                echo "<h2>Não há sessões finalizadas no momento</h2>";
            }
        } else {
            echo "<h2>Nível de acesso não encontrado</h2>";
        }
    } else {
        // Se a sessão não estiver definida ou o ID do cliente não estiver presente, redirecionar para a página de login
        header("Location: ../View/../View/login.html");
        exit();
    }
}
