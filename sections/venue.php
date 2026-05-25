<?php
/**
 * Venue section with Yandex Maps
 */
$config = get_conference_config();
?>
<section id="venue" class="section" aria-labelledby="venue-heading">
    <div class="container">
        <div class="text-center">
            <p class="section-label">Место проведения</p>
            <h2 id="venue-heading" class="text-balance" style="margin-top:0.75rem;"><?= e($config['venue']) ?></h2>
            <p class="section-desc"><?= e($config['address']) ?></p>
        </div>

        <div class="venue__grid">
            <!-- Yandex Map -->
            <div class="venue__map">
                <iframe
                    src="https://yandex.ru/map-widget/v1/?ll=37.4800%2C55.6700&z=16&pt=37.4800%2C55.6700%2Cpm2rdm&lang=ru_RU"
                    title="<?= e($config['venue']) ?> на Яндекс Картах"
                    loading="lazy"
                    allowfullscreen
                ></iframe>
            </div>

            <!-- Getting there info -->
            <div class="venue__info">
                <h3 style="font-size:1.125rem;">Как добраться</h3>

                <div class="venue__info-item">
                    <div class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"/></svg>
                    </div>
                    <div>
                        <h4 class="venue__info-title">Авиасообщение</h4>
                        <p class="venue__info-text">
                            Аэропорт Внуково &mdash; 30 минут на аэроэкспрессе до Киевского вокзала, далее метро.
                            Аэропорт Домодедово и Шереметьево &mdash; 60&ndash;90 минут.
                        </p>
                    </div>
                </div>

                <div class="venue__info-item">
                    <div class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M4 11h16"/><path d="M12 3v8"/></svg>
                    </div>
                    <div>
                        <h4 class="venue__info-title">Общественный транспорт</h4>
                        <p class="venue__info-text">
                            Станция метро &laquo;Юго-Западная&raquo; (Сокольническая линия) &mdash; 5 минут пешком.
                            Также можно доехать от станции &laquo;Тропарёво&raquo;.
                        </p>
                    </div>
                </div>

                <div class="venue__info-item">
                    <div class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7v11a2 2 0 002 2h14a2 2 0 002-2V7"/><path d="M21 7l-9-4-9 4"/><path d="M12 12v4"/><circle cx="12" cy="12" r="1"/></svg>
                    </div>
                    <div>
                        <h4 class="venue__info-title">Проживание</h4>
                        <p class="venue__info-text">
                            В пешей доступности расположены гостиницы различных категорий. Рекомендуем бронировать заранее.
                        </p>
                    </div>
                </div>

                <div class="venue__info-item">
                    <div class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <h4 class="venue__info-title">Адрес</h4>
                        <p class="venue__info-text">
                            <?= e($config['venue']) ?><br>
                            проспект Вернадского, 78, стр. 6<br>
                            г. Москва, <?= e($config['zip']) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
