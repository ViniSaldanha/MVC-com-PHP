<?php

namespace App\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class Testimony{

    public $id;

    public $nome;

    public $mensagem;

    public $data;

    public function cadastrar(){

        $this->data = date('Y-m-d H:i:s');

        $this->id = (new Database('depoimentos'))->insert([
            'nome'       => $this->nome,
            'mensagem'   => $this->mensagem,
            'data'       => $this->data
        ]);

        return true;
    }

    public function atualizar(){


        return (new Database('depoimentos'))->update('id = '.$this->id,[
            'nome'       => $this->nome,
            'mensagem'   => $this->mensagem
        ]);
    }

    
    public function excluir(){


        return (new Database('depoimentos'))->delete('id = '.$this->id);
    }

    public static function getTestimonyById($id){
        return self::getTestimonies('id = '.$id)->fetchObject(self::class);
    }

    public static function getTestimonies($where = null, $order = null, $limit = null, $field = '*'){
        return (new Database('depoimentos'))->select($where,$order,$limit,$field);
    }
}