<?php
/**
 * Registration form section
 */
$config = get_conference_config();
$smartCaptchaSiteKey = $config['smartcaptcha_sitekey'] ?? '';
$flags = get_feature_flags();
$isRegistrationEnabled = !empty($flags['registration_enabled']);
?>
<section id="registration" class="section section--alt" aria-labelledby="registration-heading">
    <div class="container">
        <div class="text-center">
            <p class="section-label">Регистрация</p>
            <h2 id="registration-heading" class="text-balance" style="margin-top:0.75rem;">Зарегистрироваться</h2>
            <p class="section-desc">Выберите категорию участия и заполните форму регистрации.</p>
        </div>

        <?php if (!$isRegistrationEnabled): ?>
            <div class="section-soon-card" role="status" aria-live="polite" style="margin-top:2rem;">
                <div class="section-soon-card__title">Регистрация закрыта</div>
                <p class="section-soon-card__text">Регистрация завершена, до встречи в следующем году!</p>
            </div>
        <?php else: ?>
        <div id="reg-success-box" class="reg__success" style="display:none;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <h2 style="margin-top:1.5rem;">Заявка отправлена</h2>
            <p id="reg-success-text" class="text-muted" style="margin-top:0.75rem;"></p>
        </div>

        <div id="reg-form-wrapper">
            <div class="reg__selector">
                <button type="button" class="reg__selector-btn is-active" data-type="education">
                    Образовательные организации и ФОИВ
                </button>
                <button type="button" class="reg__selector-btn" data-type="other">
                    Иные организации
                </button>
            </div>

            <form action="/register" method="POST" class="reg__form" novalidate>
                <input type="hidden" name="category" id="category-input" value="education">
                <input type="hidden" name="is_paid" id="is-paid-input" value="0">
                <input type="hidden" name="payment_type" id="payment-type-input" value="company">

                <div class="reg__errors" style="display:none;"></div>

                <div class="form-group">
                    <label for="reg-fullname" class="form-label">ФИО <span class="required">*</span></label>
                    <input type="text" id="reg-fullname" name="full_name" required class="form-input" placeholder="Иванов Иван Иванович">
                </div>

                <div class="form-group">
                    <label for="reg-email" class="form-label">Рабочая электронная почта <span class="required">*</span></label>
                    <input type="email" id="reg-email" name="email" required class="form-input" placeholder="ivanov@university.ru">
                </div>

                <div class="form-group">
                    <label for="reg-phone" class="form-label">Телефон <span class="required">*</span></label>
                    <input type="tel" id="reg-phone" name="phone" required class="form-input" placeholder="+7 (999) 123-45-67">
                </div>

                <div class="form-group">
                    <label for="reg-organization" class="form-label">Организация <span class="required">*</span></label>
                    <input type="text" id="reg-organization" name="organization" required class="form-input" placeholder="Название организации">
                </div>

                <div class="form-group">
                    <label for="reg-position" class="form-label">Должность <span class="required">*</span></label>
                    <input type="text" id="reg-position" name="position" required class="form-input" placeholder="Должность">
                </div>

                <div id="report-fields" style="margin-bottom:1.25rem;">
                    <div class="form-check" style="margin-bottom:0.75rem;">
                        <input type="checkbox" id="reg-plans-report" name="plans_report" value="1">
                        <label for="reg-plans-report">Планирую выступление с докладом</label>
                    </div>
                    <div class="form-group" id="report-topic-group" style="display:none;">
                        <label for="reg-report-topic" class="form-label">Тема выступления:</label>
                        <input type="text" id="reg-report-topic" name="report_topic" class="form-input" maxlength="255" placeholder="Введите название доклада">
                    </div>
                </div>

                <div id="other-fields" style="display:none;">
                    <div class="form-group" style="margin-bottom:1.25rem;">
                        <label for="reg-org-type" class="form-label">Тип организации <span class="required">*</span></label>
                        <select id="reg-org-type" name="org_type" class="form-select">
                            <option value="">Выберите тип</option>
                            <option value="apkits_azi">Члены АПКИТ и члены АЗИ</option>
                            <option value="dpo">Представители центров и организаций ДПО</option>
                            <option value="other">Иные организации</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom:1.25rem;">
                        <p class="form-label">Тип оплаты</p>
                        <div class="payment-toggle">
                            <button type="button" class="payment-toggle__btn is-active" data-payment="company">Оплата от организации</button>
                            <button type="button" class="payment-toggle__btn" data-payment="self">Оплата за себя</button>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:1.25rem;">
                        <label for="reg-inn" class="form-label">ИНН <span class="required">*</span></label>
                        <input type="text" id="reg-inn" name="inn" class="form-input font-mono" maxlength="10" placeholder="1234567890">
                        <p class="form-hint">Для оплаты от организации: 10 цифр, для оплаты за себя: 12 цифр.</p>
                    </div>

                    <div class="form-check" style="margin-bottom:1.25rem;">
                        <input type="checkbox" id="reg-partner" name="wants_partner" value="1">
                        <label for="reg-partner">
                            Хотим получить статус и возможности партнёра конференции
                            <br>
                            <small style="color:var(--color-fg-muted)">Варианты партнёрства обсуждаются с оператором в отдельном порядке.</small>
                        </label>
                    </div>
                </div>

                <?php if (!empty($smartCaptchaSiteKey)): ?>
                    <div class="captcha-box">
                        <label class="form-label">Проверка <span class="required">*</span></label>
                        <div id="captcha-container" class="smart-captcha" data-sitekey="<?= e($smartCaptchaSiteKey) ?>" data-hl="ru" data-callback="onSmartCaptchaSuccess" data-expired-callback="onSmartCaptchaTokenExpired" data-network-error-callback="onSmartCaptchaNetworkError" style="height:100px;"></div>
                        <input type="hidden" id="smartcaptcha-token" name="smart_token" value="">
                        <p class="captcha-box__hint">Подтвердите, что вы не робот.</p>
                    </div>
                <?php endif; ?>

                <div class="form-check">
                    <input type="checkbox" id="reg-personal-data" name="agreed_personal_data" value="1">
                    <label for="reg-personal-data">Даю согласие на обработку персональных данных. <span class="required">*</span></label>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="reg-offer" name="agreed_offer" value="1">
                    <label for="reg-offer">Принимаю условия договора-оферты. <span class="required">*</span></label>
                </div>

                <button type="submit" class="btn btn--primary" style="width:100%;margin-top:0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Зарегистрироваться
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>
</section>
