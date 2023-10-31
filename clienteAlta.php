<?php
include('./Cliente.php');
include('./utils.php');
define("PATH_IMAGES_CLIENTS", "ImagenesDeClientes/2023/");

if( 
    isset($_POST["nombre"]) && 
    isset($_POST["apellido"]) && 
    isset($_POST["tipoDocumento"]) && 
    isset($_POST["email"]) && 
    isset($_POST["tipoCliente"]) && 
    isset($_POST["pais"]) && 
    isset($_POST["ciudad"]) &&
    isset($_POST["telefono"])
    ){
    $name = $_POST["nombre"];
    $surname = $_POST["apellido"];
    $typeIdentification = $_POST["tipoDocumento"];
    $numberIdentification = $_POST["documento"];
    $email = $_POST["email"];
    $type = $_POST["tipoCliente"];
    $country = $_POST["pais"];
    $city = $_POST["ciudad"];
    $phone = $_POST["telefono"];

    $file_name = $_FILES['image']['name'];
    $file_type = $_FILES['image']['type'];
    $file_size = $_FILES['image']['size'];

    if(isUserDataValid($typeIdentification, $numberIdentification, $phone, $email, $type)){
        if ((strpos($file_type, "png") || strpos($file_type, "jpeg"))){
            $newClient = new Client($name, $surname, $typeIdentification, $numberIdentification, $email, $type, $country, $city, $phone);
            $file_path = PATH_IMAGES_CLIENTS . $newClient->id . substr($type, 0, 2) . '.jpg';
            if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)){
                Client::Save($newClient);
            } else {
                echo json_encode(['error' => 'Ocurrió algún error al subir el fichero. No pudo guardarse.']);
            }
        } else {
            echo json_encode(['error' => 'Formato de image incorrecto']);
        }
    }
} else {
    echo json_encode(['error' => 'Mal parametros en' . $_POST["accion"]]);
}

