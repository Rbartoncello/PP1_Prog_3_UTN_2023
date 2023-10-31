<?php
include('./Habitacion.php');
include('./Ajuste.php');

if(isset($_POST["id"]) && isset($_POST["motivo"])){
    $idRoom = $_POST["id"];
    $reasonModification = $_POST["motivo"];

    $room = Room::Exist($idRoom);

    if($room !== false){
        $ajusment = new Ajustment($idRoom, $reasonModification);
        $room->reasonModification = $reasonModification;
        echo ((Ajustment::Save($ajusment) !== false && Room::UploadData($room))) ? json_encode(['response' => 'Actulizado con exito']): ['error' => 'No se pudo actulizar'];;
    } else
        echo json_encode(['error' => 'No exite reserva'], JSON_PRETTY_PRINT);
} else {
    echo json_encode(['error' => 'Mal parametros en cancelarReserva']);
}