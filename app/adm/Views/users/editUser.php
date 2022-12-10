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
$user = "";
$nick = "";

if (isset($valorForm['name'])) { $name = $valorForm['name']; }
if (isset($valorForm['nick'])) { $nick = $valorForm['nick']; }
if (isset($valorForm['email'])) { $email = $valorForm['email']; }
if (isset($valorForm['cpf'])) { $cpf = $valorForm['cpf']; }
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
<div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="mb-2">
          <h3 class="text-color-branco">Editar</h3>
          <hr class="mb-1 line-grey">
        </div>
      </div>
    </div>
  <div class="justify-content-center">
    <section class="card mb-4 centralizar" >
      <div class="card grid mt-3 text-center largura-50">
        <h3 class="text-color-black grid">Editar Usuário: <span class="nome_usuario" style="background-color: #6C5B7B;">[NOME DO USUÁRIO]</span></h3>
        <hr class="mb-1 line-grey">
        <span class="grid" id="msg"></span>
        <div class="card-body grid dados-usuarios">
          <form action="" method="POST" id="form-edit-user">
            <div class="grid">
              <div class="form-group mb-4 search">
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name?>" placeholder="Carregando..." required>
              </div>
              <div class="form-group mb-4 search">
                <input type="text" class="form-control" id="nick" name="nick" value="<?php echo $nick?>" placeholder="Carregando..." >
              </div>
              <div class="form-group mb-4 search">
                <input type="text" class="form-control" id="cpf" name="cpf" onkeypress="return onlynumber();" value="<?php echo $cpf?>" placeholder="Carregando..." required>
              </div>
              <div class="form-group mb-4 search">
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email?>" placeholder="Carregando..." required>
              </div>
              <div class="form-group mb-4 search">
                <input type="text" class="form-control" id="user" name="user" value="<?php echo $user?>" placeholder="Carregando..." required>
              </div>
              <div class="form-group mb-5 search  list-situ">
                <select class="form-control" name="situacao" id="situacao">
                  <option value="Carregando...">Carregando...</option>
                </select>
              </div>
              <div class="form-group bg mb-5 search">
                <input type="password" class="form-control" id="password" name="password" autocomplete="on" onkeyup="passwordStrength()" placeholder="Carregando..." required>
                <span id="msgViewStrengh"></span>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
  </div>
</div>
</main>
<script>

//=------------------------------------------------------------------------------------------------------------
  async function getEditUsers()
  {
      const keyuser = getParameter('keyuser');
      const nome_usuario = document.querySelector('.nome_usuario');

      const form = new FormData();
      form.append('id', keyuser);

      const response = await axios.post('user-edit-data', form);
      const situation = await axios.post('get-list-situ');

      if (response.data.error === 0) {
        const data = response.data.res;
        const situacoes = situation.data.res.adm_situacao;

        nome_usuario.innerText = data[0].nome;

        preencherDadosUsuarios(data[0]);
        preencherSituacao(situacoes);
        preencherSituationAtual(data[0]);
      }
    }

//=------------------------------------------------------------------------------------------------------------
   // Funções de preenchimento
   function preencherDadosUsuarios(dados_usuarios, situacoes)
   {

    const input_id        = dados_usuarios.id;
    const input_nome      = dados_usuarios.nome;
    const input_cpf       = dados_usuarios.cpf;
    const input_email     = dados_usuarios.email;
    const input_user      = dados_usuarios.user;
    const input_nick      = dados_usuarios.nickname;
    const input_status    = dados_usuarios.status;
    const input_id_situ   = dados_usuarios.id_situ;
    const input_cor       = dados_usuarios.cor;
    const input_create    = formatarData(dados_usuarios.created);

    const dados_usuario =  document.querySelector(".dados-usuarios");

    let html = `
      <form action="" method="POST" id="form-edit-user">
        <div class="grid">
            <div class="form-group mb-4 search ">
              <input type="text" class="form-control" id="name" name="name" value="${input_nome}" placeholder="Digite o nome completo" required>
              <input type="hidden" class="form-control" id="id" name="id" value="${input_id}" placeholder="ID">
            </div>
            <div class="form-group mb-4 search ">
              <input type="text" class="form-control" id="nick" name="nick" value="${input_nick}" placeholder="Digite um apelido">
            </div>
            <div class="form-group mb-4 search ">
              <input type="text" class="form-control" id="cpf" name="cpf" onkeypress="return onlynumber();" value="${input_cpf}" placeholder="CPF" required>
            </div>
            <div class="form-group mb-4 search">
              <input type="email" class="form-control" id="email" name="email" value="${input_email}" placeholder="E-mail" required>
            </div>
            <div class="form-group mb-4 search">
              <input type="text" class="form-control" id="user" name="user" value="${input_user}" placeholder="Usuário" required>
            </div>
            <div class="form-group mb-5 search list-situ"></div>
            <div class="form-group bg mb-5 search">
              <input type="password" class="form-control" id="password" name="password" autocomplete="on" onkeyup="passwordStrength()" placeholder="Senha">
              <span id="msgViewStrengh"></span>
            </div>
            <div class="grid">
              <a href="<?php echo URLADM?>users/edit-user-img?keyuserimg=${input_id}" class="btn btn-info col-2" style="margin-left: 45rem!important;" role="button" aria-pressed="true">Editar Imagem</a>
            </div>
            <button class="form-group col-4 button-success mb-3" type="submit" name="SendEditUser" id="SendEditUser" value="Atualizar" onclick='updateDataUser(${input_id})'>Atualizar</button>
        </div>
      </form>
      `;

      dados_usuario.innerHTML = html;
  }

