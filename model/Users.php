<?php

class User
{

    private $nom = null;
    private $mail;
    private $role;
    private $password;
    

    public function __construct()
    {

    }

    public function getName()
    {
        return $this->nom;
    }
    public function getMail()
    {
        return $this->mail;
    }
    public function getRole()
    {
        return $this->role;
    }
    public function getPassword()
    {
        return $this->password;
    }

    public function setName($nom)
    {
        $this->nom = $nom;
    }

    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }

}

?>