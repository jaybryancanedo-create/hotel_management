<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $room = Room::query()
                ->whereKey($validated['room_id'])
                ->where('status', 'available')
                ->lockForUpdate()
                ->first();

            if (! $room) {
                throw ValidationException::withMessages([
                    'room_id' => 'That room is not currently available.',
                ]);
            }

            $hasConflictingReservation = Reservation::query()
                ->where('room_id', $room->id)
                ->whereNotIn('status', ['cancelled', 'canceled', 'rejected'])
                ->where('check_in', '<', $validated['check_out'])
                ->where('check_out', '>', $validated['check_in'])
                ->exists();

            if ($hasConflictingReservation) {
                throw ValidationException::withMessages([
                    'room_id' => 'That room is already reserved for part of those dates.',
                ]);
            }

            $nights = Carbon::parse($validated['check_in'])
                ->diffInDays(Carbon::parse($validated['check_out']));

            $request->user()->reservations()->create([
                'room_id' => $room->id,
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'status' => 'pending',
                'total_amount' => round((float) $room->price_per_night * $nights, 2),
            ]);
        });

        return redirect()
            ->route('dashboard')
            ->with('status', 'Your reservation request has been submitted.');
    }
}
