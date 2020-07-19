<?php

namespace Tests\Feature;

use App\Http\Controllers\ReportController;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testPassphraseMissing()
    {
        $this->markTestIncomplete();
        $response = $this->post('/report/check_in');

    }

    public function testInvalidPassphrase()
    {
        $this->markTestIncomplete();
        $response = $this->post('/report/check_in', [
            'passphrase' => 'ABCDEFGH',
        ]);
    }

    public function testSerialMissing()
    {
        $response = $this->post('/report/check_in');

        $data = unserialize($response->content());
        $this->assertArrayHasKey("error", $data);
        $this->assertEquals("Serial is missing or empty", $data['error']);
    }

    public function testInvalidSerial()
    {
        $response = $this->post('/report/check_in', [
            'serial' => '_-?!@serial',
        ]);

        $data = unserialize($response->content());
        $this->assertArrayHasKey("error", $data);
        $this->assertEquals("Serial is missing or empty", $data['error']);
    }

    public function testHashCheckWithoutItems()
    {
        $response = $this->post('/report/hash_check', [
            'serial' => 'ABCDEF12345',
        ]);
    }
}
