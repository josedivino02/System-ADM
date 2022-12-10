<?php

namespace Adm\Models\helper;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
//Conversão da URL
class AdmSlug
{
    private $urlSlugController;
    private $urlSlugMetodo;

    private $text;
    private static $format;
//=------------------------------------------------------------------------------------------------------------

    //Converte a url da controller (tudo para minusculo, converte traço pelo espaço, retira o espaço em branco, primeira letra em maiuscula de cada palavra)
    public function slugController($slugController)
    {

        $this->urlSlugController = $slugController;
        //Converter para minusculo
        $this->urlSlugController = strtolower($this->urlSlugController);
        //converter o traço para espaço em branco
        $this->urlSlugController = str_replace("-", " ", $this->urlSlugController);
        //converter a primeira letra em maiusculo de cada palavra
        $this->urlSlugController = ucwords($this->urlSlugController);
        //retirar o espaço em branco
        $this->urlSlugController = str_replace(" ", "", $this->urlSlugController);
        //retorna as tratativas

        return $this->urlSlugController;
    }

//=------------------------------------------------------------------------------------------------------------
    //Converte a url do metodo (tudo para minusculo, converte traço pelo espaço, retira o espaço em branco, primeira letra em maiuscula de cada palavra)
    public function slugMetodo($slugMetodo)
    {
        //converte a url do metodo
        $this->urlSlugMetodo = $this->slugController($slugMetodo);
        //primeira letra da primeira palavra em minusculo
        $this->urlSlugMetodo = lcfirst($this->urlSlugMetodo);

        //retorna a tratativa
        return $this->urlSlugMetodo;
    }

//=------------------------------------------------------------------------------------------------------------
    public function slug($text = null): string
    {
        $this->text = $text;
        //substituir para o substituido
        self::$format['substituir'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]?;:,\\\'<>°ºª';
        self::$format['substituido'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr-----------------------------------------------------------------------------------------------';
        //para fazer a substituição
        $this->text = strtr(utf8_decode($this->text), utf8_decode(self::$format['substituir']), self::$format['substituido']);
        $this->text = str_replace(" ", "-", $this->text);
        $this->text = str_replace(array('-----', '----', '---', '--'), '-', $this->text);
        $this->text = strtolower($this->text);

        return $this->text;
    }
}


