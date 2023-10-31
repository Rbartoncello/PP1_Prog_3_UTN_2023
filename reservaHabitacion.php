<?php
include('./Cliente.php');
include('./Habitacion.php');
define("PATH_IMAGES_BOOKING", "ImagenesDeReservas2023/");


if(
    isset($_POST["nroCliente"]) &&
    isset($_POST["tipoCliente"]) && 
    isset($_POST["fechaEntrada"]) && 
    isset($_POST["fechaSalida"]) && 
    isset($_POST["tipoHabitacion"]) && 
    isset($_POST["importe"])
){
    $id = $_POST["nroCliente"];
    $type = $_POST["tipoCliente"];
    $checkInDate = $_POST["fechaEntrada"];
    $checkOutDate = $_POST["fechaSalida"];
    $roomType = $_POST["tipoHabitacion"];
    $cost = $_POST["importe"];

    if(in_array($roomType, ["simple", "doble", "suite"]) ){
        $client = Client::ExistByTypeAndNumber($id, $type);
        if(gettype($client) === 'object'){
            if(is_numeric($cost)){
                $room = new Room($checkInDate, $checkOutDate, $roomType, $cost, $client);
                if(Room::Save($room) !== false){
                    $file_name = $_FILES['image']['name'];
                    $file_type = $_FILES['image']['type'];
                    $file_size = $_FILES['image']['size'];
                    if ((strpos($file_type, "png") || strpos($file_type, "jpeg"))){
                        $file_path = PATH_IMAGES_BOOKING . $client->type .$client->id . $room->id . '.jpg';
                        echo (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) ? json_encode(['response' => 'Se guardo la reserva e imagen correctamente']) : json_encode(['error' => 'Ocurrió algún error al subir el fichero. No pudo guardarse.']);
                    } else {
                        echo json_encode(['error' => 'Se guardo la reserva pero formato de image incorrecto']);
                    }
                } else {
                    echo json_encode(['error' => 'no se puedo cargar nueva venta']);
                }
            } else {
                echo json_encode(['error' => 'Importe mal ingresado']);
            }
        } else
            echo $client;
    } else {
        echo json_encode(['error' => 'Tipo habitacion mal ingresado']);
    }

    

} else {
    echo json_encode(['error' => 'Mal parametros en clienteConsulta']);
}
