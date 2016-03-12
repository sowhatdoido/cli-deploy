<?php

    if(!(Helper::hasOption($_GET, array("--dryrun", "-d")))){
        if(!$deploy->delete($fileInfo['filename'])){
            Console::log("Couldn't delete {$fileInfo['filename']}.");
        }
        
        //clean the directory from bottom up
        if(isset($fileInfo['directories'])){
            foreach(array_reverse($fileInfo['directories']) as $directory){
                //Console::log("Checking $directory size");
                if($deploy->isEmptyDir('.')){
                    Console::log("Deleting /{$directory}");
                    $deploy->chdir('..'); //Go up a level
                    if(!$deploy->rmdir($directory)){ 
                        Console::log("Something went wrong when trying to delete {$directory}. Please report this!"); 
                        die;
                    } // we use rmdir because it fails if there is actually data in there
                }
            }
        }
    }

    