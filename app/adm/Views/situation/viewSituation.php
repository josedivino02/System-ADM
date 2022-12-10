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
          <h1 class="text-color-branco">Consultar Situação</h1>
        </div>
      </div>
    </div>
    <div class="d-grid gap-2 d-md-flex mb-3 ">
      <a class="btn btn-info btn-sm" href="<?php URLADM ?>view-create-situation" role="button">Cadastrar Situação</a>
    </div>

    <div class="col-lg-12 col-md-12 mb-4">
      <div class="card grid">
        <div class="card-body grid">
          <h4 style="background-color: #6C5B7B;">Situação</h4>
          <table class="table table-striped">
            <thead class="tr">
              <th scope="col" class="back-th">ID</th>
              <th scope="col" class="back-th">Nome</th>
              <th scope="col" class="back-th">cor</th>
              <th scope="col" class="back-th"></th>
            </thead>
            <tbody class="tabela-situacao"></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<script>
//=------------------------------------------------------------------------------------------------------------
 async function getListSituation()
  {
    const response = await axios.post('list-situation');

    if (response.data.error === 0) {
      createtableSituacaos(response.data.res);
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
  async function createtableSituacaos(situacoes)
  {
    const tableSituacao = document.querySelector(".tabela-situacao");
    let listSituation = '';

    $emptyArray = situacoes.map((situacao) => {
      const row = `
      <tr class='back-td'>
        <td>${situacao.id}</td>
        <td>${situacao.situacao}</td>
        <td><span class="badge alert-${situacao.color}">${situacao.color}</span></td>
        <td class="text-center">
          <span class="d-none d-md-block">
            <a href="<?php echo URLADM; ?>situacao/view-edit-situation?keysituation=${situacao.id}" class="btn btn-outline-warning btn-sm" style="float: right; margin: 0 5px;">Editar</a>
            <a href="<?php echo URLADM; ?>situacao/delete-situation?keysituation=${situacao.id}" class="btn btn-outline-danger btn-sm" style="float: right" onclick='return confirm("Tem certeza que deseja excluir o registro?")'>Deletar</a>
          </span>
        </td>
      </tr>
      `;

      listSituation += row;
    });

    tableSituacao.innerHTML = listSituation;
  }

//=------------------------------------------------------------------------------------------------------------
  function clearInputs()
  {
    const tableSituacao = document.querySelector(".tabela-situacao");

    tableSituacao.innerHTML = '';
  }

//=------------------------------------------------------------------------------------------------------------
  window.onload = function(e)
  {
    getListSituation()
  }

</script>
</main>