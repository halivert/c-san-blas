<?php

namespace App;

use App\Providers\OnlyPassUserProvider;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'id', 'home', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Test only purposes
    public static function find($id)
    {
        if (is_array($id)) {
            return $this->findMany($id);
        }

        $provider = new OnlyPassUserProvider();

        return $provider->retrieveById($id);
    }

    public function hasPermission($viewName)
    {
        $permissions = [
            'markedHouses' => [
                'exhibitors' => false,
                'marked-houses' => true,
            ],
            'exhibitors' => [
                'exhibitors' => true,
                'marked-houses' => true,
            ]
        ];

        if (array_key_exists($this->name, $permissions))
            if (array_key_exists($viewName, $permissions[$this->name]))
                return $permissions[$this->name][$viewName];

        return false;
    }
}
