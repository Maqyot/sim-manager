<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentApiTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'student_number' => '2026-3001',
            'first_name' => 'Api',
            'last_name' => 'Student',
            'email' => 'api.student@school.test',
            'course' => 'BSCS',
            'year_level' => 3,
        ], $overrides);
    }

    private function asRole(string $role): static
    {
        $user = User::factory()->create(['role' => $role]);

        return $this->withToken($user->issueApiToken());
    }

    public function test_requests_without_token_get_401(): void
    {
        $this->getJson('/api/students')->assertUnauthorized();
    }

    public function test_invalid_token_gets_401(): void
    {
        $this->withToken('not-a-real-token')->getJson('/api/students')->assertUnauthorized();
    }

    public function test_login_returns_working_bearer_token(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $token = $this->postJson('/api/login', ['email' => $user->email, 'password' => 'password123'])
            ->assertOk()
            ->assertJsonStructure(['token', 'token_type', 'role'])
            ->json('token');

        $this->withToken($token)->getJson('/api/user')->assertOk()->assertJsonPath('email', $user->email);
    }

    public function test_login_rejects_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'wrong'])->assertUnprocessable();
    }

    public function test_logout_revokes_token(): void
    {
        $user = User::factory()->create();
        $token = $user->issueApiToken();

        $this->withToken($token)->postJson('/api/logout')->assertOk();
        $this->withToken($token)->getJson('/api/students')->assertUnauthorized();
    }

    public function test_index_and_show(): void
    {
        $student = Student::factory()->create(['student_number' => '2026-3001', 'email' => 'api.student@school.test']);
        $client = $this->asRole('staff');

        $client->getJson('/api/students')->assertOk()->assertJsonPath('data.0.student_number', '2026-3001');
        $client->getJson("/api/students/{$student->id}")->assertOk()->assertJsonPath('data.email', 'api.student@school.test');
    }

    public function test_show_missing_student_returns_404_json(): void
    {
        $this->asRole('staff')->getJson('/api/students/9999')->assertNotFound();
    }

    public function test_store_returns_201_then_422_for_duplicates(): void
    {
        $client = $this->asRole('staff');

        $client->postJson('/api/students', $this->payload())->assertCreated()->assertJsonPath('data.full_name', 'Api Student');
        $client->postJson('/api/students', $this->payload())->assertUnprocessable()->assertJsonValidationErrors(['student_number', 'email']);
    }

    public function test_store_validates_fields(): void
    {
        $this->asRole('staff')->postJson('/api/students', $this->payload(['email' => 'nope', 'year_level' => 9, 'first_name' => '']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'year_level', 'first_name']);
    }

    public function test_update_keeps_own_unique_values(): void
    {
        $student = Student::create($this->payload());

        $this->asRole('staff')->putJson("/api/students/{$student->id}", $this->payload(['year_level' => 4]))
            ->assertOk()
            ->assertJsonPath('data.year_level', 4);
    }

    public function test_only_admin_can_delete(): void
    {
        $student = Student::create($this->payload());

        $this->asRole('staff')->deleteJson("/api/students/{$student->id}")->assertForbidden();
        $this->assertDatabaseHas('students', ['id' => $student->id]);

        $this->asRole('admin')->deleteJson("/api/students/{$student->id}")->assertNoContent();
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
