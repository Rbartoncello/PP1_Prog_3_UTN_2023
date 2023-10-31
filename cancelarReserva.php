<?php
include('./Cliente.php');
include('./Habitacion.php');

if(isset($_POST["nroCliente"]) && isset($_POST["tipoCliente"]) && isset($_POST["idReserva"])){
    $idClient = $_POST["nroCliente"];
    $idRoom = $_POST["idReserva"];
    $type = $_POST["tipoCliente"];

    $client = Client::ExistByTypeAndNumber($idClient, $type);
    $room = Room::Exist($idRoom);

    if(gettype($client) === 'object' && $room !== false)
        Room::Delete($idRoom);
    else
        echo json_encode(['error' => 'No exite reserva o cliente'], JSON_PRETTY_PRINT);
} else {
    echo json_encode(['error' => 'Mal parametros en cancelarReserva']);
}
