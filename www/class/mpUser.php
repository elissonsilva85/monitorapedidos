<?php

/*
 * User.php (UTF-8)
 * Desenvolvido por Elisson Silva em 20/06/2014
 */

class mpUser {
    
    private $login;
    private $name;
    private $city;
    private $country;
    private $language;
    private $send_mail;
    private $mail_grouping;
    private $mail_format;
    private $last_access;
    private $last_navigation;
    private $active;
    private $confirmation;
    private $created_date;
    
    function __construct() {
        $paramsQtde = func_num_args();
        
        if( $paramsQtde == 1 )
        {
            $this->loadUser(func_get_arg(0));
        }
        else if( $paramsQtde > 1 )
        {
            $this->login = func_get_arg(0);
            $this->name = func_get_arg(1);
            $this->city = func_get_arg(2);
            $this->country = func_get_arg(3);
            $this->language = func_get_arg(4);
            $this->send_mail = func_get_arg(5);
            $this->mail_grouping = func_get_arg(6);
            $this->mail_format = func_get_arg(7);
            $this->last_access = func_get_arg(8);
            $this->last_navigation = func_get_arg(9);
            $this->active = func_get_arg(10);
            $this->confirmation = func_get_arg(11);
            $this->created_date = func_get_arg(12);
        }
    }
    
    private function loadUser($login) {
        $c = new mpConnect();
        
        $login = $c->escape($login);
        if( $c->query("select * from users where login = '{$login}'") )
        {
            $c->nextRow();
            $this->login = $c->getColumnValue("login");
            $this->name = $c->getColumnValue("name");
            $this->city = $c->getColumnValue("city");
            $this->country = $c->getColumnValue("country");
            $this->language = $c->getColumnValue("language");
            $this->send_mail = $c->getColumnValue("send_mail");
            $this->mail_grouping = $c->getColumnValue("mail_grouping");
            $this->mail_format = $c->getColumnValue("mail_format");
            $this->last_access = $c->getColumnValue("last_access");
            $this->last_navigation = $c->getColumnValue("last_navigation");
            $this->active = $c->getColumnValue("active");
            $this->confirmation = $c->getColumnValue("confirmation");
            $this->created_date = $c->getColumnValue("created_date");
        }
    }
    
    public function getActive() {
        return $this->active;
    }

    public function getName() {
        return $this->name;
    }

