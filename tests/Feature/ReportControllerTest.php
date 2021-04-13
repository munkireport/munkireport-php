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
        $payload = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>SMC_version_system</key>
	<string>2.33f12</string>
	<key>_name</key>
	<string>hardware_overview</string>
	<key>boot_rom_version</key>
	<string>429.80.1.0.0</string>
	<key>buildversion</key>
	<string>19H524</string>
	<key>computer_name</key>
	<string>munkireport</string>
	<key>cpu</key>
	<string>Intel(R) Core(TM) i7-6700K CPU @ 4.00GHz</string>
	<key>cpu_arch</key>
	<string>x86_64</string>
	<key>cpu_type</key>
	<string>Quad-Core Intel Core i7</string>
	<key>current_processor_speed</key>
	<string>4 GHz</string>
	<key>hostname</key>
	<string>munkireport.local</string>
	<key>l2_cache_core</key>
	<string>256 KB</string>
	<key>l3_cache</key>
	<string>8 MB</string>
	<key>machine_model</key>
	<string>iMac17,1</string>
	<key>machine_name</key>
	<string>iMac</string>
	<key>number_processors</key>
	<integer>4</integer>
	<key>os_version</key>
	<string>10.15.7</string>
	<key>packages</key>
	<integer>1</integer>
	<key>physical_memory</key>
	<string>32 GB</string>
	<key>platform_UUID</key>
	<string>8B56F84D-7167-495B-B00C-F5EB8E48E3CE</string>
	<key>platform_cpu_htt</key>
	<string>htt_enabled</string>
	<key>serial_number</key>
	<string>ABCDEF12345</string>
</dict>
</plist>';

        $hash = md5($payload);

        $response = $this->post('/report/hash_check', [
            'serial' => 'ABCDEF12345',
            'items' => serialize(['machine' => ['data' => $payload, 'hash' => $hash]]),
            'hash' => $hash,
        ]);

        $response->assertOk();

        $response = $this->post('/report/check_in', [
            'serial' => 'ABCDEF12345',
            'items' => serialize(['machine' => ['data' => $payload, 'hash' => $hash]]),
            'hash' => $hash,
        ]);

        $response->assertOk();
        $response->assertNoContent(200);
    }
}
