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
          <h3 class="text-color-branco">Novo Link</h3>
          <hr class="mb-1 line-grey">
        </div>
      </div>
    </div>
  <div class=" justify-content-center">
    <section class="card mb-4 centralizar">
      <div class="card grid mt-3 text-center largura-50">
        <h1 class="text-color-black grid">Solicite o link</h1>
        <hr class="mb-1 line-grey">
        <span class="grid" id="msg"></span>
        <div class="card-body grid">
          <form action="" method="POST" id="form-new-conf-email">
            <div class="grid">
              <div class="form-group mb-4 search">
                <input type="text" class="form-control" id="email" name="email" value="<?php echo $email?>" placeholder="E-mail" required>
              </div>
                <button class="form-group col-6 button-success mb-3" type="submit" name="SendNewConfEmail" id="SendNewConfEmail" value="Solicitar">Solicitar</button>
            </div>
          </form>
          <a href="<?php echo URLADM?>" class="btn btn-primary col-2" role="button" aria-pressed="true">Acessar</a>
        </div>
      </div>
    </section>
  </div>
</div>
</main>
