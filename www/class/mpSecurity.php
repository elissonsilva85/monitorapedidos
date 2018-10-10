<?php
session_start();
/*
 * Security.php (UTF-8)
 * Desenvolvido por Elisson Silva em 20/06/2014
 */

class mpSecurity {
    
    public function autenticate($user, $pass) {
        
        $c = new mpConnect();

        $user  = $c->escape($user);
        $pass  = $c->escape($pass);
        $query = "select * from users where login = '{$user}' and pass = md5('{$pass}') and active = 1";
        if($c->query($query))
        {
            if($c->numberRows() == 1) {  
                $row = $c->nextRow();
                $_SESSION['auth'] = 1;
                $_SESSION['user_login'] = $row['login'];
                $_SESSION['user_name'] = $row['name'];
                
                $query = $c->buildQuery( "UPDATE", "users", 
                                         array( "last_access" => "now()" ),
                                         "login = '{$user}'");
                $c->query($query);
                
                return true;
            } else {
                return "Falha na autenticação";
            }
        }
        else
        {
            return "Usuário ou senha inválidos";
        }
        
    }
    
    public function logoff() {
        
        session_unset();
        //$_SESSION['auth'] = 0;
        
    }
    
    public function isAuth() {
        
        return (isset($_SESSION['auth']) && $_SESSION['auth'] === 1);
        
    }
    
    public function redirectHomePage()
    {
        header("Location: home.php");
        die;
    }
    
    public function redirectLoginPage()
    {
        header("Location: index.php");
        die;
    }
    
    public function getLogedUser() {
        return $_SESSION['user_login'];
    }
    
    public function getLogedUserName() {
        return $_SESSION['user_name'];
    }
        
}
