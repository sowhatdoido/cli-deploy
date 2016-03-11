<?php
    require("../vendor/autoload.php");

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
    //Check if current branch matches working copy
    if($branch != Git::execute("symbolic-ref --short HEAD")){
        Console::log("Working copy does not match target branch. Checkout the correct branch, or try the --force option.");
        die;
    }

    //To do: add --force, -f option

    //Grab current hash
    $current_hash = Git::execute("rev-parse --verify {$branch}");
    if($current_hash == "fatal: Needed a single revision"){
        //Note: This might not be necessary due to all the other checks
        Console::log("Branch {$branch} not found in git repository.");
        die;
    }

    //Connect to Remote Server
    $_cred = $_config[$branch];
    $_connectionClass = "{$_cred['protocol']}Connection";
    $deploy = new $_connectionClass();
    $deploy->connect($_cred['server']);
    if(!@$deploy->login($_cred['user'], $_cred['pass'])) {
        Console::log("Login failed with {$_cred['user']}@{$_cred['server']} using password {$_cred['pass']}");
        die;
    }

    //Set base directory
    $deploy->chdir($_cred['path']);

    //Grab remote hash or set empty
    $remote_hash = ($deploy->getString('.revisionhash'))?: "4b825dc642cb6eb9a060e54bf8d69288fbee4904";

    //Calculate differences
    $diff = Git::executeToArray("diff --name-status {$remote_hash} {$current_hash}");

    
    