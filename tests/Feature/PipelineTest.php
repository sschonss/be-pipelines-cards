<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;

class PipelineTest extends TestCase
{
    use DatabaseMigrations;

    public function test_create_pipeline_without_authentication()
    {
        $response = $this->post('/api/pipelines', [
            'name' => 'Pipeline 1',
            'description' => 'Description 1',
        ]);
        $response->assertStatus(401);
    }

    public function test_create_pipeline_with_authentication()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api:jwt')->post('/api/pipelines', [
            'name' => 'Pipeline 1',
            'description' => 'Description 1',
        ]);
        $response->assertStatus(201);
    }

    public function test_create_pipeline_with_invalid_data()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api:jwt')->post('/api/pipelines', [
            'name' => 'Pipeline 1',
            'description' => 'Description 1',
        ]);
        $response->assertStatus(400);
    }

    public function test_get_all_pipelines()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api:jwt')->get('/api/pipelines');
        $response->assertStatus(200);
    }

    public function test_get_pipeline_by_id()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api:jwt')->get('/api/pipelines/1');
        $response->assertStatus(200);
    }

    public function test_update_pipeline()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api:jwt')->put('/api/pipelines/1', [
            'name' => 'Pipeline 1',
            'description' => 'Description 1',
        ]);
        $response->assertStatus(200);
    }

    public function test_update_pipeline_with_invalid_data()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api:jwt')->put('/api/pipelines/1', [
            'name' => 'Pipeline 1',
            'description' => 'Description 1',
        ]);
        $response->assertStatus(400);
    }

    public function test_delete_pipeline()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api:jwt')->delete('/api/pipelines/1');
        $response->assertStatus(200);
    }

    public function test_delete_pipeline_with_invalid_id()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api:jwt')->delete('/api/pipelines/1');
        $response->assertStatus(400);
    }

    public function test_delete_pipeline_without_authentication()
    {
        $response = $this->delete('/api/pipelines/1');
        $response->assertStatus(401);
    }

    public function test_get_pipeline_by_id_without_authentication()
    {
        $response = $this->get('/api/pipelines/1');
        $response->assertStatus(401);
    }


}
