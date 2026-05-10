<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index()
    {
        $org = function_exists('tenant') && tenant() ? tenant() : null;
        $tickets = SupportTicket::where('user_id', auth()->id())->latest()->get();
        return view('support.index', compact('tickets', 'org'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $org = function_exists('tenant') && tenant() ? tenant() : null;

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'organization_id' => $org ? $org->id : auth()->user()->organization_id,
            'subject' => $request->subject,
            'status' => 'open',
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Notify super admins
        $superAdmins = \App\Models\User::where('role', 'super_admin')->get();
        foreach ($superAdmins as $superAdmin) {
            \App\Models\Notification::create([
                'user_id' => $superAdmin->id,
                'type' => 'support_ticket_created',
                'title' => 'New Support Ticket',
                'message' => auth()->user()->name . ' opened a new support ticket: "' . $request->subject . '".',
                'link' => route('admin.support.show', $ticket->id),
                'icon' => '🎫'
            ]);
        }

        return redirect()->route(request()->routeIs('org.*') ? 'org.support.show' : 'support.show', $ticket->id)
            ->with('success', 'Support ticket created successfully. We will get back to you soon.');
    }

    public function show($id)
    {
        $org = function_exists('tenant') && tenant() ? tenant() : null;
        $ticket = SupportTicket::where('id', $id)->where('user_id', auth()->id())->with('messages.user')->firstOrFail();
        return view('support.show', compact('ticket', 'org'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if ($ticket->status === 'closed') {
            return back()->with('error', 'This ticket is closed. You cannot reply.');
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Notify super admins
        $superAdmins = \App\Models\User::where('role', 'super_admin')->get();
        foreach ($superAdmins as $superAdmin) {
            \App\Models\Notification::create([
                'user_id' => $superAdmin->id,
                'type' => 'support_ticket_replied',
                'title' => 'New Support Ticket Reply',
                'message' => auth()->user()->name . ' replied to the support ticket: "' . $ticket->subject . '".',
                'link' => route('admin.support.show', $ticket->id),
                'icon' => '💬'
            ]);
        }

        return back()->with('success', 'Reply sent successfully.');
    }
}
