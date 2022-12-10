<?php
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------
//se existe a posição do array
if (isset($this->data['form'])) {  $valorForm = $this->data['form']; }

//=------------------------------------------------------------------------------------------------------------
//continuar preenchido os campos
$user = "";
if (isset($valorForm['user'])) { $user = $valorForm['user']; }

//=------------------------------------------------------------------------------------------------------------
//verifica se existe uma mensagem e depois destroi
if (isset($_SESSION['msg'])) {
  echo $_SESSION['msg'];
  unset($_SESSION['msg']);
}

//=------------------------------------------------------------------------------------------------------------
?>

<main>
  <div class="container">
    <div class="justify-content-center">
      <section class="card mb-4 centralizar" >
        <div class="card grid mt-3 text-center largura-50">
          <h1 class="text-color-branco grid">Login</h1>
          <hr class="mb-1 line-grey">
          <div class="card-body grid">
            <h5 class="mb-4  text-color-black grid">Credenciais para acesso</h5>
            <span id="msg"></span>
            <form action="" method="POST" id="form-login">
              <div class="grid">
                <div class="form-group mb-4 search">
                  <input type="text" class="form-control active" id="user" name="user" value="<?php echo $user?>" placeholder="CPF ou E-Mail" required>
                </div>
                <div class="form-group bg mb-5 search">
                  <input type="password" class="form-control" id="password" name="password" autocomplete="on" placeholder="Senha" required>
                </div>
                  <button class="form-group col-6 button-success mb-3" type="submit" name="SendLogin" id="SendLogin" value="Acessar">Acessar</button>
              </div>
            </form>
            <a href="<?php echo URLADM?>out-side-users/new-user" class="btn btn-primary col-2" role="button" aria-pressed="true">Cadastrar</a>
            <div>
              <a href="<?php echo URLADM; ?>out-side-users/recover-password" class="badge alert-info" style="float: right">Esqueceu a senha?</a>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</main>
