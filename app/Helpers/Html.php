<?php
class Html{
    // Escapa texto para exibir com segurança no HTML.
    public static function e($valor){
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }

    // Converte uma imagem guardada no banco (BLOB) em data URI para usar no <img src>.
    public static function imagem($binario){
        if (empty($binario)) {
            return '';
        }

        $tipo = (new finfo(FILEINFO_MIME_TYPE))->buffer($binario);

        if (!in_array($tipo, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return '';
        }

        return 'data:' . $tipo . ';base64,' . base64_encode($binario);
    }

    // Foto de perfil da pessoa ou a imagem padrão.
    public static function fotoPerfil($binario){
        $foto = self::imagem($binario);
        return $foto !== '' ? $foto : URL . '/public/img/foto_perfil.png';
    }

    public static function data($data, $formato = 'd/m/Y'){
        return $data ? date($formato, strtotime($data)) : '';
    }

    // "Agora mesmo", "Há 5 minutos", "Há 3 dias"...
    public static function tempoRelativo($data){
        $segundos = time() - strtotime($data);

        if ($segundos < 60) return 'Agora mesmo';
        if ($segundos < 3600) {
            $n = floor($segundos / 60);
            return 'Há ' . $n . ($n == 1 ? ' minuto' : ' minutos');
        }
        if ($segundos < 86400) {
            $n = floor($segundos / 3600);
            return 'Há ' . $n . ($n == 1 ? ' hora' : ' horas');
        }
        if ($segundos < 86400 * 30) {
            $n = floor($segundos / 86400);
            return 'Há ' . $n . ($n == 1 ? ' dia' : ' dias');
        }

        return 'Em ' . date('d/m/Y', strtotime($data));
    }

    // Valida um arquivo de imagem enviado. Retorna [conteudo, erro].
    public static function lerImagemEnviada($campo, $tamanhoMaximoMb = 5){
        if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] === UPLOAD_ERR_NO_FILE) {
            return [null, null];
        }

        if ($_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
            return [null, 'Não foi possível enviar a imagem.'];
        }

        if ($_FILES[$campo]['size'] > $tamanhoMaximoMb * 1024 * 1024) {
            return [null, 'A imagem deve ter no máximo ' . $tamanhoMaximoMb . ' MB.'];
        }

        $tipo = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES[$campo]['tmp_name']);

        if (!in_array($tipo, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return [null, 'Use uma imagem JPG, PNG ou WEBP.'];
        }

        $conteudo = file_get_contents($_FILES[$campo]['tmp_name']);

        if ($conteudo === false) {
            return [null, 'Não foi possível ler a imagem.'];
        }

        return [$conteudo, null];
    }
}
