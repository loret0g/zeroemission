<section class="error-page container">
    <h1>Error <?= htmlspecialchars($errorCode ?? 500) ?></h1>
    <p><?= htmlspecialchars($errorMessage ?? "Ha ocurrido un error inesperado.") ?></p>
    <a href="/" class="btn">Volver a la página principal</a>
</section>