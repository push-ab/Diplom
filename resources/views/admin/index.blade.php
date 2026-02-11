@php use Illuminate\Support\Facades\Storage; @endphp

@extends('admin.layouts.app')

@section('title', 'Идём в кино')

@section('content')

    <main class="conf-steps">
        <section class="conf-step">
            <header class="conf-step__header conf-step__header_opened">
                <h2 class="conf-step__title">Управление залами</h2>
            </header>
            <div class="conf-step__wrapper">
                <p class="conf-step__paragraph">Доступные залы:</p>
                <ul class="conf-step__list">
                    @forelse($halls as $hall)
                        <li>
                            {{ $hall->title }}
                            <button class="conf-step__button conf-step__button-trash"
                                    type="button"
                                    data-popup-open="#popup-remove-hall"
                                    data-remove-id="{{ $hall->id }}"
                                    data-remove-title="{{ $hall->title }}"
                                    data-remove-action="{{ route('admin.halls.destroy', $hall) }}">
                            </button>
                        </li>
                    @empty
                        <li>Залов пока нет</li>
                    @endforelse
                </ul>
                <button class="conf-step__button conf-step__button-accent"
                        type="button"
                        data-popup-open="#popup-add-hall"
                >
                    Создать зал
                </button>
            </div>
        </section>

        <section class="conf-step">
            <header class="conf-step__header conf-step__header_opened">
                <h2 class="conf-step__title">Конфигурация залов</h2>
            </header>
            <div class="conf-step__wrapper">
                <p class="conf-step__paragraph">Выберите зал для конфигурации:</p>
                <ul class="conf-step__selectors-box">
                    @foreach($halls as $hall)
                        <li>
                            <input type="radio"
                                   class="conf-step__radio"
                                   name="chairs-hall"
                                   value="{{ $hall->id }}"
                                   {{ $selectedHall && $selectedHall->id === $hall->id ? 'checked' : '' }}
                                   onclick="location.href='{{ route('admin.index', ['hall_id' => $hall->id]) }}'">
                            <span class="conf-step__selector">{{ $hall->title }}</span>
                        </li>
                    @endforeach
                </ul>
                @if($selectedHall)
                    <form method="POST" action="{{ route('admin.halls.config', $selectedHall) }}">
                        @csrf
                        @method('PATCH')

                        {{-- rows/cols --}}
                        <p class="conf-step__paragraph">Укажите количество рядов и максимальное количество кресел в ряду:</p>
                        <div class="conf-step__legend">
                            <label class="conf-step__label">Рядов, шт
                                <input type="text" class="conf-step__input" name="rows" value="{{ $selectedHall->rows }}">
                            </label>
                            <span class="multiplier">x</span>
                            <label class="conf-step__label">Мест, шт
                                <input type="text" class="conf-step__input" name="cols" value="{{ $selectedHall->cols }}">
                            </label>
                        </div>

                        {{-- hidden JSON схемы --}}
                        <input type="hidden" name="seats_json" id="seats_json" value="">

                        {{-- СХЕМА ЗАЛА (рисуем из БД, классы те же) --}}
                        <div class="conf-step__hall">
                            <div class="conf-step__hall-wrapper">
                                @for($r=1; $r <= $selectedHall->rows; $r++)
                                    <div class="conf-step__row">
                                        @for($c=1; $c <= $selectedHall->cols; $c++)
                                            @php
                                                $seat = $selectedHall->seats->first(fn($s) => $s->row === $r && $s->col === $c);
                                                $class = 'conf-step__chair conf-step__chair_standart';

                                                if (!$seat || !$seat->is_enabled) {
                                                    $class = 'conf-step__chair conf-step__chair_disabled';
                                                } elseif ($seat->type === 'vip') {
                                                    $class = 'conf-step__chair conf-step__chair_vip';
                                                }
                                            @endphp

                                            <span class="{{ $class }}"
                                                  data-seat-id="{{ $seat?->id }}"
                                                  data-seat-type="{{ (!$seat || !$seat->is_enabled) ? 'disabled' : $seat->type }}">
                                            </span>
                                        @endfor
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <fieldset class="conf-step__buttons text-center">
                            <button class="conf-step__button conf-step__button-regular" type="button">Отмена</button>
                            <input type="submit" value="Сохранить" class="conf-step__button conf-step__button-accent">
                        </fieldset>
                    </form>
                @endif
            </div>
        </section>

        <section class="conf-step">
            <header class="conf-step__header conf-step__header_opened">
                <h2 class="conf-step__title">Конфигурация цен</h2>
            </header>
            <div class="conf-step__wrapper">
                <p class="conf-step__paragraph">Выберите зал для конфигурации:</p>
                <ul class="conf-step__selectors-box">
                    @foreach($halls as $hall)
                        <li>
                            <input type="radio"
                                   class="conf-step__radio"
                                   name="chairs-hall"
                                   value="{{ $hall->id }}"
                                   {{ $selectedHall && $selectedHall->id === $hall->id ? 'checked' : '' }}
                                   onclick="location.href='{{ route('admin.index', ['hall_id' => $hall->id]) }}'">
                            <span class="conf-step__selector">{{ $hall->title }}</span>
                        </li>
                    @endforeach
                </ul>

                @if($selectedHall)
                    <form method="POST" action="{{ route('admin.halls.prices', $selectedHall) }}">
                        @csrf
                        @method('PATCH')

                        <p class="conf-step__paragraph">Установите цены для типов кресел:</p>
                        <div class="conf-step__legend">
                            <label class="conf-step__label">Цена, рублей
                                <input type="text" class="conf-step__input" name="price_standard" value="{{ $selectedHall->price_standard }}">
                            </label>
                            за <span class="conf-step__chair conf-step__chair_standart"></span> обычные кресла
                        </div>
                        <div class="conf-step__legend">
                            <label class="conf-step__label">Цена, рублей
                                <input type="text" class="conf-step__input" name="price_vip" value="{{ $selectedHall->price_vip }}">
                            </label>
                            за <span class="conf-step__chair conf-step__chair_vip"></span> VIP кресла
                        </div>

                        <fieldset class="conf-step__buttons text-center">
                            <button class="conf-step__button conf-step__button-regular" type="button">Отмена</button>
                            <input type="submit" value="Сохранить" class="conf-step__button conf-step__button-accent">
                        </fieldset>
                    </form>
                @endif
            </div>
        </section>

        <section class="conf-step">
            <header class="conf-step__header conf-step__header_opened">
                <h2 class="conf-step__title">Сетка сеансов</h2>
            </header>
            <div class="conf-step__wrapper">
                <p class="conf-step__paragraph">
                    <button class="conf-step__button conf-step__button-accent"
                            data-popup-open="#popup-add-film"
                    >
                        Добавить фильм
                    </button>
                    <button class="conf-step__button conf-step__button-accent"
                            data-popup-open="#popup-add-seance"
                    >
                        Добавить сенас
                    </button>
                </p>
                <div class="conf-step__movies">
                    @forelse($movies as $movie)
                        <div class="conf-step__movie">
                            <img class="conf-step__movie-poster"
                                 alt="poster"
                                 src="{{ $movie->poster ? Storage::url($movie->poster) : asset('assets/images/admin/poster.jpg') }}">
                            <h3 class="conf-step__movie-title">{{ $movie->title }}</h3>
                            <p class="conf-step__movie-duration">{{ $movie->duration_minutes }} минут</p>

                            <button class="conf-step__button conf-step__button-trash"
                                    type="button"
                                    data-popup-open="#popup-remove-movie"
                                    data-remove-id="{{ $movie->id }}"
                                    data-remove-title="{{ $movie->title }}"
                                    data-remove-action="{{ route('admin.movies.destroy', $movie) }}">
                            </button>
                        </div>
                    @empty
                        <p class="conf-step__paragraph">Фильмов пока нет</p>
                    @endforelse
                </div>

                @php
                    $showingsByHall = $showings->groupBy('hall_id');
                @endphp

                <div class="conf-step__seances">
                    @forelse($halls as $hall)
                        <div class="conf-step__seances-hall">
                            <h3 class="conf-step__seances-title">{{ $hall->title }}</h3>
                            <div class="conf-step__seances-timeline">
                                @foreach(($showingsByHall[$hall->id] ?? collect()) as $s)
                                    <div class="conf-step__seances-movie"
                                         data-popup-open="#popup-remove-showing"
                                         data-remove-id="{{ $s->id }}"
                                         data-remove-title="{{ $s->movie->title }}"
                                         data-remove-action="{{ route('admin.showings.destroy', $s) }}">
                                        <p class="conf-step__seances-movie-title">{{ $s->movie->title }}</p>
                                        <p class="conf-step__seances-movie-start">{{ $s->start_time->format('H:i') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="conf-step__paragraph">Залов нет — нечего показывать.</p>
                    @endforelse
                </div>

                <fieldset class="conf-step__buttons text-center">
                    <button class="conf-step__button conf-step__button-regular">Отмена</button>
                    <input type="submit" value="Сохранить" class="conf-step__button conf-step__button-accent">
                </fieldset>
            </div>
        </section>

        <section class="conf-step">
            <header class="conf-step__header conf-step__header_opened">
                <h2 class="conf-step__title">Открыть продажи</h2>
            </header>
            <div class="conf-step__wrapper text-center">
                <p class="conf-step__paragraph">Всё готово, теперь можно:</p>

                @foreach($halls as $hall)
                    <form method="POST" action="{{ route('admin.halls.toggleSales', $hall) }}" style="margin: 10px 0;">
                        @csrf
                        <button class="conf-step__button conf-step__button-accent" type="submit">
                            {{ $hall->is_active ? 'Приостановить продажу билетов' : 'Открыть продажу билетов' }}
                            ({{ $hall->title }})
                        </button>
                    </form>
                @endforeach
            </div>
        </section>

        @include('admin.partials.popup_add-hall')
        @include('admin.partials.popup_remove-hall')

        @include('admin.partials.popup_add-film')
        @include('admin.partials.popup_remove-film')

        @include('admin.partials.popup_add-seance')
        @include('admin.partials.popup_remove-seance')

    </main>
@endsection
