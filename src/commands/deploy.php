<?php

    //Assign $branch from CL
    if(!$branch = (!empty($_GET[1]))? $_GET[1] : null){
        Console::log("Branch not specified."); 
        die;
    }
    
    $_cfg_path = "";
    if(Helper::hasOption($_GET, array("--config-file", "-cf"))){ 
        $_cfg_path = (@($_GET['options']['--config-file'])?: $_GET['options']['-cf']);

        if(substr($_cfg_path, 0, 1) !== DIRECTORY_SEPARATOR){
            $_cfg_path = "{$_CWD}/{$_cfg_path}";
        } 
    } else {
        $_cfg_path = "{$_CWD}/deploy.config.php";
    }

    //Check for config file
    if(!file_exists("{$_cfg_path}")){
        Console::log("Config not found in {$_cfg_path}");
        die;
    }
    $_config = include("{$_cfg_path}");
    
    //Check if settings for branch exist
    if(!isset($_config[$branch])){
        Console::log("Settings for {$branch} not found.");
        die;
    }
    //Check if current branch matches working copy
    if($branch != Git::execute("symbolic-ref --short HEAD")){
        Console::log("Working copy does not match target branch. Checkout the correct branch.");
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
    Console::log("Connecting to {$_cred['server']}...");
    if(!@$deploy->login($_cred['user'], $_cred['pass'])) {
        Console::log("Login failed with {$_cred['user']}@{$_cred['server']} using password {$_cred['pass']}");
        die;
    }
    Console::log("...connected!");
    //Set base directory
    $deploy->chdir($_cred['path']);

    //Grab remote hash or set empty
    $remote_hash = ($deploy->getString('.revisionhash'))?: "4b825dc642cb6eb9a060e54bf8d69288fbee4904";

    //Calculate differences
    $diff = Git::executeToArray("diff --name-status {$remote_hash} {$current_hash}");

    if(Helper::hasOption($_GET, array("--dryrun", "-d"))){
        Console::log("Dry Run! No actions will actually be taken!");
    }

    foreach($diff as $line){
        //Grab File Info
        $fileInfo = Git::parseGitDiff($line);

        //Change directories to the appropriate one, making in the process
        include("_chdir.php");

        if($fileInfo['command'] === 'D'){
            Console::log("- Deleting {$fileInfo['path']}");
            include("_delete.php");
        } else if($fileInfo['command'] == 'R'){
            Console::log("% Renaming file {$fileInfo['path']}");
            include("_add.php");
            //For the time being, renaming a file uploads the newly named file.
            //This should be changed to a move command.
        } else {
            Console::log("+ Adding file {$fileInfo['path']}");
            include("_add.php");
        }

        //Reset to home path
        $deploy->chdir($_cred['path']);
    }

    if(!Helper::hasOption($_GET, array("--dryrun", "-d"))){
        $deploy->putString($current_hash, ".revisionhash"); 
    }

    Console::log("Deployed {$current_hash} to {$branch}");