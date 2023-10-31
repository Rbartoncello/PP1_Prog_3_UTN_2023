<?php

define("INVENTARY_ROOMS", "reservas.json");


    class Room
    {
        public $id;
        public $checkinDate;
        public $checkoutDate; 
        public $type; 
        public $cost; 
        public $guest;
        public $deleted;

        public function __construct($checkinDate, $checkoutDate, $type, $cost, $guest, $deleted = false){
            $this->id = date("ymdhis");
            $this->checkinDate = date_format(date_create($checkinDate), 'y-m-d');
            $this->checkoutDate = date_format(date_create($checkoutDate), 'y-m-d');
            $this->cost = $cost;
            $this->type = $type;
            $this->guest = $guest;
            $this->deleted = $deleted;
        }

        public static function Save($room){
            $data = Room::Get();
            $data[] = $room;
            echo (file_put_contents(INVENTARY_ROOMS, json_encode($data, JSON_PRETTY_PRINT)))? json_encode(['response' => 'ingresada']): ['error' => 'no se puedo cargar nueva venta'];
        }

        public static function Get() {
            if (!file_exists(INVENTARY_ROOMS)) {
                return [];                
            }

            $jsonContent = file_get_contents(INVENTARY_ROOMS);
            if ($jsonContent !== false) {
    
                $data = json_decode($jsonContent);
            
                return $data !== null ? $data : [];                
            }
            return [];
        }

        public static function Exist($id){
            $data = Room::Get();

            if ($data) {
                foreach ($data as $item) {
                    if (($item->id === $id)){
                        return $item;
                    }
                }
            }
            return false;
        }

        public static function UploadData($room) {
            $fixed = false;
            $data = Room::Get();

            foreach ($data as &$item) {
                if (($item->id === $room->id)){
                    $item = $room;
                    $fixed = true;
                    break;
                }
            }

            return $fixed ? file_put_contents(INVENTARY_ROOMS, json_encode($data, JSON_PRETTY_PRINT)) : false;
        }

        public static function Delete($id){
            $room = Room::Exist($id);
            if($room !== false){
                $room->deleted = true;
                Room::UploadData($room);
                echo json_encode(["response" => "Se ha borrado con exito"]);  
            } else {
                echo json_encode(["error" => "El id no exite no existe en los registros."]);  
            }
        }

        public static function FilterByDate($date, $rooms = null){
            if ($rooms === null)
                $rooms = Room::Get();
            return array_filter($rooms, function ($room) use ($date) {
                return $room->checkinDate <= $date && $room->checkoutDate >= $date;
            });
        }

        public static function FilterByCondition($condition, $rooms = null){
            if ($rooms === null)
                $rooms = Room::Get();
            return array_filter($rooms, function ($room) use ($condition) {
                return $room->deleted === $condition;
            });
        }

        public static function FilterByType($type, $rooms = null){
            if ($rooms === null)
                $rooms = Room::Get();

            return array_filter($rooms, function ($room) use ($type) {
                return $room->type === $type;
            });
        }

        public static function FilterByGuestType($type, $rooms = null){
            if ($rooms === null)
                $rooms = Room::Get();

            return array_filter($rooms, function ($room) use ($type) {
                return $room->guest->type === $type;
            });
        }

        public static function FilterByGuestPaymentMethod($paymentMethod, $rooms = null){
            if ($rooms === null)
                $rooms = Room::Get();

            return array_filter($rooms, function ($room) use ($paymentMethod) {
                return $room->guest->paymentMethod === $paymentMethod;
            });
        }

        public static function FilterByClientId($clientId, $rooms = null){
            if ($rooms === null)
                $rooms = Room::Get();

            return array_filter($rooms, function ($room) use ($clientId) {
                return $room->guest->id === $clientId;
            });
        }

        public static function FilterByGuestNumberIdentification($numberIdentification, $rooms = null){
            if ($rooms === null)
                $rooms = Room::Get();

            return array_filter($rooms, function ($room) use ($numberIdentification) {
                return $room->guest->numberIdentification === $numberIdentification;
            });
        }

        public static function CalculeCost($rooms = null){
            if ($rooms === null)
                $rooms = Room::Get();

            return array_reduce($rooms, function ($carry, $room){
                return $room->cost + $carry;
            }, 0);
        }
    }