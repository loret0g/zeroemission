<?php
use App\Services\FlashService;
$flash = FlashService::getFlash();
?>
<?php if (!empty($flash['error'])): ?>
  <div id="flashMessage" class="flash-message flash-message--error"><?= htmlspecialchars($flash['error']) ?></div>
<?php endif; ?>
<?php if (!empty($flash['success'])): ?>
  <div id="flashMessage" class="flash-message flash-message--success"><?= htmlspecialchars($flash['success']) ?></div>
<?php endif; ?>