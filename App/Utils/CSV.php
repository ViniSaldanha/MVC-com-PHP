<?php

namespace App\Utils;

class CSV{

    public static function lerArquivo($arquivo, $cabecalho = true, $delimitador = ','){
        if(!file_exists($arquivo)){
            die("Arquivo não encontrado!\n");
        }

        $dados = [];

        $csv = fopen($arquivo, 'r');

        $cabecalhoDados = $cabecalho ? fgetcsv($csv, 0, $delimitador) : [];
       
        while($linha = fgetcsv($csv,0,$delimitador)){
       
            $dados[] = $cabecalho ? array_combine($cabecalhoDados, $linha) : $linha;

        }
        print_r($dados);

        fclose($csv);
        
        return $dados;
    }
}