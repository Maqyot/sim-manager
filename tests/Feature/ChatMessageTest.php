<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatMessageTest extends TestCase
{
    use RefreshDatabase;

    private function student(string $email, string $number): Student
    {
        return Student::create([
            'student_number' => $number,
            'first_name' => str_contains($email, 'alex') ? 'Alex' : 'Jamie',
            'last_name' => 'Learner',
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

    public function test_student_can_send_and_view_only_their_own_admin_conversation(): void
    {
        $student = $this->student('jamie@school.test', '2026-6001');
        $otherStudent = $this->student('alex@school.test', '2026-6002');
        $studentUser = $this->studentUser($student);
        $otherUser = $this->studentUser($otherStudent);
        ChatMessage::create([
            'student_id' => $otherStudent->id,
            'sender_user_id' => $otherUser->id,
            'sender_role' => 'student',
            'body' => 'Private note from another student',
        ]);

        $this->actingAs($studentUser)
            ->get(route('student.messages'))
            ->assertOk()
            ->assertSee('Your conversation')
            ->assertDontSee('Private note from another student');

        $this->post(route('student.messages.store'), ['body' => 'Please help me with my enrollment.'])
            ->assertRedirect(route('student.messages'))
            ->assertSessionHas('success');

        $this->get(route('student.messages'))
            ->assertSee('Please help me with my enrollment.')
            ->assertDontSee('Private note from another student');

        $this->assertDatabaseHas('chat_messages', [
            'student_id' => $student->id,
            'sender_user_id' => $studentUser->id,
            'sender_role' => User::ROLE_STUDENT,
            'body' => 'Please help me with my enrollment.',
        ]);
    }

    public function test_admin_can_view_all_student_conversations_and_reply(): void
    {
        $student = $this->student('jamie@school.test', '2026-6001');
        $otherStudent = $this->student('alex@school.test', '2026-6002');
        $studentUser = $this->studentUser($student);
        $otherUser = $this->studentUser($otherStudent);
        ChatMessage::create([
            'student_id' => $student->id,
            'sender_user_id' => $studentUser->id,
            'sender_role' => User::ROLE_STUDENT,
            'body' => 'First private question',
        ]);
        ChatMessage::create([
            'student_id' => $otherStudent->id,
            'sender_user_id' => $otherUser->id,
            'sender_role' => User::ROLE_STUDENT,
            'body' => 'Second private question',
        ]);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('admin.messages.index'))
            ->assertOk()
            ->assertSee('Student messages')
            ->assertSee('Jamie Learner')
            ->assertSee('Alex Learner');

        $this->get(route('admin.messages.show', $student))
            ->assertOk()
            ->assertSee('First private question')
            ->assertDontSee('Second private question');

        $this->post(route('admin.messages.store', $student), ['body' => 'Admin response'])
            ->assertRedirect(route('admin.messages.show', $student));

        $this->actingAs($studentUser)
            ->get(route('student.messages'))
            ->assertSee('First private question')
            ->assertSee('Admin response')
            ->assertDontSee('Second private question');
    }

    public function test_staff_and_other_students_cannot_access_admin_or_another_students_chat(): void
    {
        $student = $this->student('jamie@school.test', '2026-6001');
        $otherStudent = $this->student('alex@school.test', '2026-6002');
        $studentUser = $this->studentUser($student);
        $staff = User::factory()->create(['role' => User::ROLE_STAFF]);
        $admin = User::factory()->admin()->create();

        $this->actingAs($staff)
            ->get(route('admin.messages.index'))
            ->assertForbidden();

        $this->actingAs($studentUser)
            ->get(route('admin.messages.show', $otherStudent))
            ->assertForbidden();

        $this->actingAs($admin)
            ->post(route('admin.messages.store', $student), ['body' => '   '])
            ->assertSessionHasErrors('body');

        $this->assertDatabaseCount('chat_messages', 0);
    }

    public function test_guests_are_redirected_to_login_for_student_and_admin_messages(): void
    {
        $student = $this->student('jamie@school.test', '2026-6001');

        $this->get(route('student.messages'))
            ->assertRedirect(route('login'));

        $this->post(route('student.messages.store'), ['body' => 'Guest message'])
            ->assertRedirect(route('login'));

        $this->get(route('admin.messages.index'))
            ->assertRedirect(route('login'));

        $this->post(route('admin.messages.store', $student), ['body' => 'Guest reply'])
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('chat_messages', 0);
    }
}
