<?php
namespace App\Http\Controllers;
use App\Models\Enrollment;
use Illuminate\View\View;
class SessionController extends Controller
{
    public function index(): View
    {
        $sessions = Enrollment::query()
            ->selectRaw('school_year, semester, COUNT(*) as enrollment_count')
            ->groupBy('school_year', 'semester')
            ->orderByDesc('school_year')
            ->orderBy('semester')
            ->get();
        return view('sessions.index', compact('sessions'));
    }
}
