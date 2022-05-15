<?php

namespace App\Models;

use Core\Model;

/**
 * Example Model
 * 
 * @package App\Models
 * @uses Core\Model Core Model
 * @author Mohammed-Aymen Benadra
 */
class Example extends Model
{
    public function __construct()
    {
        parent::__construct([
            'exampleField' => 'required|numeric|min:1|max:10|int|float|bool|array|object|email|file|image|url|date|date_format|same|matches|ip|exists:table,field|unique:table,field|regex:pattern'
        ]);
        $this->table = 'Examples';
    }
}
