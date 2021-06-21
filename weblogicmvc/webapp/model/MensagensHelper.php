<?php

use ArmoredCore\WebObjects\Session;

class MensagensHelper
{
    // =========================================================
    //                          helpers mensagens
    // =========================================================    

    public static function setSuccess($mensagem)
    {
        Session::set("successMessage", $mensagem);
    }

    public static function setError($mensagem)
    {
        Session::set("errorMessage", $mensagem);
    }

    public static function setWarning($mensagem)
    {
        Session::set("warningMessage", $mensagem);
    }
}