//=------------------------------------------------------------------------------------------------------------
function preencherSituacao(situacoes)
{
  const status = document.querySelector('.list-situ');

    let html = `<select class="form-control" name="situacao" id="situacao">`;
  situacoes.map((situacao) => {
    
     html += `<option class="form-control" value ="${situacao.id}" >${situacao.situacao}</option>`;
    });

    html += `</select>`;

    status.innerHTML = html;
}

function preencherSituationAtual(situacoes) {
  const situa_now = document.querySelector('#situacao');
  situa_now.value = situacoes.id_situ;
}
//=------------------------------------------------------------------------------------------------------------
  async function updateDataUser(input_id)
  {
    const id        = document.querySelector('#id').value;
    const name      = document.querySelector('#name').value;
    const nick      = document.querySelector('#nick').value;
    const cpf       = document.querySelector('#cpf').value;
    const email     = document.querySelector('#email').value;
    const user      = document.querySelector('#user').value;
    const password  = document.querySelector('#password').value;
    const situacao  = document.querySelector('#situacao').value;

    const data = new FormData();

    if(password.length == 0) {

      data.append('id', id);
      data.append('name', name);
      data.append('nickname', nick);
      data.append('cpf', cpf);
      data.append('email', email);
      data.append('user', user);
      data.append('situacao', situacao);
    }else if (situacao.length == 0) {

      data.append('id', id);
      data.append('name', name);
      data.append('nickname', nick);
      data.append('cpf', cpf);
      data.append('email', email);
      data.append('user', user);
      data.append('password', password);
    } else if (password.length == 0 && situacao.length == 0) {

      data.append('id', id);
      data.append('name', name);
      data.append('nickname', nick);
      data.append('cpf', cpf);
      data.append('email', email);
      data.append('user', user);
    } else {

      data.append('id', id);
      data.append('name', name);
      data.append('nickname', nick);
      data.append('cpf', cpf);
      data.append('email', email);
      data.append('user', user);
      data.append('password', password);
      data.append('situacao', situacao);
    }

    const response = await axios.post('update-user-data', data);
    reload();
  }

//=------------------------------------------------------------------------------------------------------------
  function getParameter(name, url = window.location.href)
  {
    //limpa a url
    name = name.replace(/[\[\]]/g, '\\$&');
    //instancia o regex para utilizar expressão regular para manipular a string
    var regex = new RegExp('[?&]'+ name +'(=([^&#]*)|&|#|$)'),
      results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    //decodifica o componente da url
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  }

//=------------------------------------------------------------------------------------------------------------
  function clearInputs()
  {
    const dados_usuario = document.querySelector(".dados-usuarios");

    dados_usuario.innerHTML = '';
  }

//=------------------------------------------------------------------------------------------------------------
  function formatarData(data)
  {

    if (data.includes(':')) {
      data = data.split(' ')[0];
    }

    let new_data = data.split('-');
    return `${new_data[2]}/${new_data[1]}/${new_data[0]}`;
  }


//=------------------------------------------------------------------------------------------------------------
  window.onload = function(e)
  {
    getEditUsers();
    // listSituation()
  }

//=------------------------------------------------------------------------------------------------------------
  function reload()
  {
    document.location.reload(true);
  }
</script>