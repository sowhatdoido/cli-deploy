<?php
    require(__DIR__ . "/vendor/autoload.php");

    $_CWD = getcwd(); //Get current working directory

    //$_GET['options'] contains your options
    //$_GET[0] main argument, increments for additional arguments
    parse_str(implode('&', array_slice($argv, 1)), $_GET['options']);
    foreach($_GET['options'] as $k => $v){
        if($v === '' && substr($k, 0, 1) !== '-'){
            $_GET[] = $k;
            unset($_GET['options'][$k]);
        }
    }

    //Assign $branch from CL
    if(!$command = (!empty($_GET[0]))? str_replace("../", '', $_GET[0]) : null){
        Console::log("Command not specified!"); 
        die;
    }

    $command_path = __DIR__ . "/commands/{$command}.php";

    if(!file_exists($command_path)){
        Console::log("Command doesn't exist!"); 
        die;
    }
    
    require($command_path);
    