<?php

namespace Core;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
//Tratativas da URL Carregar a Controller
class ConfigController extends Config
{
    private $url;
    private $urlArray;
    private $urlController;
    private $urlMetodo;
    private $urlParameter;
    private $listpgPublic;
    private $listpgPrivate;
    private $classLoad;

//=------------------------------------------------------------------------------------------------------------

    //Configuração de carregamento de paginas
    public function __construct()
    {
        //Iniciando as Configurações
        $this->configAdm();
        //conversão da URL
        $slug = new \Adm\Models\helper\AdmSlug();
        //verifica a url
        if (!empty(filter_input(INPUT_GET, 'url', FILTER_DEFAULT))) {
            $this->url = filter_input(INPUT_GET, 'url', FILTER_DEFAULT);
            //Limpa Url
            $limpa = new \Adm\Models\helper\AdmClear();
            $limpa->clearUrl();
            //divide Controller e Método da url
            $this->urlArray = explode("/", $this->url);
           //verifica se está setado a Controller
           if (isset($this->urlArray[0])) {
            $this->urlController =  $slug->slugController($this->urlArray[0]);
           }else {
            //se não, carrega a pagina padrão
            $this->urlController =  $slug->slugController(CONTROLLERERRO);
           }

            //verifica se está setado o Metodo
           if (isset($this->urlArray[1])) {
            $this->urlMetodo = $slug->slugMetodo($this->urlArray[1]);
           }else {
            //se não, carrega a pagina padrão
            $this->urlMetodo = $slug->slugMetodo(METODO);
           }

            //verifica se está setado os parametros
           if (isset($this->urlArray[2])) {
            $this->urlParameter = $this->urlArray[2];
           }else {
            //se não, carrega a pagina padrão
            $this->urlParameter = "";
           }

        }else {
            $this->urlController = $slug->slugController(CONTROLLER);
            $this->urlMetodo = $slug->slugMetodo(METODO);
            $this->urlParameter = "";
        }
    }

//=------------------------------------------------------------------------------------------------------------

    //Carregar as paginas/controllers
    public function loadPage()
    {

        //Linux: erro de primeira letra minuscula
        // $urlController = ucwords($this->urlController);

        //conversão da URL
        $slug = new \Adm\Models\helper\AdmSlug();
        //verifica se é pagina publica
        $this->pgPublic();
        //carrega controller
        // $this->classLoad = "\\Adm\Controllers\\". $this->urlController;

        //verifica se existe a classe para carregar
        if (class_exists($this->classLoad)) {
            $this->loadClass();
        } else {
            //retorna a controller de erro
            $this->urlController = $slug->slugController(CONTROLLERERRO);
            $this->loadPage();
        }
    }

//=------------------------------------------------------------------------------------------------------------

    //carrega a classe (class)
    private function loadClass()
    {
        //conversão da URL
        $slug = new \Adm\Models\helper\AdmSlug();

        $classPage = new $this->classLoad();
        //verifica se o metodo existe
        if (method_exists($classPage, $slug->slugMetodo(METODO))) {
            $classPage->{$slug->slugMetodo($this->urlMetodo)}($this->urlParameter = null);
        }else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>CTL100: Por favor tente novamente. Caso o problema persistir, entre em contato com o administrador ". EMAILADM ." do sistema</div>";
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //validação de pagina publica
    private function pgPublic(): void
    {
        //Array de paginas publicas
        $this->listpgPublic = ["Login", "Erro", "ConfEmail", "Logout", "OutSideUsers"];
        //verifica o array e redireciona
        if (in_array($this->urlController, $this->listpgPublic)) {
    
            //carrega controller
            $this->classLoad = "\\Adm\Controllers\\". $this->urlController;
        }else {
            $this->pgPrivate();
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //validação de pagina privada
    private function pgPrivate(): void
    {
        //Array de paginas privadas
        $this->listpgPrivate = ["Dashboard", "Users", "Situacao", "Colors"];
        //verifica o array e redireciona
        if (in_array($this->urlController, $this->listpgPrivate)) {
            //carrega controller verifica a sessão
            $this->verifyLogin();
        }else {
            
            $_SESSION['msg'] = "<div class='alert alert-warning'>CTL110: Página não encontrada!</div>";
            $urlRedirect = URLADM ."login/index";
            header("Location: $urlRedirect");
            
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //Verifica a sessão do usuário
    private function verifyLogin()
    {
        //se existir a sessão do usuario para redirecionar-lo
        if ((isset($_SESSION['usuario_id'])) && (isset($_SESSION['usuario_nome'])) && (isset($_SESSION['usuario_password']))) {
            //carrega controller
            $this->classLoad = "\\Adm\Controllers\\". $this->urlController;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>CTL120: Para acessar a página, por favor efetue o Login</div>";
            
            $urlRedirect = URLADM ."login/index";
            header("Location: $urlRedirect");
        }
    }
}
