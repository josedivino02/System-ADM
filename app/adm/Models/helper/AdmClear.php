<?php

namespace Adm\Models\helper;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
//Classe para limpar
class AdmClear
{
    private static $format;
    private $url;

    //=------------------------------------------------------------------------------------------------------------
    //limpa a Url
    public function clearUrl()
    {
        //Eliminar as tag
        $this->url = strip_tags($this->url);
        //Eliminar os espaços em branco
        $this->url = trim($this->url);
        //Eliminar a barra no final
        $this->url = rtrim($this->url, "/");
        //Eliminar caracteres
        self::$format['substituir'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]?;:.,\\\'<>°ºª ';
        self::$format['substituido'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr-------------------------------------------------------------------------------------------------';
        $this->url = strtr(utf8_decode($this->url), utf8_decode(self::$format['substituir']), self::$format['substituido']);

        $this->url = str_replace(' ', '-', $this->url);
        $this->url = strtolower($this->url);

        return $this->url;
    }
}