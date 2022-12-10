<?php

namespace Adm\Models\helper;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
  
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

//Classe para email's
class AdmEmail
{
    private $data;
    private $infoEmail;
    private $fromEmail = EMAILADM;
    private $optionEmail;

    private $key;

    private $result;
    private $resultBd;

    private $firstName;
    private $emailData;

//=------------------------------------------------------------------------------------------------------------
//retorna o resultado
    public function getResult()
    {
        return $this->result;
    }

//=------------------------------------------------------------------------------------------------------------
//retorna o email para contato
    public function getEmailContact()
    {
        return $this->fromEmail;
    }

//=------------------------------------------------------------------------------------------------------------
    //envia email
    public function sendEmail($data, $optionEmail): void
    {
        //pode testar com o emailtrap (cria caixa, configuração, credenciais FuelPHP) - informações necessárias
        //posso comentar se estiver trazendo as informações pelo BD
        // $this->infoEmail['host']        = EMAILHOST;
        // $this->infoEmail['fromEmail']   = EMAILEMAIL;
        // $this->infoEmail['fromName']    = "José Divivo";
        // $this->infoEmail['username']    = EMAILUSER;
        // $this->infoEmail['password']    = EMAILPASSWORD;
        // $this->infoEmail['port']        = EMAILPORT ;
        // //email
        // $this->fromEmail = $this->infoEmail['fromEmail'];

        // //qual email enviar para usuario pelo ID
        $this->optionEmail = $optionEmail;

        $this->data = $data;
        //Destinatario
        // $this->data['toEmail']      = "josedivinooficial02@gmail.com";
        // $this->data['toName']       = "Divino";
        // $this->data['subject']      = "Confirma e-mail";
        //duas formas de enviar o conteudo do email
        // $this->data['contentHtml']  = "<div class='alert alert-danger'>Olá <strong>Divino</strong><br>Cadastro realizado com sucesso!</div>";
        // $this->data['contentText']  = "Olá Divino \n\nCadastro realizado com sucesso!";

        //seta a function que envia o email
        // $this->sendEmailPhpMailer();
        //se as informações do email vem pelo banco de dados
        $this->infoPhpMailer();
    }

//=------------------------------------------------------------------------------------------------------------
    //Informações das credenciais via Banco de dados
    private function infoPhpMailer(): void
    {
        //Select para buscar informação do email
        $pdoSelect = new \Adm\Models\helper\AdmRead();
        $pdoSelect->fullRead("SELECT name, email, host, username, password, smtpsecure, port FROM adm_config_emails WHERE id =:id", "id={$this->optionEmail}");
        $this->resultBd = $pdoSelect->getResultado();
        //validação
        if ($this->resultBd) {

             //pode testar com o emailtrap (cria caixa, configuração, credenciais FuelPHP) - informações necessárias
            $this->infoEmail['host']            = $this->resultBd[0]['host'];
            $this->infoEmail['fromEmail']       = $this->resultBd[0]['email'];
            $this->infoEmail['fromName']        = $this->resultBd[0]['name'];
            $this->infoEmail['username']        = $this->resultBd[0]['username'];
            $this->infoEmail['password']        = $this->resultBd[0]['password'];
            $this->infoEmail['smtpsecure']      = $this->resultBd[0]['smtpsecure'];
            $this->infoEmail['port']            = $this->resultBd[0]['port'];
            //email
            $this->fromEmail = $this->infoEmail['fromEmail'];

            $this->sendEmailPhpMailer();
        }else {
            $this->result = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //execução do Email
    private function sendEmailPhpMailer(): void
    {
        //instanciando o objeto
        $mail = new PHPMailer(true);
            
        try {
            //DEBUG
            // $mail->SMTPDebug    = SMTP::DEBUG_SERVER;               //Enable verbose debug output
            //para caracteres especiais
            $mail->CharSet = "UTF-8";
        
            $mail->isSMTP();                                        //Send using SMTP
            $mail->Host         = $this->infoEmail['host'];         //Set the SMTP server to send through
            $mail->SMTPAuth     = true;                             //Enable SMTP authentication
            $mail->Username     = $this->infoEmail['username'];     //SMTP username
            $mail->Password     = $this->infoEmail['password'];     //SMTP password
            // $mail->SMTPSecure   = PHPMailer::ENCRYPTION_STARTTLS;   //Enable implicit TLS encryption
            //se as informações do SMTP estiver pelo BD
            $mail->SMTPSecure   = $this->infoEmail['smtpsecure'];
            $mail->Port         = $this->infoEmail['port'];         //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Quuem está enviando e recebendo
            $mail->setFrom($this->infoEmail['fromEmail'], $this->infoEmail['username']);
            $mail->addAddress($this->data['toEmail'], $this->data['toName']);     //Add a recipient

            //anexos
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Conteudo enviado
            $mail->isHTML(true);                                    //Set email format to HTML
            $mail->Subject = $this->data['subject'];
            $mail->Body    = $this->data['contentHtml'];
            $mail->AltBody = $this->data['contentText'];

            //envia o email
            $mail->send();
            $this->result = true;

        } catch (Exception $err) {
            $this->result = false;
        }
    }
    
//=------------------------------------------------------------------------------------------------------------
    //para confirmação de email
    public function confEmail($key)
    {
        $this->key = $key;
         //verifica se existe a chave
         if (!empty($this->key)) {

            $pdoSelect = new \Adm\Models\helper\AdmRead();
            //busca no banco de dados a chave de confirmação de email
            $pdoSelect->fullRead("SELECT id FROM adm_users WHERE confirmar_email =:confirmar_email", "confirmar_email={$this->key}");
            //verifica se recebeu algum resultado
            $this->resultBd = $pdoSelect->getResultado();
            //verifica se encontrou resultado
            if ($this->resultBd) {
                $this->updateSitUser();
            }else {
                $_SESSION['msg'] = "<div class='alert alert-warning'>EMA130: Link Inválido</div>";
                $this->result = false;
            }

        } else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>EMA140: Link Inválido</div>";
            $this->result = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //atualiza a confirmação do email para o usuario ter acesso
    private function updateSitUser(): void
    {

        $emailConfUpdate = [
            "confirmar_email"   => 'Email confirmado',
            "adm_situacao_id"   => 1,
            "modified"         => date('Y-m-d H:i:s')
        ];

        //update para status de usuario ter acesso
        $pdoUpdate = new \Adm\Models\helper\AdmUpdate();

        $pdoUpdate->exeUpdate("adm_users", $emailConfUpdate, "WHERE id=:id", "id={$this->resultBd['0']['id']}");

        if ($pdoUpdate->getResultado()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>EMA150: E-Mail ativado com sucesso!</div>";
            $this->result = true;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>EMA160: Link Inválido</div>";
            $this->result = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //enviar novo link de confirmação via email
    public function newConfEmail($data = null): void
    {
        $this->data = $data;

        $valEmptyField = new \Adm\Models\helper\AdmValidate();
        $valEmptyField->valField($this->data);
       
        if ($valEmptyField->getResult()) {
            $this->valUser();
        }else {
            $this->result = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //valida usuario
    private function valUser(): void
    {
        //buscando o usuario no BD que o cliente digitou o email
        $pdoSelect = new \Adm\Models\helper\AdmRead();
        $pdoSelect->fullRead("SELECT id, name, email, confirmar_email FROM adm_users WHERE email =:email", "email={$this->data['email']}");

        $this->resultBd = $pdoSelect->getResultado();
        //se encontrou o resultado
            if ($this->resultBd) {
                
                $this->valConEmail();
            }else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>EMA170: E-Mail não encontrado!</div>";
                $this->result = false;
            }
    }

//=------------------------------------------------------------------------------------------------------------
    //para cadastrar uma nova chave se nao tiver
    private function valConEmail(): void
    {
        //verifica se o campo email no BD está vazio
        if ((empty($this->resultBd[0]['confirmar_email']))  || ($this->resultBd[0]['confirmar_email'] == null)) {
            //gera uma nova chave de confirmação
            $newKeyAccess = password_hash(date("Y-m-d H:i:s") .$this->resultBd[0]['id'], PASSWORD_DEFAULT);

            $newKeyEmail = [
                "confirmar_email"   => $newKeyAccess,
                "modified"          => date("Y-m-d H:i:S")
            ];
    
            //update para status de usuario ter acesso
            $pdoUpdate = new \Adm\Models\helper\AdmUpdate();
    
            $pdoUpdate->exeUpdate("adm_users", $newKeyEmail, "WHERE id=:id", "id={$this->resultBd['0']['id']}");

                if ($pdoUpdate->getResultado()) {
                    $this->resultBd[0]['confirmar_email'] = $newKeyAccess;
                    $this->newSendEmail();
                }else {
                    $_SESSION['msg'] = "<div class='alert alert-warning'>EMA190: Link não enviado, tente novamente!</div>";
                    $this->result = false;
                }
        }else {
            $this->newSendEmail();
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //Enviar novo email
    private function newSendEmail(): void
    {

        //instancia o corpo do emial
        $this->contentEMail();
        $this->contentEMailText();
        //instancia o metodo de enviar email
        $this->sendEmail($this->emailData, 2);

        if ($this->getResult()) {

            $_SESSION['msg'] = "<div class='alert alert-success'>EMA150: Novo Link enviado com sucesso!</div>";
            $this->resultado = true;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-warning'>EMA200: Usuário cadastrado com sucesso. Houve um erro ao enviar o E-Mail de confirmação, entre em contato com {$this->getEmailContact()} para mais informações!</div>";
            $this->resultado = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------

    private function contentEMail(): void
    {
       $name = explode(" ", $this->resultBd[0]['name']);
       $this->firstName = $name[0];

        //confirmaçao de email
        $this->url = URLADM ."conf-email/confirmar-email?keyemail=" .$this->resultBd[0]['confirmar_email'];

       $this->emailData['toEmail']      = $this->data['email'];
       $this->emailData['toName']       = $this->firstName;
       $this->emailData['subject']      = "Novo Link enviado! confirme sua conta para acessar o sistema!";
       $this->emailData['contentHtml']  = "Prezado(a) {$this->firstName}<br><br>";
       $this->emailData['contentHtml']  .= "Para liberar o acesso enviamos um novo link. Clique no link: <br><br>";
       $this->emailData['contentHtml']  .= "<a class='btn btn-info' href='{$this->url}'>{$this->url}</a>";
    }

//=------------------------------------------------------------------------------------------------------------
    private function contentEMailText(): void
    {
       $this->emailData['contentText']  = "Prezado(a) {$this->firstName}\n\n";
       $this->emailData['contentText']  .= "Para liberar o acesso clique no link: \n\n";
       $this->emailData['contentText']  .= $this->url ."\n\n";
    }
}
