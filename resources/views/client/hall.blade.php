@extends('client.layouts.app')

@section('title', 'Выбор мест')

@section('content')
    <section class="buying">
        <div class="buying__info">
            <div class="buying__info-description">
                <h2 class="buying__info-title">{{ $showing->movie->title }}</h2>
                <p class="buying__info-start">
                    {{ $showing->start_time->format('d.m.Y') }},
                    {{ $showing->start_time->format('H:i') }}
                </p>
                <p class="buying__info-hall">{{ $showing->hall->title }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('client.reserve', $showing) }}">
            @csrf
            <input type="hidden" name="email" value="">

            <div class="buying-scheme">
                <div class="buying-scheme__wrapper">

                    @for ($row = 1; $row <= $showing->hall->rows; $row++)
                        <div class="buying-scheme__row">

                            @for ($col = 1; $col <= $showing->hall->cols; $col++)
                                @php
                                    $seat = $showing->hall->seats
                                        ->first(fn($s) => $s->row === $row && $s->col === $col);

                                    $isBusy = $seat && in_array($seat->id, $busySeatIds, true);
                                    $isVip = $seat && $seat->type === 'vip';
                                @endphp

                                @if (!$seat || !$seat->is_enabled)
                                    <span class="buying-scheme__chair buying-scheme__chair_disabled"></span>
                                @else
                                    <label class="buying-scheme__chair-wrapper">
                                        <input
                                            type="checkbox"
                                            name="seat_ids[]"
                                            value="{{ $seat->id }}"
                                            {{ $isBusy ? 'disabled' : '' }}
                                            hidden
                                        >
                                        <span class="buying-scheme__chair
                                        {{ $isVip ? 'buying-scheme__chair_vip' : 'buying-scheme__chair_standart' }}
                                        {{ $isBusy ? 'buying-scheme__chair_taken' : '' }}">
                                    </span>
                                    </label>
                                @endif
                            @endfor

                        </div>
                    @endfor

                </div>
            </div>

            <button class="acceptin-button" type="submit" id="reserveBtn">Забронировать</button>
        </form>
    </section>
@endsection
