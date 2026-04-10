<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{
    public function index()
    {
        $unreadCount = ContactMessage::where('is_read', false)->count();

        return view('admin.pages.contacts.index', compact('unreadCount'));
    }

    public function getData(Request $request)
    {
        $query = ContactMessage::select('contact_messages.*');

        if ($request->filled('status')) {
            $query->where('is_read', $request->status === 'read');
        }

        return DataTables::eloquent($query)
            ->addColumn('time_ago', fn ($m) => $m->created_at->diffForHumans())
            ->addColumn('date_fmt', fn ($m) => $m->created_at->format('d M Y, h:i A'))
            ->addColumn('initials', fn ($m) => strtoupper(substr($m->name, 0, 2)))
            ->addColumn('actions', fn ($m) => [
                'showUrl'    => route('admin.contacts.show', $m),
                'destroyUrl' => route('admin.contacts.destroy', $m),
            ])
            ->make(true);
    }

    public function show(ContactMessage $contact)
    {
        if (! $contact->is_read) {
            $contact->update(['is_read' => true, 'read_at' => now()]);
        }

        return view('admin.pages.contacts.show', compact('contact'));
    }

    public function destroy(ContactMessage $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Message deleted.');
    }

    public function markAllRead()
    {
        ContactMessage::where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'All messages marked as read.');
    }
}
