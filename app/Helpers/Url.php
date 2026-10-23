<?php
class Url{
    public static function redirecionar($url){
        header('Location: ' . URL . '/' . ltrim($url, '/'));
        exit;
    }// fim da função redirecionar
}//fim da classe Url
