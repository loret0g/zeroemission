<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? htmlspecialchars($title) : "Zero Emission Mallorca" ?></title>
  <link rel="icon" href="/assets/media/img/icon.png">
  <!-- Fuentes -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900&display=swap" rel="stylesheet">
  <!-- CSS -->
  <link rel="preload" href="/assets/css/normalize.css" as="style">
  <link rel="stylesheet" href="/assets/css/normalize.css">
  <link rel="preload" href="/assets/css/style.css" as="style">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
  <?php include __DIR__ . '/partials/header.php'; ?>
  <?php include __DIR__ . '/partials/flash.php'; ?>
  <main>
    <?= $content ?>
  </main>
  <?php include __DIR__ . '/partials/footer.php'; ?>

  <!-- Scripts JS -->
  <script src="/assets/js/app.js"></script>
  <script src="/assets/js/flash.js"></script>
  <script src="/assets/js/vehicleReservation.js"></script>
  <script src="/assets/js/auth.js"></script>
  <script src="/assets/js/account.js"></script>
</body>

</html>