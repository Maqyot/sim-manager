<?php
namespace Tests\Feature;
use App\Models\Event;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class EventTest extends TestCase
{
    use RefreshDatabase;
    public function test_events_are_visible_to_signed_in_users_and_only_admins_can_manage_them(): void
    {
        Event::create(['title' => 'Open campus', 'starts_at' => now()->addDays(3)]);
        $staff = User::factory()->create(['role' => 'staff']);
        $this->actingAs($staff)->get(route('events.index'))->assertOk()->assertSee('Open campus')->assertDontSee(route('events.create'), false);
        $this->post(route('events.store'), ['title' => 'Staff event', 'starts_at' => now()->addDays(4)])->assertForbidden();
        $student = Student::create(['student_number'=>'2026-7001','first_name'=>'Casey','last_name'=>'Student','email'=>'casey@school.test','course'=>'BSIT','year_level'=>1]);
        $studentUser = User::create(['name'=>$student->full_name,'email'=>$student->email,'password'=>'password123','role'=>User::ROLE_STUDENT,'student_id'=>$student->id]);
        $this->actingAs($studentUser)->get(route('events.index'))->assertOk()->assertSee('Open campus');
        $this->get(route('events.create'))->assertForbidden();
        $admin = User::factory()->create(['role'=>'admin']);
        $this->actingAs($admin)->post(route('events.store'), ['title'=>'Foundation day','description'=>'School celebration','location'=>'Main quad','starts_at'=>now()->addDays(10)->format('Y-m-d H:i:s'),'ends_at'=>now()->addDays(10)->addHours(2)->format('Y-m-d H:i:s')])->assertRedirect(route('events.index'));
        $this->assertDatabaseHas('events',['title'=>'Foundation day','location'=>'Main quad']);
    }
    public function test_event_end_cannot_precede_start(): void
    {
        $this->actingAs(User::factory()->create(['role'=>'admin']))->from(route('events.create'))
            ->post(route('events.store'), ['title'=>'Invalid event','starts_at'=>'2026-11-15 12:00','ends_at'=>'2026-11-15 11:00'])
            ->assertRedirect(route('events.create'))->assertSessionHasErrors('ends_at');
    }
}
