<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class StudentWebTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();
        parent::tearDown();
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'student_number' => '2026-1001',
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'test.student@school.test',
            'course' => 'BSIT',
            'year_level' => 2,
        ], $overrides);
    }

    private function staff(): User
    {
        return User::factory()->create(['role' => 'staff']);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_staff_can_create_student(): void
    {
        $this->actingAs($this->staff())->post('/students', $this->payload())->assertRedirect(route('students.index'));

        $this->assertDatabaseHas('students', ['student_number' => '2026-1001']);
    }

    public function test_student_directory_shows_records_and_search_results(): void
    {
        Student::create($this->payload());
        Student::create($this->payload([
            'student_number' => '2026-1002',
            'first_name' => 'Another',
            'email' => 'another@sample.org',
        ]));

        $this->actingAs($this->staff())
            ->get(route('students.index', ['q' => 'Test']))
            ->assertOk()
            ->assertSee('Student directory')
            ->assertSee('Matching records')
            ->assertSee('Student, Test')
            ->assertDontSee('Student, Another')
            ->assertSee(route('students.create'), false);
    }

    public function test_student_profile_shows_enrollment_timeline_and_next_scheduled_subject(): void
    {
        CarbonImmutable::setTestNow('2026-11-11 08:00:00');
        $student = Student::create($this->payload());
        $otherStudent = Student::create($this->payload([
            'student_number' => '2026-1002',
            'email' => 'other@school.test',
        ]));
        $nextSubject = Subject::create([
            'code' => 'IT101',
            'name' => 'Next class',
            'units' => 3,
            'meeting_days' => ['Wednesday', 'Friday'],
            'start_time' => '09:30',
            'end_time' => '11:00',
        ]);
        $laterSubject = Subject::create([
            'code' => 'IT102',
            'name' => 'Later class',
            'units' => 3,
            'meeting_days' => ['Thursday'],
            'start_time' => '10:00',
            'end_time' => '11:30',
        ]);
        $otherSubject = Subject::create(['code' => 'IT103', 'name' => 'Other student class', 'units' => 3]);

        foreach ([
            [$student, $nextSubject, 'enrolled'],
            [$student, $laterSubject, 'enrolled'],
            [$otherStudent, $otherSubject, 'enrolled'],
        ] as [$owner, $subject, $status]) {
            Enrollment::create([
                'student_id' => $owner->id,
                'subject_id' => $subject->id,
                'school_year' => '2026-2027',
                'semester' => '1st',
                'status' => $status,
            ]);
        }

        $this->actingAs($this->staff())
            ->get(route('students.show', $student))
            ->assertOk()
            ->assertSee("{$student->first_name}'s Timeline", false)
            ->assertSee('What\'s coming up', false)
            ->assertSee('Personal information')
            ->assertSee('Academic activity')
            ->assertSee('Student portal')
            ->assertSee('Not activated')
            ->assertSee('Next class')
            ->assertSee('Wednesday, November 11')
            ->assertSee('9:30 AM')
            ->assertSee('Later class')
            ->assertDontSee('Other student class');

    }

    public function test_validation_rejects_bad_input(): void
    {
        $this->actingAs($this->staff())
            ->post('/students', $this->payload(['email' => 'not-an-email', 'first_name' => '', 'year_level' => 9]))
            ->assertSessionHasErrors(['email', 'first_name', 'year_level']);

        $this->assertDatabaseCount('students', 0);
    }

    public function test_student_number_and_email_must_be_unique(): void
    {
        Student::create($this->payload());

        $this->actingAs($this->staff())->post('/students', $this->payload())->assertSessionHasErrors(['student_number', 'email']);
    }

    public function test_staff_can_update_student_without_tripping_unique_rule(): void
    {
        $student = Student::create($this->payload());

        $this->actingAs($this->staff())->put("/students/{$student->id}", $this->payload(['course' => 'BSCS']))
            ->assertRedirect(route('students.index'));

        $this->assertDatabaseHas('students', ['id' => $student->id, 'course' => 'BSCS']);
    }

    public function test_staff_cannot_delete_student(): void
    {
        $student = Student::create($this->payload());

        $this->actingAs($this->staff())->delete("/students/{$student->id}")->assertForbidden();
        $this->assertDatabaseHas('students', ['id' => $student->id]);
    }

    public function test_admin_can_delete_student(): void
    {
        $student = Student::create($this->payload());

        $this->actingAs($this->admin())->delete("/students/{$student->id}")->assertRedirect(route('students.index'));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_staff_cannot_create_subjects(): void
    {
        $this->actingAs($this->staff())->post('/subjects', ['code' => 'IT900', 'name' => 'Nope', 'units' => 3])->assertForbidden();
    }
}
