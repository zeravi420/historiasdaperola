<?php

class Database
{
    private $host = "localhost";
    private $usuario = "root";
    private $senha = "";
    private $banco = "bd_historias";
    private $porta = "3307"; //verificar a porta do seu banco
    private $dbh;
    private $stmt;

    public function __construct()
    {
        //fonte de dados ou DNS  que contém as informações para conectar ao banco de dados.
        //charset=utf8mb4 garante que acentos (ç, ã, é...) sejam gravados e lidos corretamente.
        $dns = 'mysql:host=' . $this->host . ';port=' . $this->porta . ';dbname=' . $this->banco . ';charset=utf8mb4';

        $opcoes = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_PERSISTENT => false
        ];
        try {
            //cria a instacia do PDO
            $this->dbh = new PDO($dns, $this->usuario, $this->senha, $opcoes);

            // Usa no MySQL o mesmo fuso horário do PHP (definido em configuracao.php).
            $this->dbh->exec("SET time_zone = '" . date('P') . "'");
        } catch (Throwable $erro) {
            error_log('Erro ao conectar ao banco de dados: ' . $erro->getMessage());

            die('<div style="font-family:sans-serif;max-width:640px;margin:40px auto;padding:20px;' .
                'border:1px solid #e0b4b4;background:#fff6f6;color:#9f3a38;border-radius:8px">' .
                '<strong>Não foi possível conectar ao banco de dados.</strong><br><br>' .
                'Verifique se o MySQL do XAMPP está ligado, se o banco <code>' . $this->banco . '</code> ' .
                'foi importado (arquivo <code>bd/bd_historias.sql</code>) e se usuário, senha e porta ' .
                'em <code>app/Libraries/Database.php</code> estão corretos.</div>');
        } //fim do catch
    } //fim do método construtor

    // prepara o statement com query
    public function query($sql)
    {
        //prepara a consulta sql
        $this->stmt = $this->dbh->prepare($sql);
    } //fim da função query
    //vincula um valor a um parâmetro
    public function bind($parametro, $valor, $tipo = null)
    {
        if (is_null($tipo)):
            switch (true):
                case is_int($valor):
                    $tipo = PDO::PARAM_INT;
                    break;
                case is_bool($valor):
                    $tipo = PDO::PARAM_BOOL;
                    break;
                case is_null($valor):
                    $tipo = PDO::PARAM_NULL;
                    break;
                default:
                    $tipo = PDO::PARAM_STR;
            endswitch;
        endif;
        $this->stmt->bindValue($parametro, $valor, $tipo);
    } //fim da função bind

    public function executa()
    {
        return $this->stmt->execute();
    } //fim da função executa

    //obtem um único registro
    public function resultado()
    {
        $this->executa();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    } //fim da função resultados

    //obtem um conjunto de registros
    public function resultados()
    {
        $this->executa();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    } //fim da função resultados

    //retorna o número de linhas afetadas pela última instrução SQL
    public function totalResultados()
    {
        return $this->stmt->rowCount();
    } //fim da função totalResultados

    //retorna o último id inserido no banco de dados
    public function ultimoIdInserido()
    {
        return $this->dbh->lastInsertId();
    } //fim da função ultimoIdInserido

    public function iniciarTransacao()
    {
        $this->dbh->beginTransaction();
    }

    public function confirmarTransacao()
    {
        if ($this->dbh->inTransaction()) {
            $this->dbh->commit();
        }
    }

    public function cancelarTransacao()
    {
        try {
            if ($this->dbh->inTransaction()) {
                $this->dbh->rollBack();
            }
        } catch (Throwable $erro) {
            error_log(
                'Erro ao cancelar transação: ' . $erro->getMessage()
            );
        }
    }
}//fim da classe Database
