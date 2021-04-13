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
        $this->markTestIncomplete('Process exits with return code 0, without providing a response');
        $response = $this->post('/report/check_in');

        $data = unserialize($response->content());
        $this->assertArrayHasKey("error", $data);
        $this->assertEquals("Serial is missing or empty", $data['error']);
    }

    public function testInvalidSerial()
    {
        $this->markTestIncomplete('Process exits with return code 0, without providing a response');
        $response = $this->post('/report/check_in', [
            'serial' => '_-?!@serial',
        ]);

        $data = unserialize($response->content());
        $this->assertArrayHasKey("error", $data);
        $this->assertEquals("Serial is missing or empty", $data['error']);
    }

    public function testHashCheckWithoutItems()
    {
        // $this->markTestIncomplete('Process exits with return code 0, without providing a response');
        $response = $this->post('/report/hash_check', [
            'serial' => 'ABCDEF12345',
        ]);

        $data = unserialize($response->content());
        $this->assertArrayHasKey("error", $data);
        $this->assertEquals("Items are missing", $data['error']);
    }

    public function testProcessReportData()
    {
        $payload = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>console_user</key>
	<string>munkireport</string>
	<key>long_username</key>
	<string>Munki Report</string>
	<key>runstate</key>
	<string>done</string>
	<key>runtype</key>
	<string>auto</string>
	<key>uid</key>
	<integer>505</integer>
	<key>uptime</key>
	<integer>59086</integer>
</dict>
</plist>';

        $hash = md5($payload);

        $response = $this->post('/report/hash_check', [
            'serial' => 'ABCDE01234',
            'items' => serialize(['reportdata' => ['data' => $payload, 'hash' => $hash]]),
            'hash' => $hash,
        ]);

        $response->assertOk();

        $response = $this->post('/report/check_in', [
            'serial' => 'ABCDE01234',
            'items' => serialize(['reportdata' => ['data' => $payload, 'hash' => $hash]]),
            'hash' => $hash,
        ]);

        $response->assertOk();
        $response->assertNoContent(200);
    }

    public function testProcessMachine()
    {
        $this->markTestIncomplete();
    }
}
