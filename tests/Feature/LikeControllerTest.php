<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Reply;

class LikeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_like_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson("/api/likes/post/{$post->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Liked successfully']);

        $this->assertDatabaseHas('likes', [
            'likeable_id' => $post->id,
            'likeable_type' => Post::class,
            'user_id' => $user->id,
        ]);
    }

    public function test_unlike_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);
        $this->postJson("/api/likes/post/{$post->id}");
        $response = $this->deleteJson("/api/likes/post/{$post->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Unliked successfully']);

        $this->assertDatabaseMissing('likes', [
            'likeable_id' => $post->id,
            'likeable_type' => Post::class,
            'user_id' => $user->id,
        ]);
    }

    public function test_like_reply()
    {
        $user = User::factory()->create();
        $reply = Reply::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson("/api/likes/reply/{$reply->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Liked successfully']);

        $this->assertDatabaseHas('likes', [
            'likeable_id' => $reply->id,
            'likeable_type' => Reply::class,
            'user_id' => $user->id,
        ]);
    }

    public function test_unlike_reply()
    {
        $user = User::factory()->create();
        $reply = Reply::factory()->create();

        $this->actingAs($user);
        $this->postJson("/api/likes/reply/{$reply->id}");
        $response = $this->deleteJson("/api/likes/reply/{$reply->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Unliked successfully']);

        $this->assertDatabaseMissing('likes', [
            'likeable_id' => $reply->id,
            'likeable_type' => Reply::class,
            'user_id' => $user->id,
        ]);
    }

    public function test_invalid_resource_like()
    {
        $user = User::factory()->create();
        $reply = Reply::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson("/api/likes/invalid/{$reply->id}");

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Invalid resource']);
    }
}