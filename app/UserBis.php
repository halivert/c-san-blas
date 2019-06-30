<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $id;

    protected $name;

    protected $password;

    protected $remember_token;

    protected $home_route;

    protected $hidden = [
        'password'
    ];

    public function __construct($id, array $params)
    {
        $this->id = $id;
        $this->name = $params[0];
        $this->home_route = $params[1];
        $this->password = $params[2];
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function setHomeRoute($value)
    {
        $this->home_route = $value;
    }

    public function getHomeRoute()
    {
        return $this->home_route;
    }
}
