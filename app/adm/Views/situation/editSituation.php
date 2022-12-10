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
          <h3 class="text-color-branco">Editar</h3>
          <hr class="mb-1 line-grey">
        </div>
      </div>
    </div>
  <div class="justify-content-center">
    <section class="card mb-4 centralizar" >
      <div class="card grid mt-3 text-center largura-50">
        <h3 class="text-color-black grid">Editar Situação</h3>
        <hr class="mb-1 line-grey">
        <span class="grid" id="msg"></span>
        <div class="card-body grid dados-situacoes">
          <form action="" method="POST" id="form-edit-situation">
            <div class="grid">
              <div class="form-group mb-4 search">
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $name?>" placeholder="Carregando..." required>
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
  async function getEditSituation()
  {
      const keysituation = getParameter('keysituation');

      const form = new FormData();
      form.append('id', keysituation);

      const response = await axios.post('list-situation', form);

      if (response.data.error === 0) {
        const data = response.data.res;

        preencherDadosSituacao(data[0]);
      }
    }

//=------------------------------------------------------------------------------------------------------------
   // Funções de preenchimento
   function preencherDadosSituacao(situacoes)
   {
    const input_nome      = situacoes.situacao;
    const input_id        = situacoes.id;

    const dados_situacoes =  document.querySelector(".dados-situacoes");

    let html = `
      <form action="" method="POST" id="form-edit-situation">
        <div class="grid">
            <div class="form-group mb-4 search ">
              <input type="text" class="form-control" id="name" name="name" value="${input_nome}"required>
              <input type="hidden" class="form-control" id="id" name="id" value="${input_id}" placeholder="ID">
            </div>
            <button class="form-group col-4 button-success mb-3" type="submit" name="SendEditSituacao" id="SendEditSituacao" value="Atualizar" onclick='updateDataSituation(${input_id})'>Atualizar</button>
        </div>
      </form>
      `;

      dados_situacoes.innerHTML = html;
  }

//=------------------------------------------------------------------------------------------------------------
  async function updateDataSituation(input_id)
  {
    const id        = document.querySelector('#id').value;
    const name      = document.querySelector('#name').value;

    const data = new FormData();

      data.append('id', id);
      data.append('name', name);

    const response = await axios.post('alter-situation', data);
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
    const dados_situacoes = document.querySelector(".dados-situacoes");

    dados_situacoes.innerHTML = '';
  }

//=------------------------------------------------------------------------------------------------------------
  window.onload = function(e)
  {
    getEditSituation();
  }

//=------------------------------------------------------------------------------------------------------------
  function reload()
  {
    document.location.reload(true);
  }
</script>