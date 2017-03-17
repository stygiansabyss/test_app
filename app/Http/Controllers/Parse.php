<?php

namespace App\Http\Controllers;

use App\Http\Requests\Person;
use App\Models\Parsed\Person as PersonModel;
use App\Transformers\Parsed\Person as ParsedPersonTransformer;
use App\Transformers\Person as PersonTransformer;

class Parse extends Controller
{
    /**
     * Validate and handle the request.
     *
     * @param \App\Http\Requests\Person $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function people(Person $request)
    {
        // Gather the data from the request and parse it as needed.
        $parsedPeople = $this->getParsedPeople($request);
        $emails       = $parsedPeople->implode('email', ',');

        // Persist the data to the database.
        $person = $this->storePerson($emails, $parsedPeople);

        // Return a successful response with the new object.
        return response()->json(ParsedPersonTransformer::transform($person), 201);
    }

    /**
     * Get the parsed results from the request.
     *
     * @param \App\Http\Requests\Person $request
     *
     * @return \Illuminate\Support\Collection
     */
    private function getParsedPeople(Person $request)
    {
        // Add the full name to each person and order by their age, oldest to youngest.
        return collect($request->json('data'))
            ->map(function ($person) {
                return PersonTransformer::transform($person);
            })
            ->sortByDesc('age')
            ->values();
    }

    /**
     * Store the comma separated emails and full person objects in the database.
     *
     * @param string     $emails
     * @param collection $people
     *
     * @return boolean|PersonModel
     */
    private function storePerson($emails, $people)
    {
        return PersonModel::create(compact('emails', 'people'));
    }
}
