<?php

/*
 * Project.php (UTF-8)
 * Desenvolvido por Elisson Silva em 20/06/2014
 */

class mpProject {
    private $codigo;
    private $nome;
    private $inicio;
    private $fim;
    private $responsavel;
    private $ativo;
    private $criacao;
    private $criador;
    
    private $projectList;
            
    function __construct() {
        $this->projectList = "";
        $paramsQtde = func_num_args();
        
        if( $paramsQtde == 1 )
        {
            $this->loadProject(func_get_arg(0));
        }
        else if( $paramsQtde > 1 )
        {
            $this->codigo = func_get_arg(0);
            $this->nome = func_get_arg(1);
            $this->inicio = func_get_arg(2);
            $this->fim = func_get_arg(3);
            $this->responsavel = func_get_arg(4);
            $this->ativo = func_get_arg(5);
            $this->criacao = func_get_arg(6);
            $this->criador = func_get_arg(7);
        }
    }
    
    private function loadProject($codigo) {
        
        $c = new mpConnect();
        
        $codigo = $c->escape($codigo);
        if( $c->query("select * from projeto where cod_projeto = {$codigo}") )
        {
            $c->nextRow();
            $this->codigo = $c->getColumnValue("cod_projeto");
            $this->nome = $c->getColumnValue("nome");
            $this->inicio = $c->getColumnValue("inicio");
            $this->fim = $c->getColumnValue("fim");
            $this->responsavel = new mpUser($c->getColumnValue("usuario_responsavel"));
            $this->ativo = $c->getColumnValue("ativo");
            $this->criacao = $c->getColumnValue("criacao_data");
            $this->criador = $c->getColumnValue("criacao_usuario");
        }
        
    }
    
    public function getProjectList() {
        
        if( is_array($this->projectList) )
        {
            return $this->projectList;
        }
        
        $c = new mpConnect();
        $this->projectList = [];

        if( $c->query("select * from projeto order by nome") )
        {
            while( $c->nextRow() )
            {
                $this->projectList[] = new mpProject( $c->getColumnValue("cod_projeto"),
                                                      $c->getColumnValue("nome"),
                                                      $c->getColumnValue("inicio"),
                                                      $c->getColumnValue("fim"),
                                                      $c->getColumnValue("usuario_responsavel"),
                                                      $c->getColumnValue("ativo"),
                                                      $c->getColumnValue("criacao_data"),
                                                      $c->getColumnValue("criacao_usuario") );

            }
        }
        
        return $this->projectList;
    }    
    
    public function saveProject( $codigo, $nome, $inicio, $fim, $responsavel, $ativo ) {
        
        $c = new mpConnect();
       
        $nome = $c->escape($nome);
        $inicio = $c->escape($inicio);
        $inicio = ( $inicio == "" ? "null" : "STR_TO_DATE('{$inicio}','%d/%m/%Y')" );
        $fim = $c->escape($fim);
        $fim = ( $fim == "" ? "null" : "STR_TO_DATE('{$fim}','%d/%m/%Y')" );
        $responsavel = $c->escape($responsavel);
        $ativo = $c->escape($ativo);
        if( $codigo == 0 )
        {
            $sec = new mpSecurity();
            $criador = $sec->getLogedUser();
            $c->query("insert into projeto (nome, inicio, fim, usuario_responsavel, ativo, criacao_data, criacao_usuario) values ('{$nome}', {$inicio}, {$fim}, {$responsavel}, {$ativo}, NOW(), {$criador} )");
        }
        else
        {
            $c->query("update projeto set nome = '{$nome}', inicio = {$inicio}, fim = {$fim}, usuario_responsavel = {$responsavel}, ativo = {$ativo} where cod_projeto = {$codigo}");
        }
        
        return true;        
    }
    
    public function getCodigo() {
        return $this->codigo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getInicio() {
        return $this->inicio;
    }

    public function getFim() {
        return $this->fim;
    }

    public function getResponsavel() {
        if(is_numeric($this->responsavel) )
        {
            $this->responsavel = new mpUser($this->responsavel);
        }
        
        return $this->responsavel;
    }

    public function getAtivo() {
        return $this->ativo;
    }

    public function getCriacao() {
        return $this->criacao;
    }

    public function getCriador() {
        if(is_numeric($this->criador) )
        {
            $this->criador = new mpUser($this->criador);
        }
        
        return $this->criador;
    }


}
