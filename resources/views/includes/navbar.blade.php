<nav class="navbar is-primary" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item" href="{{ route('home.index') }}">
      {{ config('app.name') }}
    </a>

    <a
      role="button"
      class="navbar-burger burger"
      aria-label="menu"
      aria-expanded="false"
      data-target="navBar">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

  <div id="navBar" class="navbar-menu">
    @auth
      @php
        $user = Auth::user();
      @endphp
      <div class="navbar-start">
        @if ($user->hasPermission('marked-houses'))
          <a class="navbar-item" href="{{ route('home.marked-houses') }}">
            {{ __('Marked houses') }}
          </a>
        @endif
        @if ($user->hasPermission('exhibitors'))
          <a class="navbar-item" href="{{ route('home.exhibitors') }}">
            {{ __('Exhibitors') }}
          </a>
        @endif
      </div>
    @endauth

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="button is-light" onClick="logout(event)">
              Salir
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</nav>

<script charset="utf-8">
  document.addEventListener('DOMContentLoaded', () => {
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
    if ($navbarBurgers.length > 0) {
      $navbarBurgers.forEach( el => {
        el.addEventListener('click', (e) => {
          e.stopPropagation();
          const target = el.dataset.target;
          const $target = document.getElementById(target);
          el.classList.toggle('is-active');
          $target.classList.toggle('is-active');
        });
      });
    }
  });

  document.addEventListener('click', () => {
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
    $navbarBurgers.forEach( el => {
      const target = el.dataset.target;
      const $target = document.getElementById(target);
      el.classList.remove('is-active');
      $target.classList.remove('is-active');
    });
  });

  const logout = (e) => {
    localStorage.publishersAssigned = '';
    e.target.closest('form').submit();
  }
</script>
