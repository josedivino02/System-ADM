<?php
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
?>
    

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/af-2.4.0/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/date-1.1.2/kt-2.7.0/r-2.3.0/sb-1.3.4/sl-1.4.0/sr-1.1.1/datatables.min.js"></script>

    <script src="<?php echo URLADM ?>app/adm/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo URLADM ?>app/adm/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo URLADM ?>app/adm/assets/js/api.js"></script>
    <script src="<?php echo URLADM ?>app/adm/assets/js/jquery.min.js"></script>

    <script src="<?php echo URLADM ?>app/adm/assets/js/admJS/user.js"></script>
    <script src="<?php echo URLADM ?>app/adm/assets/js/admJS/only.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.1.3/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  </body>
</html>
