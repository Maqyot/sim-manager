<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    private function seedPair(): array
    {
        $student = Student::create(['student_number' => '2026-2001', 'first_name' => 'A', 'last_name' => 'B', 'email' => 'ab@school.test', 'course' => 'BSIT', 'year_level' => 1]);
        $subject = Subject::create(['code' => 'IT101', 'name' => 'Intro', 'units' => 3]);

        return [$student, $subject];
    }

    public function test_student_can_be_enrolled_once_per_term(): void
    {
        [$student, $subject] = $this->seedPair();
        $user = User::factory()->create();
        $data = ['student_id' => $student->id, 'subject_id' => $subject->id, 'school_year' => '2026-2027', 'semester' => '1st', 'status' => 'enrolled'];

        $this->actingAs($user)->post('/enrollments', $data)->assertRedirect(route('enrollments.index'));
        $this->actingAs($user)->post('/enrollments', $data)->assertSessionHasErrors('subject_id');

        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_enrollment_directory_shows_current_records(): void
    {
        [$student, $subject] = $this->seedPair();
        Enrollment::create(['student_id' => $student->id, 'subject_id' => $subject->id, 'school_year' => '2026-2027', 'semester' => '1st', 'status' => 'enrolled']);

        $this->actingAs(User::factory()->create(['role' => 'staff']))
            ->get(route('enrollments.index'))
            ->assertOk()
            ->assertSee('Enrollment management')
            ->assertSee('Total enrollments')
            ->assertSee('A B')
            ->assertSee('Intro')
            ->assertSee('2026-2027')
            ->assertSee('Enrolled');
    }

    public function test_deleting_a_student_removes_their_enrollments(): void
    {
        [$student, $subject] = $this->seedPair();
        Enrollment::create(['student_id' => $student->id, 'subject_id' => $subject->id, 'school_year' => '2026-2027', 'semester' => '1st', 'status' => 'enrolled']);

        $this->actingAs(User::factory()->create(['role' => 'admin']))->delete("/students/{$student->id}");

        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_school_year_format_is_validated(): void
    {
        [$student, $subject] = $this->seedPair();

        $this->actingAs(User::factory()->create())
            ->post('/enrollments', ['student_id' => $student->id, 'subject_id' => $subject->id, 'school_year' => '2026', 'semester' => '1st', 'status' => 'enrolled'])
            ->assertSessionHasErrors('school_year');
    }
}
