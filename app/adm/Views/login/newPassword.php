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
//verifica se existe uma mensagem e depois destroi
if (isset($_SESSION['msg'])) {
  echo $_SESSION['msg'];
  unset($_SESSION['msg']);
}

//=------------------------------------------------------------------------------------------------------------
?>
<main>
  <div class="justify-content-center">
    <section class="card mb-4 centralizar" >
      <div class="card grid mt-3 text-center largura-50">
        <h1 class="text-color-branco grid">Senha</h1>
        <hr class="mb-1 line-grey">
        <div class="card-body grid">
          <h5 class="mb-4  text-color-black grid">Nova Senha</h5>
          <span id="msg"></span>
          <form action="" method="POST" id="form-new-password">
            <div class="grid">
              <div class="form-group mb-5 search">
                <input type="password" class="form-control" id="password" name="password" autocomplete="on" onkeyup="passwordStrength()" placeholder="Atualiza sua senha" required>
              </div>
                <button class="form-group col-6 button-success mb-3" type="submit" name="SendNewPassword" id="SendNewPassword" value="Salvar">Salvar</button>
            </div>
          </form>
          <a href="<?php echo URLADM?>" class="btn btn-primary col-2" role="button" aria-pressed="true">Acessar</a>
        </div>
      </div>
    </section>
  </div>
</main>
