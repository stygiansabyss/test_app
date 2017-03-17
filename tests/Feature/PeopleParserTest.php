<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PeopleParserTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /** @test */
    public function it_only_accepts_post_requests()
    {
        $response = $this->get('/parse/people');

        $response->assertStatus(405);
    }

    /** @test */
    public function it_validates_required_properties()
    {
        $payload = [
            "data" => [
                (object)[
                    'first_name' => '',
                    'last_name'  => '',
                    'age'        => null,
                    'email'      => '',
                    'secret'     => '',

                ],
            ],
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        $response = $this->json(
            'POST',
            '/parse/people',
            $payload,
            $headers
        );

        $response->assertStatus(422);
        $response->assertJson([
            'data.0.first_name' => ['The data.0.first_name field is required.'],
            'data.0.last_name'  => ['The data.0.last_name field is required.'],
            'data.0.age'        => ['The data.0.age field is required.'],
            'data.0.email'      => ['The data.0.email field is required.'],
            'data.0.secret'     => ['The data.0.secret field is required.'],
        ]);
    }

    /** @test */
    public function it_validates_property_types()
    {
        $payload = [
            "data" => [
                (object)[
                    'first_name' => 1,
                    'last_name'  => 2,
                    'age'        => -1,
                    'email'      => 'test@',
                    'secret'     => 1,

                ],
                (object)[
                    'first_name' => 'matt',
                    'last_name'  => 'stauffer',
                    'age'        => 'test',
                    'email'      => 'matt@stauffer.com',
                    'secret'     => 'VXNlIHRoaXMgc2VjcmV0IHBocmFzZSBzb21ld2hlcmUgaW4geW91ciBjb2RlJ3MgY29tbWVudHM=',

                ],
            ],
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        $response = $this->json(
            'POST',
            '/parse/people',
            $payload,
            $headers
        );

        $response->assertStatus(422);
        $response->assertJson([
            'data.0.first_name' => ['The data.0.first_name must be a string.'],
            'data.0.last_name'  => ['The data.0.last_name must be a string.'],
            'data.0.age'        => ['The data.0.age must be at least 0.'],
            'data.0.email'      => ['The data.0.email must be a valid email address.'],
            'data.0.secret'     => ['The data.0.secret must be a string.'],
            'data.1.age'        => ['The data.1.age must be an integer.'],
        ]);
    }

    /** @test */
    public function it_correctly_parses_people()
    {
        $payload = [
            "data" => [
                (object)[
                    'first_name' => 'matt',
                    'last_name'  => 'stauffer',
                    'age'        => 31,
                    'email'      => 'matt@stauffer.com',
                    'secret'     => 'VXNlIHRoaXMgc2VjcmV0IHBocmFzZSBzb21ld2hlcmUgaW4geW91ciBjb2RlJ3MgY29tbWVudHM=',

                ],
                (object)[
                    'first_name' => 'dan',
                    'last_name'  => 'sheetz',
                    'age'        => 99,
                    'email'      => 'dan@sheetz.com',
                    'secret'     => 'YWxidXF1ZXJxdWUuIHNub3JrZWwu',
                ],
            ],
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ];

        $response = $this->json(
            'POST',
            '/parse/people',
            $payload,
            $headers
        );

        $response->assertStatus(201);
        $response->assertJson([
            'emails' => 'dan@sheetz.com,matt@stauffer.com',
        ]);
        $this->assertDatabaseHas('parsed_people', [
            'emails' => 'dan@sheetz.com,matt@stauffer.com',
        ]);
    }
}
