<?php

namespace App\Providers;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use Revolution\Google\Sheets\Facades\Sheets;

class OnlyPassUserProvider implements UserProvider
{
    private $users = [];

    public function __construct()
    {
        $this->users = [];
        try {
            $users = Sheets::spreadsheet(env('EXHIBITORS_DAYS_ID'))
                ->sheet('Passwords!A2:D3')->all();

            foreach ($users as $user) {
                array_push($this->users, new User([
                    'id' => $user[0],
                    'name' => $user[1],
                    'home' => $user[2],
                    'password' => $user[3],
                ]));
            }
        }
        catch (\Exception $e) {
        }
    }

    public function retrieveById($identifier)
    {
        if ($identifier <= count($this->users) and $identifier > 0)
            return $this->users[$identifier - 1];
        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        return $this->retrieveById($identifier);
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
    }

    public function retrieveByCredentials(array $credentials)
    {
        foreach ($this->users as $user) {
            if ($this->validateCredentials($user, $credentials)) {
                return $user;
            }
        }

        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return Hash::check($credentials['password'], $user->getAuthPassword());
    }
}
