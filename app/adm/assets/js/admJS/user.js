//permitir retornar no formulario apos o erro
if(window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

//=------------------------------------------------------------------------------------------------------------

//calcular forca da senha
function passwordStrength() {
    var password = document.getElementById("password").value;
    var strength = 0;

    //medindo a forca da senha devido a quantidade de caracter digitado
    if((password.length >= 0) && (password.length <= 7)) {
        strength += 10;
    }else if (password.length > 7) {
        strength += 20;
    }
    //caracter minusculo
    if((password.length >= 6) && (password.match(/[a-z]+/))) {
        strength += 10;
    }
    //caracter maiusculo
    if((password.length >= 7) && (password.match(/[A-Z]+/))) {
        strength += 10;
    }

    if((password.length >= 7) && (password.match(/[0-9]+/))) {
        strength += 10;
    }
    //caracter especial
    if((password.length >= 8) && (password.match(/[_+-.,!@#$%^&*();/|<>]+/))) {
        strength += 20;
    }
    //numero sequencia iguais
    if(password.match(/([1-9]+)\1{1,}/)) {
        strength -= 25;
    }
    console.log(strength);

    viewStrengh(strength);
}

//visualizar a forca da senha
function viewStrengh(strength) {
    //imprimir força da senha
    if((strength > 10) && (strength < 30)){
        document.getElementById("msgViewStrengh").innerHTML = "<div class='progress'><div class='progress-bar bg-danger' role='progressbar' style='width: 25%' aria-valuenow='25' aria-valuemin='0' aria-valuemax='100'>Senha Fraca</div></div>";
    }else if((strength >= 30) && (strength < 50)){
        document.getElementById("msgViewStrengh").innerHTML = "<div class='progress'><div class='progress-bar bg-warning' role='progressbar' style='width: 50%' aria-valuenow='50' aria-valuemin='0' aria-valuemax='100'>Senha Média</div></div>";
    }else if((strength >= 50) && (strength < 70)){
        document.getElementById("msgViewStrengh").innerHTML = "<div class='progress'><div class='progress-bar bg-info' role='progressbar' style='width: 75%' aria-valuenow='75' aria-valuemin='0' aria-valuemax='100'>Senha Boa</div></div>";
    }else if(strength >= 70){
        document.getElementById("msgViewStrengh").innerHTML = "<div class='progress'><div class='progress-bar bg-success' role='progressbar' style='width: 100%' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100'>Senha Forte</div></div>";
    }else {
        document.getElementById("msgViewStrengh").innerHTML = "";
    }
}

//=------------------------------------------------------------------------------------------------------------

//Tela Login
const formLogin = document.getElementById("form-login");
//esperando uma ação
if (formLogin) {
formLogin.addEventListener("submit", async(e) => {

    document.getElementById("msg").innerHTML = "<div class='alert alert-info'>Credenciais corretas, acessando!</div>";

    var user = document.querySelector("#user").value;
    //verifica se está vazio
    if (user === "") {
        e.preventDefault();
        document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL100*: Necessário preencher o campo E-Mail!</div>";
        return;
    }

    var password = document.querySelector("#password").value;
    //verifica se está vazio
    if (password === "") {
        e.preventDefault();
        document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL110*: Necessário preencher o campo Senha!</div>";
        return;
    }
});
}

//=------------------------------------------------------------------------------------------------------------
//Tela reenviar email
const formNewConfEmail = document.getElementById("form-new-conf-email");
//esperando uma ação
if (formNewConfEmail) {
    formNewConfEmail.addEventListener("submit", async(e) => {

        var email = document.querySelector("#email").value;
        //verifica se está vazio
        if (email === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL120*: Necessário preencher o campo E-Mail!</div>";
            return;
        }else {
            document.getElementById("msg").innerHTML = "";
            return;
        }
});
}

//=------------------------------------------------------------------------------------------------------------
//Tela recuperar senha
const formRecoverPassword = document.getElementById("form-recover-password");
//esperando uma ação
if (formRecoverPassword) {
    formRecoverPassword.addEventListener("submit", async(e) => {

        var recoverpass = document.querySelector("#recoverpass").value;
        //verifica se está vazio
        if (recoverpass === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL130*: Necessário preencher o campo E-Mail!</div>";
            return;
        }else {
            document.getElementById("msg").innerHTML = "";
            return;
        }
});
}

//=------------------------------------------------------------------------------------------------------------
//Tela Novo usuário
const formNewUser = document.getElementById("form-new-user");
//esperando uma ação
    if (formNewUser) {
    formNewUser.addEventListener("submit", async(e) => {

        document.getElementById("msg").innerHTML = "<div class='alert alert-info'>USE100*: Novo usuário cadastrado! AGUARDE...</div>";
        //recebe o valor do campo
        var name = document.querySelector("#name").value;
        //verifica se está vazio
        if (name === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL140*: Necessário preencher o campo Nome!</div>";
            return;
        }

        var email = document.querySelector("#email").value;
        //verifica se está vazio
        if (email === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL150*: Necessário preencher o campo E-Mail!</div>";
            return;
        }

        var password = document.querySelector("#password").value;
        //verifica se está vazio
        if (password === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL160*: Necessário preencher o campo Senha!</div>";
            return;
        }

        //valida quantidade de caracter
        if (password.length < 6) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL170*: A senha deve conter no mínimo 6 caracteres!</div>";
            return;
        }

        //valida numero repetido
        if (password.match(/([1-9]+)\1{1,}/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL180*: A senha não deve ter número sequenciais repetido!</div>";
            return;
        }

        //valida deve conter letras
        if (!password.match(/([A-Za-z])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL190*: A senha deve conter pelo menos uma letra!</div>";
            return;
        }  

        //valida letra maiúscula
        if (!password.match(/([A-Z])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL200*: A senha deve conter pelo menos uma letra maiúscula!</div>";
            return;
        }  
        
        //valida letra minúscula
        if (!password.match(/([a-z])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL210*: A senha deve conter pelo menos uma letra minúscula!</div>";
            return;
        } 

        //valida caracter especial
        if (!password.match(/([_+-.,!@#$%^&*();/|<>"])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL220*: A senha deve conter pelo menos um caracter especial! (Ex: @#$%&)</div>";
            return;
        } 
    });
}

//=------------------------------------------------------------------------------------------------------------
//Tela adicionar usuário
const formAddUser = document.getElementById("form-add-user");
//esperando uma ação
    if (formAddUser) {
        formAddUser.addEventListener("submit", async(e) => {

        document.getElementById("msg").innerHTML = "<div class='alert alert-info'>USE110*: Novo usuário cadastrado! AGUARDE...</div>";
        //recebe o valor do campo
        var name = document.querySelector("#name").value;
        //verifica se está vazio
        if (name === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL230*: Necessário preencher o campo Nome!</div>";
            return;
        }

        var email = document.querySelector("#email").value;
        //verifica se está vazio
        if (email === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL240*: Necessário preencher o campo E-Mail!</div>";
            return;
        }

        var user = document.querySelector("#user").value;
        //verifica se está vazio
        if (user === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL250*: Necessário preencher o campo Usuário!</div>";
            return;
        }


        var password = document.querySelector("#password").value;
        //verifica se está vazio
        if (password === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL260*: Necessário preencher o campo Senha!</div>";
            return;
        }

        //valida quantidade de caracter
        if (password.length < 6) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL270*: A senha deve conter no mínimo 6 caracteres!</div>";
            return;
        }

        //valida numero repetido
        if (password.match(/([1-9]+)\1{1,}/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL280*: A senha não deve ter número sequenciais repetido!</div>";
            return;
        }

        //valida deve conter letras
        if (!password.match(/([A-Za-z])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL290*: A senha deve conter pelo menos uma letra!</div>";
            return;
        }  

        //valida letra maiúscula
        if (!password.match(/([A-Z])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL300*: A senha deve conter pelo menos uma letra maiúscula!</div>";
            return;
        }  
        
        //valida letra minúscula
        if (!password.match(/([a-z])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL310*: A senha deve conter pelo menos uma letra minúscula!</div>";
            return;
        } 

        //valida caracter especial
        if (!password.match(/([_+-.,!@#$%^&*();/|<>"])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL320*: A senha deve conter pelo menos um caracter especial! (Ex: @#$%&)</div>";
            return;
        } 
    });
}

//=------------------------------------------------------------------------------------------------------------
//Tela adicionar usuário
const formEditUser = document.getElementById("form-edit-user");
//esperando uma ação
    if (formEditUser) {
        formEditUser.addEventListener("submit", async(e) => {

        document.getElementById("msg").innerHTML = "<div class='alert alert-info'>USE130*: Usuário Atualizado com sucesso! AGUARDE...</div>";
        //recebe o valor do campo
        var name = document.querySelector("#name").value;
        //verifica se está vazio
        if (name === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL330*: Necessário preencher o campo Nome!</div>";
            return;
        }

        var email = document.querySelector("#email").value;
        //verifica se está vazio
        if (email === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL340*: Necessário preencher o campo E-Mail!</div>";
            return;
        }

        var user = document.querySelector("#user").value;
        //verifica se está vazio
        if (user === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL350*: Necessário preencher o campo Usuário!</div>";
            return;
        }


        var password = document.querySelector("#password").value;
        //verifica se está vazio
        if (password === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL360*: Necessário preencher o campo Senha!</div>";
            return;
        }

        //valida quantidade de caracter
        if (password.length < 6) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL370*: A senha deve conter no mínimo 6 caracteres!</div>";
            return;
        }

        //valida numero repetido
        if (password.match(/([1-9]+)\1{1,}/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL380*: A senha não deve ter número sequenciais repetido!</div>";
            return;
        }

        //valida deve conter letras
        if (!password.match(/([A-Za-z])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL390*: A senha deve conter pelo menos uma letra!</div>";
            return;
        }  

        //valida letra maiúscula
        if (!password.match(/([A-Z])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL400*: A senha deve conter pelo menos uma letra maiúscula!</div>";
            return;
        }  
        
        //valida letra minúscula
        if (!password.match(/([a-z])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL410*: A senha deve conter pelo menos uma letra minúscula!</div>";
            return;
        } 

        //valida caracter especial
        if (!password.match(/([_+-.,!@#$%^&*();/|<>"])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL420*: A senha deve conter pelo menos um caracter especial! (Ex: @#$%&)</div>";
            return;
        } 
    });
}

//=------------------------------------------------------------------------------------------------------------
//Tela adicionar usuário
const formMyEditUser = document.getElementById("form-my-edit-user");
//esperando uma ação
    if (formMyEditUser) {
        formMyEditUser.addEventListener("submit", async(e) => {

        document.getElementById("msg").innerHTML = "<div class='alert alert-info'>USE140*: Seu usuário foi atualizado com sucesso! AGUARDE...</div>";
        //recebe o valor do campo
        var name = document.querySelector("#name").value;
        //verifica se está vazio
        if (name === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL430*: Necessário preencher o campo Nome!</div>";
            return;
        }

        var email = document.querySelector("#email").value;
        //verifica se está vazio
        if (email === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL440*: Necessário preencher o campo E-Mail!</div>";
            return;
        }

        var user = document.querySelector("#user").value;
        //verifica se está vazio
        if (user === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL450*: Necessário preencher o campo Usuário!</div>";
            return;
        }


        var password = document.querySelector("#password").value;
        //verifica se está vazio
        if (password === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL460*: Necessário preencher o campo Senha!</div>";
            return;
        }

        //valida quantidade de caracter
        if (password.length < 6) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL470*: A senha deve conter no mínimo 6 caracteres!</div>";
            return;
        }

        //valida numero repetido
        if (password.match(/([1-9]+)\1{1,}/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL480*: A senha não deve ter número sequenciais repetido!</div>";
            return;
        }

        //valida deve conter letras
        if (!password.match(/([A-Za-z])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL490*: A senha deve conter pelo menos uma letra!</div>";
            return;
        }  

        //valida letra maiúscula
        if (!password.match(/([A-Z])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL500*: A senha deve conter pelo menos uma letra maiúscula!</div>";
            return;
        }  
        
        //valida letra minúscula
        if (!password.match(/([a-z])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL510*: A senha deve conter pelo menos uma letra minúscula!</div>";
            return;
        } 

        //valida caracter especial
        if (!password.match(/([_+-.,!@#$%^&*();/|<>"])/)) {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-warning'>VAL520*: A senha deve conter pelo menos um caracter especial! (Ex: @#$%&)</div>";
            return;
        } 
    });
}

//=------------------------------------------------------------------------------------------------------------
//IMAGEM
const formMyImgtUser = document.getElementById("form-my-img-user");
//esperando uma ação
    if (formMyImgtUser) {
        formMyImgtUser.addEventListener("submit", async(e) => {

        //recebe o valor do campo
        var new_img = document.querySelector("#new_img").value;
        //verifica se está vazio
        if (new_img === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL530*: Necessário preencher a Imagem!</div>";
            return;
        }else {
            document.getElementById("msg").innerHTML = "";
            return;
        }
    });
}

//=------------------------------------------------------------------------------------------------------------
function inputFileValImg()
{
    //recebe o valor do campo
    var new_img = document.querySelector("#new_img");

    var filePath = new_img.value;
    var extensoesPerm = /(\.jpg|\.jpeg|\.png)$/i;

    if (!extensoesPerm.exec(filePath)) {
        new_img.value = '';
        document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL530*: Necessário selecionar uma imagem JPG ou PNG!</div>";
        return;
    }else {
        previewImg(new_img)
        document.getElementById("msg").innerHTML = "";
        return;
    }
}

//=------------------------------------------------------------------------------------------------------------
//IMAGEM
const formImgtUser = document.getElementById("form-img-user");
//esperando uma ação
    if (formImgtUser) {
        formImgtUser.addEventListener("submit", async(e) => {

        //recebe o valor do campo
        var new_img = document.querySelector("#new_img").value;
        //verifica se está vazio
        if (new_img === "") {
            e.preventDefault();
            document.getElementById("msg").innerHTML = "<div class='alert alert-info'>VAL530*: Necessário preencher a Imagem!</div>";
            return;
        }else {
            document.getElementById("msg").innerHTML = "";
            return;
        }
    });
}
//=------------------------------------------------------------------------------------------------------------
//previo da IMG
    function previewImg(new_img)
    {
        //verificar se é uma imagem (arquivo)
        if ((new_img.files) && (new_img.files[0])) {
            //FileReader() - ler o conteúdo dos arquivos
            var reader = new FileReader()
            //onload
            reader.onload = function(e) {
                document.getElementById("preview-span-img-text").innerHTML = "<div class='alert alert-success col-4 centered mb-2'>Imagem selecionada</div>"
                document.getElementById("preview-span-img").innerHTML = "<img class='img-modal' src='"+ e.target.result +"' style='width: 50%; alt='foto'>"
            }
        }

        //readAsDataURL - Retorna os dados do formato blob como uma URL de dados - BLOB representa um arquivo
        reader.readAsDataURL(new_img.files[0])
    }