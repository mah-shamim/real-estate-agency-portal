<?php

/**
 * Description of MY_Composer
 *
 * @author Rana
 */
class MY_Composer
{
    function __construct()
    {
        if(file_exists('./vendor/autoload.php'))
            include("./vendor/autoload.php");
    }
}

?>