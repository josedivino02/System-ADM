<?php
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
?>
<main>
  <nav class="navbar navbar-expand-lg bg-dark" >
    <div class="container-fluid">
      <!-- logo -->
      <!-- <a class="navbar-brand" href="#">
        <img src="img/cruz.png" alt="logo_divine">
      </a> -->
      <!-- menu sanduiche -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#id-menu-sanduiche" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" id="botao_menu"></span>
      </button>

      <!-- Menu -->
      <div class="collapse navbar-collapse" id="id-menu-sanduiche">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link text-light active" aria-current="page" href="<?php echo URLADM ?>dashboard/index">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-light" href="<?php echo URLADM ?>users/view-profile">Perfil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-light" href="<?php echo URLADM ?>users/view-users">Usuários</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-light" href="<?php echo URLADM ?>situacao/view-situation">Situação</a>
          </li>
          <li class="nav-item dropdown">
            <a  class="nav-link text-light" href="<?php echo URLADM ?>logout/index">Sair</a>
          </li>
        </ul>
        <!-- buscar -->
        <!-- <form class="d-flex">
          <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search">
          <button class="btn btn-outline-light" type="submit">buscar</button>
        </form> -->
      </div>
    </div>
  </nav>
</main>