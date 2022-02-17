<?php

namespace App\Models;

use Core\Model;

/**
 * User Model
 * 
 * @package App\Models
 * @author Mohammed-Aymen Benadra
 */
class User extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'Users';
    }
}
