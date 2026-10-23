<?php

class Administrador extends Controller {
    use AcoesDaConta;

    // Páginas que podem ser abertas sem estar logado como administrador.
    private $paginasPublicas = ['login_adm', 'login', 'logout'];

    public function __construct() {
        $metodo = explode('/', trim((string) ($_GET['url'] ?? ''), '/'))[1] ?? '';

        if (!in_array($metodo, $this->paginasPublicas, true)) {
            $this->exigirAdministrador();
        }
    }

    public function index() {
        Url::redirecionar('administrador/inicio_adm');
    }

    // ---------------------------------------------------------------
    // Acesso
    // ---------------------------------------------------------------

    public function login_adm() {
        if (Sessao::ehAdministrador()) {
            Url::redirecionar('administrador/inicio_adm');
        }
        $this->view('administrador/login_adm');
    }

    public function login() {
        $this->processarLogin('administrador/login_adm', true);
    }

    public function logout() {
        Sessao::sair();
        Sessao::flash('Você saiu da sua conta.');
        Url::redirecionar('administrador/login_adm');
    }

    public function cadastro_adm() {
        // Administradores são definidos em "Gerenciar Perfis".
        Url::redirecionar('administrador/gerenciar_perfis');
    }

    // ---------------------------------------------------------------
    // Perfil
    // ---------------------------------------------------------------

    public function perfil_adm() {
        $this->mostrarPerfil('administrador/perfil_adm');
    }

    public function editar_perfil_adm() {
        $this->processarEdicaoPerfil('administrador/editar_perfil_adm', 'administrador/perfil_adm', 'administrador/editar_perfil_adm');
    }

    public function alterar_senha_adm() {
        $this->processarAlteracaoSenha('administrador/alterar_senha_adm', 'administrador/perfil_adm', 'administrador/alterar_senha_adm');
    }

    public function inicio_adm() {
        $this->view('administrador/inicio_adm');
    }

    // ---------------------------------------------------------------
    // Gerenciar perfis (pessoas cadastradas)
    // ---------------------------------------------------------------

    public function gerenciar_perfis() {
        $this->view('administrador/gerenciar_perfis', [
            'pessoas' => $this->model('Pessoa')->listarTodas()
        ]);
    }

    public function editar_usuarios() {
        $pessoaModel = $this->model('Pessoa');

        if ($this->ehPost()) {
            $id = $this->idRecebido('id', INPUT_POST);
            $perfil = filter_input(INPUT_POST, 'perfil', FILTER_VALIDATE_INT);
            $status = $_POST['status'] ?? '';
            $pessoa = $id ? $pessoaModel->buscarPorId($id) : false;

            if (!$pessoa || !in_array($perfil, [Pessoa::PERFIL_ADMINISTRADOR, Pessoa::PERFIL_USUARIO], true)
                || !in_array($status, ['Ativo', 'Inativo'], true)) {
                Sessao::flash('Dados inválidos.', 'erro');
                Url::redirecionar('administrador/gerenciar_perfis');
            }

            $continuaAdminAtivo = $perfil === Pessoa::PERFIL_ADMINISTRADOR && $status === 'Ativo';

            if ($id === Sessao::id() && !$continuaAdminAtivo) {
                Sessao::flash('Você não pode remover o seu próprio acesso de administrador.', 'erro');
                Url::redirecionar('administrador/editar_usuarios?id=' . $id);
            }

            $pessoaModel->atualizarPerfilEStatus($id, $perfil, $status);

            Sessao::flash('Alterações salvas com sucesso!');
            Url::redirecionar('administrador/gerenciar_perfis');
        }

        $id = $this->idRecebido();
        $pessoa = $id ? $pessoaModel->buscarPorId($id) : false;

        if (!$pessoa) {
            Sessao::flash('Selecione uma pessoa na lista para editar.', 'erro');
            Url::redirecionar('administrador/gerenciar_perfis');
        }

        $this->view('administrador/editar_usuarios', [
            'pessoa' => $pessoa,
            'perfis' => $pessoaModel->listarPerfis()
        ]);
    }

    public function excluir_pessoa() {
        $id = $this->idRecebido('id', INPUT_POST);
        $pessoaModel = $this->model('Pessoa');

        if (!$this->ehPost() || !$id) {
            Url::redirecionar('administrador/gerenciar_perfis');
        }

        if ($id === Sessao::id()) {
            Sessao::flash('Você não pode excluir a sua própria conta.', 'erro');
            Url::redirecionar('administrador/gerenciar_perfis');
        }

        $totalHistorias = $pessoaModel->totalHistoriasCadastradas($id);

        if ($totalHistorias > 0) {
            Sessao::flash(
                'Esta pessoa cadastrou ' . $totalHistorias . ' história(s) e não pode ser excluída. ' .
                'Edite e marque como "Inativo" para bloquear o acesso.',
                'erro'
            );
            Url::redirecionar('administrador/gerenciar_perfis');
        }

        if ($pessoaModel->excluir($id)) {
            Sessao::flash('Pessoa excluída com sucesso.');
        } else {
            Sessao::flash('Não foi possível excluir esta pessoa.', 'erro');
        }

        Url::redirecionar('administrador/gerenciar_perfis');
    }

