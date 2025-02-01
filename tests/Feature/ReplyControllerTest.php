<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class ReplyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_reply()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson('/api/replies', [
            'post_id' => $post->id,
            'message' => 'This is a test reply',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'post_id',
                         'user_id',
                         'message',
                         'created_at',
                         'updated_at'
                     ]
                 ]);

        $this->assertDatabaseHas('replies', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'message' => 'This is a test reply',
        ]);
    }
}