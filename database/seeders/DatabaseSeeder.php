<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Admin User', 'email' => 'admin@sims.test', 'password' => 'password', 'role' => User::ROLE_ADMIN]);
        User::create(['name' => 'Staff User', 'email' => 'staff@sims.test', 'password' => 'password', 'role' => User::ROLE_STAFF]);

        $subjects = [
            ['IT101', 'Introduction to Computing', 3],
            ['IT102', 'Programming Fundamentals', 3],
            ['IT201', 'Database Management Systems', 3],
            ['IT202', 'Web Development with Laravel', 3],
            ['MATH101', 'Discrete Mathematics', 3],
            ['PE101', 'Physical Fitness', 2],
        ];
        foreach ($subjects as [$code, $name, $units]) {
            Subject::create(['code' => $code, 'name' => $name, 'units' => $units]);
        }

        $students = [
            ['2026-0001', 'Juan', 'Dela Cruz', 'juan.delacruz@school.test', 'BSIT', 3],
            ['2026-0002', 'Maria', 'Santos', 'maria.santos@school.test', 'BSIT', 2],
            ['2026-0003', 'Jose', 'Reyes', 'jose.reyes@school.test', 'BSCS', 4],
            ['2026-0004', 'Ana', 'Garcia', 'ana.garcia@school.test', 'BSCS', 1],
            ['2026-0005', 'Pedro', 'Bautista', 'pedro.bautista@school.test', 'BSIT', 3],
            ['2026-0006', 'Liza', 'Mendoza', 'liza.mendoza@school.test', 'BSIS', 2],
        ];
        foreach ($students as [$number, $first, $last, $email, $course, $year]) {
            Student::create([
                'student_number' => $number, 'first_name' => $first, 'last_name' => $last,
                'email' => $email, 'course' => $course, 'year_level' => $year,
            ]);
        }

        // [student_id, subject_id, status, grade]
        $enrollments = [
            [1, 4, 'enrolled', null], [1, 3, 'enrolled', null], [2, 2, 'completed', 1.75],
            [2, 1, 'enrolled', null], [3, 4, 'enrolled', null], [3, 3, 'enrolled', null],
            [4, 1, 'dropped', null], [5, 5, 'enrolled', null], [5, 2, 'enrolled', null],
            [6, 6, 'completed', 2.00],
        ];
        foreach ($enrollments as [$studentId, $subjectId, $status, $grade]) {
            Enrollment::create([
                'student_id' => $studentId, 'subject_id' => $subjectId,
                'school_year' => '2026-2027', 'semester' => '1st', 'status' => $status, 'grade' => $grade,
            ]);
        }
    }
}