    // ---------------------------------------------------------------
    // Histórias
    // ---------------------------------------------------------------

    public function menu_adm() {
        $this->view('administrador/menu_adm');
    }

    public function nova_historia() {
        $this->view('administrador/nova_historia');
    }

    public function salvar_historia() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URL . '/administrador/nova_historia');
            exit;
        }

        $categoria = filter_input(INPUT_POST, 'categoria', FILTER_VALIDATE_INT);

        if (!$categoria || !in_array($categoria, [1, 2], true)) {
            die('Selecione uma categoria válida.');
        }

        $todosIdiomas = [
            [
                'id' => 1,
                'titulo' => trim($_POST['titulo_pt'] ?? ''),
                'legenda' => trim($_POST['legenda_pt'] ?? ''),
                'texto' => trim($_POST['texto_pt'] ?? '')
            ],
            [
                'id' => 2,
                'titulo' => trim($_POST['titulo_es'] ?? ''),
                'legenda' => trim($_POST['legenda_es'] ?? ''),
                'texto' => trim($_POST['texto_es'] ?? '')
            ],
            [
                'id' => 3,
                'titulo' => trim($_POST['titulo_wari'] ?? ''),
                'legenda' => trim($_POST['legenda_wari'] ?? ''),
                'texto' => trim($_POST['texto_wari'] ?? '')
            ]
        ];

        $idiomas = [];

        foreach ($todosIdiomas as $idioma) {
            $tituloPreenchido = $idioma['titulo'] !== '';
            $legendaPreenchida = $idioma['legenda'] !== '';
            $textoPreenchido = $idioma['texto'] !== '';

            $algumPreenchido =
                $tituloPreenchido ||
                $legendaPreenchida ||
                $textoPreenchido;

            $todosPreenchidos =
                $tituloPreenchido &&
                $legendaPreenchida &&
                $textoPreenchido;

            // aq o português é obrigatório.
            if ($idioma['id'] === 1 && !$todosPreenchidos) {
                die('Preencha título, legenda e texto em português.');
            }

            // tradução opcional precisa estar completa ou então precisa ter todos os campos vazios.
            if ($idioma['id'] !== 1 && $algumPreenchido && !$todosPreenchidos) {
                die(
                    'Preencha título, legenda e texto da tradução escolhida, ' .
                    'ou deixe os três campos vazios.'
                );
            }

            // Só envia ao model os idiomas que foram preenchidos.
            if ($todosPreenchidos) {
                $idiomas[] = $idioma;
            }
        }

        // Verifica o envio da imagem.
        if (
            !isset($_FILES['imagem']) ||
            $_FILES['imagem']['error'] !== UPLOAD_ERR_OK
        ) {
            die('Selecione uma imagem válida.');
        }

        if ($_FILES['imagem']['size'] > 5 * 1024 * 1024) {
            die('A imagem deve ter no máximo 5 MB.');
        }

        $arquivoTemporario = $_FILES['imagem']['tmp_name'];

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $tipoImagem = $finfo->file($arquivoTemporario);

        $tiposPermitidos = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!in_array($tipoImagem, $tiposPermitidos, true)) {
            die('Use uma imagem JPG, PNG ou WEBP.');
        }

        $imagem = file_get_contents($arquivoTemporario);

        if ($imagem === false) {
            die('Não foi possível ler a imagem.');
        }

        // A história fica registrada em nome do administrador logado.
        $pessoa = Sessao::id();

        $historia = $this->model('Historia');

        $cadastroRealizado = $historia->cadastrar(
            $imagem,
            $categoria,
            $pessoa,
            $idiomas
        );

        if ($cadastroRealizado) {
            $tipo = $categoria === 1 ? 'Um novo mito foi publicado' : 'Uma nova lenda foi publicada';

            $this->model('Notificacao')->enviarParaPerfil(
                Pessoa::PERFIL_USUARIO,
                'Nova história adicionada!',
                $tipo . ': "' . $idiomas[0]['titulo'] . '".',
                $historia->ultimoIdCadastrado()
            );

            Sessao::flash('História cadastrada com sucesso!');
            Url::redirecionar('administrador/gerenciar_historia_adm');
        }

        Sessao::flash(
            'Não foi possível cadastrar a história. Verifique os dados e tente novamente. ' .
            'Se a imagem for grande, confira o max_allowed_packet do MySQL (veja o README).',
            'erro'
        );
        Url::redirecionar('administrador/nova_historia');
    }

    public function gerenciar_historia_adm() {
        $historiaModel = $this->model('Historia');

        $historias = $historiaModel->listarTodas();

        $this->view('administrador/gerenciar_historia_adm', [
            'historias' => $historias
        ]);
    }

    // Rota antiga sem id: volta para a lista.
    public function editar_historia() {
        Url::redirecionar('administrador/gerenciar_historia_adm');
    }

    public function editar_historia_adm() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            header('Location: ' . URL . '/administrador/gerenciar_historia_adm');
            exit;
        }

        $historiaModel = $this->model('Historia');
        $historia = $historiaModel->buscarPorId($id);

        if (!$historia) {
            header('Location: ' . URL . '/administrador/gerenciar_historia_adm');
            exit;
        }

        $this->view('administrador/editar_historia', [
            'historia' => $historia
        ]);
    }

    public function atualizar_historia() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URL . '/administrador/gerenciar_historia_adm');
            exit;
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $categoria = filter_input(INPUT_POST, 'categoria', FILTER_VALIDATE_INT);

        if (!$id || $id < 1 || !$categoria || !in_array($categoria, [1, 2], true)) {
            die('Dados inválidos para editar a história.');
        }

        $historiaModel = $this->model('Historia');
        $historiaAtual = $historiaModel->buscarPorId($id);

        if (!$historiaAtual) {
            die('História não encontrada.');
        }

        $todosIdiomas = [
            [
                'id' => 1,
                'titulo' => trim($_POST['titulo_pt'] ?? ''),
                'legenda' => trim($_POST['legenda_pt'] ?? ''),
                'texto' => trim($_POST['texto_pt'] ?? '')
            ],
            [
                'id' => 2,
                'titulo' => trim($_POST['titulo_es'] ?? ''),
                'legenda' => trim($_POST['legenda_es'] ?? ''),
                'texto' => trim($_POST['texto_es'] ?? '')
            ],
            [
                'id' => 3,
                'titulo' => trim($_POST['titulo_wari'] ?? ''),
                'legenda' => trim($_POST['legenda_wari'] ?? ''),
                'texto' => trim($_POST['texto_wari'] ?? '')
            ]
        ];

        $idiomasParaSalvar = [];

        foreach ($todosIdiomas as $idioma) {
            $algumPreenchido =
                $idioma['titulo'] !== '' ||
                $idioma['legenda'] !== '' ||
                $idioma['texto'] !== '';

            $todosPreenchidos =
                $idioma['titulo'] !== '' &&
                $idioma['legenda'] !== '' &&
                $idioma['texto'] !== '';

            if ($idioma['id'] === 1 && !$todosPreenchidos) {
                die('Preencha título, legenda e texto em português.');
            }

            if ($idioma['id'] !== 1 && $algumPreenchido && !$todosPreenchidos) {
                die('Preencha todos os campos da tradução escolhida.');
            }

            if ($todosPreenchidos) {
                $idiomasParaSalvar[] = $idioma;
            }
        }

        $imagem = null;

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
                die('Não foi possível enviar a imagem.');
            }

            if ($_FILES['imagem']['size'] > 5 * 1024 * 1024) {
                die('A imagem deve ter no máximo 5 MB.');
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $tipoImagem = $finfo->file($_FILES['imagem']['tmp_name']);

            if (!in_array($tipoImagem, ['image/jpeg', 'image/png', 'image/webp'], true)) {
                die('Use uma imagem JPG, PNG ou WEBP.');
            }

            $imagem = file_get_contents($_FILES['imagem']['tmp_name']);

            if ($imagem === false) {
                die('Não foi possível ler a imagem.');
            }
        }

        $salvou = $historiaModel->salvarEdicao(
            $id,
            $categoria,
            $imagem,
            $idiomasParaSalvar
        );

        if ($salvou) {
            $this->model('Notificacao')->enviarParaPerfil(
                Pessoa::PERFIL_ADMINISTRADOR,
                'História editada',
                'A história "' . $idiomasParaSalvar[0]['titulo'] . '" foi editada por ' . Sessao::nome() . '.',
                $id
            );

            Sessao::flash('História atualizada com sucesso!');
            Url::redirecionar('administrador/gerenciar_historia_adm');
        }

        die('Não foi possível atualizar a história. Verifique os dados e tente novamente.');
    }

    // Rota antiga: mesma tela de confirmação.
    public function excluir_historia() {
        $this->excluir_historia_adm();
    }

    public function excluir_historia_adm() {
        $historiaModel = $this->model('Historia');

        if ($this->ehPost()) {
            $id = $this->idRecebido('id', INPUT_POST);

            if ($id && $historiaModel->excluir($id)) {
                Sessao::flash('História excluída com sucesso.');
            } else {
                Sessao::flash('Não foi possível excluir a história.', 'erro');
            }

            Url::redirecionar('administrador/gerenciar_historia_adm');
        }

        $id = $this->idRecebido();
        $titulo = $id ? $historiaModel->tituloPorId($id) : '';

        if ($titulo === '') {
            Sessao::flash('História não encontrada.', 'erro');
            Url::redirecionar('administrador/gerenciar_historia_adm');
        }

        $this->view('administrador/excluir_historia', ['id' => $id, 'titulo' => $titulo]);
    }

    public function historias_adm() {
        $this->view('administrador/historias_adm', $this->dadosLeituraHistoria('administrador/gerenciar_historia_adm'));
    }

    // ---------------------------------------------------------------
    // Comentários
    // ---------------------------------------------------------------

    public function comentarios_adm() {
        Url::redirecionar('administrador/gerenciar_historia_adm');
    }

    public function excluir_comentario() {
        $comentarioModel = $this->model('Comentario');

        if ($this->ehPost()) {
            $id = $this->idRecebido('excluir_comentario', INPUT_POST) ?: $this->idRecebido('id', INPUT_POST);
            $comentario = $id ? $comentarioModel->buscarPorId($id) : false;

            if ($comentario && $comentarioModel->excluir($id)) {
                Sessao::flash('Comentário excluído.');
                Url::redirecionar('administrador/historias_adm?id=' . $comentario->hist_id . '#comentarios');
            }

            Sessao::flash('Não foi possível excluir o comentário.', 'erro');
            Url::redirecionar('administrador/gerenciar_historia_adm');
        }

        $id = $this->idRecebido();
        $comentario = $id ? $comentarioModel->buscarPorId($id) : false;

        if (!$comentario) {
            Sessao::flash('Comentário não encontrado.', 'erro');
            Url::redirecionar('administrador/gerenciar_historia_adm');
        }

        $this->view('administrador/excluir_comentario', ['comentario' => $comentario]);
    }

    // A resposta chega ao autor do comentário como notificação.
    public function responder_comentario() {
        $comentarioModel = $this->model('Comentario');

        if ($this->ehPost()) {
            $id = $this->idRecebido('id', INPUT_POST);
            $resposta = trim($_POST['resposta'] ?? '');
            $comentario = $id ? $comentarioModel->buscarPorId($id) : false;

            if (!$comentario) {
                Sessao::flash('Comentário não encontrado.', 'erro');
                Url::redirecionar('administrador/gerenciar_historia_adm');
            }

            if ($resposta === '' || mb_strlen($resposta, 'UTF-8') > 2000) {
                Sessao::flash('Escreva uma resposta de até 2000 caracteres.', 'erro');
                Url::redirecionar('administrador/responder_comentario?id=' . $id);
            }

            $enviou = $this->model('Notificacao')->enviarParaPessoa(
                $comentario->pess_id,
                Sessao::nome() . ' respondeu seu comentário',
                'Seu comentário sobre "' . $comentario->hiid_titulo . '" foi respondido pelo administrador ' .
                Sessao::nome() . ":\n\n" . $resposta . "\n\nSeu comentário: \"" . $comentario->come_texto . '"',
                $comentario->hist_id
            );

            Sessao::flash(
                $enviou ? 'Resposta enviada para ' . $comentario->pess_nome . '.' : 'Não foi possível enviar a resposta.',
                $enviou ? 'sucesso' : 'erro'
            );
            Url::redirecionar('administrador/historias_adm?id=' . $comentario->hist_id . '#comentarios');
        }

        $id = $this->idRecebido();
        $comentario = $id ? $comentarioModel->buscarPorId($id) : false;

        if (!$comentario) {
            Sessao::flash('Comentário não encontrado.', 'erro');
            Url::redirecionar('administrador/gerenciar_historia_adm');
        }

        $this->view('administrador/responder_comentario', ['comentario' => $comentario]);
    }

    // ---------------------------------------------------------------
    // Notificações
    // ---------------------------------------------------------------

    public function notificacoes_adm() {
        $this->listarNotificacoes('administrador/notificacoes_adm');
    }

    public function detalhe_notificacao_adm() {
        $this->detalharNotificacao('administrador/detalhe_notificacao_adm', 'administrador/notificacoes_adm');
    }

    public function marcar_lida() {
        $this->marcarNotificacaoLida('administrador/notificacoes_adm');
    }

    public function excluir_notificacao_adm() {
        $this->excluirNotificacao('administrador/excluir_notificacao_adm', 'administrador/notificacoes_adm');
    }

    // ---------------------------------------------------------------
    // Páginas simples
    // ---------------------------------------------------------------

    public function sobre_nos_adm() {
        $this->view('administrador/sobre_nos_adm');
    }

    public function sair_adm() {
        $this->view('administrador/sair_adm');
    }
}
