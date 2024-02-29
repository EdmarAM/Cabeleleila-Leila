<?php
// Verifica se o ID do pedido foi passado via GET
if(isset($_GET['id_pedido'])) {
    require_once("../Configuration/conecta.php");

    // Obtém o ID do pedido da URL
    $idPedido = $_GET['id_pedido'];

    // Prepara a consulta SQL para excluir o pedido de alteração com base no ID fornecido
    $sql = "DELETE FROM pedidos_Alteracao WHERE id = $idPedido";

    if ($conn->query($sql) === TRUE) {
        // Redireciona de volta para a página de pedidos de alteração após a exclusão
        header("Location: ../View/pedidosDeAlteracoes.php");
        exit();
    } else {
        echo "Erro ao cancelar o pedido de alteração: " . $conn->error;
    }

    $conn->close();
} else {
    // Se o ID do pedido não foi fornecido, redireciona para a página de pedidos de alteração
    header("Location: ../View/pedidosDeAlteracoes.php");
    exit();
}
?>
