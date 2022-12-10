<?php

namespace Adm\Models;

//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------

class AdmLogin
{
    private $data;
    private $resultadoBd;
    private $resultado;
    
//=------------------------------------------------------------------------------------------------------------
    //resultado

    function getResult()
    {
        return $this->resultado;
    }

//=------------------------------------------------------------------------------------------------------------
    //Valida o acesso ao Login
    public function login($data = null)
    {
        $this->data = $data;
        
        //valida campos preenchidos
        $valfield = new \Adm\Models\helper\AdmValidate();
        $valfield->valField($this->data);
        if ($valfield->getResult()) {

            $pdoSelect = new \Adm\Models\helper\AdmRead();
            $pdoSelect->fullRead("SELECT id, name, cpf, nickname, password, image, adm_situacao_id FROM adm_users WHERE user =:user or email =:email or cpf =:cpf", "user={$this->data['user']}&email={$this->data['user']}&cpf={$this->data['user']}");
            $this->resultadoBd = $pdoSelect->getResultado();

            if ($this->resultadoBd) {
                //instancia o metodo de permissão de acesso
                $this->valPerm();
            }else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>USE100: Usuário ou Senha incorreta!</div>";
                $this->resultado = false;
            }
        }else {
            $this->resultado = false;
        }
    }

    private function valPerm(): void
    {
        if ($this->resultadoBd[0]['adm_situacao_id'] == 1) {
            //instancia o metodo de validaçao da senha
            $this->valPassword();
        }elseif ($this->resultadoBd[0]['adm_situacao_id'] == 3) {
            $_SESSION['msg'] = "<div class='alert alert-warning'>USE120: Necessário confirmar o E-Mail, se o e-mail for excluído, solicite um novo nesse link: <a href='". URLADM ."conf-email/index' class='badge alert-info bg-warning'>Clique aqui</a>. </div>";
            $this->resultado = false;
        }elseif ($this->resultadoBd[0]['adm_situacao_id'] == 4) {
            $_SESSION['msg'] = "<div class='alert alert-warning'>USE130: Acesso desativado! Entre em contato com o ADM do sistema</div>";
            $this->resultado = false;
        }elseif ($this->resultadoBd[0]['adm_situacao_id'] == 2) {
            $_SESSION['msg'] = "<div class='alert alert-warning'>USE140: Acesso inativo!</div>";
            $this->resultado = false;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>USE150: Não possui cadastro no sistema!</div>";
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //valida a senha
    private function valPassword()
    {
        //verifica a senha
        if (password_verify($this->data['password'], $this->resultadoBd[0]['password'])) {
            //criando a sessão de login
            $_SESSION['usuario_id']         = $this->resultadoBd[0]['id'];
            $_SESSION['usuario_nome']       = $this->resultadoBd[0]['name'];
            $_SESSION['usuario_cpf']        = $this->resultadoBd[0]['cpf'];
            $_SESSION['usuario_nick']       = $this->resultadoBd[0]['nickname'];
            $_SESSION['usuario_password']   = $this->resultadoBd[0]['password'];
            $_SESSION['usuario_image']      = $this->resultadoBd[0]['image'];

            $this->resultado = true;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>USE110: Usuário ou Senha incorreta!</div>";
            $this->resultado = false;
        }
    }
}
