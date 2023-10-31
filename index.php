<?php

switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        switch(($_GET["action"])){
            case 'consultaReservas':
                include 'consultaReservas.php';
                break;
            default:
                echo json_encode(['error' => 'Mal parametros en get']);
                break;
        }
        break;
    case 'POST':
        switch(($_GET["action"])){
            case 'clienteAlta':
                include 'clienteAlta.php';
                break;
            case 'clienteConsulta':
                include 'consultarCliente.php';
                break;
            case 'reservaHabitacion':
                include 'reservaHabitacion.php';
                break;
            case 'cancelarReserva':
                include 'cancelarReserva.php';
                break;
            case 'ajusteReserva':
                include 'ajusteReserva.php';
                break;
            default:
                echo json_encode(['error' => 'Mal parametros en post']);
                break;
        }
        break;
    case 'PUT':
        switch(($_GET["action"])){
            case 'modificarCliente':
                include 'modificarCliente.php';
                break;
            default:
                echo json_encode(['error' => 'Mal parametros en put']);
                break;
        }
        break;
    case 'DELETE':
        switch(($_GET["action"])){
            case 'borrarCliente':
                include 'borrarCliente.php';
                break;
            default:
                echo json_encode(['error' => 'Mal parametros en delete']);
                break;
        }
        break;
    default:
        echo 'Verbo no permitido';
        break;
}
?>