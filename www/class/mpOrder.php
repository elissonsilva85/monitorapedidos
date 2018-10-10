<?php

/*
 * Project.php (UTF-8)
 * Desenvolvido por Elisson Silva em 26/08/2014
 */

class mpOrder {
    private $order_id;
    private $shop_id;
    private $seller_id;
    private $order_no;
    private $order_status;
    private $description;
    private $purchase_date;
    private $delivery_date_prev;
    private $delivery_date_real;
    private $due_date;
    private $invoice_value;
    private $freight_value;
    private $position;
    private $created_date;
    private $created_by;
    
    function __construct() {
        $paramsQtde = func_num_args();
        
        if( $paramsQtde == 1 )
        {
            $this->loadTracking(func_get_arg(0));
        }
        else if( $paramsQtde > 1 )
        {
            $this->order_id = func_get_arg(0);
            $this->shop_id = func_get_arg(1);
            $this->seller_id = func_get_arg(2);
            $this->order_no = func_get_arg(3);
            $this->order_status = func_get_arg(4);
            $this->description = func_get_arg(5);
            $this->purchase_date = func_get_arg(6);
            $this->delivery_date_prev = func_get_arg(7);
            $this->delivery_date_real = func_get_arg(8);
            $this->due_date = func_get_arg(9);
            $this->invoice_value = func_get_arg(10);
            $this->freight_value = func_get_arg(11);
            $this->position = func_get_arg(12);
            $this->created_date = func_get_arg(13);
            $this->created_by = func_get_arg(14);
        }
    }
    
    private function loadTracking($codigo) {
        
        $c = new mpConnect();
        
        $codigo = $c->escape($codigo);
        if( $c->query("select * from orders where order_id = {$codigo}") )
        {
            $c->nextRow();
            $this->order_id = $c->getColumnValue("order_id");
            $this->shop_id = $c->getColumnValue("shop_id");
            $this->seller_id = $c->getColumnValue("seller_id");
            $this->order_no = $c->getColumnValue("order_no");
            $this->order_status = $c->getColumnValue("order_status");
            $this->description = $c->getColumnValue("description");
            $this->purchase_date = $c->getColumnValue("purchase_date");
            $this->delivery_date_prev = $c->getColumnValue("delivery_date_prev");
            $this->delivery_date_real = $c->getColumnValue("delivery_date_real");
            $this->due_date = $c->getColumnValue("due_date");
            $this->invoice_value = $c->getColumnValue("invoice_value");
            $this->freight_value = $c->getColumnValue("freight_value");
            $this->position = $c->getColumnValue("position");
            $this->created_date = $c->getColumnValue("created_date");
            $this->created_by = $c->getColumnValue("created_by");
        }
    }
    
    public function getId() {
        return $this->order_id;
    }
    
    public function getNumber() {
        return $this->order_no;
    }

    public function getDescription() {
        return $this->description;
    }
    
    public function getValue() {
        return $this->invoice_value;
    }

    public function getCreator() {
        if(is_object($this->created_by) )
            return $this->created_by;
        
        $this->created_by = new mpUser($this->created_by);
        return $this->created_by;
    }
    
}
