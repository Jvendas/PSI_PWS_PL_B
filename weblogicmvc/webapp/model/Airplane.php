<?php

use ActiveRecord\Model;

class Airplane extends Model
{
    public static function obter($idaviao)
    {
        return Airplane::find([$idaviao]);
    }
}