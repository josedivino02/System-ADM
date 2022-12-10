<?php
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
  
//=------------------------------------------------------------------------------------------------------------
//verifica se existe uma mensagem e depois destroi
if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
  }
?>
<!-- =------------------------------------------------------------------------------------------------------------ -->
<!-- Visualização de usuarios -->
<style>
  .grid {
    background-color: #6C5B7B !important;
    margin-bottom: 10px;
    width: 100%;
    border-radius: 6px;
    position: relative;
    box-shadow: 0px 4px 10px 0px rgba(0, 0, 0, 0.12);
  }

  .back-th{
    background-color: #C06C84!important;
    border-bottom-width: 3px !important;
  }

  .back-td{
    background-color: #f0f8ff;
    color: #000;
    border-bottom-width: 3px !important;
  }

  
</style>

<main>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="mb-2">
          <h1 class="text-color-branco">Consultar detalhes do Usuário</h1>
        </div>
      </div>
    </div>

    <div class="col-lg-12 col-md-12 mb-4">
      <div class="card grid">
        <div class="card-body grid">
          <h3 style="background-color: #6C5B7B;">Usuário: <span class="nome_usuario" style="background-color: #6C5B7B;">[NOME DO USUÁRIO]</span></h3>
          <table class="table table-striped">
            <thead class="tr">
              <th scope="col" class="back-th">ID</th>
              <th scope="col" class="back-th">Nome</th>
              <th scope="col" class="back-th">CPF</th>
              <th scope="col" class="back-th">E-Mail</th>
              <th scope="col" class="back-th">Apelido</th>
              <th scope="col" class="back-th">status</th>
              <th scope="col" class="back-th">Cadastrado</th>
              <th scope="col" class="back-th">Editado</th>
            </thead>
            <tbody class="tabela-usuario">
              <tr class='back-td'>
                <td id="user_id"      >Aguardando...</td>
                <td id="user_nome"    >Aguardando...</td>
                <td id="user_cpf"     >Aguardando...</td>
                <td id="user_email"   >Aguardando...</td>
                <td id="user_nick"    >Aguardando...</td>
                <td id="user_status"  >Aguardando...</td>
                <td id="user_create"  >Aguardando...</td>
                <td id="user_modified">Aguardando...</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<script>

//=------------------------------------------------------------------------------------------------------------
  async function getUserDetails()
  {
    const keyuser = getParameter('keyuser');
    const nome_usuario = document.querySelector('.nome_usuario');

    const form = new FormData();
    form.append('id', keyuser);

    const response = await axios.post('user-details-data', form);

    if (response.data.error === 0) {
      const data = response.data.res;

      nome_usuario.innerText = data[0].nome;

      preencherDadosUsuarios(data[0]);
    }

  }

//=------------------------------------------------------------------------------------------------------------
   // Funções de preenchimento
   function preencherDadosUsuarios(dados_usuarios)
   {
    const input_id        = dados_usuarios.id;
    const input_nome      = dados_usuarios.nome;
    const input_cpf       = dados_usuarios.cpf;
    const input_email     = dados_usuarios.email;
    const input_nick      = dados_usuarios.nickname;
    const input_status    = dados_usuarios.status;
    const input_cor       = dados_usuarios.cor;
    const input_create    = formatarData(dados_usuarios.created);
    
    const input_modified  = dados_usuarios.modified;

    var modified = '';

    input_modified === null ? modified = 'nunca modificado' : modified = formatarData(dados_usuarios.modified);

    const tableUsuario = document.querySelector(".tabela-usuario");

    let html = `
      <tr class='back-td'>
        <td>${input_id}</td>
        <td>${input_nome}</td>
        <td>${input_cpf}</td>
        <td>${input_email}</td>
        <td>${input_nick}</td>
        <td><span class="badge bg-${input_cor}">${input_status}</span></td>
        <td>${input_create}</td>
        <td>${modified}</td>
      </tr>
      `;

      tableUsuario.innerHTML = html;
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
    const tableUsuario = document.querySelector(".tabela-usuario");

    tableUsuario.innerHTML = '';
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
    getUserDetails();
  }
</script>
</main>