<?php

namespace Tests\Feature;

use App\Http\Controllers\CommentController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;
use Tests\AuthorizationTestCase;

class CommentControllerTest extends AuthorizationTestCase
{
    use RefreshDatabase;

    /**
     * @todo Failing because the machine record isnt merged with the comment record as it was in v5.
     */
    public function testRetrieve()
    {
        $reportData = \App\ReportData::factory()->create();
        $comment = \App\Comment::factory()->create([
            'serial_number' => $reportData->serial_number,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/module/comment/retrieve/{$reportData->serial_number}/client");
        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'serial_number',
            'section',
            'user',
            'text',
            'html',
            'timestamp',
            'console_user',
            'long_username',
            'remote_ip',
            'uptime',
            'machine_group',
            'reg_timestamp',
            'uid',
            'archive_status',
        ]);
    }

    public function testSave()
    {
        $reportData = \App\ReportData::factory()->create();
        $data = [
            'serial_number' => $reportData->serial_number,
            'section' => 'client',
            'html' => '<p>this+be+a+<strong>comment</strong></p>',
            'text' => 'this+be+a+**comment**',
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/module/comment/save', $data);
        $response->assertOk();
        $response->assertJson([
            'status' => 'saved',
        ]);
    }

    public function testUpdate()
    {
        $this->markTestIncomplete("This action was never implemented in v5");
    }

    public function testDelete()
    {
        $this->markTestIncomplete("This action was never implemented in v5");
    }
}
