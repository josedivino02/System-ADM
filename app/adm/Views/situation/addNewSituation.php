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

if (isset($valorForm['name'])) { $name = $valorForm['name']; }

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
  <div class=" justify-content-center">
    <section class="card mb-4 centralizar" >
      <div class="card grid mt-3 text-center largura-50">
        <h1 class="text-color-black grid">Nova Situação</h1>
        <hr class="mb-1 line-grey">
        <span class="grid" id="msg"></span>
        <div class="card-body grid show-form-sit">
          <form action="" method="POST" id="form-add-situation">
            <div class="grid">
              <div class="form-group mb-4 search">
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name?>" placeholder="Carregando..."  required>
              </div>
              <div class="form-group mb-5 search  list-cor">
                <select class="form-control" name="cor" id="cor">
                  <option value="Carregando...">Carregando...</option>
                </select>
              </div>
                <button class="form-group col-6 button-success mb-3" type="submit" name="SendAddSituation" id="SendAddSituation" value="Cadastrar">Cadastrar</button>
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
  async function getColors()
  {
      const response = await axios.post('../colors/list-colors');

      if (response.data.error === 0) {
        const data = response.data.res;

        addFormSituation();
        preencherCor(data);
      }
    }

//=------------------------------------------------------------------------------------------------------------
   function addFormSituation()
   {
    const showFormSit =  document.querySelector(".show-form-sit");

    let html = `
      <form action="" method="POST" id="form-add-situation">
        <div class="grid">
            <div class="form-group mb-4 search ">
              <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group mb-5 search list-cor"></div>

            <button class="form-group col-4 button-success mb-3" type="submit" name="SendAddSituation" id="SendAddSituation" value="Cadastrar" onclick='incluideDataSituation()'>Cadastrar</button>
        </div>
      </form>
      <a href="<?php echo URLADM?>situacao/view-situation" class="btn btn-primary col-2" role="button" aria-pressed="true">Situações</a>
      `;

      showFormSit.innerHTML = html;
  }

//=------------------------------------------------------------------------------------------------------------
  function preencherCor(cores)
  {
    const color = document.querySelector('.list-cor');

      let html = `<select class="form-control" name="cor" id="cor">`;
      cores.map((cor) => {
      
      html += `<option class="form-control" value="${cor.id}" >${cor.name}</option>`;
      });

      html += `</select>`;

      color.innerHTML = html;
  }
//=------------------------------------------------------------------------------------------------------------
  async function incluideDataSituation()
  {
    const name = document.querySelector('#name').value;
    const cor = document.querySelector('#cor').value;

    const data = new FormData();

      data.append('name', name);
      data.append('cor', cor);

    const response = await axios.post('create-situation', data);
  }

//=------------------------------------------------------------------------------------------------------------
  function clearInputs()
  {
    const dados_situacoes = document.querySelector(".dados-situacoes");

    dados_situacoes.innerHTML = '';
  }

//=------------------------------------------------------------------------------------------------------------
  window.onload = function(e)
  {
    getColors();
  }

//=------------------------------------------------------------------------------------------------------------
  function reload()
  {
    document.location.reload(true);
  }
</script>
