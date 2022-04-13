<?php

use Phalcon\Mvc\Model;

class Users extends Model
{
    public $id;
    public $name;
    public $email;
    public $username;
    public $password;
    public $accesstoken;
    public $refreshtoken;

}