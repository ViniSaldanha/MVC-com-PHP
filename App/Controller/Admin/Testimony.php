<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Testimony as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;
use \App\Utils\CSV;

class Testimony extends Page{

    
    private static function getTestimonyItems($request,&$obPagination){
        $itens = '';

        $quantidadetotal = EntityTestimony::getTestimonies(null,null,null,'COUNT(*) as qtd')->fetchObject()->qtd;

        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        $obPagination = new Pagination($quantidadetotal, $paginaAtual, 5);


        $results = EntityTestimony::getTestimonies(null, 'id DESC',$obPagination->getLimit());
        

        while($obTestimony = $results->fetchObject(EntityTestimony::class)){

            $itens .= View::render('admin/modules/testimonies/item', [
                'id'         => $obTestimony->id,
                'nome'       => $obTestimony->nome,
                'mensagem'   => $obTestimony->mensagem,
                'data'       => date('d/m/Y H:i:s',strtotime($obTestimony->data))
            ]);

        }
        return $itens;

    }


    public static function getTestimonies($request){

        $content = View::render('admin/modules/testimonies/index',[
            'itens' => self::getTestimonyItems($request,$obPagination),
            'pagination' => parent::getPagination($request,$obPagination),
            'status'    => self::getStatus($request)
        ]);

        return parent::getPanel('Informações BA > Icomon',$content,'testimonies');
    }

    public static function getNewTestimony($request){

        //ler o csv;
        $dadosCSV = CSV::lerArquivo(__DIR__.'/files/teste-importacao.csv', true, ';');
        //$dadosCSV = "";

        //echo "<pre>";
        //print_r($dadosCSV);
        //echo "</pre>"; exit;

        $content = View::render('admin/modules/testimonies/form',[
            'title'     => 'Cadastrar BA',
            'nome'      => '',
            'mensagem'  => '',
            'status'    => '',
            'dadosCVS'  => $dadosCSV
           
        ]);

        return parent::getPanel('Cadastrar BA > Icomon',$content,'testimonies');
    }

    public static function setNewTestimony($request){
        $postVars = $request->getPostVars();

        $obTestimony = new EntityTestimony;
        $obTestimony->nome = $postVars['nome'] ?? '';
        $obTestimony->mensagem = $postVars['mensagem'] ?? '';
        $obTestimony->cadastrar();

        $request->getRouter()->redirect('/admin/testimonies/' .$obTestimony->id.'/edit?status=created');
    }

    private static function getStatus($request){

        $queryParams = $request->getQueryParams();

        if(!isset($queryParams['status'])) return '';

        switch ($queryParams['status']) {
            case 'created':
                return Alert::getSuccess('BA cadastrado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('BA atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('BA apagado com sucesso!');
                break;

        }

    }
    public static function getEditTestimony($request,$id){

        $obTestimony = EntityTestimony::getTestimonyById($id);

        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testmonies');
        }

        $content = View::render('admin/modules/testimonies/form',[
            'title'    => 'Editar BA',
            'nome'     => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem,
            'status'   => self::getStatus($request)
        ]);

        return parent::getPanel('Editar BA > Icomon',$content,'testimonies');
    }

    public static function setEditTestimony($request,$id){

        $obTestimony = EntityTestimony::getTestimonyById($id);

        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testmonies');
        }

        $postVars = $request->getPostVars();

        $obTestimony->nome = $postVars['nome'] ?? $obTestimony->nome;
        $obTestimony->mensagem = $postVars['mensagem'] ?? $obTestimony->mensagem;
        $obTestimony->atualizar();

        
        $request->getRouter()->redirect('/admin/testimonies/' .$obTestimony->id.'/edit?status=updated');
        

    }

    public static function getDeleteTestimony($request,$id){

        $obTestimony = EntityTestimony::getTestimonyById($id);

        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testmonies');
        }

        $content = View::render('admin/modules/testimonies/delete',[
            'nome'     => $obTestimony->nome,
            'mensagem' => $obTestimony->mensagem
        ]);

        return parent::getPanel('Excluir BA > Icomon',$content,'testimonies');
    }

    public static function setDeleteTestimony($request,$id){

        $obTestimony = EntityTestimony::getTestimonyById($id);

        if(!$obTestimony instanceof EntityTestimony){
            $request->getRouter()->redirect('/admin/testmonies');
        }

        $obTestimony->excluir();

        
        $request->getRouter()->redirect('/admin/testimonies?status=deleted');
        

    }
}