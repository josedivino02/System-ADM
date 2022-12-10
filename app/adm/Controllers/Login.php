<?php

namespace Adm\Controllers;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------
class Login
{
    private $data;
    private $dataForm;

//=------------------------------------------------------------------------------------------------------------
    //Acesso e validação de login
    public function index()
    {
        //envia os dados por POST
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //verifica se o botão foi acionado
        if (!empty($this->dataForm['SendLogin'])) {
            //instancia a models
            $valLogin = new \Adm\Models\AdmLogin();
            $valLogin->login($this->dataForm);

            //verifica se realmente conseguiu encontrar o usuario
            if ($valLogin->getResult()) {
                $urlRedirect = URLADM ."dashboard/index";
                header("Location: $urlRedirect");
            }else {
                $this->data['form'] = $this->dataForm;
            }
            
        }

        //carrega a view de login
        $carregarView = new \Core\ConfigView("adm/Views/login/login", $this->data);
        $carregarView->renderizar();
    }
}
