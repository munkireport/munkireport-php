<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArchiverControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     */
    public function testUpdate_status()
    {
        $this->markTestIncomplete();
//        $response = $this->post('/archiver/update_status/012345', [
//            'status' => '',
//        ]);
    }

    public function bulk_update_status()
    {
        $this->markTestIncomplete();
//        $response = $this->post('/archiver/update_status/012345', [
//            'days' => 30,
//        ]);
    }

    public function testIndex()
    {
        $this->markTestIncomplete();
    }

    // Business Units Configured: FALSE

    public function testAdminCanArchiveMachine()
    {

    }

    public function testManagerCanArchiveMachine()
    {

    }

    public function testArchiverCanArchiveMachine()
    {

    }

    public function testUserCannotArchiveMachine()
    {

    }

    // Business Units Configured: TRUE


}
