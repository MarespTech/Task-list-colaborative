<?php
//URL Constante
    //define('PORT'     , '7879'); 
define('BASEPATH' , '/Tasklist/');
define('URL'      , 'http://localhost'/*'http://127.0.0.1:'.PORT*/.BASEPATH); //En casa tratar de utilizar con IP
 
//Constantes para los paths de archivos
define('DS'       , DIRECTORY_SEPARATOR);
define('ROOT'     , getcwd().DS);
define('APP'      , ROOT.'app'.DS);
define('VIEWS'    , ROOT.'views'.DS);
define('INCLUDES' , ROOT.'includes'.DS);


define('ASSETS'       , URL.'assets/');
define('CSS'          , ASSETS.'css/');
define('JS'           , ASSETS.'js/');
define('IMG'          , ASSETS.'img/');
define('PLUGINS'      , ASSETS.'plugins/');

require_once APP.'dbConnect.php';
require_once APP.'functions.php';