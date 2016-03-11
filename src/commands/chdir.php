<?php

    if(!(Helper::hasOption($_GET, array("--dryrun", "-d")))){
        foreach($fileInfo['directories'] as $directory){
            //Console::log("Attempting to change to directory {$directory}");
            if(!$deploy->chdir($directory)){
                Console::log("Creating /{$directory}");
                $deploy->mkdir($directory);
                if(!$deploy->chdir($directory)){ 
                    Console::log("Couldn't create /{$directory}"); 
                    die;
                }
            }
        }
    }