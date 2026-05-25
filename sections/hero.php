<?php
/**
 * Hero section
 */
$config = get_conference_config();
$flags = get_feature_flags();
$isRegistrationEnabled = !empty($flags['registration_enabled']);
?>
<section id="hero" class="hero" aria-label="Главный баннер конференции">
    <img src="images/hero-bg.jpg" alt="" class="hero__bg" aria-hidden="true">
    <div class="hero__overlay" aria-hidden="true"></div>

    <div class="hero__content">
        <div class="badge<?= $isRegistrationEnabled ? '' : ' badge--closed' ?>">
            <span class="badge__dot"></span>
            <?= $isRegistrationEnabled ? 'Регистрация открыта' : 'Регистрация завершена' ?>
        </div>

        <p class="hero__label"><?= e($config['title']) ?></p>

        <h1 class="hero__title text-balance">
            <span><?= e($config['name']) ?></span>
        </h1>

        <p class="hero__subtitle"><?= e($config['subtitle']) ?></p>

        <div class="hero__meta">
            <div class="hero__meta-item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <time datetime="<?= e($config['date_start']) ?>"><?= e($config['dates']) ?></time>
            </div>
            <div class="hero__meta-item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span><?= e($config['venue']) ?></span>
            </div>
        </div>

        <div class="hero__buttons">
            <a href="#registration" class="btn btn--primary">
                Регистрация
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
            <a href="#schedule" class="btn btn--secondary">Программа</a>
        </div>

        <div class="hero__stats">
            <div class="text-center">
                <p class="hero__stat-number">150+</p>
                <p class="hero__stat-label">Образовательных организаций</p>
            </div>
            <div class="text-center">
                <p class="hero__stat-number">50+</p>
                <p class="hero__stat-label">Спикеров</p>
            </div>
            <div class="text-center">
                <p class="hero__stat-number">500+</p>
                <p class="hero__stat-label">Участников</p>
            </div>
        </div>
    </div>
</section>
