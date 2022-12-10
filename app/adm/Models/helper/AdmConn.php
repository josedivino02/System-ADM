<?php

namespace Adm\Models\helper;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
//Para conexão do banco de dados
use PDO;
use PDOException;

//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------
//Classe para fazer a conexão do banco de dados (abstrata, pois é apenas para ser instanciada)
abstract class AdmConn
{
    //Variaveis necessárias
    private string $db      = DB;
    private string $host    = HOST;
    private string $user    = USER;
    private string $pass    = PASS;
    private string $dbname  = DBNAME;
    private int $port       = PORT;

    private object $connect;

//Instancias para conexão
//=------------------------------------------------------------------------------------------------------------
    public function connectDb(): object
    {
        try {
            //tratativas da conexão
            $this->connect = new PDO("{$this->db}:host={$this->host};dbname=". $this->dbname, $this->user, $this->pass);
            //retorna conexão
            return $this->connect;

        }catch (PDOException $err) {
            die("Erro CNX100: Por favor tente novamente. Caso o problema persistir, entre em contato com o administrador ". EMAILADM ." do sistema");
        }
    }
}
