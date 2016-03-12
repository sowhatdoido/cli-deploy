<?php
    
    if(file_exists("{$_CWD}/deploy.config.php")){
        Console::log("A config file already exists!");
        die;
    }

    if(!copy(__DIR__ . "/../deploy.config.php", "{$_CWD}/deploy.config.php")){
         Console::log("deploy.config.php could not be created in {$_CWD}. Please check your folder permissions, or manually add a config file.");
    } else {
        Console::log("deploy.config.php created in {$_CWD}.");

        if(strpos(file_get_contents("{$_CWD}/.gitignore"), "deploy.config.php") === false){
            if(!file_put_contents("{$_CWD}/.gitignore", "\ndeploy.config.php", FILE_APPEND)){
                Console::log("Add \"deploy.config.php\" to your .gitignore file");
            }
        }
    }