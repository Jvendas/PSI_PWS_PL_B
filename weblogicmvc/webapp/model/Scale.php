<?php

use ActiveRecord\Model;

class Scale extends Model
{
    public static function obter($idescala)
    {
        return Scale::find([$idescala]);
    }
}