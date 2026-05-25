<?php
/**
 * Fixed navigation bar
 * Флаги включения секций задаются в includes/functions.php -> get_feature_flags().
 */
$flags = get_feature_flags();
$showSpeakersSection = !empty($flags['speakers_enabled']);
$showScheduleSection = !empty($flags['schedule_enabled']);

$navLinks = [
    ['href' => '#about', 'label' => 'О конференции', 'enabled' => true],
    ['href' => '#goals', 'label' => 'Цели', 'enabled' => true],
    ['href' => '#speakers', 'label' => 'Спикеры', 'enabled' => $showSpeakersSection],
    ['href' => '#schedule', 'label' => 'Программа', 'enabled' => $showScheduleSection],
    ['href' => '#partners', 'label' => 'Партнёры', 'enabled' => true],
    ['href' => '#gallery', 'label' => 'Галерея', 'enabled' => true],
    ['href' => '#venue', 'label' => 'Место', 'enabled' => true],
];
?>
<header class="nav" role="banner">
    <nav class="nav__inner" aria-label="Основная навигация">
        <a
            href="http://xn--90anlixf.xn--p1ai/"
            class="nav__logo"
            aria-label="Кадры ИБ — официальный сайт"
            target="_blank"
            rel="noopener noreferrer"
        >
            <img src="images/logo_var.png" alt="Логотип Кадры ИБ" class="nav__logo-image" width="44" height="44">
            <span class="nav__logo-text">Кадры ИБ</span>
        </a>

        <ul class="nav__links">
            <?php foreach ($navLinks as $link): ?>
                <li>
                    <?php if (!empty($link['enabled'])): ?>
                        <a href="<?= e($link['href']) ?>"><?= e($link['label']) ?></a>
                    <?php else: ?>
                        <span class="nav__link-disabled" aria-disabled="true">
                            <span><?= e($link['label']) ?></span>
                            <span class="nav__soon">Скоро будет</span>
                        </span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <a href="#registration" class="btn btn--primary nav__cta">Регистрация</a>

        <button type="button" class="nav__toggle" aria-label="Открыть меню" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </nav>

    <div class="nav__mobile">
        <?php foreach ($navLinks as $link): ?>
            <?php if (!empty($link['enabled'])): ?>
                <a href="<?= e($link['href']) ?>"><?= e($link['label']) ?></a>
            <?php else: ?>
                <span class="nav__link-disabled" aria-disabled="true">
                    <span><?= e($link['label']) ?></span>
                    <span class="nav__soon">Скоро будет</span>
                </span>
            <?php endif; ?>
        <?php endforeach; ?>
        <a href="#registration" class="nav__mobile-cta">Регистрация</a>
    </div>
</header>
