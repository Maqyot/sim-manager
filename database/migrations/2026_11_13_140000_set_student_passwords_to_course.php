<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->join('students', 'students.id', '=', 'users.student_id')
            ->where('users.role', 'student')
            ->select('users.id as user_id', 'students.course')
            ->orderBy('users.id')
            ->chunk(100, function ($users): void {
                foreach ($users as $user) {
                    DB::table('users')
                        ->where('id', $user->user_id)
                        ->update(['password' => Hash::make($user->course)]);
                }
            });
    }

    public function down(): void
    {
        // Previous password hashes cannot be restored after replacing them with course values.
    }
};
