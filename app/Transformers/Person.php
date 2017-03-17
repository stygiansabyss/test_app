<?php

namespace App\Transformers;

class Person
{
    public static function transform($person)
    {
        $fullName = collect([
            $person['first_name'],
            $person['last_name'],
        ])
            ->filter()
            ->implode(' ');

        return [
            'first_name' => array_get($person, 'first_name'),
            'last_name'  => array_get($person, 'last_name'),
            'name'       => $fullName,
            'age'        => (int)array_get($person, 'age'),
            'email'      => array_get($person, 'email'),
            'secret'     => array_get($person, 'secret'),
        ];
    }
}
