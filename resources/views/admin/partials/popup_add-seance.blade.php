<div class="popup" id="popup-add-seance">
    <div class="popup__container">
      <div class="popup__content">
        <div class="popup__header">
          <h2 class="popup__title">
            Добавление сеанса
            <a class="popup__dismiss" href="#" data-popup-close>
                <img src="{{ asset('assets/images/admin/close.png') }}" alt="Закрыть">
            </a>
          </h2>
        </div>
        <div class="popup__wrapper">
            <form action="{{ route('admin.showings.store') }}" method="post">
                @csrf

                <label class="conf-step__label conf-step__label-fullsize" for="hall_id">
                    Название зала

                    <select name="hall_id" class="conf-step__input" required>
                        @foreach($halls as $hall)
                            <option value="{{ $hall->id }}">{{ $hall->title }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="conf-step__label conf-step__label-fullsize" for="movie_id">
                    Название фильма

                    <select name="movie_id" class="conf-step__input" required>
                        @foreach($movies as $movie)
                            <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="conf-step__label conf-step__label-fullsize" for="hall">
                    Время начала

                    <input class="conf-step__input" type="datetime-local" name="start_time" required>
                </label>

                <div class="conf-step__buttons text-center">
                    <input type="submit" value="Добавить" class="conf-step__button conf-step__button-accent" data-event="seance_add">
                    <button class="conf-step__button conf-step__button-regular" type="button" data-popup-close>
                        Отменить
                    </button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>
