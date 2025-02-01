<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_post()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson('/api/posts', [
            'caption' => 'Test Caption',
            'image' => UploadedFile::fake()->image('test.jpg')
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'caption',
                         'image_url',
                         'created_at',
                         'updated_at'
                     ]
                 ]);

        $this->assertDatabaseHas('posts', [
            'caption' => 'Test Caption',
            'user_id' => $user->id
        ]);

    }
}