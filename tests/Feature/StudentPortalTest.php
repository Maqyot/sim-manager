<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentPortalTest extends TestCase
{
    use RefreshDatabase;

    private function student(string $email, string $number): Student
    {
        return Student::create([
            'student_number' => $number,
            'first_name' => 'Taylor',
            'last_name' => 'Student',
            'email' => $email,
            'course' => 'BSIT',
            'year_level' => 2,
        ]);
    }

    private function studentUser(Student $student): User
    {
        return User::create([
            'name' => $student->full_name,
            'email' => $student->email,
            'password' => 'password123',
            'role' => User::ROLE_STUDENT,
            'student_id' => $student->id,
        ]);
    }

    public function test_student_can_register_without_password_and_log_in_with_course_after_approval(): void
    {
        $this->get(route('student.register'))
            ->assertOk()
            ->assertDontSee('name="password"', false)
            ->assertDontSee('name="password_confirmation"', false);

        $this->post(route('student.register.store'), [
            'student_number' => '2026-5001',
            'first_name' => 'Taylor',
            'last_name' => 'Student',
            'email' => 'taylor@school.test',
            'course' => 'BSIT',
            'year_level' => 2,
        ])->assertRedirect(route('login', ['account_type' => 'student']));

        $student = Student::where('student_number', '2026-5001')->firstOrFail();
        $user = User::where('student_id', $student->id)->firstOrFail();
        $this->assertFalse($user->is_approved);
        $this->assertTrue(Hash::check('BSIT', $user->password));

        $this->post(route('login'), [
            'account_type' => 'student',
            'student_number' => $student->student_number,
            'password' => $student->course,
        ])->assertRedirect(route('student.register'));
        $this->assertGuest();

        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin)
            ->post(route('admin.student-registrations.approve', $user))
            ->assertRedirect(route('dashboard'));
        $this->post(route('logout'))->assertRedirect(route('login'));

        $this->post(route('login'), [
            'account_type' => 'student',
            'student_number' => $student->student_number,
            'password' => $student->course,
        ])->assertRedirect(route('student.dashboard'));

        $this->assertSame(User::ROLE_STUDENT, $user->role);
        $this->assertAuthenticatedAs($user);

        $this->get(route('student.dashboard'))
            ->assertOk()
            ->assertDontSee('temporary course password');
    }

    public function test_student_login_requires_student_mode_and_redirects_to_student_dashboard(): void
    {
        $student = $this->student('taylor@school.test', '2026-5001');
        $user = $this->studentUser($student);

        $this->get(route('login', ['account_type' => 'student']))
            ->assertOk()
            ->assertSee('<label for="password" class="form-label">Password</label>', false)
            ->assertDontSee('Course (password)')
            ->assertSee('Use the password the admin was given');

        $this->from(route('login'))
            ->post(route('login'), [
                'student_number' => $student->student_number,
                'password' => 'password123',
                'account_type' => 'staff',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post(route('login'), [
            'student_number' => $student->student_number,
            'password' => 'password123',
            'account_type' => 'student',
        ])->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_student_dashboard_and_enrollments_only_show_the_signed_in_students_records(): void
    {
        $student = $this->student('taylor@school.test', '2026-5001');
        $otherStudent = $this->student('other@school.test', '2026-5002');
        $subject = Subject::create([
            'code' => 'IT501',
            'name' => 'Student-owned course',
            'units' => 3,
            'meeting_days' => [now()->format('l')],
            'start_time' => '09:30',
            'end_time' => '11:00',
        ]);
        $otherSubject = Subject::create(['code' => 'IT502', 'name' => 'Other course', 'units' => 3]);
        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'school_year' => '2026-2027',
            'semester' => '1st',
            'status' => 'enrolled',
        ]);
        Enrollment::create([
            'student_id' => $otherStudent->id,
            'subject_id' => $otherSubject->id,
            'school_year' => '2026-2027',
            'semester' => '1st',
            'status' => 'enrolled',
        ]);
        $user = $this->studentUser($student);

        $this->actingAs($user)
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('My current subjects')
            ->assertSee('schedule')
            ->assertSee('Student-owned course')
            ->assertDontSee('Other course')
            ->assertDontSee('Change password')
            ->assertSee(route('student.messages'), false)
            ->assertSee('9:30 AM');

        $this->get(route('student.enrollments'))
            ->assertOk()
            ->assertSee('IT501')
            ->assertDontSee('IT502')
            ->assertSee('My Enrollments');
    }

    public function test_student_accounts_cannot_access_staff_routes_or_staff_api(): void
    {
        $student = $this->student('taylor@school.test', '2026-5001');
        $user = $this->studentUser($student);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertForbidden();
        $this->get(route('students.index'))->assertForbidden();
        $this->get(route('subjects.index'))->assertForbidden();
        $this->get(route('enrollments.index'))->assertForbidden();

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }
}
