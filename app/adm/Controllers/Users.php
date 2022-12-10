<?php

namespace Adm\Controllers;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------
class Users
{
    private $data;
    private $dataForm;

    private $dados;

    private $id;

//=------------------------------------------------------------------------------------------------------------
    public function index()
    {
        echo 'pagina e users';
    }

//=------------------------------------------------------------------------------------------------------------
    //view de listar usuario
    public function viewUsers():void
    {
        //Em PHP
        // $listUsers = new \Adm\Models\AdmUsers();
        // //instanciando o metodo de listagem
        // $listUsers->listUsers();
        // //verificando o resultado
        // if ($listUsers->getResult()) {
        //     $this->data['listUsers'] = $listUsers->getResultBd();
        // }else {
        //     $this->data['listUsers'] = [];
        // }

        //carrega view de listagem de usuarios
        $carregarView = new \Core\ConfigView("adm/Views/users/viewUsers", $this->data);
        $carregarView->renderizarLogado();
    }

//=------------------------------------------------------------------------------------------------------------
    //dados dos usuarios
    public function searchUsers()
    {

        $this->dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $sendAplication = new \Adm\Models\AdmUsers();
        $sendAplication->listUsers($this->dados);
    }

//=------------------------------------------------------------------------------------------------------------
    //detalhes do usuario
    public function userDetails(): void
    {

        $this->id = filter_input(INPUT_GET, "keyuser", FILTER_DEFAULT);
        //verifico se existe o ID
        if (!empty($this->id)) {
            //transforma em inteiro
            // $this->id = (int) $id;

            $sendAplication = new \Adm\Models\AdmUsers();
            $sendAplication->userDetails($this->id);

            if ($sendAplication->getResult()) {
                $this->viewUserDetails();
            } else {
                $urlRedirect = URLADM ."users/view-users";
                header("Location: $urlRedirect");
            }
        } else {
            $urlRedirect = URLADM ."users/view-users";
            header("Location: $urlRedirect");
        }
    }
//=------------------------------------------------------------------------------------------------------------
    //dados dos usuarios
    public function userDetailsData()
    {
        $this->id = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $sendAplication = new \Adm\Models\AdmUsers();
        $sendAplication->userDetailsData($this->id);
    }
    
//=------------------------------------------------------------------------------------------------------------
    //visualalizar detalhes do usuario
    private function viewUserDetails()
    {
        $carregarView = new \Core\ConfigView("adm/Views/users/UserDetails", $this->data);
        $carregarView->renderizarLogado();
    }

//=------------------------------------------------------------------------------------------------------------
    //adicionar um novo usuário
    public function addNewUser(): void
    {
        //envia os dados por POST
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //verifica se o botão foi acionado
        if (!empty($this->dataForm['SendAddUser'])) {
            unset($this->dataForm['SendAddUser']);
            //instancia a models
            $addUser = new \Adm\Models\AdmUsers();
            $addUser->addUser($this->dataForm);

            //verifica se realmente conseguiu criar o usuario
            if ($addUser->getResult()) {
                $urlRedirect = URLADM ."users/view-users";
                header("Location: $urlRedirect");
            }else {
                $this->data['form'] = $this->dataForm;
                $this->viewAddUser();
            }
        }else {
            $this->viewAddUser();
        }
        
    }

//=------------------------------------------------------------------------------------------------------------
    //view add usuario
    private function viewAddUser(): void
    {
        $carregarView = new \Core\ConfigView("adm/Views/users/addNewUser", $this->data);
        $carregarView->renderizarLogado();
    }

//=------------------------------------------------------------------------------------------------------------
    //Editar usuário
    public function userEdit(): void
    {
        $this->id = filter_input(INPUT_GET, "keyuser", FILTER_DEFAULT);
        //verifico se existe o ID
        if (!empty($this->id)) {
            //transforma em inteiro
            // $this->id = (int) $id;

            $sendAplication = new \Adm\Models\AdmUsers();
            $sendAplication->userDetails($this->id);

            if ($sendAplication->getResult()) {
                $this->viewEditUser();
            } else {
                $urlRedirect = URLADM ."users/view-users";
                header("Location: $urlRedirect");
            }
        } else {
            $urlRedirect = URLADM ."users/view-users";
            header("Location: $urlRedirect");
        }
        
    }
//=------------------------------------------------------------------------------------------------------------
    //editar usuarios Models
    public function userEditData()
    {
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $sendAplication = new \Adm\Models\AdmUsers();
        $sendAplication->userEditData($this->data);
    }

//=------------------------------------------------------------------------------------------------------------
    //editar usuarios Models
    public function updateUserData()
    {
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $sendAplication = new \Adm\Models\AdmUsers();
        $sendAplication->updateUserData($this->data);

        if ($sendAplication->getResult()) {
            $urlRedirect = URLADM ."view-users";
            header("Location: $urlRedirect");
        }else {
            $urlRedirect = URLADM ."view-users";
            header("Location: $urlRedirect");
        }
    }
//=------------------------------------------------------------------------------------------------------------
    //view editar usuario
    private function viewEditUser(): void
    {
        $carregarView = new \Core\ConfigView("adm/Views/users/editUser", $this->data);
        $carregarView->renderizarLogado();
    }

//=------------------------------------------------------------------------------------------------------------
    //editar imagem do usuario
    public function editUserImg(): void
    {
        $this->id = filter_input(INPUT_GET, "keyuserimg", FILTER_DEFAULT);
        //verifico se existe o ID
        if (!empty($this->id)) {

            $sendAplication = new \Adm\Models\AdmUsers();
            $sendAplication->userDetails($this->id);

            if ($sendAplication->getResult()) {
                $this->viewEditUserImg();
            } else {
                $urlRedirect = URLADM ."users/view-users";
                header("Location: $urlRedirect");
            }
        } else {
            $urlRedirect = URLADM ."users/view-users";
            header("Location: $urlRedirect");
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //img Models
    public function userImgEdit()
    {
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $sendAplication = new \Adm\Models\AdmUsers();
        $sendAplication->userImgEdit($this->data);
    }

//=------------------------------------------------------------------------------------------------------------
    //editar usuarios Models
    public function updateUserImg()
    {
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //recebendo formato em imagem
        
        $this->data['new_img'] = $_FILES ? $_FILES : null;
        
        $sendAplication = new \Adm\Models\AdmUsers();
        $sendAplication->updateUserImg($this->data);

        if ($sendAplication->getResult()) {
            $urlRedirect = URLADM ."view-users";
            header("Location: $urlRedirect");
        }else {
            $urlRedirect = URLADM ."view-users";
            header("Location: $urlRedirect");
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //view editar usuario
    private function viewEditUserImg(): void
    {
        $carregarView = new \Core\ConfigView("adm/Views/users/editUserImg", $this->data);
        $carregarView->renderizarLogado();
    }

//=------------------------------------------------------------------------------------------------------------
    public function getListSitu()
    {
        $sendAplication = new \Adm\Models\AdmUsers();
        $sendAplication->getListSitu();
    }

//=------------------------------------------------------------------------------------------------------------
    //Editar usuário
    public function userDelet(): void
    {
        $this->id = filter_input(INPUT_GET, "keyuser", FILTER_DEFAULT);
        //verifico se existe o ID
        if (!empty($this->id)) {

            $sendAplication = new \Adm\Models\AdmUsers();
            $sendAplication->deleteUser($this->id);

        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>USE270: Selecione o Usuário!</div>";
            $this->resultado = false;
        }
        $urlRedirect = URLADM ."users/view-users";
        header("Location: $urlRedirect");
    }

//=------------------------------------------------------------------------------------------------------------
    //carregar profile
    public function viewProfile(): void
    {
        $carregarView = new \Core\ConfigView("adm/Views/users/viewProfile", $this->data);
        $carregarView->renderizarLogado();
    }

//=------------------------------------------------------------------------------------------------------------
    //carregar minha imagem
    public function editMyUserImg(): void
    {
        $carregarView = new \Core\ConfigView("adm/Views/users/editMyUserImg", $this->data);
        $carregarView->renderizarLogado();
    }

//=------------------------------------------------------------------------------------------------------------
    //img Models
    public function updateMyUserData()
    {
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $sendAplication = new \Adm\Models\AdmUsers();
        $sendAplication->updateMyUserData($this->data);
    }
}
