<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\puntos_gps;

class MapsController extends Controller
{
    //xzncbdbc
    public function buildArray()
    {
        $arc = fopen(base_path('puntos_gps.txt'), "r");
        while ($linea = fgets($arc)) {
            $array[] = $linea;
        }
        
        fclose($arc);
        return $array;
    }

    public function getRecord($record, $data)
    {
        switch ($data) {
            case 'dispositivo':
                return explode(",", $record)[0];
                break;
            case 'imei':
                return explode(",", $record)[1];
                break;
            case 'tiempo':
                return explode(",", $record)[2];
                break;
            case 'placa':
                return explode(";", explode(":", explode(",", $record)[4])[1])[0];
                break;
            case 'version':
                return explode(";", explode(":", explode(",", $record)[4])[1])[1];
                break;
            case 'latitud':
                return substr(explode(";", explode(",", $record)[5])[2], 1);
                break;
            case 'longitud':
                return '-' . substr(explode(";", explode(",", $record)[5])[3], 1);
                break;
            case 'fecha_recepcion':
                return explode("]", substr(explode("#", $record)[1], 1))[0];
                break;
            default:
                # code...
                break;
        }
    }

    public function saveRecords()
    {

        if (puntos_gps::count() == 0) {
            for ($i = 0; $i < count($this->buildArray()); $i++) {
                $data = new puntos_gps();
                $data->dispositivo = $this->getRecord($this->buildArray()[$i], 'dispositivo');
                $data->imei = $this->getRecord($this->buildArray()[$i], 'imei');
                $data->tiempo = $this->getRecord($this->buildArray()[$i], 'tiempo');
                $data->placa = $this->getRecord($this->buildArray()[$i], 'placa');
                $data->version = $this->getRecord($this->buildArray()[$i], 'version');
                $data->latitud = $this->getRecord($this->buildArray()[$i], 'latitud');
                $data->longitud = $this->getRecord($this->buildArray()[$i], 'longitud');
                $data->fecha_recepcion = $this->getRecord($this->buildArray()[$i], 'fecha_recepcion');
                $data->save();
            }
        }
    }
    
    public function getData()
    {
        $this->saveRecords();
        return response()->json(['data' => puntos_gps::all()]);
    }
}
