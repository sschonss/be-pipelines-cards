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

}
