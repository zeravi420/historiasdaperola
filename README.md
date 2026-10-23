O desenvolvimento deste site surgiu da necessidade de preservar,
valorizar e divulgar o patrimônio cultural imaterial da região
de Guajará-Mirim, em Rondônia (RO). A região é marcada por uma
expressiva diversidade étnica e cultural, resultado da intensa
interação social característica de uma região de fronteira.

Nosso projeto busca contribuir para a valorização dessas histórias,
memórias e manifestações culturais, tornando esse conhecimento
mais acessível à comunidade. O acervo reúne narrativas disponíveis
em diferentes línguas, incluindo português brasileiro, espanhol
latino (Bolívia) e Wari' Oro Nao.


## Como rodar no XAMPP

1. Copie a pasta do projeto para dentro de `C:\xampp\htdocs`
   (qualquer nome de pasta funciona: o endereço é detectado automaticamente).
2. Ligue o **Apache** e o **MySQL** no painel do XAMPP.
3. Importe o banco no phpMyAdmin (aba *Importar*) ou no MySQL Workbench:
   - **Instalação nova:** `bd/bd_historias.sql` — cria o banco `bd_historias` do zero
     (apaga um banco com esse nome que já exista).
   - **Já tem o banco com histórias e quer mantê-las:** `bd/atualizacao_bd_historias.sql`.
4. Acesse `http://localhost/NOME-DA-PASTA` (ex.: `http://localhost/historiasdaperola-main`).

**Administrador padrão:** `admin@historiasdaperola.com` / `Admin@123`
(troque a senha em *Perfil > Alterar senha*).

### Imagens grandes
As histórias aceitam imagens de até 5 MB, mas o MySQL do XAMPP vem limitado a 1 MB por envio.
Para liberar, abra `C:\xampp\mysql\bin\my.ini`, procure `max_allowed_packet` na seção `[mysqld]`,
troque para `max_allowed_packet=16M` e reinicie o MySQL.

### Conexão
Usuário, senha, porta e nome do banco ficam em `app/Libraries/Database.php`
(padrão do XAMPP: `root`, sem senha, porta `3306`).

## O que o banco guarda

| Tela | Tabelas |
|---|---|
| Cadastro, login, perfil, alterar senha | `pessoa`, `email`, `perfil` |
| Gerenciar perfis (admin) | `pessoa`, `perfil` (view `vw_usuarios`) |
| Cadastrar / editar / excluir / ler histórias | `historia`, `historia_idioma`, `categoria`, `idioma` |
| Comentários (usuário comenta e exclui os seus; admin exclui e responde) | `comentario` (view `vw_comentarios`) |
| Avaliação de 1 a 5 estrelas | `avaliacao` (trigger `tg_avaliacao_notificacao_pos_ins`) |
| Notificações (filtros, ler, marcar como lida, excluir) | `notificacao`, `pessoa_notificacao`, `historia_notificacao` (view `vw_notificacoes`) |

Notificações geradas automaticamente:
- **Nova história** → todos os usuários.
- **Novo comentário** e **novas avaliações** → administradores.
- **História editada** → administradores.
- **Resposta a comentário** → o autor do comentário.
