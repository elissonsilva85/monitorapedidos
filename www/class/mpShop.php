<?php

/*
 * Project.php (UTF-8)
 * Desenvolvido por Elisson Silva em 21/07/2014
 */

class mpShop {
    private $shop_id;
    private $name;
    private $description;
    private $link_site;
    private $link_order;
    private $link_seller;
    private $active;
    private $created_date;
    private $created_by;
    private $approved_date;
    private $approved_by;
    
    function __construct() {
        $paramsQtde = func_num_args();
        
        if( $paramsQtde == 1 )
        {
            $this->loadShop(func_get_arg(0));
        }
        else if( $paramsQtde > 1 )
        {
            $this->shop_id = func_get_arg(0);
            $this->name = func_get_arg(1);
            $this->description = func_get_arg(2);
            $this->link_site = func_get_arg(3);
            $this->link_order = func_get_arg(4);
            $this->link_seller = func_get_arg(5);
            $this->active = func_get_arg(6);
            $this->created_date = func_get_arg(7);
            $this->created_by = func_get_arg(8);
            $this->approved_date = func_get_arg(9);
            $this->approved_by = func_get_arg(10);
        }
    }
    
    private function loadShop($codigo) {
        
        $c = new mpConnect();
        
        $codigo = $c->escape($codigo);
        if( $c->query("select * from shops where shop_id = {$codigo}") )
        {
            $c->nextRow();
            $this->shop_id = $c->getColumnValue("shop_id");
            $this->name = $c->getColumnValue("name");
            $this->description = $c->getColumnValue("description");
            $this->link_site = $c->getColumnValue("link_site");
            $this->link_order = $c->getColumnValue("link_order");
            $this->link_seller = $c->getColumnValue("link_seller");
            $this->active = $c->getColumnValue("active");
            $this->created_date = $c->getColumnValue("created_date");
            $this->created_by = $c->getColumnValue("created_by");
            $this->approved_date = $c->getColumnValue("approved_date");
            $this->approved_by = $c->getColumnValue("approved_by");
        }
    }
    
    public function getLinkSite()
    {
        return $this->link_site;
    }
    
    public function getLinkOrder($orderNo)
    {
        $link = $this->link_order;
        $link = str_replace("{orderNo}", $orderNo, $link);
        
        return $link;
    }
    
    public function getLinkSeller($sellerNo)
    {
        $link = $this->link_seller;
        $link = str_replace("{sellerNo}", $sellerNo, $link);
        
        return $link;
    }
    
    public function getId() {
        return $this->shop_id;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getActivated() {
        return $this->active;
    }

    public function getCreator() {
        if(is_object($this->created_by) )
            return $this->created_by;
        
        $this->created_by = new mpUser($this->created_by);
        return $this->created_by;
    }
    
}
