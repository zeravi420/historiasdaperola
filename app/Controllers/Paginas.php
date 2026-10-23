<?php

class Paginas extends Controller {

    public function index() {
        $this->view('paginas/index');
    }

    public function esqueceu_senha() {
        $this->view('paginas/esqueceu_senha');
    }

        public function configuracoes() {
        $this->view('paginas/Configuracoes');
    }
}