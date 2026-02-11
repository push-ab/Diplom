@extends('client.layouts.app')

@section('title', 'Идём в кино')

@section('content')
    @php
        $today = now()->startOfDay();
    @endphp

    <nav class="page-nav">
        {{-- назад --}}
        <a class="page-nav__day page-nav__day_prev"
           href="{{ route('client.index', ['date' => $prevDate->toDateString()]) }}"
           aria-label="Предыдущие дни">
        </a>

        @foreach($navDays as $day)
            @php
                $isToday = $day->equalTo($today);
                $isChosen = $day->equalTo($selectedDate->copy()->startOfDay());
                $isWeekend = in_array($day->dayOfWeekIso, [6, 7], true); // 6=сб,7=вс

                $classes = 'page-nav__day';
                if ($isToday) $classes .= ' page-nav__day_today';
                if ($isChosen) $classes .= ' page-nav__day_chosen';
                if ($isWeekend) $classes .= ' page-nav__day_weekend';

                // Пн/Вт/Ср...
                $weekShort = match ($day->dayOfWeekIso) {
                    1 => 'Пн',
                    2 => 'Вт',
                    3 => 'Ср',
                    4 => 'Чт',
                    5 => 'Пт',
                    6 => 'Сб',
                    7 => 'Вс',
                };
            @endphp

            <a class="{{ $classes }}" href="{{ route('client.index', ['date' => $day->toDateString()]) }}">
                <span class="page-nav__day-week">{{ $weekShort }}</span>
                <span class="page-nav__day-number">{{ $day->format('j') }}</span>
            </a>
        @endforeach

        <a class="page-nav__day page-nav__day_next"
           href="{{ route('client.index', ['date' => $nextDate->toDateString()]) }}"
           aria-label="Следующие дни">
        </a>
    </nav>

    <main>
        @php
            $byMovie = $showings->groupBy('movie_id');
        @endphp

        @foreach($byMovie as $movieId => $movieShowings)
            @php
                $movie = $movieShowings->first()->movie;
                $byHall = $movieShowings->groupBy('hall_id');
            @endphp

            <section class="movie">
                <div class="movie__info">
                    <div class="movie__poster">
                        <img
                            class="movie__poster-image"
                            alt="poster"
                            src="{{ $movie->poster ? Storage::url($movie->poster) : asset('assets/images/client/poster.jpg') }}">
                    </div>
                    <div class="movie__description">
                        <h2 class="movie__title">{{ $movie->title }}</h2>
                        <p class="movie__synopsis">{{ $movie->description }}</p>
                        <p class="movie__data">
                            <span class="movie__data-duration">{{ $movie->duration_minutes }} минут</span>
                            @if($movie->age_rating)
                                <span class="movie__data-origin">{{ $movie->age_rating }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                @foreach($byHall as $hallId => $hallShowings)
                    @php $hall = $hallShowings->first()->hall; @endphp

                    <div class="movie-seances__hall">
                        <h3 class="movie-seances__hall-title">{{ $hall->title }}</h3>
                        <ul class="movie-seances__list">
                            @foreach($hallShowings as $showing)
                                <li class="movie-seances__time-block">
                                    <a class="movie-seances__time"
                                       href="{{ route('client.hall', $showing) }}">
                                        {{ $showing->start_time->format('H:i') }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </section>
        @endforeach
    </main>
@endsection
