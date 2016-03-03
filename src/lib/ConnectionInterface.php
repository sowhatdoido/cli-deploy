<?php

/*
    Interface for S/FTP protocol.
    Lists of common methods required for both SFTP and FTP to work interchangably using the same logic.
    May not apply for future implementations methods... when that time comes this interface should be renamed.
*/

interface ConnectionInterface {
    public function connect($server);
    public function login($user, $pass);
    public function chdir($path);
    public function mkdir($path);
    public function rmdir($path);
    public function rename($old, $new);
    public function delete($file);
    public function getString($file); // Reads a $file from the server at cwd
    public function putString($content, $file); // Put a string $content to a $file on the server at cwd
    public function put($path, $file); // Put local file at $path to $file on the server at cwd
    public function rawlist($dir);
    public function isEmptyDir($dir);
}