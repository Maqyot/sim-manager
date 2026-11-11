<?php

namespace Tests\Feature;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_view_subject_catalogue_without_admin_actions(): void
    {
        Subject::create(['code' => 'IT101', 'name' => 'Introduction to IT', 'units' => 3]);

        $this->actingAs(User::factory()->create(['role' => 'staff']))
            ->get(route('subjects.index'))
            ->assertOk()
            ->assertSee('Subject catalogue')
            ->assertSee('Total subjects')
            ->assertSee('Introduction to IT')
            ->assertSee('IT101')
            ->assertSee('View only')
            ->assertDontSee(route('subjects.create'), false);
    }

    public function test_admin_can_view_subject_management_actions(): void
    {
        $subject = Subject::create(['code' => 'IT101', 'name' => 'Introduction to IT', 'units' => 3]);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('subjects.index'))
            ->assertOk()
            ->assertSee(route('subjects.create'), false)
            ->assertSee(route('subjects.edit', $subject), false)
            ->assertSee(route('subjects.destroy', $subject), false);
    }

    public function test_admin_can_create_subject_with_meeting_schedule(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->post(route('subjects.store'), [
                'code' => 'IT101',
                'name' => 'Introduction to IT',
                'units' => 3,
                'meeting_days' => ['Monday', 'Wednesday'],
                'start_time' => '09:30',
                'end_time' => '11:00',
            ])
            ->assertRedirect(route('subjects.index'));

        $subject = Subject::where('code', 'IT101')->firstOrFail();
        $this->assertSame(['Monday', 'Wednesday'], $subject->meeting_days);
        $this->assertSame('09:30', $subject->start_time);
        $this->assertSame('11:00', $subject->end_time);
    }

    public function test_subject_schedule_requires_days_and_valid_ordered_times(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->from(route('subjects.create'))
            ->post(route('subjects.store'), [
                'code' => 'IT101',
                'name' => 'Introduction to IT',
                'units' => 3,
                'meeting_days' => ['Funday'],
                'start_time' => '11:00',
                'end_time' => '09:30',
            ])
            ->assertRedirect(route('subjects.create'))
            ->assertSessionHasErrors(['meeting_days.0', 'end_time']);
    }

    public function test_subject_schedule_can_be_updated_or_cleared(): void
    {
        $subject = Subject::create([
            'code' => 'IT101',
            'name' => 'Introduction to IT',
            'units' => 3,
            'meeting_days' => ['Monday'],
            'start_time' => '09:00',
            'end_time' => '10:00',
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->put(route('subjects.update', $subject), [
                'code' => $subject->code,
                'name' => $subject->name,
                'units' => 3,
                'meeting_days' => ['Friday'],
                'start_time' => '14:00',
                'end_time' => '15:30',
            ])
            ->assertRedirect(route('subjects.index'));

        $this->assertSame(['Friday'], $subject->fresh()->meeting_days);
        $this->assertSame('14:00', $subject->fresh()->start_time);

        $this->put(route('subjects.update', $subject), [
            'code' => $subject->code,
            'name' => $subject->name,
            'units' => 3,
        ])->assertRedirect(route('subjects.index'));

        $this->assertNull($subject->fresh()->meeting_days);
        $this->assertNull($subject->fresh()->start_time);
        $this->assertNull($subject->fresh()->end_time);
    }
}
