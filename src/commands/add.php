<?php

    if(!(Helper::hasOption($_GET, array("--dryrun", "-d")))){
        if(!$deploy->put("{$_CWD}/{$fileInfo['path']}", $fileInfo['filename'])){
            Console::log("Couldn't add file {$fileInfo['path']}");
        }
    }