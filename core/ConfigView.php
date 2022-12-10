<?php

namespace Core;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
//Carrega as paginas das Views
class ConfigView
{

//=------------------------------------------------------------------------------------------------------------
    //carregar as view (variaveis: nome do arquivo e dados)
    public function __construct(private string $nomeView, private string|array|null $data = null)
    {

    }

//=------------------------------------------------------------------------------------------------------------
    //renderiza a View
    public function renderizar(): void
    {
        //verifica se existe o arquivo
        if (file_exists('app/' .$this->nomeView. '.php')) {
            //Cabeçalho
            include 'app/adm/Views/Include/header.php';

            //Pagina dinamica
            include 'app/' .$this->nomeView. '.php';
            //Rodapé
            include 'app/adm/Views/Include/footer.php';

        } else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>VIW100: Por favor tente novamente. Caso o problema persistir, entre em contato com o administrador ". EMAILADM ." do sistema</div>";
        }
    }

    //renderiza a View
    public function renderizarLogado(): void
    {
        //verifica se existe o arquivo
        if (file_exists('app/' .$this->nomeView. '.php')) {
            //Cabeçalho
            include 'app/adm/Views/Include/header.php';
            //menu
            include 'app/adm/Views/Include/menu.php';
            //Pagina dinamica
            include 'app/' .$this->nomeView. '.php';
            //Rodapé
            include 'app/adm/Views/Include/footer.php';

        } else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>VIW100: Por favor tente novamente. Caso o problema persistir, entre em contato com o administrador ". EMAILADM ." do sistema</div>";
        }
    }
}
