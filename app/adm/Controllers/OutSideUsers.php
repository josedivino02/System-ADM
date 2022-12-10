<?php

namespace Adm\Controllers;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------
class OutSideUsers
{
    private $data;
    private $dataForm;

    private $key;

//=------------------------------------------------------------------------------------------------------------
    public function index()
    {
        echo 'pagina e users';
    }

//=------------------------------------------------------------------------------------------------------------
    //cadastro de novo usuário
    public function newUser()
    {
        //envia os dados por POST
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //verifica se o botão foi acionado
        if (!empty($this->dataForm['SendNewUser'])) {
            //instancia a models
            $newUser = new \Adm\Models\AdmUsers();
            $newUser->createUser($this->dataForm);

            //verifica se realmente conseguiu criar o usuario
            if ($newUser->getResult()) {
                $urlRedirect = URLADM;
                header("Location: $urlRedirect");
            }else {
                $this->data['form'] = $this->dataForm;
                $this->viewNewUser();
            }
        }else {
            $this->viewNewUser();
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //view do usuario
    private function viewNewUser():void
    {
        //carrega a view de novo usuario
        $carregarView = new \Core\ConfigView("adm/Views/login/newUser", $this->data);
        $carregarView->renderizar();
    }

//=------------------------------------------------------------------------------------------------------------
    //para resetar a senha que esqueci
    public function recoverPassword()
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dataForm['SendRecoverPassword'])) {

            $recover = new \Adm\Models\AdmUsers();
            $recover->linkRecoverPass($this->dataForm);
           
            //verifica se realmente conseguiu enviar o email
            if ($recover->getResult()) {
                $urlRedirect = URLADM;
                header("Location: $urlRedirect");
            }else {
                $this->data['form'] = $this->dataForm;
                $this->viewRecoverPassword();
            }
        }else {
            $this->viewRecoverPassword();
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //view de esqueci senha
    private function viewRecoverPassword():void
    {
        //carrega view de resetar senha
        $carregarView = new \Core\ConfigView("adm/Views/login/recoverPassword", $this->data);
        $carregarView->renderizar();
    }

//=------------------------------------------------------------------------------------------------------------
    //editar nova senha
    public function updateRecoverPassword(): void
    {
        //pegando valor do (GET)
        $this->key = filter_input(INPUT_GET, 'keyrecover', FILTER_DEFAULT);
        //recebe os valores dos campos
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($this->key)) && (empty($this->dataForm['SendNewPassword']))) {
            $this->valKey();
        }else {
           $this->updatePassword();
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //valida o GET
    private function valKey()
    {
        //validação da chave, a models trata
        $valKeyRecoverPass = new \Adm\Models\AdmUsers();
        $valKeyRecoverPass->valKeyRecover($this->key);
        //verifica o resultado da models
        if ($valKeyRecoverPass->getResult()) {
            //carrega view de resetar senha
            $this->viewUpdatePassword();
        }else {
            $urlRedirect = URLADM ."login/index";
            header("Location: $urlRedirect");
        }
    }

//=------------------------------------------------------------------------------------------------------------
    private function updatePassword(): void
    {
        if (!empty($this->dataForm['SendNewPassword'])) {
            unset($this->dataForm['SendNewPassword']);
            //atribui a chave no posicionamento do array
            $this->dataForm['key'] = $this->key;

            //models de editar a senha
            $updatePass = new \Adm\Models\AdmUsers();
            $updatePass->editPassword($this->dataForm);

            if ($updatePass->getResult()) {
                $urlRedirect = URLADM ."login/index";
                header("Location: $urlRedirect");
            }else {
                //carrega a view
                $this->viewUpdatePassword();
            }

        }else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>CHA110: Link Inválido, solicite um novo link: <a href='". URLADM ."out-side-users/recover-password' class='badge alert-info bg-warning'>Clique aqui</a>. </div>";
            $urlRedirect = URLADM ."login/index";
            header("Location: $urlRedirect");
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //view de atualizar senha
    private function viewUpdatePassword():void
    {
        //carrega view de resetar senha
        $carregarView = new \Core\ConfigView("adm/Views/login/newPassword", $this->data);
        $carregarView->renderizar();
    }
}
