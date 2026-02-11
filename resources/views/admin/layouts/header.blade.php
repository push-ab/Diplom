<header class="page-header">
    <h1 class="page-header__title">@yield('title', 'Идём в кино')</h1>
    <span class="page-header__subtitle">Администраторррская</span>

    @auth
        <form method="POST"
              action="{{ route('admin.logout') }}"
              style="margin-left:auto;">
            @csrf
            <button type="submit" class="page-header__logout">
                Выйти
            </button>
        </form>
    @endauth
</header>
