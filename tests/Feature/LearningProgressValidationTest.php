<?php

namespace Tests\Feature;

use App\Models\LearningProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LearningProgressValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->create());
    }

    public function test_create_learning_progress_validation_response_is_unified(): void
    {
        $response = $this->postJson('/api/learning-progresses', []);

        $this->assertValidationErrorResponse($response, ['title']);
    }

    public function test_create_learning_progress_fails_when_status_is_invalid(): void
    {
        $response = $this->postJson('/api/learning-progresses', [
            'title' => 'API学習',
            'status' => 'done',
        ]);

        $this->assertValidationErrorResponse($response, ['status']);
    }

    public function test_create_learning_progress_fails_when_started_at_is_invalid_date(): void
    {
        $response = $this->postJson('/api/learning-progresses', [
            'title' => '日付テスト',
            'started_at' => '2026/99/99',
        ]);

        $this->assertValidationErrorResponse($response, ['started_at']);
    }

    public function test_update_learning_progress_fails_when_title_is_empty(): void
    {
        $progress = LearningProgress::create([
            'user_id' => auth()->id(),
            'title' => '初期タイトル',
            'status' => 'in_progress',
        ]);

        $response = $this->putJson('/api/learning-progresses/' . $progress->id, [
            'title' => '',
        ]);

        $this->assertValidationErrorResponse($response, ['title']);
    }

    private function assertValidationErrorResponse($response, array $fields): void
    {
        $errorsStructure = [];

        foreach ($fields as $field) {
            $errorsStructure[] = $field;
        }

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => $errorsStructure,
            ])
            ->assertJson([
                'success' => false,
                'message' => 'バリデーションエラーです。',
            ]);
    }
}
