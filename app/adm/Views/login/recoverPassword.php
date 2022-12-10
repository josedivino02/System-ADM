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
$email = "";

if (isset($valorForm['email'])) { $email = $valorForm['email']; }

//=------------------------------------------------------------------------------------------------------------
//verifica se existe uma mensagem e depois destroi
if (isset($_SESSION['msg'])) {
  echo $_SESSION['msg'];
  unset($_SESSION['msg']);
}

//=------------------------------------------------------------------------------------------------------------
?>
<main>
<div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="mb-2">
          <h3 class="text-color-branco">Senha</h3>
          <hr class="mb-1 line-grey">
        </div>
      </div>
    </div>
  <div class=" justify-content-center">
    <section class="card mb-4 centralizar" >
      <div class="card grid mt-3 text-center largura-50">
        <h1 class="text-color-black grid">Recuperar Senha</h1>
        <hr class="mb-1 line-grey">
        <span class="grid" id="msg"></span>
        <div class="card-body grid">
          <form action="" method="POST" id="form-recover-password">
            <div class="grid">
              <div class="form-group mb-4 search">
                <input type="text" class="form-control search" id="email" name="email" value="<?php echo $email?>" placeholder="E-mail" required>
              </div>
                <button class="form-group col-6 button-success mb-3" type="submit" name="SendRecoverPassword" id="SendRecoverPassword" value="Recuperar">Recuperar</button>
            </div>
          </form>
          <a href="<?php echo URLADM?>" class="btn btn-primary col-2" role="button" aria-pressed="true">Acessar</a>
        </div>
      </div>
    </section>
  </div>
</div>
</main>
