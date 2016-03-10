<?php

class Console {
    
    //Reminiscent and inspired by javascript's console.log command
    //Eventually add an optional file parameter
    public static function log($string){
        echo "{$string}" . PHP_EOL;
    }
    
}