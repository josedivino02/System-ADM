<?php

namespace Adm\Models\helper;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
//Classe para limpar
class AdmValidate
{
    private $data;
    private $email;
    private $edit;
    private $id;
    private $password;
    private $user;
    private $result;
    private $resultBd;

    private $key;

    private $mimeType;

//=------------------------------------------------------------------------------------------------------------
    //retorna o resultado
    public function getResult()
    {
        return $this->result;
    }

//=------------------------------------------------------------------------------------------------------------
    //Validação de campo vazio
    public function valField($data = null): void
    {
        //atribui
        $this->data = $data;

        //retirar tags
        $this->data = array_map('strip_tags', $this->data);
        //espaço em branco
        $this->data = array_map('trim', $this->data);

        //verifica campo vazio
        if (in_array('', $this->data)) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>VAL100: Necessário preencher todos os campos</div>";

            $this->result = false;
        } else {
            $this->result = true;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //Validação de E-Mail
    public function valEmail($email): void
    {
        //atribui
        $this->email = $email;

        //valida oq esta dentro da variavel (email)
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>VAL110: Email inválido!</div>";
            $this->result = false;
        }

    }

//=------------------------------------------------------------------------------------------------------------
    //Validação de email usado
    public function valEmailUsed($email, $edit = null, $id = null): void
    {
        //atribui
        $this->email = $email;
        $this->edit = $edit;
        $this->id = $id;

        //pesquisa no banco
        $pdoRead = new \Adm\Models\helper\AdmRead();
        //se o usuario for editar
        if ($this->edit === true && !empty($this->id)) {
            //editando o meu perfil
            $pdoRead->fullRead("SELECT id FROM adm_users WHERE email =:email and id <>:id", "email={$this->email}&id={$this->id}");
        } else {
            //procurando o usuario do email
            $pdoRead->fullRead("SELECT id FROM adm_users WHERE email =:email", "email={$this->email}");
        }

        //resultado do select
        $this->resultBd = $pdoRead->getResultado();
        //verifica se encontrou o usuario com o email
        if (!$this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>VAL120: Email já cadastrado!</div>";
            $this->result = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //Validação de usuario usado
    public function valUserUsed($user, $edit = null, $id = null): void
    {
        //atribui
        $this->user = $user;
        $this->edit = $edit;
        $this->id = $id;

        //pesquisa no banco
        $pdoRead = new \Adm\Models\helper\AdmRead();
        //se o usuario for editar
        if ($this->edit === true && !empty($this->id)) {
            //editando o meu perfil
            $pdoRead->fullRead("SELECT id FROM adm_users WHERE (user =:user or email =:email) and id <>:id", "user={$this->user}&email={$this->user}&id={$this->id}");
        } else {
            //procurando o usuario do user
            $pdoRead->fullRead("SELECT id FROM adm_users WHERE user =:user", "user={$this->user}");
        }

        //resultado do selec
        $this->resultBd = $pdoRead->getResultado();
        //verifica se encontrou o usuario com o email
        if (!$this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>VAL190: Email já cadastrado!</div>";
            $this->result = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //Validação de email usado
    public function valPassword($password): void
    {
        //atribui
        $this->password = $password;

        //verifica se tiver tal caracter (')
        if (stristr($this->password, "'")) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>VAL130: Caracter ( ' ), não pode ser usado na senha!</div>";
            $this->result = false;
        } else {
            //verifica se tiver espaço em branco
            if (stristr($this->password, " ")) {
                $_SESSION['msg'] = "<div class='alert alert-danger'>VAL140: Proibido utilizar espaço em branco na senha!</div>";
                $this->result = false;
            } else {
                $this->result = true;
            }
            //metodo verifica qtd de caracter
            $this->valQtdPassword();
        }
    }

//=----------------------------------------------------
    //verifica quantidade de caracter
    private function valQtdPassword(): void
    {
        //verifica se é menor que 6 caracter
        if (strlen($this->password) < 6) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>VAL150: Senha não pode ter menos de 6 caracteres!</div>";
            $this->result = false;
        } else {
            $this->valValuesPassword();
        }
    }
//=-----------------------------------------------------
    //valida caracteres da senha
    private function valValuesPassword(): void
    {
        //verifica letra
        if (!preg_match('/^(?=.*[0-9])(?=.*[a-zA-Z])(?=.*[_+-.,!@#$%^&*();|<>])[a-zA-Z0-9-_+-.,!@#$%^&*();|<>]/', $this->password)) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>VAL160: A senha deve conter números, letras maiúscula ou minúscula e caracter especial!</div>";
            $this->result = false;
        } else {
            $this->result = true;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //validar extensão da IMG
    public function validateExtImg($mimeType): void
    {
        //atributo recebe parametro
        $this->mimeType = $mimeType;
        //qualquer tipo de extensaõ é so procurar por "mimetype"
        switch ($this->mimeType) {
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->result = true;
                break;
            case 'image/png':
            case 'image/x-png':
                $this->result = true;
                break;
            case 'image/gif':
                $this->result = true;
                break;
            default:
                $_SESSION['msg'] = "<div class='alert alert-danger'>VAL170: Necessário selecionar imagem com extensão JPEG ou PNG!</div>";
                $this->resultado = false;
        }
    }
}
