<?php

namespace App\Transformers\Parsed;

class Person
{
    public static function transform($person)
    {
        return [
            'id'         => $person->id,
            'emails'     => $person->emails,
            'people'     => $person->people,
            'created_at' => (string)$person->created_at,
        ];
    }
}
