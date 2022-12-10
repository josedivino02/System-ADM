<?php

namespace Adm\Controllers;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------
class Situacao
{
    private $data;
    private $id;

//=------------------------------------------------------------------------------------------------------------
    //carrega a dashboard após o Login
    public function index()
    {

        $this->data = "<div class='alert alert-success'>USE100: Dashboard Bem vindo!</div>";
    }

//=------------------------------------------------------------------------------------------------------------
    public function viewSituation()
    {
        $carregarView = new \Core\ConfigView("adm/Views/situation/viewSituation", $this->data);
        $carregarView->renderizarLogado();
    }

//=------------------------------------------------------------------------------------------------------------
    public function viewEditSituation()
    {
        $carregarView = new \Core\ConfigView("adm/Views/situation/editSituation", $this->data);
        $carregarView->renderizarLogado();
    }

//=------------------------------------------------------------------------------------------------------------
    public function viewCreateSituation()
    {
        $carregarView = new \Core\ConfigView("adm/Views/situation/addNewSituation", $this->data);
        $carregarView->renderizarLogado();
    }
//=------------------------------------------------------------------------------------------------------------
    public function listSituation()
    {
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $sendAplication = new \Adm\Models\AdmSituation();
        $sendAplication->listSituation($this->data);
    }

//=------------------------------------------------------------------------------------------------------------
    public function createSituation()
    {
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $sendAplication = new \Adm\Models\AdmSituation();
        $sendAplication->createSituation($this->data);
    }

//=------------------------------------------------------------------------------------------------------------
    //Editar Situacao
    public function alterSituation(): void
    {
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $sendAplication = new \Adm\Models\AdmSituation();
        $sendAplication->alterSituation($this->data);
    }

//=------------------------------------------------------------------------------------------------------------
    public function deleteSituation(): void
    {
        $this->id = filter_input(INPUT_GET, "keysituation", FILTER_DEFAULT);
        //verifico se existe o ID
        if (!empty($this->id)) {

            $sendAplication = new \Adm\Models\AdmSituation();
            $sendAplication->deleteSituation($this->id);

        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>SIT140: Selecione a Situação!</div>";
            $this->resultado = false;
        }
        $urlRedirect = URLADM ."situacao/view-situation";
        header("Location: $urlRedirect");
    }
}