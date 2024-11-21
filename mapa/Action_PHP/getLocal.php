<?php
require '../../config.php';
session_start();

$id_entregador = $_SESSION['entregadorID'];

$sql = "SELECT * FROM pedido WHERE id_entregador = $id_entregador and status != 'entregue'";
$res = $pdo->query($sql);

$data =[];

if($res->rowCount() > 0){
    while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $data[] = $row;
    }
}
header('Content-Type: application/json');
echo json_encode($data);
?>