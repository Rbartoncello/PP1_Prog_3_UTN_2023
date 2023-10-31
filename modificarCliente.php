<?php
include('./Cliente.php');
include('./utils.php');

$input_data = file_get_contents('php://input');
$data = json_decode($input_data, true);

$id = $data["nroCliente"];
$name = $data["nombre"];
$surname = $data["apellido"];
$typeIdentification = $data["tipoDocumento"];
$numberIdentification = $data["documento"];
$email = $data["email"];
$type = $data["tipoCliente"];
$country = $data["pais"];
$city = $data["ciudad"];
$phone = $data["telefono"];

if(isUserDataValid($typeIdentification, $numberIdentification, $phone, $email, $type)){
    $client = Client::ExistByTypeAndNumber($id, $type);
    if(gettype($client) === 'object'){
        $client = new Client($name, $surname, $typeIdentification, $numberIdentification, $email, $type, $country, $city, $phone);
        $client->id = $id;
        echo (file_put_contents(RECORD_CLIENTS, json_encode(Client::Upload($client), JSON_PRETTY_PRINT)))? json_encode(['response' => 'actulizado']): ['error' => 'no se puedo actualizar cliente'];
    } else {
        echo json_encode(['error' => 'El cliente ingresado no existe']);
    }
}


