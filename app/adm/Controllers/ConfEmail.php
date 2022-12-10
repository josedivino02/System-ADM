<?php

namespace Adm\Controllers;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------
//Para confirmar o login de acesso pelo email
class ConfEmail
{
    private $key;

    private $data;
    private $dataForm;

    public function index()
    {
        //recebe o email pelo input
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //verifica se clicou no botão
        if (!empty($this->dataForm['SendNewConfEmail'])) {

            unset($this->dataForm['SendNewConfEmail']);
            $newConfEmail = new \Adm\Models\helper\AdmEmail();
            $newConfEmail->newConfEmail($this->dataForm);

                if ($newConfEmail->getResult()) {
                    
                    $urlRedirect = URLADM ."login/index";
                    header("Location: $urlRedirect");
                }else {
                    
                    $this->data['form'] = $this->dataForm;
                    $this->viewNewConfEmail();
                }
        }else {
            
            $this->viewNewConfEmail();
        }
    }
//=------------------------------------------------------------------------------------------------------------
    //confirmar o email
    public function confirmarEmail()
    {
        //pegando o valor da URL (GET)
        $this->key = filter_input(INPUT_GET, "keyemail", FILTER_DEFAULT);
        
        //verifica se existe a chave
        if (!empty($this->key)) {
            $this->valKey();
        } else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>EMA120: Link Inválido</div>";
        
            $urlRedirect = URLADM ."login/index";
            header("Location: $urlRedirect");
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //validação da chave
    private function valKey(): void
    {
        //setando a models de confirmação de email e enviando o valor da chave
        $confEmail = new \Adm\Models\helper\AdmEmail();
        $confEmail->confEmail($this->key);
        //verifica o resultado final
        if ($confEmail->getResult()) {
            $urlRedirect = URLADM ."login/index";
            header("Location: $urlRedirect");
        }else {
            $urlRedirect = URLADM ."login/index";
            header("Location: $urlRedirect");
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //reenviar o email de confirmação
    private function viewNewConfEmail(): void
    {

        $carregarView = new \Core\ConfigView("adm/Views/login/newConfEmail", $this->data);
        $carregarView->renderizar();
    }
}

