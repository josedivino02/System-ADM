<?php

namespace Adm\Models\helper;

use PDOException;

//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------
class AdmCreate extends AdmConn
{
    private $tabela;
    private $data;
    private $resultado = null;
    private $insert;
    private $query;
    private $conn;

//=------------------------------------------------------------------------------------------------------------
    //retorna os dados
    public function getResultado()
    {
        return $this->resultado;
    }

//=------------------------------------------------------------------------------------------------------------
    //executa o create na tabela
    public function exeCreate($tabela, $data): void
    {
        //setando os valores que está recebendo
        $this->tabela = $tabela;
        $this->data = $data;

        //instancia o metodo
        $this->exeReplaceValues();

    }

//=------------------------------------------------------------------------------------------------------------
    //substituir os valores
    private function exeReplaceValues():void
    {
        //separa o valor do array pela virgula (criando as colunas)
        $coluns = implode(',', array_keys($this->data));
        //separa as parseStrings (criando os valores)
        $values = ":". implode(', :', array_keys($this->data));
        //Inseri no banco de dados
        $this->query = "INSERT INTO {$this->tabela} ($coluns) VALUES ($values)";

        //instancia o metodo
        $this->exeIntrucoes();
    }

//=------------------------------------------------------------------------------------------------------------
    //tratativa da execução para cadastramento
    private function exeIntrucoes()
    {
        //instancia o metodo
        $this->connection();
        //se executar com sucesso
        try {
            //envia com sucesso
            $this->insert->execute($this->data);
            //retorna ultimo ID
            $this->resultado = $this->conn->lastInsertId();
        } catch (PDOException $err) {
            $this->resultado = null;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //Preparando a conexão com o Banco
    private function connection()
    {
        //seta o metodo de conexão
        $this->conn = $this->connectDb();
        //executa o metodo
        $this->insert = $this->conn->prepare($this->query);
    }
}
