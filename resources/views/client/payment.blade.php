@extends('client.layouts.app')

@section('title', 'Идём в кино')

@section('content')
    <main>
        <section class="ticket">

          <header class="tichet__check">
            <h2 class="ticket__check-title">Вы выбрали билеты:</h2>
          </header>

          <div class="ticket__info-wrapper">
            <p class="ticket__info">На фильм:
                <span class="ticket__details ticket__title">{{ $booking->showing->movie->title }}</span>
            </p>
            <p class="ticket__info">Места:
                <span class="ticket__details ticket__chairs">{{ $booking->seats->map(fn($s) => $s->seat->row.'-'.$s->seat->col)->implode(', ') }}</span>
            </p>
            <p class="ticket__info">В зале:
                <span class="ticket__details ticket__hall">{{ $booking->showing->hall->title }}</span>
            </p>
            <p class="ticket__info">Начало сеанса:
                <span class="ticket__details ticket__start">{{ $booking->showing->start_time->format('H:i') }}</span>
            </p>
            <p class="ticket__info">Стоимость:
                <span class="ticket__details ticket__cost">{{ $booking->total }}</span> рублей
            </p>

            <form method="POST" action="{{ route('client.pay', $booking->code) }}">
              @csrf
              <button class="acceptin-button" type="submit">Получить код бронирование</button>
            </form>

            <p class="ticket__hint">После оплаты билет будет доступен в этом окне, а также придёт вам на почту. Покажите QR-код нашему контроллёру у входа в зал.</p>
            <p class="ticket__hint">Приятного просмотра!</p>
          </div>
        </section>
    </main>
@endsection
