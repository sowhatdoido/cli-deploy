<?php

class FTPConnection implements ConnectionInterface {
    private $_conn = null;
    
    public function __destruct(){
        if(!empty($this->_conn))
            ftp_close($this->_conn);
        return;
    }
    
    public function connect($server){
        $this->_conn = ftp_connect($server);
        
        if($this->_conn)
            return true;
        return false;
    }
    
    public function login($user, $pass){
        if(ftp_login($this->_conn, $user, $pass)){
            ftp_pasv($this->_conn, true);
            return true;
        }
        
        return false;
    }
    
    public function chdir($path){
        return @ftp_chdir($this->_conn, $path);
    }
    
    public function mkdir($path){
        return ftp_mkdir($this->_conn, $path);
    }
    
    public function rmdir($path){
        return ftp_rmdir($this->_conn, $path);
    }
    
    public function rename($old, $new){
        return ftp_rename($this->_conn, $old, $new);
    }
    
    public function getString($file){
        ob_start();
        $result = @ftp_get($this->_conn, "php://output", $file, FTP_BINARY);
        $data = ob_get_contents();
        ob_end_clean();
        return $data;
    }
    
    public function delete($file){
        return ftp_delete($this->_conn, $file);
    }
    
    public function rawlist($dir){
        return ftp_nlist($this->_conn, $dir);
    }
    
    public function isEmptyDir($dir){
        $directorysize = $this->rawlist($dir);
        return (sizeof($directorysize) == 2 && in_array('.', $directorysize) && in_array('..', $directorysize));
    }
    
    public function put($path, $file){
        return ftp_put($this->_conn, $file, $path, FTP_BINARY);
    }
    
    public function putString($content, $file){
        $stream = fopen('data://text/plain,' . $content,'r');
        return ftp_fput($this->_conn, $file, $stream, FTP_BINARY);
    }
}