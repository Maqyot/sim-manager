<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/students')->assertRedirect(route('login'));
    }

    public function test_authenticated_user_sees_sidebar_links_for_existing_sections(): void
    {
        $this->assertSame(url('/students'), route('students.index'));
        $this->assertSame(url('/api/students'), route('api.students.index'));

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('aria-label="Main navigation"', false)
            ->assertSee('href="'.route('students.index').'"', false)
            ->assertSee(route('subjects.index'), false)
            ->assertSee(route('enrollments.index'), false)
            ->assertSee('href="'.route('password.edit').'"', false);
    }

    public function test_login_offers_staff_and_student_options(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Admin / Staff')
            ->assertSee('Student')
            ->assertSee('id="staff-login-help"', false)
            ->assertSee('Contact the system admin before requesting your credentials.')
            ->assertDontSee('Register</a>')
            ->assertDontSee('First student login?');
    }

    public function test_user_can_change_password_with_current_password(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->actingAs($user)
            ->get(route('password.edit'))
            ->assertOk()
            ->assertSee('Change password');

        $this->put(route('password.update'), [
            'current_password' => 'password123',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ])->assertRedirect(route('dashboard'))
            ->assertSessionHas('success', 'Password updated.');

        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    public function test_password_change_rejects_wrong_current_password_and_invalid_new_password(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->actingAs($user)
            ->from(route('password.edit'))
            ->put(route('password.update'), [
                'current_password' => 'wrong-password',
                'password' => 'short',
                'password_confirmation' => 'different',
            ])
            ->assertRedirect(route('password.edit'))
            ->assertSessionHasErrors(['current_password', 'password']);

        $this->assertTrue(Hash::check('password123', $user->fresh()->password));
    }

    public function test_dashboard_shows_live_academic_overview(): void
    {
        $this->actingAs(User::factory()->create(['name' => 'SIMS Admin']))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Good day, SIMS Admin')
            ->assertSee('Students by course')
            ->assertSee('Enrollment status')
            ->assertSee('Recent enrollments');
    }

    public function test_user_can_register_as_staff(): void
    {
        $this->post('/register', [
            'name' => 'New Staff',
            'email' => 'new@sims.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', ['email' => 'new@sims.test', 'role' => 'staff']);
        $this->assertAuthenticated();
    }

    public function test_registration_cannot_self_assign_admin_role(): void
    {
        $this->post('/register', [
            'name' => 'Sneaky',
            'email' => 'sneaky@sims.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'sneaky@sims.test', 'role' => 'staff']);
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->post('/login', ['email' => $user->email, 'password' => 'password123'])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');
    }
}
