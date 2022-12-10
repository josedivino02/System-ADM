<?php

namespace Adm\Models;

//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------

class AdmSituation
{
    private $data;
    private $id;
    private $resultadoBd;
    private $resultado;
    
//=------------------------------------------------------------------------------------------------------------
    //FUNCTION PARA RETORNAR OS RESULTADOS
    private function returnResult($resultPdo, $lista = null, $erro = NULL, $msgErro = NULL)
    {
        if ($resultPdo) {
            $display = array(

                "error" => 0,
                "msg" => 'Realizado com sucesso',
                "res" => $lista
            );

            echo json_encode($display, true);
            exit;
            
        } else {

            if (isset($msgErro)) {
                $msg = 'Não existe a '.$msgErro;

            } else {

                $msg = 'Consulta não retornou nenhum resultado.';
            }

            $display = array(
                "error" => $erro,
                "msg" => $msg
            );

            echo json_encode($display, true);
            exit;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //resultado
    function getResult()
    {
        return $this->resultado;
    }

//=------------------------------------------------------------------------------------------------------------
    function getResultBd()
    {
        return $this->resultadoBd;
    }

//=------------------------------------------------------------------------------------------------------------
    //listar situation
    public function listSituation($data)
    {
        $this->data = $data;

        $pdoSelect = new \Adm\Models\helper\AdmRead();

        $sql = "SELECT s.id AS id, s.name AS situacao, c.color_hexa AS color FROM adm_situacao s INNER JOIN adm_colors c ON s.adm_colors_id = c.id";

        if (!empty($this->data['id'])) {
            $sql .= " WHERE s.id = '{$this->data['id']}'";
        }

        $pdoSelect->fullRead($sql);

        $result = $pdoSelect->getResultado();

        if ($result) {

            $listSituation = array();
            foreach ($result as $situations) {

                extract($situations);

                $situation = array(
                    "id"        => $id,
                    "situacao"  => $situacao,
                    "color"     => $color
                );

                array_push($listSituation, $situation);
            }

            $this->returnResult(true, $listSituation);
        } else {

            $this->returnResult(false, $listSituation = NULL, 500, 'SIT');
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //atualiza situação
    public function alterSituation($data): void
    {
        $this->data = $data;
        
        $alterSituation = [
            "name"      => $this->data['name'],
            "modified"  => date("Y-m-d H:i:s")
        ];

        //update para status de usuario ter acesso
        $pdoUpdate = new \Adm\Models\helper\AdmUpdate();

        $pdoUpdate->exeUpdate("adm_situacao", $alterSituation, "WHERE id=:id", "id={$this->data['id']}");

        if ($pdoUpdate->getResultado()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>SIT100: Situação atualizada com sucesso!</div>";
            $this->resultado = true;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>SIT110: Algo de errado não está certo!</div>";
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //criar situação
    public function createSituation($data): void
    {
        $this->data = $data;

        $createSituation = [
            "name"          => $this->data['name'],
            "adm_colors_id" => $this->data['cor'],
            "created"       => date("Y-m-d H:i:s")
        ];

        //update para status de usuario ter acesso
        $pdoCreate = new \Adm\Models\helper\AdmCreate();

        $pdoCreate->exeCreate("adm_situacao", $createSituation);

        if ($pdoCreate->getResultado()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>SIT120: Situação incluida com sucesso!</div>";
            $this->resultado = true;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>SIT130: Algo de errado não está certo!</div>";
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    public function deleteSituation($id)
    {
        $this->id = $id;

        if ($this->verifyUserSit()) {

            $pdoDelete = new \Adm\Models\helper\AdmDelete();
            $pdoDelete->exeDelete("adm_situacao", "WHERE id=:id", "id={$this->id}");

            if ($pdoDelete->getResultado()) {
                $_SESSION['msg'] = "<div class='alert alert-success'>SIT150: Situação excluída com sucesso!</div>";
                $this->resultado = true;
            }else {
                $_SESSION['msg'] = "<div class='alert alert-warning'>SIT160: Algo de errado não está certo!</div>";
                $this->resultado = false;
            }
        }else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>SIT170: Não foi possível excluir essa situação, pois contém usuários com essa situação</div>";
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    private function verifyUserSit(): bool
    {
        $pdoSelect = new \Adm\Models\helper\AdmRead();
        $pdoSelect->fullRead("SELECT id FROM adm_users WHERE adm_situacao_id=:adm_situacao_id", "adm_situacao_id={$this->id}");

        if ($pdoSelect->getResultado()) {
            return false;
        } else {
            return true;
        }
    }
}
