<?php

use function PHPSTORM_META\type;

include('./Cliente.php');


if(isset($_POST["nroCliente"]) && isset($_POST["tipoCliente"]))
    {
    $id = $_POST["nroCliente"];
    $type = $_POST["tipoCliente"];

    $client = Client::ExistByTypeAndNumber($id, $type);
    if(gettype($client) !== 'object')
        echo json_encode($client, JSON_PRETTY_PRINT);
    else
        echo json_encode($client, JSON_PRETTY_PRINT);


} else {
    echo json_encode(['error' => 'Mal parametros en clienteConsulta']);
}

