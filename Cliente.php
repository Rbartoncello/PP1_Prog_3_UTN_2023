<?php

define("RECORD_CLIENTS", "hoteles.json");


    class Client
    {
        public $id;
        public $name;
        public $surname;
        public $typeIdentification;
        public $numberIdentification;
        public $email;
        public $type;
        public $country;
        public $city;
        public $phone;
        public $paymentMethod;

        public function __construct($name, $surname, $typeIdentification, $numberIdentification, $email, $type, $country, $city, $phone, $paymentMethod = "Efectivo"){
            $this->id = date("ymdhis");
            $this->name = $name;
            $this->surname = $surname;
            $this->typeIdentification = $typeIdentification;
            $this->numberIdentification = $numberIdentification;
            $this->email = $email;
            $this->type = $type;
            $this->country = $country;
            $this->city = $city;
            $this->phone = $phone;  
            $this->paymentMethod = $paymentMethod;
        }

        public static function Save($client){
            if(!Client::Exist($client)){
                $data = Client::Get();
                $data[] = $client;
                echo (file_put_contents(RECORD_CLIENTS, json_encode($data, JSON_PRETTY_PRINT)))? json_encode(['response' => 'ingresado']): ['error' => 'no se puedo cargar nueva cliente'];
            } else {
                echo (file_put_contents(RECORD_CLIENTS, json_encode(Client::uploadData($client), JSON_PRETTY_PRINT)))? json_encode(['response' => 'actulizado']): ['error' => 'no se puedo actualizar cliente'];
            }
            
        }

        public static function Get() {
            if(!file_exists(RECORD_CLIENTS)){
                return [];
            }

            $jsonContent = file_get_contents(RECORD_CLIENTS);
            if ($jsonContent !== false) {
                $data = json_decode($jsonContent);
                return $data !== null ? $data : [];                
            }
            return [];
        }
    
        public static function Exist($client){
            $data = Client::Get();
    
            if ($data) {
                foreach ($data as $item) {
                    if (strval($item->numberIdentification) === strval($client->numberIdentification))
                        return $item;
                }
            }
            return false;
        }

        public static function ExistByTypeAndNumber($id, $type){
            $data = Client::Get();
    
            if ($data) {
                foreach ($data as $item) {
                    if (strval($item->id) === strval($id) && $item->type === $type){
                        return $item;
                    } else if ($item->id === $id) {
                        return json_encode(['error' => 'Tipo de cliente incorrecto']);
                    }
                }
            }
            return json_encode(['error' => 'No existe la combinacion de numero y tipo de cliente']);
        }

        public static function uploadData($client, $data = null) {
            if(!$data) {
                $data = Client::Get();
            }

            foreach ($data as &$item) {
                if (strval($item->numberIdentification) === strval($client->numberIdentification)){
                    $id_old = $item->id;
                    $item = $client;
                    $item->id = $id_old;
                }
            }
            return $data;
        }

        public static function Upload($client, $data = null) {
            if(!$data) {
                $data = Client::Get();
            }

            foreach ($data as &$item) {
                if ((strval($item->id) === strval($client->id)) && ($item->type === $client->type)){
                    $item = $client;
                }
            }
            return $data;
        }
    }
