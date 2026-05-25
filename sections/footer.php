<?php
/**
 * Footer section
 */
$config = get_conference_config();
$flags = get_feature_flags();
$showSpeakersSection = !empty($flags['speakers_enabled']);
$showScheduleSection = !empty($flags['schedule_enabled']);
$mailIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>';
?>
<footer class="footer" role="contentinfo">
    <div class="container">
        <div class="footer__grid">
            <div>
                <a href="#" class="nav__logo" style="display:inline-flex;">
                    <img src="images/logo_var.png" alt="Логотип Кадры ИБ" class="nav__logo-image" width="44" height="44">
                    <span class="nav__logo-text">Кадры ИБ</span>
                </a>
                <p class="footer__brand-desc">
                    Всероссийская конференция «Кадры ИБ»
                    Кадровое обеспечение информационной безопасности Российской Федерации
                </p>
            </div>

            <div>
                <h3 class="footer__heading">Конференция</h3>
                <ul class="footer__links">
                    <li><a href="#about">О конференции</a></li>
                    <li><a href="#goals">Цели</a></li>
                    <li>
                        <?php if ($showSpeakersSection): ?>
                            <a href="#speakers">Спикеры</a>
                        <?php else: ?>
                            <span class="footer__link-disabled">Спикеры <span class="footer__soon">Скоро будет</span></span>
                        <?php endif; ?>
                    </li>
                    <li>
                        <?php if ($showScheduleSection): ?>
                            <a href="#schedule">Программа</a>
                        <?php else: ?>
                            <span class="footer__link-disabled">Программа <span class="footer__soon">Скоро будет</span></span>
                        <?php endif; ?>
                    </li>
                    <li><a href="#gallery">Галерея</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer__heading">Информация</h3>
                <ul class="footer__links">
                    <li><a href="#">Политика конфиденциальности</a></li>
                    <li><a href="#">Договор-оферта</a></li>
                    <li><a href="#">Обработка персональных данных</a></li>
                    <li><a href="#conditions">Условия участия</a></li>
                </ul>
            </div>

            <div>
                <h3 class="footer__heading">Контакты</h3>
                <div>
                    <p class="footer__contact-label">Общие вопросы</p>
                    <a href="mailto:<?= e($config['email_info']) ?>" class="footer__contact-link">
                        <?= $mailIcon ?>
                        <?= e($config['email_info']) ?>
                    </a>
                </div>
                <div>
                    <p class="footer__contact-label">Партнёрство</p>
                    <a href="mailto:<?= e($config['email_partners']) ?>" class="footer__contact-link">
                        <?= $mailIcon ?>
                        <?= e($config['email_partners']) ?>
                    </a>
                </div>
                <div>
                    <p class="footer__contact-label">Регистрация</p>
                    <a href="mailto:<?= e($config['email_reg']) ?>" class="footer__contact-link">
                        <?= $mailIcon ?>
                        <?= e($config['email_reg']) ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer__bottom">
            <p>&copy; 2026 Кадры ИБ. Все права защищены.</p>
        </div>
    </div>
</footer>
