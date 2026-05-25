<?php
/**
 * Closing HTML tags and scripts
 */
$config = get_conference_config();
$smartCaptchaSiteKey = $config['smartcaptcha_sitekey'] ?? '';
?>
<?php if (!empty($smartCaptchaSiteKey)): ?>
<script src="https://smartcaptcha.cloud.yandex.ru/captcha.js" defer></script>
<?php endif; ?>
<script src="assets/js/main.js" defer></script>
</body>
</html>
