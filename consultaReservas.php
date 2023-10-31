<?php
include('./Habitacion.php');

$rooms = Room::Get();

switch($_GET["tipoReserva"]){
    case 'activas':
        activeBookingQuerries();
        break;
    case 'canceladas':
        desactiveBookingQuerries();
        break;
    default:
        allBookingQueries();
        break;
};

function activeBookingQuerries() {
    if(isset($_GET["fecha"])){
        if(strlen($_GET["fecha"]) > 0){
            $date = date("d-m-y", strtotime($_GET["fecha"]));
        } else {
            $date = date('y-m-d', strtotime('yesterday'));
        }
    
        $filterByDate = Room::FilterByDate($date);
        $simpleRooms = Room::FilterByType("simple", $filterByDate);
        $dobleRooms = Room::FilterByType("doble", $filterByDate);
        $suiteRooms = Room::FilterByType("suite", $filterByDate);
    
        $costOnDate = Room::CalculeCost($filterByDate);
        $costSimpleRooms = Room::CalculeCost($simpleRooms);
        $costDoubleRooms = Room::CalculeCost($dobleRooms);
        $costSuiteRooms = Room::CalculeCost($suiteRooms);
    
        echo json_encode([
            "response" => [
                "total" => $costOnDate, 
                "simple" => $costSimpleRooms, 
                "doble" => $costDoubleRooms, 
                "suite" => $costSuiteRooms
            ]
        ], JSON_PRETTY_PRINT);
    
    } else if(isset($_GET["nroCliente"])){
        echo json_encode([
            "response" => [Room::FilterByClientId($_GET["nroCliente"])]
        ], JSON_PRETTY_PRINT);
    } else if(isset($_GET["tipo"])){
        echo json_encode([
            "response" => [Room::FilterByType($_GET["tipo"])]
        ], JSON_PRETTY_PRINT);
    } else if(isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])){
        $startDate = date("d-m-y", strtotime($_GET["fechaInicial"]));
        $endDate = date("d-m-y", strtotime($_GET["fechaFinal"]));
    
        $filterByDate = Room::filterByDate($endDate, Room::filterByDate($startDate));
    
        usort($filterByDate, function ($a, $b) {
            return strcmp($a->checkinDate, $b->checkinDate);
        });
    
        echo json_encode([
            "response" => [$filterByDate]
        ], JSON_PRETTY_PRINT);
    
    } else {
        echo json_encode(['error' => 'Parametros no validos en consulta de reserva cancelada']);
    }
}

function desactiveBookingQuerries() {
    if(isset($_GET["fecha"])){
        if(strlen($_GET["fecha"]) > 0){
            $date = date("d-m-y", strtotime($_GET["fecha"]));
        } else {
            $date = date('y-m-d', strtotime('yesterday'));
        }
    
        $filterByDate = Room::FilterByDate($date);
        $filterByDateAndDeleted = Room::FilterByCondition(true, $filterByDate);

        $roomsWithIndividualGuest = Room::FilterByGuestType("INDI", $filterByDateAndDeleted);
        $roomsWithCorporativeGuest = Room::FilterByGuestType("CORPO", $filterByDateAndDeleted);
    
        $costOnDate = Room::CalculeCost($filterByDateAndDeleted);
        $roomsWithIndividualGuest = Room::CalculeCost($roomsWithIndividualGuest);
        $roomsWithCorporativeGuest = Room::CalculeCost($roomsWithCorporativeGuest);
    
        echo json_encode([
            "response" => [
                "total" => $costOnDate, 
                "habitaciones con tipo individual" => $roomsWithIndividualGuest, 
                "habitaciones con tipo corporativo" => $roomsWithCorporativeGuest, 
            ]
        ], JSON_PRETTY_PRINT);
    
    } else if(isset($_GET["nroCliente"])){
        $filterByDeleted = Room::FilterByCondition(true);

        echo json_encode([
            "response" => [Room::FilterByClientId($_GET["nroCliente"], $filterByDeleted)]
        ], JSON_PRETTY_PRINT);
    } else if(isset($_GET["tipo"])){
        $filterByDeleted = Room::FilterByCondition(true);
        echo json_encode([
            "response" => [Room::FilterByType($_GET["tipo"], $filterByDeleted)]
        ], JSON_PRETTY_PRINT);
    } else if(isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])){
        $startDate = date("d-m-y", strtotime($_GET["fechaInicial"]));
        $endDate = date("d-m-y", strtotime($_GET["fechaFinal"]));
    
        $filterByDate = Room::filterByDate($endDate, Room::filterByDate($startDate));
        $filterByDateAndDeleted = Room::FilterByCondition(true, $filterByDate);
    
        usort($filterByDateAndDeleted, function ($a, $b) {
            return strcmp($a->checkinDate, $b->checkinDate);
        });
    
        echo json_encode([
            "response" => [$filterByDateAndDeleted]
        ], JSON_PRETTY_PRINT);
    
    } else {
        echo json_encode(['error' => 'Parametros no validos en consulta de reserva cancelada']);
    }
}

function allBookingQueries(){
    if(isset($_GET["nroDniCliente"])){
        echo json_encode([
            "response" => [Room::FilterByGuestNumberIdentification($_GET["nroDniCliente"])]
        ], JSON_PRETTY_PRINT);
    } else if(isset($_GET["modalidadDePago"])){
        echo json_encode([
            "response" => [Room::FilterByGuestPaymentMethod($_GET["modalidadDePago"])]
        ], JSON_PRETTY_PRINT);
    } else {
        echo json_encode(['error' => 'Parametros no validos en consulta de todo tipo de reserva']);
    }
}
