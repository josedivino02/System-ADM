<?php

namespace Core;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
        header("Location: /");
        exit();
      }
abstract class Config
{
    protected function configAdm()
    {
//=------------------------------------------------------------------------------------------------------------
        // //EXIBIR ERROS PHP
        // ini_set("display_errors", 1);
        // ini_set("display_startup_erros", 1);
        // error_reporting(E_ALL);

//=------------------------------------------------------------------------------------------------------------
        //Seta horario Brasilia
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

//=------------------------------------------------------------------------------------------------------------
        //Url's
        define('URLSTS', 'http://localhost/estrutura_site_mvc/');
        define('URLADM', 'http://localhost/estrutura_adm_mvc/');
        
//=------------------------------------------------------------------------------------------------------------
        //MVC - Pagina padrões
        define('CONTROLLER', 'Login');
        define('METODO', 'index');
        define('CONTROLLERERRO', 'Login');

//=------------------------------------------------------------------------------------------------------------
        //Email
        define('EMAILADM', 'josedivinooficial@gmail.com');

//=------------------------------------------------------------------------------------------------------------
        //Credenciais DB
        define('HOST', 'localhost');
        define('USER', 'root');
        define('PASS', '');
        define('DBNAME', 'estrutura_mvc');
        define('PORT', 3306);
        define('DB', 'mysql');

//=------------------------------------------------------------------------------------------------------------
        //Credenciais PHPEmail
        define('EMAILEMAIL', 'pelankinha0202@gmail.com');
        define('EMAILHOST', 'smtp.mailtrap.io');
        define('EMAILUSER', '7fe9759078c40b');
        define('EMAILPASSWORD', 'c825afdc6ed512');
        define('EMAILPORT', 2525);
    }
}
