<?php

class Git 
{
    private static function prepareCommandString($command){
        //These paths should be configurable at one point
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $git = "\"C:\\Program Files\\Git\\bin\\bash.exe\" -c \"/mingw32/bin/git";
            $command .= "\"";
        } else {
            //run `sudo xcode-select --switch /Library/Developer/CommandLineTools/` if you are having issues with xcode 
            $git = "export DYLD_LIBRARY_PATH=/usr/lib;/usr/bin/git";
        }
        
        $execution_string = "{$git} {$command} 2>&1";
        
        return $execution_string;
    }
    
    public static function execute($command){
        $execution_string = self::prepareCommandString($command);
        return trim(shell_exec($execution_string));
    }
    
    public static function executeToArray($command){
        $execution_string = self::prepareCommandString($command);
        exec("$execution_string", $return);
        return $return;
    }
    
    public static function parseGitDiff($line){
        $return['command'] = substr($line, 0, 1); //Command issued by Git
        $return['path'] = substr($line, 2); //Full path of the file
        $filePathArray = explode("/", $return['path']);
        $return['filename'] = array_pop($filePathArray); //Just the file name
        $return['directories'] = $filePathArray; //Array of directories
        return $return;
    }
    
}