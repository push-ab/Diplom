 <div class="popup" id="popup-add-film">
  <div class="popup__container">
    <div class="popup__content">
      <div class="popup__header">
        <h2 class="popup__title">
          Добавление фильма
          <a class="popup__dismiss" href="#" data-popup-close>
              <img src="{{ asset('assets/images/admin/close.png') }}" alt="Закрыть">
          </a>
        </h2>
      </div>
      <div class="popup__wrapper">
          <form action="{{ route('admin.movies.store') }}"
                method="post"
                accept-charset="utf-8"
                enctype="multipart/form-data">
              @csrf

              <div class="popup__container">
                  <div class="popup__poster"></div>
                  <div class="popup__form">

                      <label class="conf-step__label conf-step__label-fullsize">
                          Название фильма
                          <input class="conf-step__input" type="text" name="title" required>
                      </label>

                      <label class="conf-step__label conf-step__label-fullsize">
                          Продолжительность фильма (мин.)
                          <input class="conf-step__input" type="number" name="duration_minutes" min="1" required>
                      </label>

                      <label class="conf-step__label conf-step__label-fullsize">
                          Описание фильма
                          <textarea class="conf-step__input" name="description"></textarea>
                      </label>

                      <label class="conf-step__label conf-step__label-fullsize">
                          Возрастной рейтинг
                          <input class="conf-step__input" type="text" name="age_rating" placeholder="12+">
                      </label>

                      <label class="conf-step__label conf-step__label-fullsize">
                          Постер
                          <input class="conf-step__input" type="file" name="poster" accept="image/*">
                      </label>

                  </div>
              </div>

              <div class="conf-step__buttons text-center">
                  <input type="submit" value="Добавить фильм" class="conf-step__button conf-step__button-accent">
                  <button class="conf-step__button conf-step__button-regular" type="button" data-popup-close>Отменить</button>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>
