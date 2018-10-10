<?php

/*
 * mpConfig.php (UTF-8)
 * Desenvolvido por Elisson Silva em 20/06/2014
 */

class mpConfig {

    public $bd_host = "mysql.monitorapedidos.com.br";
    public $bd_port = 3306;
    public $bd_pass = "logos1821";
    public $bd_user = "monitorapedido";
    public $bd_dbname = "monitorapedido";
    
    /*
    public $bd_host = "localhost";
    public $bd_port = 3306;
    public $bd_pass = "elisson";
    public $bd_user = "elisson";
    public $bd_dbname = "monitorapedido";
    */
}

if(!function_exists('classAutoLoader'))
{
    function classAutoLoader($class){
        $classFile='./'.$class.'.php';
        //echo "(1)".$classFile;
        if( is_file($classFile) )
        {
          if( !class_exists($class) )
            include $classFile;
          return;
        }

        $classFile='./class/'.$class.'.php';
        //echo "(2)".$classFile;
        if( is_file($classFile) )
        {
          if( !class_exists($class) )
            include $classFile;
          return;
        }

        $classFile='./class/Companies/'.$class.'.php';
        //echo "(3)".$classFile;
        if( is_file($classFile) )
        {
          if( !class_exists($class) )
            include $classFile;
          return;
        }
    }
}
spl_autoload_register('classAutoLoader');

?>
