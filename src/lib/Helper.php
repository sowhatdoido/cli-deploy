<?php 

class Helper {
    
    public static function hasOption($get, $args){
        if(!is_array($args)){
            $args = array($args);
        }
        $keys = array_keys($get['options']);
        
        return !empty(array_intersect($keys, $args));
    }
    
}