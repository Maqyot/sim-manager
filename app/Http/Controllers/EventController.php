<?php
namespace App\Http\Controllers;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
class EventController extends Controller
{
    public function index(): View { $events = Event::orderBy('starts_at')->paginate(10); return view('events.index', compact('events')); }
    public function create(): View { return view('events.create'); }
    public function store(EventRequest $request): RedirectResponse { Event::create($request->validated()); return redirect()->route('events.index')->with('success', 'Event added.'); }
    public function edit(Event $event): View { return view('events.edit', compact('event')); }
    public function update(EventRequest $request, Event $event): RedirectResponse { $event->update($request->validated()); return redirect()->route('events.index')->with('success', 'Event updated.'); }
    public function destroy(Event $event): RedirectResponse { $event->delete(); return redirect()->route('events.index')->with('success', 'Event deleted.'); }
}
