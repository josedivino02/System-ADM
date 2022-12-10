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
//Classe para fazer atualizar os dados do Banco de dados dinamicamente
class AdmUpdate extends AdmConn
{
    private $tabela;
    private $data;
    private $terms;
    private $value = [];
    private $resultado;
    private $conn;
    private $update;
    private $query;

//retorna o resultado ds query
//=------------------------------------------------------------------------------------------------------------
    public function getResultado()
    {
        return $this->resultado;
    }

//=------------------------------------------------------------------------------------------------------------
    //atualiza os dados enviados
    public function exeUpdate($tabela, array $data, $terms = null, $parseString = null): void
    {
        //instancia a tabela e os dados do update
        $this->tabela = $tabela;
        $this->data = $data;
        $this->terms = $terms;

        //convertendo a parseString em array
        parse_str($parseString, $this->value);

        $this->exeReplaceValues();
    }

//=------------------------------------------------------------------------------------------------------------
        //substituir os valores
        private function exeReplaceValues():void
        {
            //verifica e le todas as linhas para dar o update
            foreach ($this->data as $key => $value) {
                $values[] = $key ."=:". $key;
            }

            //converte o array em string
            $values = implode(', ', $values);
            //execução do update
            $this->query = "UPDATE {$this->tabela} SET {$values} {$this->terms}";

            $this->exeIntrucoes();
        }

//=------------------------------------------------------------------------------------------------------------
     //tratativa da execução para cadastramento
     private function exeIntrucoes():void
     {
         //instancia o metodo
         $this->connection();
         //se executar com sucesso
         try {
             //atualiza com sucesso
             $this->update->execute(array_merge($this->data, $this->value));
             $this->resultado = true;
         } catch (PDOException $err) {
             $this->resultado = null;
         }
     }
 
//=------------------------------------------------------------------------------------------------------------
   

//=------------------------------------------------------------------------------------------------------------
    //Seta a conexão
    private function connection()
    {
        //prepara conexão
        $this->conn = $this->connectDb();
        //prepara o UPDATE
        $this->update = $this->conn->prepare($this->query);
       
    }
}
