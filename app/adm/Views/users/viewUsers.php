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
          <h1 class="text-color-branco">Consultar Usuários</h1>
        </div>
      </div>
    </div>
    <div class="d-grid gap-2 d-md-flex mb-3 ">
      <a class="btn btn-info btn-sm" href="<?php URLADM ?>add-new-user" role="button">Cadastrar usuário</a>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 mb-4">
        <div class="card grid">
          <div class="card-body grid consulta">
            <h2 style="background-color: #6C5B7B;">Buscar o cliente</h2>
            <div class="grid">
              <div class="search">
                <a href="" target="_blank" hidden></a>
                <input type="text" placeholder="Procurar pelo nome" id="BuscaNome" name="BuscaNome" onkeyup="getUsuarios(event)">
                <div class="autocomplete"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-12 col-md-12 mb-4">
      <div class="card grid">
        <div class="card-body grid">
          <h4 style="background-color: #6C5B7B;">Resultado da busca:</h4>
          <table class="table table-striped">
            <thead class="tr">
              <th scope="col" class="back-th">ID</th>
              <th scope="col" class="back-th">Nome</th>
              <th scope="col" class="back-th">CPF</th>
              <th scope="col" class="back-th">E-Mail</th>
              <th scope="col" class="back-th"></th>
            </thead>
            <tbody class="tabela-usuarios"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<script>
 const search = document.querySelector(".search");
  const BuscaNome = document.getElementById("BuscaNome");
  const autocomplete = search.querySelector(".autocomplete");
  const icon = search.querySelector(".icon");
  let linkTag = search.querySelector("a");

//=------------------------------------------------------------------------------------------------------------
  const getUsuarios = async function(e)
  {
    const searchName = e.target.value;

    if (searchName.length >= 2) {
      const form = new FormData();

      form.append('name', searchName);

      const response = await axios.post('search-users', form);
      
      if (response.data.error === 0) {
        const usuarios = response.data.res;

        emptyArray = usuarios.map((usuario) => {
          return data = `<li>${usuario.nome} ${usuario.cpf} <input type="hidden" name="id" id="id" value="${usuario.id}" /> </li>`;
        });

        search.classList.add("active");

        showSuggestions(emptyArray);

        let allList = autocomplete.querySelectorAll("li");

        for (let i = 0; i < allList.length; i++) {
          allList[i].setAttribute("onclick", "select(this)");
        }
      }
    }
  }

//=------------------------------------------------------------------------------------------------------------
  function showSuggestions(list)
  {
    let listData;

    if (!list.length) {
      userValue = BuscaNome.value;
      listData = `<li>${userValue}</li>`;

    } else {
      listData = list.join('');
    }

    autocomplete.innerHTML = listData;
  }

//=------------------------------------------------------------------------------------------------------------
  function select(element)
  {
    let selectData = element.textContent;
    const id = element.querySelector('#id').value;

    clearInputs();

    BuscaNome.value = selectData;
    search.classList.remove("active");

    getListUsers(id);
  }

//=------------------------------------------------------------------------------------------------------------
  async function getListUsers(id)
  {
    const form = new FormData();

    form.append('id', id);

    const response = await axios.post('search-users', form);

    if (response.data.error === 0) {
      createTableUsuarios(response.data.res);
    } else {
      createVoidTable(".tabela-usuarios", response.data.msg)
    }
  }

//=------------------------------------------------------------------------------------------------------------
  function createVoidTable(classe, msg)
  {
    const table = document.querySelector(classe);

    const row = `
      <tr>
        <td><h5>${msg}</h5></td>
      </tr>
    `;

    table.innerHTML = row;
  }

//=------------------------------------------------------------------------------------------------------------
  function createTableUsuarios(nomes)
  {
    const tableUsuario = document.querySelector(".tabela-usuarios");
    let listUsers = '';

    $emptyArray = nomes.map((nome) => {
      const row = `
      <tr class='back-td'>
        <td>${nome.id}</td>
        <td>${nome.nome}</td>
        <td>${nome.cpf}</td>
        <td>${nome.email}</td>
        <td class="text-center">
          <span class="d-none d-md-block">
            <a href="<?php echo URLADM; ?>users/user-details?keyuser=${nome.id}" class="btn btn-outline-info btn-sm" style="float: right; margin: 0 5px;">Visualizar</a>
            <a href="<?php echo URLADM; ?>users/user-edit?keyuser=${nome.id}" class="btn btn-outline-warning btn-sm" style="float: right; margin: 0 5px;">Editar</a>
            <a href="<?php echo URLADM; ?>users/user-delet?keyuser=${nome.id}" class="btn btn-outline-danger btn-sm" style="float: right" onclick='return confirm("Tem certeza que deseja excluir o registro?")'>Deletar</a>
          </span>
        </td>
      </tr>
      `;

      listUsers += row;
    });

    tableUsuario.innerHTML = listUsers;
  }

//=------------------------------------------------------------------------------------------------------------
  function clearInputs()
  {
    const tableUsuario = document.querySelector(".tabela-usuarios");

    tableUsuario.innerHTML = '';
  }
</script>
</main>