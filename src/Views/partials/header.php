<header class="header">
  <div class="container header__container">
    <a href="/" class="header__logo-link">
      <h1 class="header__logo">zeroEmission</h1>
    </a>
    <nav class="header__nav">
      <ul class="nav">
        <li class="nav__item"><a href="/#services" class="nav__link">Vehículos</a></li>
        <li class="nav__item"><a href="/about" class="nav__link">Nosotros</a></li>
        <?php if (\App\Services\SessionService::isAuthenticated()): ?>
          <li class="nav__item"><a href="/account" class="nav__link nav__link--account">Mi cuenta</a></li>
        <?php else: ?>
          <li class="nav__item"><a href="/auth" class="nav__link nav__link--account">Registro / Inicio de sesión</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>