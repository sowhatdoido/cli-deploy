<?php

use phpseclib\Net\SFTP;

class SFTPConnection implements ConnectionInterface {
    private $_conn = null;
    
    public function connect($server){
        //This method always return true on connect
        $this->_conn = new SFTP($server);
        return true;
    }
    
    public function login($user, $pass){
        if($this->_conn->login($user, $pass)){
            return true;
        }
        
        return false;
    }
    
    public function chdir($path){
        return $this->_conn->chdir($path);
    }
    
    public function mkdir($path){
        return $this->_conn->mkdir($path);
    }
    
    public function rmdir($path){
        return $this->_conn->rmdir($path);
    }
    
    public function rename($old, $new){
        return $this->_conn->rename($old, $new);
    }
    
    public function getString($file){
        return $this->_conn->get($file);
    }
    
    public function delete($file){
        return $this->_conn->delete($file);
    }
    
    public function rawlist($dir){
        return $this->_conn->rawlist($dir);
    }
    
    public function isEmptyDir($dir){
        $directorysize = $this->rawlist($dir);
        return (sizeof($directorysize) == 2 && isset($directorysize['.']) && isset($directorysize['..']));
    }
    
    public function put($path, $file){
        return $this->_conn->put($file, $path, SFTP::SOURCE_LOCAL_FILE);
    }
    
    public function putString($content, $file){
        return $this->_conn->put($file, $content);
    }
}