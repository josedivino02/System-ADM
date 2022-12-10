<?php

namespace Adm\Models\helper;

use PDO;
use PDOException;

//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------
//Classe para fazer uma Query do Banco de dados dinamicamente
class AdmRead extends AdmConn
{
    private $select;
    private $values;
    private $resultado;
    private $conn;
    private $query;

//retorna o resultado ds query
//=------------------------------------------------------------------------------------------------------------
    public function getResultado()
    {
        return $this->resultado;
    }

//=------------------------------------------------------------------------------------------------------------
    //Executa a leitura da query
    public function execRead(string $tabela, $termos = null, $parseString = null)
    {
        //convertendo a string em array
        if (!empty($parseString)) {
            //parse_str = transforma string em variaveis
            parse_str($parseString, $this->values);
        }
        $this->Select = "SELECT * FROM {$tabela} {$termos}";
        //executa os dados
        $this->exeInstrucoes();
    }

//=------------------------------------------------------------------------------------------------------------
    //executa a query com mais detalhes com parseString
    public function fullRead($query, $parseString = null)
    {
        //instancia a query na select
        $this->select = $query;

         //convertendo a string em array
         if (!empty($parseString)) {
            //parse_str = transforma string em variaveis
            parse_str($parseString, $this->values);
        }

        //executa os dados
        $this->exeInstrucoes();
    }

//=------------------------------------------------------------------------------------------------------------
    //executa as instruções do DB
    public function exeInstrucoes()
    {
        $this->connection();

        try {
            //parametros
            $this->exeParameter();
            //executa
            $this->query->execute();
            //traz todos os valores
            $this->resultado = $this->query->fetchAll();
        } catch (PDOException $err) {
            $this->resultado = null;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //Executa os parametros(Defesa com sql injection)
    private function exeParameter()
    {
        if ($this->values) {
            //separo os valoes e chaves
            foreach ($this->values as $key => $value) {
                //transformo o limit em inteiro
                if (($key == 'limit') || ($key == 'offset')) {
                    $value = (int) $value;
                }
                //instanciando o bindValue e verificando se é um valor inteiro ou string
                $this->query->bindValue(":{$key}", $value, (is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR));
            }
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //Seta a conexão
    private function connection()
    {
        //prepara conexão
        $this->conn = $this->connectDb();
        //prepara o SELECT
        $this->query = $this->conn->prepare($this->select);
        //obter os dados
        $this->query->setFetchMode(PDO::FETCH_ASSOC);
    }
}
