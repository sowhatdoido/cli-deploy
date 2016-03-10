<?php
    require("../vendor/autoload.php");

    $_CWD = getcwd(); //Get current working directory

    //$_GET['options'] contains your options
    //$_GET[0] main argument, increments for additional arguments
    parse_str(implode('&', array_slice($argv, 1)), $_GET['options']);
    foreach($_GET['options'] as $k => $v){
        if($v === '' && substr($k, 1) !== '-'){
            $_GET[] = $k;
            unset($_GET['options'][$k]);
        }
    }


    //Check for config file
    if(!file_exists("{$_CWD}/deploy.config.php")){
        Console::log("deploy.config.php not found in {$_CWD}");
        die;
    }
    $_config = include("{$_CWD}/deploy.config.php");

    
    //Assign $branch from CL
    if(!$branch = (!empty($_GET[0]))? $_GET[0] : null){
        Console::log("Branch not specified."); die;
    }
    //Check if settings for branch exist
    if(!isset($_config[$branch])){
        Console::log("Settings for {$branch} not found.");
        die;
    }
    if($branch != Git::execute("symbolic-ref --short HEAD")){
        Console::log("Working copy does not match target branch. Checkout the correct branch, or try the --force option.");
        die;
    }
    //To do: add --force, -f option

    $current_hash = Git::execute("rev-parse --verify {$branch}");
    if($current_hash == "fatal: Needed a single revision"){
        //Note: This might not be necessary due to all the other checks
        Console::log("Branch {$branch} not found in git repository.");
        die;
    }

    var_dump($current_hash);