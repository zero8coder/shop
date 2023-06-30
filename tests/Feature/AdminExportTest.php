<?php

namespace Tests\Feature;

use App\Jobs\ExportTaskJob;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AdminExportTest extends TestCase
{
    public function test_admin_export()
    {
        Queue::fake();
        $this->signInAdmin();
        $response = $this->json('post', route('admin.v1.admins.exportTask'), []);
        $response->assertStatus(200);
        Queue::assertPushed(ExportTaskJob::class);
    }
}
