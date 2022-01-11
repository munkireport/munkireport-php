<?php

namespace Tests;

use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\ClearsSchemaCache;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class GraphqlTestCase extends AuthorizationTestCase
{
    use CreatesApplication;
    use ClearsSchemaCache;
    use MakesGraphQLRequests;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootClearsSchemaCache();
    }
}
