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

    public function test_index_posts()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $posts = Post::factory()->count(15)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/posts?limit=10');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id',
                             'caption',
                             'image_url',
                             'user' => [
                                 'name'
                             ],
                             'likes_count',
                             'replies_count',
                             'is_liked_by_user',
                             'created_at',
                             'updated_at'
                         ]
                     ],
                     'next_cursor'
                 ]);

        $this->assertCount(10, $response->json('data'));
        $this->assertNotNull($response->json('next_cursor'));
    }
}