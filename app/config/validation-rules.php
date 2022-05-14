<?php

use App\Models\{Example};

/**
 * Validation Rules
 * 
 * * ? Available Validators:
 * ? - - required: Check if the field is required
 * ? - - string: Check if the field is a string
 * ? - - numeric: Check if the field is numeric
 * ? - - min: Check if the field is greater than or equal to the parameter provided
 * ? - - max: Check if the field is less than or equal to the parameter provided
 * ? - - int: Check if the field is an integer
 * ? - - float: Check if the field is a float
 * ? - - bool: Check if the field is a boolean
 * ? - - array: Check if the field is an array
 * ? - - object: Check if the field is an object
 * ? - - email: Check if the field is an email
 * ? - - file: Check if the field is a file
 * ? - - image: Check if the field is an image
 * ? - - url: Check if the field is a url
 * ? - - date: Check if the field is a date
 * ? - - date_format: Check if the field matches with the parameter provided
 * ? - - same: Check if the field matches with the parameter provided
 * ? - - matches: Check if the field matches with the parameter provided
 * ? - - ip: Check if the field is an ip address
 * ? - - exists: Check if the field exists in the database
 * ? - - unique: Check if the field is unique in the database
 * 
 * @package App\Config
 * @author Mohammed-Aymen Benadra
 */

$example = new Example();
$example2 = new Example();

$rules = [
    "example" => $example->getRequiredSchema(),
    "example2" => $example2->getRequiredSchema(),
];

$rules["all"] = $rules["example"] + $rules["example2"];

return $rules;
