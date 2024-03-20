<?php

use App\Models\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Models\Pipeline;

class CardTest extends TestCase
{
    use DatabaseMigrations;

    private $user_name = 'User Test';
    private $user_email = 'user@test.com';
    private $user_password = '123456789';

    private $pipeline_name = 'Teste';
    private $pipeline_description = 'Teste';
    private $pipeline_user_id = 1;

    public function setUp(): void
    {
        parent::setUp();

        $user = new User();
        $user->name = $this->user_name;
        $user->email = $this->user_email;
        $user->password = $this->user_password;
        $user->save();

        $pipeline = new Pipeline();
        $pipeline->name = $this->pipeline_name;
        $pipeline->description = $this->pipeline_description;
        $pipeline->user_id = $this->pipeline_user_id;
        $pipeline->save();
    }


    public function test_create_card()
    {
        $card = new Card();
        $card->name = 'Teste';
        $card->description = 'Teste';
        $card->user_id = 1;
        $card->pipeline_id = 1;
        $card->save();

        $this->assertEquals('Teste', $card->name);

        $card->delete();

    }

    public function test_update_card()
    {
        $card = new Card();
        $card->name = 'Teste';
        $card->description = 'Teste';
        $card->user_id = 1;
        $card->pipeline_id = 1;
        $card->save();

        $card->name = 'Teste 2';
        $card->save();

        $this->assertEquals('Teste 2', $card->name);

        $card->delete();

    }

    public function test_delete_card()
    {
        $card = new Card();
        $card->name = 'Teste';
        $card->description = 'Teste';
        $card->user_id = 1;
        $card->pipeline_id = 1;
        $card->save();

        $card->delete();

        $this->assertNull(Card::find($card->id));
    }

    public function test_next_stage()
    {
        $card = new Card();
        $card->name = 'Teste';
        $card->description = 'Teste';
        $card->user_id = 1;
        $card->pipeline_id = 1;
        $card->save();

        $pipeline = new Pipeline();
        $pipeline->name = 'Teste 2';
        $pipeline->description = 'Teste 2';
        $pipeline->user_id = 1;
        $pipeline->pipeline_last_id = 1;
        $pipeline->save();

        $this->assertEquals(2, $card->nextStage($card));

        $card->delete();
        $pipeline->delete();
    }

    public function test_next_stage_finished()
    {
        $card = new Card();
        $card->name = 'Teste';
        $card->description = 'Teste';
        $card->user_id = 1;
        $card->pipeline_id = 1;
        $card->save();

        $this->assertNull($card->nextStage($card));

        $card->delete();
    }

    public function test_create_card_by_authenticated_user()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        $card = new Card();
        $card->name = 'Test Card';
        $card->description = 'Test Card Description';
        $card->pipeline_id = 1;
        $card->user_id = $user->id;
        $card->save();

        $this->assertEquals('Test Card', $card->name);

        $card->delete();
    }

    public function test_update_card_by_authenticated_user()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        $card = new Card();
        $card->name = 'Test Card';
        $card->description = 'Test Card Description';
        $card->pipeline_id = 1;
        $card->user_id = $user->id;
        $card->save();

        $card->name = 'Updated Card Name';
        $card->save();

        $this->assertEquals('Updated Card Name', $card->name);

        $card->delete();
    }

    public function test_delete_card_by_authenticated_user()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        $card = new Card();
        $card->name = 'Test Card';
        $card->description = 'Test Card Description';
        $card->pipeline_id = 1;
        $card->user_id = $user->id;
        $card->save();

        $card->delete();

        $this->assertNull(Card::find($card->id));
    }

    public function test_next_stage_by_authenticated_user()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        $card = new Card();
        $card->name = 'Test Card';
        $card->description = 'Test Card Description';
        $card->pipeline_id = 1;
        $card->user_id = $user->id;
        $card->save();

        $pipeline = new Pipeline();
        $pipeline->name = 'Test Pipeline';
        $pipeline->description = 'Test Pipeline Description';
        $pipeline->user_id = $user->id;
        $pipeline->pipeline_last_id = 1;
        $pipeline->save();

        $this->assertEquals(2, $card->nextStage($card));

        $card->delete();
        $pipeline->delete();
    }

    public function test_next_stage_finished_by_authenticated_user()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($user);

        $card = new Card();
        $card->name = 'Test Card';
        $card->description = 'Test Card Description';
        $card->pipeline_id = 1;
        $card->user_id = $user->id;
        $card->save();

        $this->assertNull($card->nextStage($card));

        $card->delete();
    }
}
