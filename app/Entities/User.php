<?php

namespace App\Entities;

use Myth\Auth\Entities\User as AuthUser;

class User extends AuthUser
{
    /**
     * Default attributes.
     * @var array
     */
    protected $attributes = [
        'firstname' => 'Guest',
        'lastname' => 'User',
    ];

    /**
     * Returns a full name: "first last"
     *
     * @return string
     */
    public function getName()
    {
        return trim(trim($this->attributes['firstname']) . ' ' . trim($this->attributes['lastname']));
    }
}