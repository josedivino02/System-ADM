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
$img = "";

if (isset($valorForm['img'])) { $name = $valorForm['img']; }

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
          <h3 class="text-color-black grid">Editar Minha Imagem: <span class="nome_usuario" style="background-color: #6C5B7B;">[NOME DO USUÁRIO]</span></h3>
          <hr class="mb-1 line-grey">
          <span class="grid" id="msg"></span>
          <div class="card-body grid usuario-img">
            <form action="" method="POST" id="form-edit-user" enctype="multipart/form-data">
              <div class="grid">
                <div class="form-group mb-4 search">
                  <input type="file" class="form-control" id="new_img" name="new_img" value="<?php echo $img?>"  placeholder="Carregando..." required>
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
  async function getUsersImg()
  {
      const id_user_img = <?php echo $_SESSION['usuario_id']; ?>;
      const nome_usuario = document.querySelector('.nome_usuario');

      const form = new FormData();
      form.append('id', id_user_img);

      const response = await axios.post('user-img-edit', form);
      if (response.data.error === 0) {
        const data = response.data.res;

        nome_usuario.innerText = data[0].nome;

        preencherUsuarioImg(data[0]);
      }

    }

//=------------------------------------------------------------------------------------------------------------
   // Funções de preenchimento
   function preencherUsuarioImg(usuario_img)
   {

    const input_id        = usuario_img.id;
    const input_img      = usuario_img.img;

    const usuario_imagem =  document.querySelector(".usuario-img");

    let html = `
      <form action="" method="POST" id="form-my-img-user" enctype="multipart/form-data">
        <div class="grid">
          <label class="grid" style="font-size: 1.3rem; color: aliceblue;">Imagem recomendada: 980 x 1200</label>
            <div class="form-group mb-4 search col-12">
              <input accept="image/*" type="file" class="form-control" id="new_img" name="new_img" placeholder="Imagem" onchange="inputFileValImg()" required>
              <input type="hidden" class="form-control" id="id" name="id" value="${input_id}" placeholder="ID">
            </div>
            <hr class="mb-1 line-grey">
            <div class="grid mt-3">
              <span class="grid" id="preview-span-img-text"></span>
              <span class="grid" id="preview-span-img"></span>
            </div>
            <hr class="mb-1 line-grey">
            <button class="form-group col-2 button-success mb-3" type="submit" name="SendEditUserImg" id="SendEditUserImg" value="Atualizar" onclick='updateUserImg(${input_id})'>Atualizar</button>
        </div>
      </form>
      `;

      usuario_imagem.innerHTML = html;
  }

//=------------------------------------------------------------------------------------------------------------
  async function updateUserImg(input_id) {
    const id        = document.querySelector('#id').value;
    const img       = document.querySelector('#new_img').files[0];

    const data = new FormData();

      data.append('id', id);
      data.append('new_img', img);
      
    const response = await axios.post('update-user-img', data);
    // reload();
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
    const usuario_imagem = document.querySelector(".usuario-img");

    usuario_imagem.innerHTML = '';
  }

//=------------------------------------------------------------------------------------------------------------
  window.onload = function(e)
  {
    getUsersImg();
  }

//=------------------------------------------------------------------------------------------------------------
  function reload() {
    document.location.reload(true);
  }
</script>