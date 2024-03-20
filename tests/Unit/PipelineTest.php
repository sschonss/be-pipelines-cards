<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\Pipeline;

class PipelineTest extends TestCase
{
    use DatabaseMigrations;

    private $user_name = 'User Test';
    private $user_email = 'user@test.com';
    private $user_password = '123456789';

    public function setUp(): void
    {
        parent::setUp();

        $user = new User();
        $user->name = $this->user_name;
        $user->email = $this->user_email;
        $user->password = $this->user_password;
        $user->save();
    }


    public function test_create_pipeline()
    {
        $pipeline = new Pipeline();
        $pipeline->name = 'Teste';
        $pipeline->description = 'Teste';
        $pipeline->user_id = 1;
        $pipeline->save();

        $this->assertTrue($pipeline->id > 0);

        $pipeline->delete();

    }

    public function test_update_pipeline()
    {
        $pipeline = new Pipeline();
        $pipeline->name = 'Teste';
        $pipeline->description = 'Teste';
        $pipeline->user_id = 1;
        $pipeline->save();

        $pipeline->name = 'Teste 2';
        $pipeline->save();

        $this->assertEquals('Teste 2', $pipeline->name);

        $pipeline->delete();

    }

    public function test_delete_pipeline()
    {
        $pipeline = new Pipeline();
        $pipeline->name = 'Teste';
        $pipeline->description = 'Teste';
        $pipeline->user_id = 1;
        $pipeline->save();

        $pipeline->delete();

        $this->assertNull(Pipeline::find($pipeline->id));
    }

    public function test_associate_user_to_pipeline()
    {
        $user = User::first();
        $pipeline = new Pipeline();
        $pipeline->name = 'Test Pipeline';
        $pipeline->description = 'Test Description';
        $pipeline->user_id = $user->id;
        $pipeline->save();

        $this->assertEquals($user->id, $pipeline->user_id);

        $pipeline->delete();
    }

}
