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
$name = "";
$email = "";
$cpf = "";

if (isset($valorForm['name'])) { $name = $valorForm['name']; }
if (isset($valorForm['email'])) { $email = $valorForm['email']; }
if (isset($valorForm['cpf'])) { $cpf = $valorForm['cpf']; }

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
          <h3 class="text-color-branco">Cadastro</h3>
          <hr class="mb-1 line-grey">
        </div>
      </div>
    </div>
  <div class="justify-content-center">
    <section class="card mb-4 centralizar" >
      <div class="card grid mt-3 text-center largura-50">
        <h1 class="text-color-black grid">Novo Usuário</h1>
        <hr class="mb-1 line-grey">
        <span class="grid" id="msg"></span>
        <div class="card-body grid">
          <form action="" method="POST" id="form-new-user">
            <div class="grid">
              <div class="form-group mb-4 search">
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name?>" placeholder="Digite o nome completo" required>
              </div>
              <div class="form-group mb-4 search">
                <input type="text" class="form-control" id="cpf" name="cpf" onkeypress="return onlynumber();" value="<?php echo $cpf?>" placeholder="CPF" required>
              </div>
              <div class="form-group mb-4 search">
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email?>" placeholder="E-mail" required>
              </div>
              <div class="form-group bg mb-5 search">
                <input type="password" class="form-control" id="password" name="password" autocomplete="on" onkeyup="passwordStrength()" placeholder="Senha" required>
                <span id="msgViewStrengh"></span>
              </div>
                <button class="form-group col-6 button-success mb-3" type="submit" name="SendNewUser" id="SendNewUser" value="Cadastrar">Cadastrar</button>
            </div>
          </form>
          <a href="<?php echo URLADM?>" class="btn btn-primary col-2" role="button" aria-pressed="true">Acessar</a>
        </div>
      </div>
    </section>
  </div>
</div>
</main>
