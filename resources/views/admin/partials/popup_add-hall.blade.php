<div class="popup" id="popup-add-hall">
    <div class="popup__container">
      <div class="popup__content">
        <div class="popup__header">
          <h2 class="popup__title">
            Добавление зала
            <a class="popup__dismiss"
               href="#"
               data-popup-close
            >
                <img src="{{ asset('assets/images/admin/close.png') }}" alt="Закрыть">
            </a>
          </h2>
        </div>
        <div class="popup__wrapper">
            <form method="POST" action="{{ route('admin.halls.store') }}">
                @csrf
                <label class="conf-step__label conf-step__label-fullsize" for="title">
                  Название зала
                  <input class="conf-step__input" type="text" placeholder="Например, &laquo;Зал 1&raquo;" name="title" required>
                </label>
                <label class="conf-step__label conf-step__label-fullsize" for="rows">
                    Сколько рядов
                    <input class="conf-step__input" type="number" placeholder="Например 1" name="rows" required>
                </label>
                <label class="conf-step__label conf-step__label-fullsize" for="cols">
                    Сколько мест в ряду
                    <input class="conf-step__input" type="number" placeholder="Например 2" name="cols" required>
                </label>

                <div class="conf-step__buttons text-center">
                  <input type="submit" value="Добавить зал" class="conf-step__button conf-step__button-accent" data-event="hall_add">
                  <button
                      class="conf-step__button conf-step__button-regular"
                      type="button"
                      data-popup-close
                  >
                      Отменить
                  </button>
                </div>
          </form>
        </div>
      </div>
    </div>
</div>
