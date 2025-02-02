<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Reply;

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

    public function test_show_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);
        $replies = Reply::factory()->count(3)->create(['post_id' => $post->id, 'user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'caption',
                         'image_url',
                         'user' => [
                             'name'
                         ],
                         'likes_count',
                         'replies_count',
                         'replies' => [
                             '*' => [
                                 'id',
                                 'post_id',
                                 'user_id',
                                 'message',
                                 'user' => [
                                     'id',
                                     'name'
                                 ],
                                 'created_at',
                                 'updated_at'
                             ]
                         ],
                         'created_at',
                         'updated_at'
                     ]
                 ]);

        $this->assertEquals(3, count($response->json('data.replies')));
    }

    public function test_show_post_not_found()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson('/api/posts/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'message' => 'Post not found',
                 ]);
    }
}