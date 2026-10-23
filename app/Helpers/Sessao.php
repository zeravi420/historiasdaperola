<?php
class Sessao{
    public static function mensagem($nome, $texto = null, $classe = null){
        if(!empty($nome)):
            if(!empty($texto) && empty($_SESSION[$nome])):
                if(!empty($_SESSION[$nome])):
                    unset($_SESSION[$nome]);
                endif;
                $_SESSION[$nome] = $texto;
                $_SESSION[$nome.'classe'] = $classe;
            elseif(!empty($_SESSION[$nome]) && empty($texto)):
                $classe = !empty($_SESSION[$nome.'classe']) ? $_SESSION[$nome.'classe']: 'alert alert-success';
                echo '<div class="'.$classe.'">'.$_SESSION[$nome].'</div>';
                unset($_SESSION[$nome]);
                unset($_SESSION[$nome.'classe']);
            endif;
        endif;
    }//fim da função mensagem

    public static function estaLogado(){
        if(isset($_SESSION['usuario_id'])):
            return true;
        else:
            return false;
        endif;
    }//fim da função estaLogado

    // Guarda os dados da pessoa logada na sessão.
    public static function entrar($pessoa){
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = (int) $pessoa->pess_id;
        $_SESSION['usuario_nome'] = $pessoa->pess_nome;
        $_SESSION['usuario_email'] = $pessoa->emai_nome;
        $_SESSION['usuario_perfil'] = $pessoa->perf_nome;
    }//fim da função entrar

    // Apaga os dados da sessão e troca o identificador (a sessão continua
    // aberta só para poder mostrar a mensagem "Você saiu").
    public static function sair(){
        $_SESSION = [];
        session_regenerate_id(true);
    }//fim da função sair

    public static function id(){
        return isset($_SESSION['usuario_id']) ? (int) $_SESSION['usuario_id'] : 0;
    }

    public static function nome(){
        return $_SESSION['usuario_nome'] ?? '';
    }

    public static function ehAdministrador(){
        return self::estaLogado() && ($_SESSION['usuario_perfil'] ?? '') === 'Administrador';
    }

    // Mensagem que aparece uma única vez na próxima página (tipo: sucesso ou erro).
    public static function flash($texto, $tipo = 'sucesso'){
        $_SESSION['flash'] = ['texto' => $texto, 'tipo' => $tipo];
    }

    public static function exibirFlash(){
        if (empty($_SESSION['flash'])) {
            return;
        }

        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        $erro = $flash['tipo'] === 'erro';
        $estilo = $erro
            ? 'background:#fdecea;color:#8a1c1c;border:1px solid #f5c2c0;'
            : 'background:#e8f6ee;color:#1d5e3a;border:1px solid #b9e2c9;';

        echo '<div role="' . ($erro ? 'alert' : 'status') . '" style="' . $estilo .
            'padding:12px 16px;border-radius:8px;margin:12px 0;font-size:15px;line-height:1.4;">' .
            htmlspecialchars($flash['texto'], ENT_QUOTES, 'UTF-8') . '</div>';
    }
}//fim da classe Sessao
