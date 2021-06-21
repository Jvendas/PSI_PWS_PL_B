<?php

use ActiveRecord\Model;

class User extends Model
{
    public static function obter($idutilizador)
    {
        return User::find([$idutilizador]);
    }
}