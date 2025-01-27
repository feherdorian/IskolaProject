<?php

namespace App\Helpers;
class VeletlenVaros 
{
    public static function randomVaros(){
        $varosok = ["Budapest", "Debrecen", "Pécs", "Szolnok"];
        $randomIndex = array_rand($varosok);
        $velettlenVaros = $varosok[$randomIndex];
        return $velettlenVaros;
    }
}