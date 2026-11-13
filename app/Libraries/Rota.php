<?php
class Rota{
   private $controlador = "Paginas";
   private $metodo = 'index';
   private $parametros = [];

   public function __construct(){
      $url = $this->url() ? $this->url() : [];

      if(isset($url[0]) && file_exists(APP.'/Controllers/'.ucwords($url[0]).'.php')){
         $this->controlador = ucwords($url[0]);
         unset($url[0]);
      }

      require_once APP.'/Controllers/'.$this->controlador.'.php';
      $this->controlador = new $this->controlador;

      if(isset($url[1]) && method_exists($this->controlador, $url[1])){
         $this->metodo = $url[1];
         unset($url[1]);
      }

      $this->parametros = $url ? array_values($url) : [];
      call_user_func_array([$this->controlador, $this->metodo], $this->parametros);
   }

   private function url(){
      $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);

      if(isset($url) && $url !== ''){
         $url = trim(rtrim($url, '/'));
         return explode('/', $url);
      }

      return [];
   }
}
?>
