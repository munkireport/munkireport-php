<?php

namespace Tests;

use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\RefreshesSchemaCache;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class GraphqlTestCase extends AuthorizationTestCase
{
    use CreatesApplication;
    use RefreshesSchemaCache;
    use MakesGraphQLRequests;

    protected function setUp(): void
    {
        parent::setUp();
    }
}
