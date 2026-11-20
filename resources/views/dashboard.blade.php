<x-layout>
<style>
    body {
        align-items: flex-start;
        background: #f3f7fc;
        color: #17324f;
    }

    main {
        max-width: 1080px;
        margin: 32px auto;
        border-top-color: #0875d1;
    }

    .dashboard-header,
    .dashboard-heading,
    .dashboard-list-item {
        display: flex;
        gap: 16px;
    }

    .dashboard-header,
    .dashboard-list-item {
        align-items: center;
        justify-content: space-between;
    }

    .dashboard-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .dashboard-add-button {
        padding: 11px 16px;
        border-radius: 10px;
        background: #0865d9;
        color: #fff;
        white-space: nowrap;
    }

    .dashboard-add-button:hover {
        background: #034fae;
        text-decoration: none;
    }

    .dashboard-header {
        margin-bottom: 28px;
    }

    .dashboard-heading {
        flex-direction: column;
        gap: 6px;
    }

    .dashboard-heading h1,
    .dashboard-section h2,
    .dashboard-heading p,
    .dashboard-section p {
        margin: 0;
        text-align: left;
    }

    .dashboard-heading h1 {
        font-size: 30px;
        margin-bottom: 0;
    }

    .dashboard-heading p,
    .dashboard-muted {
        color: #58677d;
    }

    .dashboard-logout {
        width: auto;
        padding: 11px 18px;
        font-size: 14px;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        align-items: flex-start;
        gap: 16px;
    }

    .dashboard-section {
        min-width: 0;
        padding: 22px;
        border: 1px solid #d5e3f5;
        border-radius: 14px;
        background: #fff;
    }

    .dashboard-profile-section {
        grid-column: 1 / -1;
    }

    .dashboard-section h2 {
        margin-bottom: 16px;
        color: #0d2b50;
        font-size: 20px;
    }

    .dashboard-list {
        display: grid;
        gap: 14px;
    }

    .dashboard-list-item {
        padding-bottom: 12px;
        border-bottom: 1px solid #e6edf6;
    }

    .dashboard-list-item:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .dashboard-list-item strong,
    .dashboard-list-item span {
        display: block;
    }

    .dashboard-list-item span,
    .dashboard-payment {
        margin-top: 4px;
        color: #58677d;
        font-size: 14px;
    }

    .reservation-form {
        display: block;
    }

    .reservation-form table {
        width: 100%;
        border-collapse: collapse;
    }

    .reservation-form th,
    .reservation-form td {
        padding: 8px 0;
        text-align: left;
        vertical-align: top;
    }

    .reservation-form th {
        width: 120px;
        padding-right: 12px;
        color: #17324f;
        font-size: 14px;
    }

    .reservation-form input,
    .reservation-form select,
    .profile-form input,
    .profile-form textarea {
        width: 100%;
        padding: 11px;
        border: 1px solid #d5e3f5;
        border-radius: 8px;
        background: #f3f7fc;
        font-size: 14px;
    }

    .reservation-form button,
    .profile-form button {
        width: 100%;
        margin-top: 12px;
    }

    .profile-form {
        display: block;
    }

    .profile-form table {
        width: 100%;
        border-collapse: collapse;
    }

    .profile-form th,
    .profile-form td {
        padding: 8px 0;
        text-align: left;
        vertical-align: top;
    }

    .profile-form th {
        width: 120px;
        padding-right: 12px;
        color: #17324f;
        font-size: 14px;
    }

    .reservation-form button:disabled {
        background: #94a3b8;
        cursor: not-allowed;
    }

    .reservation-field-error {
        margin-top: 4px !important;
        color: #b91c1c;
        font-size: 13px;
    }

    .dashboard-alert {
        margin-bottom: 20px;
        padding: 12px 16px;
        border-radius: 8px;
        background: #dcfce7;
        color: #166534;
    }

    @media (max-width: 700px) {
        main {
            margin: 12px auto;
            padding: 28px 22px;
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-profile-section {
            grid-column: auto;
        }

        .dashboard-header {
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .dashboard-heading h1 {
            font-size: 24px;
        }
    }
</style>

<header class="dashboard-header">
    <div class="dashboard-heading">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>
        <p>Your hotel stay at a glance.</p>
    </div>
    <div class="dashboard-actions">
         <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="dashboard-logout" type="submit">Logout</button>
        </form>
    </div>
</header>

@if (session('status'))
    <p class="dashboard-alert">{{ session('status') }}</p>
@endif

<div class="dashboard-grid">
    <section class="dashboard-section dashboard-profile-section">
        <h2>Guest profile</h2>
        @if ($guestProfile)
            <p><strong>Phone:</strong> {{ $guestProfile->phone ?: 'Not provided' }}</p>
            <p><strong>Address:</strong> {{ $guestProfile->address ?: 'Not provided' }}</p>
            <p><strong>Date of birth:</strong> {{ $guestProfile->date_of_birth ?: 'Not provided' }}</p>
        @else
            <p class="dashboard-muted">Add your guest profile details.</p>
            <form class="profile-form" method="POST" action="{{ route('guest-profile.store') }}">
                @csrf
                <table>
                    <tbody>
                        <tr>
                            <th><label for="phone">Phone</label></th>
                            <td>
                                <input id="phone" name="phone" type="tel" maxlength="30" value="{{ old('phone') }}">
                                @error('phone')
                                    <p class="reservation-field-error">{{ $message }}</p>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th><label for="address">Address</label></th>
                            <td>
                                <textarea id="address" name="address" rows="3" maxlength="5000">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="reservation-field-error">{{ $message }}</p>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <th><label for="date_of_birth">Date of birth</label></th>
                            <td>
                                <input id="date_of_birth" name="date_of_birth" type="date" max="{{ now()->toDateString() }}" value="{{ old('date_of_birth') }}">
                                @error('date_of_birth')
                                    <p class="reservation-field-error">{{ $message }}</p>
                                @enderror
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit">Add profile</button>
            </form>
        @endif
    </section>

    <section id="add-reservation" class="dashboard-section">
        <h2>Add reservation</h2>
        @if ($rooms->isEmpty())
            <p class="dashboard-muted">No rooms are currently available. Add an available room before submitting a reservation.</p>
        @endif
        <form class="reservation-form" method="POST" action="{{ route('reservations.store') }}">
            @csrf
            <table>
                <tbody>
                    <tr>
                        <th><label for="room_id">Room</label></th>
                        <td>
                            <select id="room_id" name="room_id" required @disabled($rooms->isEmpty())>
                                @if ($rooms->isEmpty())
                                    <option value="">No available rooms</option>
                                @else
                                    <option value="">Choose a room</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>
                                            Room {{ $room->room_number }} · {{ $room->room_type }} · ${{ number_format($room->price_per_night, 2) }} per night
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('room_id')
                                <p class="reservation-field-error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th><label for="check_in">Check-in</label></th>
                        <td>
                            <input id="check_in" name="check_in" type="date" min="{{ now()->toDateString() }}" value="{{ old('check_in') }}" required>
                            @error('check_in')
                                <p class="reservation-field-error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th><label for="check_out">Check-out</label></th>
                        <td>
                            <input id="check_out" name="check_out" type="date" min="{{ now()->toDateString() }}" value="{{ old('check_out') }}" required>
                            @error('check_out')
                                <p class="reservation-field-error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" @disabled($rooms->isEmpty())>Add reservation</button>
        </form>
    </section>

    <section class="dashboard-section">
        <h2>Your reservations ({{ $reservations->count() }})</h2>
        @forelse ($reservations as $reservation)
            <div class="dashboard-list-item">
                <div>
                    <strong>Room {{ $reservation->room->room_number }} · {{ $reservation->room->room_type }}</strong>
                    <span>{{ $reservation->check_in }} to {{ $reservation->check_out }}</span>
                    <span>Status: {{ ucfirst($reservation->status) }} · Total: ${{ number_format($reservation->total_amount, 2) }}</span>
                    @foreach ($reservation->payments as $payment)
                        <p class="dashboard-payment">
                            Payment: ${{ number_format($payment->amount, 2) }} · {{ ucfirst($payment->status) }}
                        </p>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="dashboard-muted">You don't have any reservations yet.</p>
        @endforelse
    </section>

    <section class="dashboard-section">
        <h2>Available rooms ({{ $rooms->count() }})</h2>
        @forelse ($rooms as $room)
            <div class="dashboard-list-item">
                <div>
                    <strong>Room {{ $room->room_number }} · {{ $room->room_type }}</strong>
                    <span>${{ number_format($room->price_per_night, 2) }} per night</span>
                </div>
            </div>
        @empty
            <p class="dashboard-muted">No rooms are currently available.</p>
        @endforelse
    </section>

    <section class="dashboard-section">
        <h2>Available services ({{ $services->count() }})</h2>
        @forelse ($services as $service)
            <div class="dashboard-list-item">
                <div>
                    <strong>{{ $service->name }}</strong>
                    @if ($service->description)
                        <span>{{ $service->description }}</span>
                    @endif
                    <span>${{ number_format($service->price, 2) }}</span>
                </div>
            </div>
        @empty
            <p class="dashboard-muted">No services are currently available.</p>
        @endforelse
    </section>

    
</div>
</x-layout>