    public function getCity() {
        return $this->city;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function getSendMail() {
        return $this->send_mail;
    }
    
    public function getMailGrouping() {
        return $this->mail_grouping;
    }
    
    public function getMailFormat() {
        return $this->mail_format;
    }
    
    private function validateFields( $nome, $cidade, $pais, $idioma, $notifica_email, $agrupa_email, $formato_email ) { 
        
        if(trim($nome) == "")
            throw new Exception("Um valor para Nome é obrigatório.");

        if( preg_match("(pt-BR)",$idioma) == 0 ) 
            throw new Exception("Valor inválido no campo Idioma.");
        
        if( preg_match("(1|0)",$notifica_email) == 0 ) 
            throw new Exception("Valor inválido no campo Notificar por email.");

        if( preg_match("(1|0)",$agrupa_email) == 0 ) 
            throw new Exception("Valor inválido no campo Agrupar Email.");

        if( preg_match("(HTML|PLAIN)",$formato_email) == 0 ) 
            throw new Exception("Valor inválido no campo Formato do Email.");
    
    }
    
    public function updateUser( $nome, $senha_antiga, $senha_nova, $senha_valida, $cidade, $pais, $idioma, $notifica_email, $agrupa_email, $formato_email ) {
        
        $this->validateFields( $nome, $cidade, $pais, $idioma, $notifica_email, $agrupa_email, $formato_email );
        
        $c = new mpConnect();
        $sec = new mpSecurity();

        $login = $sec->getLogedUser();
        if( $c->query("select pass from users where login = '{$login}'") )
        {
            if($c->numberRows() == 1) 
            {    
                $campos = array( "city" => "'" . $c->escape(trim($cidade)) . "'",
                                 "country" => "'" . $c->escape(trim($pais)) . "'",
                                 "language" => "'" . $c->escape(trim($idioma)) . "'",
                                 "send_mail" => $notifica_email,
                                 "mail_grouping" => $agrupa_email,
                                 "mail_format" => "'" . $formato_email . "'" );
                
                $senha_antiga = $c->escape(trim($senha_antiga));
                $senha_nova   = $c->escape(trim($senha_nova));
                $senha_valida = $c->escape(trim($senha_valida));
                if( strlen($senha_antiga) > 0 )
                {
                    $c->nextRow();
                    if( md5($senha_antiga) != $c->getColumnValue("pass") )
                    {
                        throw new Exception("Senha antiga inválida.");                        
                    }
                    
                    if( strlen($senha_nova) == 0 )
                    {
                        throw new Exception("Um valor para Senha Nova é obrigatório.");
                    }
                    
                    if( $senha_nova != $senha_valida )
                    {
                        throw new Exception("As senhas novas não conferem.");                        
                    }
                    
                    $campos["pass"] = "md5('" . $senha_antiga . "')";
                }

                $query = $c->buildQuery( "UPDATE", 
                         "users", 
                         $campos,
                         "login = '{$login}'");
                         $c->query($query);
             }
            else
            {
                throw new Exception("Falha ao atualizar os dados (2).");
            }
        }
        else
        {
            throw new Exception("Falha ao atualizar os dados (1).");
        }
    }
    
    public function createUser( $login, $nome, $senha, $senha_valida, $cidade, $pais, $idioma, $notifica_email, $agrupa_email, $formato_email ) {

        if(trim($login) == "")
            throw new Exception("Um valor para E-mail é obrigatório.");

        $this->validateFields( $nome, $cidade, $pais, $idioma, $notifica_email, $agrupa_email, $formato_email );
        
        $c = new mpConnect();
        $login = $c->escape(trim($login));
        if( $c->query("select * from users where login = '{$login}'") )
        {
            if($c->numberRows() >= 1) 
            {                  
                throw new Exception("Este email já existe na nossa base de dados.");
            }
            
            $senha = $c->escape(trim($senha));
            $senha_valida = $c->escape(trim($senha_valida));
            if( strlen($senha) == 0 )
            {
                throw new Exception("Um valor para Senha é obrigatório.");
            }
            
            if( $senha != $senha_valida )
            {
                throw new Exception("As senhas não conferem.");
            }
            
            $query = $c->buildQuery( "INSERT", 
                                     "users", 
                                     array( "login" => "'" . $c->escape(trim($login)) . "'",
                                            "pass" => "md5('" . $c->escape(trim($senha)) . "')",
                                            "name" => "'" . $c->escape(trim($nome)) . "'",
                                            "city" => "'" . $c->escape(trim($cidade)) . "'",
                                            "country" => "'" . $c->escape(trim($pais)) . "'",
                                            "language" => "'" . $c->escape(trim($idioma)) . "'",
                                            "send_mail" => $notifica_email,
                                            "mail_grouping" => $agrupa_email,
                                            "mail_format" => "'" . $formato_email . "'",
                                            "active" => 1,
                                            "creation_date" => "now()" ) );
            $c->query($query);
            
            $_emailBody = "<p>Um novo usuário foi criado no sistema.</p>"
                        . "<table cellspacing='0' cellpadding='5'>"
                        . "<tr><td>Email:</td><td>{$c->escape(trim($login))}</td></tr>"
                        . "<tr><td>Nome:</td><td>{$c->escape(trim($nome))}</td></tr>"
                        . "<tr><td>Cidade:</td><td>{$c->escape(trim($cidade))}</td></tr>"
                        . "<tr><td>Pais:</td><td>{$c->escape(trim($pais))}</td></tr>"
                        . "<tr><td>Idioma:</td><td>{$c->escape(trim($idioma))}</td></tr>"
                        . "</table>";
            
            $mail = new mpMail();
            $mail->sendMail("novaconta@monitorapedidos.com.br", null, "MonitoraPedidos.com.br - Novo Usuário", $_emailBody);
        }
        else
        {
            throw new Exception("Falha ao criar os dados (3).");
        }
        
    }
    
}
