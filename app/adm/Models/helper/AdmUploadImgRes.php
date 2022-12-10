<?php

namespace Adm\Models\helper;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
//Classe para upload de imagens
class AdmUploadImgRes
{
    private $result;

    private $directory;
    private $tmpName;
    private $name;
    
    private $imageData;
    private $width;
    private $height;
    private $newImage;
    private $imgResize;
//=------------------------------------------------------------------------------------------------------------
//retorna o resultado
    public function getResult()
    {
        return $this->result;
    }

//=------------------------------------------------------------------------------------------------------------
    //upload de imagens e redimencionando
    public function  uploadRes($imageData, $directory, $name, $width, $height): void
    {
        $this->imageData = $imageData;
        $this->directory = $directory;
        $this->name        = $name;
        $this->width = $width;
        $this->height = $height;

        $this->valDiretory();

    }

//=------------------------------------------------------------------------------------------------------------
    //validar diretorio
    private function valDiretory(): void
    {
        //verifica o diretorio
        if ((!file_exists($this->directory)) && (!is_dir($this->directory))) {
            $this->createDir();

        }elseif (!file_exists($this->directory)) {
            $this->createDir();
        }else {
            $this->uploadFile();
        }
    }

//=------------------------------------------------------------------------------------------------------------
     //upload de arquivos
     private function uploadFile()
     {

        switch ($this->imageData['type']) {
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->uploadFileJpeg();
                break;
            case 'image/png':
            case 'image/x-png':
                $this->uploadFilePng();
                break;
            case 'image/gif':
                $this->uploadFileGif();
                break;
            default:
                $_SESSION['msg'] = "<div class='alert alert-danger'>UPL120: Necessário selecionar imagem com extensão JPEG ou PNG!</div>";
                $this->resultado = false;
        }
     }

//=------------------------------------------------------------------------------------------------------------
    private function createDir()
    {
        mkdir($this->directory, 0755);

        if (!file_exists($this->directory)) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>UPL130: Erro no Upload da imagem!</div>";
            $this->result = false;
        }else {
            $this->uploadFile();
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //imagem JPG
    private function uploadFileJpeg()
    {

        $this->newImage = imagecreatefromjpeg($this->imageData['tmp_name']);

        $this->redImg();

        //envia para servidor
        if (imagejpeg($this->imgResize, $this->directory .$this->name, 100)) {
            $_SESSION['msg'] = "<div class='alert alert-success'>UPL140: Upload da Imagem realizado com sucesso!</div>";
            $this->result = true;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>UPL150: Erro no Upload da imagem!</div>";
            $this->result = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //imagem PNG
    private function uploadFilePng()
    {
        $this->newImage = imagecreatefrompng($this->imageData['tmp_name']);

        $this->redImg();

        //envia para servidor
        if (imagepng($this->imgResize, $this->directory .$this->name, 1)) {
            $_SESSION['msg'] = "<div class='alert alert-success'>UPL160: Upload da Imagem realizado com sucesso!</div>";
            $this->result = true;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>UPL170: Erro no Upload da imagem!</div>";
            $this->result = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    //imagem GIF
    private function uploadFileGif()
    {

        $this->newImage = imagecreatefromgif($this->imageData['tmp_name']);

        //envia para servidor
        if (imagegif($this->imgResize, $this->directory .$this->name)) {
            $_SESSION['msg'] = "<div class='alert alert-success'>UPL180: Upload da Imagem realizado com sucesso!</div>";
            $this->result = true;
        }else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>UPL190: Erro no Upload da imagem!</div>";
            $this->result = false;
        }
    }

//=------------------------------------------------------------------------------------------------------------
    private function redImg()
    {
        //obter a largura da imagem
        $widthOriginal = imagesx($this->newImage);
        $heightOriginal = imagesy($this->newImage);

        //criar nova imagem com as dimensões novas
        $this->imgResize = imagecreatetruecolor($this->width, $this->height);

        //redimencionar a imagem enviada para o tamanho padrao q deixei
        imagecopyresampled($this->imgResize, $this->newImage, 0, 0, 0, 0, $this->width, $this->height,$widthOriginal, $heightOriginal);

    }
}
