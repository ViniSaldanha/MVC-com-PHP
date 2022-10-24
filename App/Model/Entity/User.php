<?php

namespace App\Model\Entity;
use \WilliamCosta\DatabaseManager\Database;

class User{

    public $id;

    public $name;

    public $email;

    public $senha;

    public function cadastrar(){
        $this->id = (new Database('usuarios'))-> insert([
            'nome' => $this->nome,
            'email'=> $this->email,
            'senha'=> $this->senha

        ]);
        return true;
    }

    public function atualizar(){
        return (new Database('usuarios'))-> update('id = ' .$this->id,[
            'nome' => $this->nome,
            'email'=> $this->email,
            'senha'=> $this->senha

        ]);

    }

    public function excluir(){
        return (new Database('usuarios'))-> delete('id = ' .$this->id,);
    }

    public static function getUserById($id){
        return self::getUsers('id = '.$id)->fetchObject(self::class);
    }

    public static function getUserByEmail($email){
        return self::getUsers('email = "'.$email.'"')->fetchObject(self::class);
    }

    public static function getUsers($where = null, $order = null, $limit = null, $field = '*'){
        return (new Database('usuarios'))->select($where,$order,$limit,$field);
    }
}