<?php

namespace App\Models;

use Core\Model;

/**
 * Post Model
 * 
 * @package App\Models
 * @author Mohammed-Aymen Benadra
 */
class Post extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'Posts';
    }
}
