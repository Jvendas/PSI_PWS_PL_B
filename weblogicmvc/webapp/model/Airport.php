<?php

use ActiveRecord\Model;

class Airport extends Model
{
    public static function obter($idaeroporto)
    {
        return Airport::find([$idaeroporto]);
    }
}
