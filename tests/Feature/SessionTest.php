<?php
namespace Tests\Feature;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class SessionTest extends TestCase
{
    use RefreshDatabase;
    public function test_staff_can_review_grouped_academic_sessions(): void
    {
        $student=Student::create(['student_number'=>'2026-7002','first_name'=>'Jordan','last_name'=>'Student','email'=>'jordan@school.test','course'=>'BSIT','year_level'=>2]);
        $subject=Subject::create(['code'=>'IT201','name'=>'Data Systems','units'=>3]);
        Enrollment::create(['student_id'=>$student->id,'subject_id'=>$subject->id,'school_year'=>'2026-2027','semester'=>'1st','status'=>'enrolled']);
        $this->actingAs(User::factory()->create(['role'=>'staff']))->get(route('sessions.index'))->assertOk()->assertSee('2026-2027')->assertSee('1st semester')->assertSee('1')->assertSee('enrollment record');
    }
}
