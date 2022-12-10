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
class AdmDelete extends AdmConn
{
    private $tabela;
    private $terms;
    private $value = [];
    private $resultado;
    private $conn;
    private $delete;
    private $query;

//retorna o resultado ds query
//=------------------------------------------------------------------------------------------------------------
    public function getResultado()
    {
        return $this->resultado;
    }

//=------------------------------------------------------------------------------------------------------------
    //atualiza os dados enviados
    public function exeDelete($tabela, $terms = null, $parseString = null): void
    {
        //instancia a tabela e os dados do deçete
        $this->tabela = $tabela;
        $this->terms = $terms;

        //convertendo a parseString em array
        parse_str($parseString, $this->value);

        //query dinamica
        $this->query = "DELETE FROM {$this->tabela} {$this->terms}";

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
            $this->delete->execute($this->value);
             $this->resultado = true;
         } catch (PDOException $err) {
             $this->resultado = false;
         }
     }
 
//=------------------------------------------------------------------------------------------------------------
   

//=------------------------------------------------------------------------------------------------------------
    //Seta a conexão
    private function connection()
    {
        //prepara conexão
        $this->conn = $this->connectDb();
        //prepara o delete
        $this->delete = $this->conn->prepare($this->query);
       
    }
}
