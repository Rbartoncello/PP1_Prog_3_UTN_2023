<?php
include('./Cliente.php');
define("PATH_IMAGES_BACKUP_CLIENTS", "ImagenesBackupClientes/2023/");
define("PATH_IMAGES_CLIENTS", "ImagenesDeClientes/2023/");

if(isset($_GET["nroCliente"]) && isset($_GET["tipoCliente"])){
    $idClient = $_GET["nroCliente"];
    $type = $_GET["tipoCliente"];

    $client = Client::ExistByTypeAndNumber($idClient, $type);

    if(gettype($client) === 'object'){
        $client->deleted = true;
        Client::Save($client);
        $file_path = PATH_IMAGES_CLIENTS . $client->id . substr($client->type, 0, 2) . '.jpg';
        $file_path_backup = PATH_IMAGES_BACKUP_CLIENTS . $client->id . substr($client->type, 0, 2) . '.jpg';
        if(file_exists($file_path))
            echo (rename($file_path, $file_path_backup)) ? 
                json_encode(['response' => 'Se borro y se modififico la imagen correctamente']) : 
                json_encode(['error' => 'Ocurrió algún error al subir el fichero. No pudo guardarse.']);
        else 
            json_encode(['error' => 'No existe el archivo']);
    } else
        echo json_encode(['error' => 'No exite reserva o cliente'], JSON_PRETTY_PRINT);
} else {
    echo json_encode(['error' => 'Mal parametros en borrarCliente']);
}
