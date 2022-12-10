<?php

namespace Adm\Models\helper;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
//Classe para limpar
class AdmFormat
{
    private $doc;

    //=------------------------------------------------------------------------------------------------------------
    //mascara censura
    public function maskDoc($doc)
    {
        $this->doc = $doc;

        if (strlen($this->doc) === 18) {
            return substr_replace($this->doc, '*.*/**', 3, -3);
        }

        return substr_replace($this->doc, '*****', 3, -3);
    }
}