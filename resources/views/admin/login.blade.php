@extends('admin.layouts.app')

@section('title', 'Идём в кино')

@section('content')
    <main>
        <section class="login">
            <header class="login__header">
                <h2 class="login__title">Авторизация</h2>
            </header>
            <div class="login__wrapper">

                @if ($errors->any())
                    <div style="margin-bottom: 12px; color: #b91c1c;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form class="login__form"
                      action="{{ route('admin.login.post') }}"
                      method="POST"
                      accept-charset="utf-8">
                    @csrf

                    <label class="login__label">
                        E-mail
                        <input class="login__input"
                               type="email"
                               placeholder="example@domain.xyz"
                               name="email"
                               value="{{ old('email') }}"
                               required>
                    </label>

                    <label class="login__label">
                        Пароль
                        <input class="login__input"
                               type="password"
                               name="password"
                               required>
                    </label>

                    <div class="text-center">
                        <input value="Авторизоваться" type="submit" class="login__button">
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
