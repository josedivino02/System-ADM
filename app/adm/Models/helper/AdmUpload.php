<?php

namespace Adm\Models\helper;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
//Classe para email's
class AdmUpload
{
    private $result;

    private $directory;
    private $tmpName;
    private $name;

//=------------------------------------------------------------------------------------------------------------
//retorna o resultado
    public function getResult()
    {
        return $this->result;
    }

//=------------------------------------------------------------------------------------------------------------
    //upload de imagens (diretorio, arquivo temporario e nome do arquivo)
    public function upload($directory, $tmpName, $name): void
    {
        $this->directory = $directory;
        $this->tmpName = $tmpName;
        $this->name = $name;

        if ($this->valDiretory()) {
            $this->uploadFile();
        }else {
            $this->result = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //validar diretorio
    private function valDiretory(): bool
    {
        //verifica o diretorio
        if ((!file_exists($this->directory)) && (!is_dir($this->directory))) {
            mkdir($this->directory, 0755);
            if ((!file_exists($this->directory)) && (!is_dir($this->directory))) {

                $_SESSION['msg'] = "<div class='alert alert-danger'>UPL100: Erro no Upload da imagem!</div>";
                return false;
            }else {
                return true;
            }
        }else {
            return true;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //upload de arquivos
    private function uploadFile()
    {
        //se consegue fazer o upload
        if (move_uploaded_file($this->tmpName, $this->directory .$this->name)) {
            $this->result = true;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>UPL110: Erro no Upload da imagem!</div>";
            $this->result = false;
        }
    }
}
