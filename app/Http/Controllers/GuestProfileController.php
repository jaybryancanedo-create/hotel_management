<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GuestProfileController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:5000'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
        ]);

        $profile = $request->user()
            ->guestProfile()
            ->firstOrCreate([], $validated);

        return redirect()
            ->route('dashboard')
            ->with(
                'status',
                $profile->wasRecentlyCreated
                    ? 'Your guest profile has been saved.'
                    : 'A guest profile already exists and cannot be edited.'
            );
    }
}
