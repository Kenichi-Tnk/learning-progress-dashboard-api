<?php

namespace Tests\Feature;

use App\Models\LearningProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_a_token(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => '学習者',
            'email' => 'learner@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token']);

        $this->assertDatabaseHas('users', ['email' => 'learner@example.com']);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $login = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $login->assertOk()->assertJsonStructure(['user', 'token']);

        $token = $login->json('token');

        $this->withToken($token)
            ->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('id', $user->id);

        $this->withToken($token)
            ->postJson('/api/logout')
            ->assertOk();
    }

    public function test_learning_progresses_are_limited_to_the_authenticated_user(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        LearningProgress::create([
            'user_id' => $owner->id,
            'title' => '自分の記録',
            'status' => 'in_progress',
        ]);
        $otherProgress = LearningProgress::create([
            'user_id' => $otherUser->id,
            'title' => '他人の記録',
            'status' => 'in_progress',
        ]);

        $this->actingAs($owner, 'sanctum')
            ->getJson('/api/learning-progresses')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.title', '自分の記録');

        $this->actingAs($owner, 'sanctum')
            ->getJson('/api/learning-progresses/' . $otherProgress->id)
            ->assertNotFound();
    }

    public function test_learning_progresses_require_authentication(): void
    {
        $this->getJson('/api/learning-progresses')->assertUnauthorized();
    }
}