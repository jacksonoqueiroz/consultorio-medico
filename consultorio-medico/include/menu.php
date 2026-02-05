<?php

include_once ('conexao/conexao.php');

//ABRIR ESSA PÁGINA QUANDO LOGADO
if ((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))) {
   $_SESSION['msg'] = '<div class="alert alert-danger" role="alert">É necessário realizar o Login, para acessar o sistema!</div>';  
   header("Location: login.php");
}
?>
<!-- CÓDIGO AO CLICAR NO LINK E FECHA NOS DISPOSITIVOS MÓVEIS  -->
            <script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function(){
                   var links = document.querySelectorAll(".navbar-nav li a:not([href='#'])");
                   for(var x=0; x<links.length; x++){
                      links[x].onclick = function(){
                         document.querySelector("button.navbar-toggler").click();
                      }
                   }
                });
            </script>
            <!-- FIM  -->


            <!-- STYLE PERFIL USUÁRIO-->

               <style>
                  img {
                     width: 30px; 
                     height: 30px;  
                     border-radius: 50%;
                  }
                  span{
                     color: white;
                     padding-left: 10px;
                  }
                  .menu-perfil{
                     margin-left: 500px;
                     margin-top: 10px;
                  }

                  @media screen and (max-width: 480px){

                  .menu-perfil{
                     margin-left: 0;
                  }
                  }
                  @media screen and (min-width: 481px) and (max-width: 768px){
                  .menu-perfil{
                     margin-left: 0;
                  }     
                  }
               </style>

            <!-- FIM STYLE PERFIL USUÁRIO-->

            <!-- NAVEGADOR ------------------------------------>

            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">

        <a class="navbar-brand" href="home.php">
            <i class="bi bi-heart-pulse-fill"></i> Consultório Médico
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuMedico">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuMedico">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="home">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="consultas">
                        <i class="bi bi-calendar-check"></i> Consultas
                    </a>
                </li>

            </ul>

            <span class="navbar-text text-white me-3">
                <i class="bi bi-person-badge"></i>
                <?= $_SESSION['nome'] ?? 'Médico' ?>
            </span>

            <a href="<?php echo URL ?>sair" class="btn btn-outline-light btn-sm">
                <i class="bi bi-box-arrow-right"></i> Sair
            </a>
        </div>

    </div>
</nav>


            <!-- FIM NAVEGADOR --------------------------->