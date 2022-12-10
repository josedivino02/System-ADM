<?php

namespace Adm\Models;

//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------

class AdmUsers
{
    private $data;
    private $resultadoBd;
    private $resultado;

    private $firstName;
    private $emailData;

    private $url;

    private $key;

    private $id;

    private $directory;

    private $delImg;

    private $result;

    private $img;

    
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
                $msg = 'Usuário não possuí nenhum '.$msgErro;

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

    function getResultBd()
    {
        return $this->resultadoBd;
    }

//=------------------------------------------------------------------------------------------------------------
    //retorna o email para contato
    public function getEmailContact()
    {
        return $this->fromEmail;
    }
//=------------------------------------------------------------------------------------------------------------
    //criar novo usuário
    public function createUser($data = null)
    {
        $this->data = $data;

        //validações
        $valField = new \Adm\Models\helper\AdmValidate();
        $valField->valField($this->data);
        if ($valField->getResult()) {
            $this->valInput();
        }else {
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //valida email
    private function valInput()
    {
        //instancia a validação
        $valEmail = new \Adm\Models\helper\AdmValidate();
        $valEmail->valEmail($this->data['email']);

        //valida email ja cadastrado
        $valEmailExist = new \Adm\Models\helper\AdmValidate();
        $valEmailExist->valEmailUsed($this->data['email']);

        //valida usuario ja cadastrado
        $valUserUsed = new \Adm\Models\helper\AdmValidate();
        $valUserUsed->valUserUsed($this->data['email']);

        //valida senha
        $valPassword = new \Adm\Models\helper\AdmValidate();
        $valPassword->valPassword($this->data['password']);

        //verifica o resultado
        if (($valEmail->getResult()) && ($valEmailExist->getResult()) && ($valUserUsed->getResult()) && ($valPassword->getResult())) {
            $this->continueCreated();
        }else {
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //criando usuario
    private function continueCreated()
    {
        //criptografar a senha
        $this->data['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);
        $this->data['confirmar_email'] = password_hash($this->data['password'] .date('Y-m-d H:i:s'), PASSWORD_DEFAULT);
        //criando nickname
        $nome = explode(" ", $this->data['name']);

        $newUserData = array (
            "name"              => $this->data['name'],
            "nickname"          => $nome[0],
            "cpf"               => $this->data['cpf'],
            "email"             => $this->data['email'],
            "user"              => $this->data['email'],
            "password"          => $this->data['password'],
            "confirmar_email"   => $this->data['confirmar_email'],
            "created"           => date('Y-m-d H:i:s')
        );

        $pdoCreate = new \Adm\Models\helper\AdmCreate();
        $pdoCreate->exeCreate('adm_users', $newUserData);
        $this->resultadoBd = $pdoCreate->getResultado();

        if ($this->resultadoBd) {
            $this->sendEmail();
        }else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>USE140: Usuário não cadastrado, por favor tente novamente. Se o erro persistir entre em contato com o administrador ". EMAILADM ." do sistema</div>";
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //enviar email
    private function sendEmail(): void
    {
        //conteúdo para o envio do email de confirmação
        $this->contentEMail();
        $this->contentEMailText();

        $sendEmail = new \Adm\Models\helper\AdmEmail();
        $sendEmail->sendEmail($this->emailData, 1);

        if ($sendEmail->getResult()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>EMA150: Usuário cadastrado com sucesso. Acesso o E-Mail para confirmar!</div>";
            $this->resultado = true;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>EMA160: Usuário cadastrado com sucesso. Houve um erro ao enviar o E-Mail de confirmação, entre em contato com {$sendEmail->getEmailContact()} para mais informações!</div>";
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    private function contentEMail(): void
    {
       $name = explode(" ", $this->data['name']);
       $this->firstName = $name[0];

        //confirmaçao de email
        $this->url = URLADM ."conf-email/confirmar-email?keyemail=" .$this->data['confirmar_email'];

       $this->emailData['toEmail']      = $this->data['email'];
       $this->emailData['toName']       = $this->firstName;
       $this->emailData['subject']      = "Confirme sua conta para acessar o sistema!";
       $this->emailData['contentHtml']  = "Prezado(a) {$this->firstName}<br><br>";
       $this->emailData['contentHtml']  .= "Para liberar o acesso clique no link: <br><br>";
       $this->emailData['contentHtml']  .= "<a class='btn btn-info' href='{$this->url}'>{$this->url}</a>";
    }

//=------------------------------------------------------------------------------------------------------------
    private function contentEMailText(): void
    {
       $this->emailData['contentText']  = "Prezado(a) {$this->firstName}\n\n";
       $this->emailData['contentText']  .= "Para liberar o acesso clique no link: \n\n";
       $this->emailData['contentText']  .= $this->url ."\n\n";
    }

//=------------------------------------------------------------------------------------------------------------
    //link de recuperação de senha
    public function linkRecoverPass($data = null): void
    {
        $this->data = $data;
        //validação campo vazio (PHP)
        $valEmptyField = new \Adm\Models\helper\AdmValidate();
        $valEmptyField->valField($this->data);

        if ($valEmptyField->getResult()) {
           
            $this->valUser();
        }else {
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //valida usuario
    private function valUser(): void
    {
        //buscando o usuario no BD que o cliente digitou o email
        $pdoSelect = new \Adm\Models\helper\AdmRead();
        $pdoSelect->fullRead("SELECT id, name, email FROM adm_users WHERE email =:email", "email={$this->data['email']}");

        $this->resultadoBd = $pdoSelect->getResultado();
        //se encontrou o resultado
            if ($this->resultadoBd) {
                
                $this->valConEmail();
            }else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>EMA210: E-Mail não encontrado!</div>";
                $this->result = false;
            }
    }

//=------------------------------------------------------------------------------------------------------------
    //para cadastrar uma nova chave se nao tiver
    private function valConEmail(): void
    {
        $newRecoverPassword = password_hash(date("Y-m-d H:i:s") .$this->resultadoBd[0]['id'], PASSWORD_DEFAULT);

        $newKeyRecoverPass = [
            "recover_password"   => $newRecoverPassword,
            "modified"          => date("Y-m-d H:i:S")
        ];

        //update para status de usuario ter acesso
        $pdoUpdate = new \Adm\Models\helper\AdmUpdate();

        $pdoUpdate->exeUpdate("adm_users", $newKeyRecoverPass, "WHERE id=:id", "id={$this->resultadoBd['0']['id']}");

            if ($pdoUpdate->getResultado()) {
                $this->resultadoBd[0]['recover_password'] = $newRecoverPassword;
                $this->newSendEmailRecover();
            }else {
                $_SESSION['msg'] = "<div class='alert alert-warning'>EMA220: Link não enviado, tente novamente!</div>";
                $this->result = false;
            }
    }

//=------------------------------------------------------------------------------------------------------------
    //Enviar novo email
    private function newSendEmailRecover(): void
    {
        //instancia o corpo do emial
        $this->contentEMailRecover();
        $this->contentEMailTextRecover();
        //instancia o metodo de enviar email
        $sendEmailRecover = new \Adm\Models\helper\AdmEmail();
        $sendEmailRecover->sendEmail($this->emailData, 1);

        if ($sendEmailRecover->getResult()) {

            $_SESSION['msg'] = "<div class='alert alert-success'>EMA230: Enviado o link com instruções para recuperação de senha. Por favor, acesse seu E-Mail!</div>";
            $this->resultado = true;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>EMA240: O E-Mail com as instruções para recuperação de senha não enviado, se o erro persistir, entre em contato com {$sendEmailRecover->getEmailContact()} para mais informações!</div>";
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------

    private function contentEMailRecover(): void
    {
       $name = explode(" ", $this->resultadoBd[0]['name']);
       $this->firstName = $name[0];

        //confirmaçao de email
        $this->url = URLADM ."out-side-users/update-recover-password?keyrecover=" .$this->resultadoBd[0]['recover_password'];

       $this->emailData['toEmail']      = $this->data['email'];
       $this->emailData['toName']       = $this->firstName;
       $this->emailData['subject']      = "Recuperar Senha";
       $this->emailData['contentHtml']  = "Prezado(a) {$this->firstName}<br><br>";
       $this->emailData['contentHtml']  .= "Você solicitou a recuperação de senha, por gentileza siga os passos abaixo: <br><br>";
       $this->emailData['contentHtml']  .= "Clique no link e atualize sua senha. <br><br>";
       $this->emailData['contentHtml']  .= "<a class='btn btn-info' href='{$this->url}'>{$this->url}</a>";
    }

//=------------------------------------------------------------------------------------------------------------

    private function contentEMailTextRecover(): void
    {
       $this->emailData['contentText']  = "Prezado(a) {$this->firstName}\n\n";
       $this->emailData['contentText']  .= "Você solicitou a recuperação de senha, por gentileza siga os passos abaixo:  \n\n";
       $this->emailData['contentText']  .= "Clique no link e atualize sua senha. \n\n";
       $this->emailData['contentText']  .= $this->url ."\n\n";
    }

//=------------------------------------------------------------------------------------------------------------
    //valida chave vindo pelo GET
    public function valKeyRecover($key): bool
    {
        $this->key = $key;

        //verifica se existe a chave
        if (!empty($this->key)) {

            $pdoSelect = new \Adm\Models\helper\AdmRead();
            //busca no banco de dados a chave de confirmação de email
            $pdoSelect->fullRead("SELECT id FROM adm_users WHERE recover_password =:recover_password", "recover_password={$this->key}");
            $this->resultadoBd = $pdoSelect->getResultado();
            //verifica se encontrou resultado
            if ($this->resultadoBd) {
                $this->resultado = true;
                return true;
            }else {
                $_SESSION['msg'] = "<div class='alert alert-warning'>CHA110: Link Inválido, solicite um novo link: <a href='". URLADM ."users/recover-password' class='badge alert-info bg-warning'>Clique aqui</a>. </div>";
                $this->resultado = false;
                return false;
            }

        } else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>CHA120: Link Inválido, solicite um novo link: <a href='". URLADM ."users/recover-password' class='badge alert-info bg-warning'>Clique aqui</a>.</div>";
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //editar senha
    public function editPassword($data = null): void
    {
        $this->data = $data;
        //verifica se esta vazio
        $valEmptyField = new \Adm\Models\helper\AdmValidate();
        $valEmptyField->valField($this->data);

        if ($valEmptyField->getResult()) {
            $this->validatePass();
        }else {
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //valida a senha
    private function validatePass(): void
    {
        //valida a senha com a models de validação
        $valPassword = new \Adm\Models\helper\AdmValidate();
        $valPassword->valPassword($this->data['password']);

        if ($valPassword->getResult()) {
            
            if ($this->valKeyRecover($this->data['key'])) {

                $this->updatePassword();
            }else {
                $this->resultado = false;
            }
        }else {
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //atualiza com a nova senha
    private function updatePassword(): void
    {
        $newPassword = password_hash($this->data['password'], PASSWORD_DEFAULT);

        $newKeyPassword = [
            "recover_password"  => null,
            "password"          => $newPassword,
            "modified"          => date("Y-m-d H:i:s")
        ];

        //update para status de usuario ter acesso
        $pdoUpdate = new \Adm\Models\helper\AdmUpdate();

        $pdoUpdate->exeUpdate("adm_users", $newKeyPassword, "WHERE id=:id", "id={$this->resultadoBd['0']['id']}");

            if ($pdoUpdate->getResultado()) {
                $_SESSION['msg'] = "<div class='alert alert-success'>SEN100: Senha atualizada com sucesso!</div>";
                $this->resultado = true;
            }else {
                $_SESSION['msg'] = "<div class='alert alert-warning'>SEN110: Algo de errado não está certo!</div>";
                $this->resultado = false;
            }
    }

//=------------------------------------------------------------------------------------------------------------
    //listar os usuarioss
    public function listUsers($data): void
    {
        $this->data = $data;

        $pdoSelect = new \Adm\Models\helper\AdmRead();
        $mask = new \Adm\Models\helper\AdmFormat();
        
        $sql = "SELECT id, cpf, name, email FROM adm_users";
        

        //FILTRO POR NOME
        if (!empty($this->data['name'])) {

            $sql .= " WHERE name LIKE '%{$this->data['name']}%'";
        }

        //FILTRO POR CPF
        if (!empty($this->data['id'])) {

            $sql .= " WHERE id = '{$this->data['id']}'";
        }
            
        $pdoSelect->fullRead($sql);
        $result = $pdoSelect->getResultado();
        
        if (count($result) > 0) {

            $listUser = array();
            foreach ($result as $user) {

                extract($user);

                $users = array(
                    "id"    => $id,
                    "nome"  => $name,
                    "cpf"   => $mask->maskDoc($cpf),
                    "email" => $email
                );

                array_push($listUser, $users);
            }

            $this->returnResult(true, $listUser);
        } else {

            $this->returnResult(false, $listUser = NULL, 500, 'USE');
        }
    }
//=------------------------------------------------------------------------------------------------------------
    //visualizar o detalhes do usuario
    public function userDetails(int $id = null): void
    {
        $this->id = $id;

        $pdoSelect = new \Adm\Models\helper\AdmRead();
            
        $sql = "SELECT id, cpf, name, email, nickname, adm_situacao_id, image, created, modified FROM adm_users WHERE id =:id";

        $par =  "id={$this->id}";

        $pdoSelect->fullRead($sql, $par);
        $result = $pdoSelect->getResultado();
        
        if (count($result) > 0) {
            $this->resultado = true;

        } else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>USE160: Usuário não encontrado!</div>";
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------

    //visualizar o detalhes do usuario
    public function userDetailsData($id = null): void
    {
        $this->id = $id;
        $pdoSelect = new \Adm\Models\helper\AdmRead();
            
        $sql = "SELECT u.id AS id, cpf, u.name AS name, email, image, nickname, adm_situacao_id, u.created AS created, u.modified AS modified, s.name AS situacao, c.color_hexa AS color
                    FROM adm_users u INNER JOIN adm_situacao s ON u.adm_situacao_id = s.id
                    INNER JOIN adm_colors c ON s.adm_colors_id = c.id
                    WHERE u.id =:id";

        $par =  "id={$this->id['id']}";

        $pdoSelect->fullRead($sql, $par);
        $result = $pdoSelect->getResultado();
        
        if (count($result) > 0) {

            $listUser = array();
            foreach ($result as $user) {

                extract($user);

                $users = array(
                    "id"        => $id,
                    "nome"      => $name,
                    "cpf"       => $cpf,
                    "email"     => $email,
                    "status"    => $situacao,
                    "nickname"  => $nickname,
                    "created"   => $created,
                    "modified"  => $modified,
                    "cor"       => $color,
                    "img"       =>$image
                );

                array_push($listUser, $users);
            }

            $this->returnResult(true, $listUser);
        } else {

            $this->returnResult(false, $listUser = NULL, 510, 'USE');
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //adicionar um novo usuário
    public function addUser($data): void
    {
        $this->data = $data;

        //validações
        $valField = new \Adm\Models\helper\AdmValidate();
        $valField->valField($this->data);
        if ($valField->getResult()) {
            $this->valInputAdd();
        }else {
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //valida email
    private function valInputAdd()
    {
        //instancia a validação
        $valEmail = new \Adm\Models\helper\AdmValidate();
        $valEmail->valEmail($this->data['email']);

        //valida email ja cadastrado
        $valEmailExist = new \Adm\Models\helper\AdmValidate();
        $valEmailExist->valEmailUsed($this->data['email']);

        //valida usuario ja cadastrado
        $valUserUsed = new \Adm\Models\helper\AdmValidate();
        $valUserUsed->valUserUsed($this->data['user']);

        //valida senha
        $valPassword = new \Adm\Models\helper\AdmValidate();
        $valPassword->valPassword($this->data['password']);

        //verifica o resultado
        if (($valEmail->getResult()) && ($valEmailExist->getResult()) && ($valUserUsed->getResult()) && ($valPassword->getResult())) {
            $this->continueCreatedAdd();
        }else {
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //criando usuario
    private function continueCreatedAdd()
    {
        //criptografar a senha
        $this->data['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);
        $this->data['confirmar_email'] = password_hash($this->data['password'] .date('Y-m-d H:i:s'), PASSWORD_DEFAULT);
        //criando nickname
        $nome = explode(" ", $this->data['name']);

        $newUserData = array (
            "name"              => $this->data['name'],
            "nickname"          => $nome[0],
            "cpf"               => $this->data['cpf'],
            "email"             => $this->data['email'],
            "user"              => $this->data['user'],
            "password"          => $this->data['password'],
            "confirmar_email"   => $this->data['confirmar_email'],
            "created"           => date('Y-m-d H:i:s')
        );

        $pdoCreate = new \Adm\Models\helper\AdmCreate();
        $pdoCreate->exeCreate('adm_users', $newUserData);
        $this->resultadoBd = $pdoCreate->getResultado();

        if ($this->resultadoBd) {
            $_SESSION['msg'] = "<div class='alert alert-success'>USE170: Usuário cadastrado com sucesso!</div>";
            $this->resultado = true;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>USE180: Usuário não cadastrado, por favor tente novamente. Se o erro persistir entre em contato com o administrador ". EMAILADM ." do sistema</div>";
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------

    //visualizar o detalhes do usuario
    public function userEditData($id = null): void
    {
        $this->id = $id;

        $pdoSelect = new \Adm\Models\helper\AdmRead();
            
        $sql = "SELECT u.id AS id, cpf, u.name AS name, email, user,image, nickname, adm_situacao_id, u.created AS created, u.modified AS modified, s.name AS situacao, c.color_hexa AS color
                    FROM adm_users u INNER JOIN adm_situacao s ON u.adm_situacao_id = s.id
                    INNER JOIN adm_colors c ON s.adm_colors_id = c.id
                    WHERE u.id = {$this->id['id']}";

        $pdoSelect->fullRead($sql);
        $result = $pdoSelect->getResultado();
        
        if (count($result) > 0) {

            $listUser = array();
            foreach ($result as $user) {

                extract($user);

                $users = array(
                    "id"        => $id,
                    "nome"      => $name,
                    "cpf"       => $cpf,
                    "email"     => $email,
                    "status"    => $situacao,
                    "id_situ"   => $adm_situacao_id,
                    "nickname"  => $nickname,
                    "user"      => $user,
                    "created"   => $created,
                    "modified"  => $modified,
                    "cor"       => $color,
                    "image"     => $image
                );

                array_push($listUser, $users);
            }

            $this->returnResult(true, $listUser);
        } else {

            $this->returnResult(false, $listUser = NULL, 510, 'USE');
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //visualizar o detalhes do usuario
    public function updateUserData($data): void
    {
        $this->data = $data;
        
         //validações
         $valField = new \Adm\Models\helper\AdmValidate();
         $valField->valField($this->data);
         if ($valField->getResult()) {

            $valEmailExist = new \Adm\Models\helper\AdmValidate();
            $valEmailExist->valEmailUsed($this->data['email'], true, $this->data['id']);

            //valida usuario ja cadastrado
            $valUserUsed = new \Adm\Models\helper\AdmValidate();
            $valUserUsed->valUserUsed($this->data['user'], true, $this->data['id']);

            //valida senha
            $valPassword = new \Adm\Models\helper\AdmValidate();
            $valPassword->valPassword($this->data['password']);

            if (($valEmailExist->getResult()) && ($valUserUsed->getResult())) {

                if (($valPassword->getResult()) && !$this->data['password'] == null) {

                $newPassword = password_hash($this->data['password'], PASSWORD_DEFAULT);

                $updateUserData = [
                    'name'              => $this->data['name'],
                    'cpf'               => $this->data['cpf'],
                    'email'             => $this->data['email'],
                    'user'              => $this->data['user'],
                    'nickname'          =>  $this->data['nickname'],
                    'password'          => $newPassword,
                    'adm_situacao_id'   => $this->data['situacao'],
                    'modified'          => date('Y-m-d H:i:s')
                ];

                }else {

                    $updateUserData = [
                    'name'              => $this->data['name'],
                    'cpf'               => $this->data['cpf'],
                    'email'             => $this->data['email'],
                    'user'              => $this->data['user'],
                    'nickname'          =>  $this->data['nickname'],
                    'adm_situacao_id'   => $this->data['situacao'],
                    'modified'          => date('Y-m-d H:i:s')
                    ];
                }

                $pdoUpdate = new \Adm\Models\helper\AdmUpdate();
                $pdoUpdate->exeUpdate("adm_users", $updateUserData, "Where id=:id", "id={$this->data['id']}");

                $this->resultadoBd = $pdoUpdate->getResultado();
                if ($this->resultadoBd) {
                    $_SESSION['msg'] = "<div class='alert alert-success'>USE190: Dados do usuário atualizados com sucesso!</div>";
                    $this->resultado = true;
                }else {
                    $_SESSION['msg'] = "<div class='alert alert-danger'>USE200: Dados não atualizados, por favor tente novamente. Se o erro persistir entre em contato com o administrador ". EMAILADM ." do sistema</div>";
                    $this->resultado = false;
                }
            }else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>USE210: Dados não atualizados, Email ou Usuário já cadastrado!</div>";
                $this->resultado = false;
                }
        }else {
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //visualizar o detalhes do usuario
    public function updateMyUserData($data): void
    {
        $this->data = $data;
        //validações
        $valField = new \Adm\Models\helper\AdmValidate();
        $valField->valField($this->data);
        if ($valField->getResult()) {
            
            $valEmailExist = new \Adm\Models\helper\AdmValidate();
            $valEmailExist->valEmailUsed($this->data['email'], true, $_SESSION['usuario_id']);

            //valida usuario ja cadastrado
            $valUserUsed = new \Adm\Models\helper\AdmValidate();
            $valUserUsed->valUserUsed($this->data['user'], true, $_SESSION['usuario_id']);

            //valida senha
            $valPassword = new \Adm\Models\helper\AdmValidate();
            $valPassword->valPassword($this->data['password']);

            if (($valEmailExist->getResult()) && ($valUserUsed->getResult())) {

                if (($valPassword->getResult()) && !$this->data['password'] == null) {

                $newPassword = password_hash($this->data['password'], PASSWORD_DEFAULT);

                $updateUserData = [
                    'name'              => $this->data['name'],
                    'cpf'               => $this->data['cpf'],
                    'email'             => $this->data['email'],
                    'user'              => $this->data['user'],
                    'nickname'          =>  $this->data['nickname'],
                    'password'          => $newPassword,
                    'modified'          => date('Y-m-d H:i:s')
                ];

                }else {

                    $updateUserData = [
                    'name'              => $this->data['name'],
                    'cpf'               => $this->data['cpf'],
                    'email'             => $this->data['email'],
                    'user'              => $this->data['user'],
                    'nickname'          =>  $this->data['nickname'],
                    'modified'          => date('Y-m-d H:i:s')
                    ];
                }

                $pdoUpdate = new \Adm\Models\helper\AdmUpdate();
                $pdoUpdate->exeUpdate("adm_users", $updateUserData, "Where id=:id", "id={$_SESSION['usuario_id']}");

                $this->resultadoBd = $pdoUpdate->getResultado();
                if ($this->resultadoBd) {
                    $_SESSION['msg'] = "<div class='alert alert-success'>USE280: Dados do usuário atualizados com sucesso!</div>";
                    $this->resultado = true;
                }else {
                    $_SESSION['msg'] = "<div class='alert alert-danger'>USE290: Dados não atualizados, por favor tente novamente. Se o erro persistir entre em contato com o administrador ". EMAILADM ." do sistema</div>";
                    $this->resultado = false;
                }
            }else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>USE300: Dados não atualizados, Email ou Usuário já cadastrado!</div>";
                $this->resultado = false;
            }
        }else {
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------

    //visualizar a imagem do usuario
    public function userImgEdit($id = null): void
    {
        $this->id = $id;

        $pdoSelect = new \Adm\Models\helper\AdmRead();

        $sql = "SELECT id, image, name
                    FROM adm_users
                    WHERE id = {$this->id['id']}";

        $pdoSelect->fullRead($sql);
        $this->result = $pdoSelect->getResultado();
        
        if (count($this->result) > 0) {

            $listUserImg = array();
            foreach ($this->result as $user) {

                extract($user);

                $users = array(
                    "id"    => $id,
                    "img"   => $image,
                    "nome"  => $name
                );

                array_push($listUserImg, $users);
            }
            $this->returnResult(true, $listUserImg);
        } else {

            $this->returnResult(false, $listUserImg = NULL, 520, 'USE');
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //verificar diretorio usuario
    public function updateUserImg($data): void
    {
        $this->data = $data;
        
        $pdoSelect = new \Adm\Models\helper\AdmRead();

        $sql = "SELECT id, image, name
                    FROM adm_users
                    WHERE id = {$this->data['id']}";

        $pdoSelect->fullRead($sql);
        $this->result = $pdoSelect->getResultado();
        
        //slug da imagem
        $slugImg = new \adm\Models\helper\AdmSlug();
        $this->img = $slugImg->slug($this->data['new_img']['new_img']['name']);

        $this->directory = "app/adm/assets/img/users/" .$this->data['id']. "/";

        //valida extensão da imagem
        $valExtImg = new \Adm\Models\helper\AdmValidate();
        $valExtImg->validateExtImg($this->data['new_img']['new_img']['type']);

        if ($valExtImg->getResult()) {
            $this->resultado = true;

            // $uploadImg = new \Adm\Models\helper\AdmUpload();
            // $uploadImg->upload($this->directory, $this->data['new_img']['new_img']['tmp_name'], $this->img);

            $uploadImgRes = new \Adm\Models\helper\AdmUploadImgRes();
            $uploadImgRes->uploadRes($this->data['new_img']['new_img'], $this->directory, $this->img, 980, 1200);

            if ($uploadImgRes->getResult()) {
                $this->updateUserImgData();
            }else {
                $this->resultado = false;
            }

        }else {
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //atualizar imagem do usuario
    private function updateUserImgData()
    {
        if (!empty($this->img)) {

            $updateUserImg = [
                'image'     => $this->img,
                'modified'  => date('Y-m-d H:i:s')
            ];

            $pdoUpdate = new \Adm\Models\helper\AdmUpdate();
            $pdoUpdate->exeUpdate("adm_users", $updateUserImg, "Where id=:id", "id={$this->data['id']}");

            $this->resultadoBd = $pdoUpdate->getResultado();

            if ($this->resultadoBd) {
                $this->deleteImage();
            }else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>USE230: Imagem não atualizados, por favor tente novamente. Se o erro persistir entre em contato com o administrador ". EMAILADM ." do sistema</div>";
                $this->resultado = true;
            }
        }else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>USE240: Necessário enviar uma imagem!</div>";
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //excluir imagem
    private function deleteImage(): void
    {
        //se for diferente de vazio ou nulo e a imagem q existe for diferente da img enviada
        if (((!empty($this->result[0]['image'])) || ($this->result[0]['image'] != null)) && ($this->result[0]['image'] != $this->img)) {
             //caminho da imagem para exclusão
            $this->delImg = "app/adm/assets/img/users/" .$this->data['id']. "/" .$this->result[0]['image'];
            //se existe o arquivo deve destroir
            if (file_exists($this->delImg)) {
                unlink($this->delImg);
            }
        }
        $_SESSION['msg'] = "<div class='alert alert-success'>USE220: Imagem do usuário atualizados com sucesso!</div>";
        $this->resultado = true;
    }

//=------------------------------------------------------------------------------------------------------------
    //carregar o status
    public function getListSitu()
    {
        $pdoSelect = new \Adm\Models\helper\AdmRead();

        $sql = "SELECT  s.id AS id, s.name AS situacao, color_hexa
                FROM adm_situacao s INNER JOIN adm_colors c ON s.adm_colors_id = c.id";

        $pdoSelect->fulLRead($sql);
        $resultSitu = $pdoSelect->getResultado();

        if (count($resultSitu) > 0) {

            $result = array(
                "adm_situacao" => $resultSitu,
            );

            $this->returnResult(true, $result);
        } else {

            $this->returnResult(false, $result = NULL, 530, 'USE');
        }
    }

//=------------------------------------------------------------------------------------------------------------
    public function deleteUser($id)
    {
        $this->id = (int) $id;

        $pdoSelect = new \Adm\Models\helper\AdmRead();

        $sql = "SELECT id, image, name
                    FROM adm_users
                    WHERE id = {$this->id}";

        $pdoSelect->fullRead($sql);
        $this->result = $pdoSelect->getResultado();
       
        $deleteUser = new \Adm\Models\helper\AdmDelete();
        $deleteUser->exeDelete("adm_users", "WHERE id =:id", "id={$this->id}");

        $this->resultadoBd = $deleteUser->getResultado();
        if ($this->resultadoBd) {
            $this->deleteImgToUser();
            $_SESSION['msg'] = "<div class='alert alert-success'>USE250: Usuário excluído com sucesso!</div>";
            $this->resultado = true;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>USE260: Erro ao excluír o usuário tente novamente!</div>";
            $this->resultado = false;
        }
        
    }

//=------------------------------------------------------------------------------------------------------------
    //deletar imagem junto com usuario
    private function deleteImgToUser()
    {

        if ((!empty($this->result[0]['image'])) || ($this->result[0]['image'] != null)) {
            $this->directory = "app/adm/assets/img/users/" .$this->result[0]['id'];
            $this->delImg = $this->directory ."/" .$this->result[0]['image'];
            //remover imagem
            if (file_exists($this->delImg)) {
                unlink($this->delImg);
            }
            //remover diretorio
            if (file_exists($this->directory)) {
                rmdir($this->directory);
            }
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //visualizar o detalhes do usuario
    public function viewProfile(): void
    {

        $pdoSelect = new \Adm\Models\helper\AdmRead();
            
        $sql = "SELECT  cpf, name, email, user, nickname, adm_situacao_id, created, modified
                    FROM adm_users id = " .$_SESSION['usuario_id'] ."";

        $pdoSelect->fullRead($sql);
        $result = $pdoSelect->getResultado();
        
        if (count($result) > 0) {

            $listUser = array();
            foreach ($result as $user) {

                extract($user);

                $users = array(
                    "nome"      => $name,
                    "cpf"       => $cpf,
                    "email"     => $email,
                    "nickname"  => $nickname,
                    "user"      => $user,
                    "created"   => $created,
                    "modified"  => $modified
                );

                array_push($listUser, $users);
            }

            $this->returnResult(true, $listUser);
        } else {

            $this->returnResult(false, $listUser = NULL, 510, 'USE');
        }
    }
}