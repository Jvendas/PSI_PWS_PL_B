<?php

use ActiveRecord\Model;

class ScaleAirplane extends Model
{
    public static function obter($idescalaaviao )
    {
        return ScaleAirplane::find([$idescalaaviao]);
    }
}