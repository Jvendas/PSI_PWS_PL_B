<?php

use ActiveRecord\Model;

class Flight extends Model
{
    public static function obter($idvoo)
    {
        return Flight::find([$idvoo]);
    }
}