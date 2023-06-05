<?php

namespace Tests\Feature\Graphql;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\GraphqlTestCase;

class BusinessUnitsTest extends GraphqlTestCase
{
    use RefreshDatabase;

    public function testQueriesBusinessUnits(): void
    {
        $response = $this->actingAs($this->user)->graphQL(/** @lang GraphQL */ '
            {
                businessUnits(first: 10) { 
                    data {
                        name
                        address
                    }
                    paginatorInfo {
                        currentPage
                        lastPage
                    }
                }
            }
        ');

        $response->assertJson([
            'data' => [
                'businessUnits' => [
                    'data' => [],
                    'paginatorInfo' => [
                        'currentPage' => 1,
                        'lastPage' => 1,
                    ]
                ]
            ]
        ]);
    }
}
