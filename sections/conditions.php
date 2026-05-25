<?php
/**
 * Conditions of participation section
 */
$checkIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
$ticketIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 003 3v0a3 3 0 003-3V7a2 2 0 012-2h4a2 2 0 012 2v2a3 3 0 003 3v0a3 3 0 003-3V5a2 2 0 00-2-2H4a2 2 0 00-2 2z"/><path d="M2 15a3 3 0 013-3v0a3 3 0 013 3v2a2 2 0 002 2h4a2 2 0 002-2v-2a3 3 0 013-3v0a3 3 0 013 3v4a2 2 0 01-2 2H4a2 2 0 01-2-2z"/></svg>';
$alertIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
?>
<section id="conditions" class="section" aria-labelledby="conditions-heading">
    <div class="container">
        <div class="text-center">
            <p class="section-label">Участие</p>
            <h2 id="conditions-heading" class="text-balance" style="margin-top:0.75rem;">Условия участия</h2>
        </div>

        <div class="conditions__body">
            <!-- Free participation -->
            <div class="card card--highlight">
                <div class="flex items-start gap-4">
                    <div class="icon-box"><?= $checkIcon ?></div>
                    <div>
                        <h3>Бесплатное участие</h3>
                        <p style="margin-top:0.5rem;font-size:0.875rem;color:var(--color-fg-muted);line-height:1.6;">
                            Для представителей федеральных органов исполнительной власти (ФОИВ)
                            и бюджетных научно-образовательных учреждений при обязательной регистрации
                            <em>(квота по согласованию с ФУМО ИБ)</em>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Paid participation -->
            <div class="card">
                <div class="flex items-start gap-4">
                    <div class="icon-box"><?= $ticketIcon ?></div>
                    <div class="flex-1">
                        <h3>Платное участие</h3>
                        <div class="conditions__list">
                            <div class="conditions__row">
                                <span class="conditions__row-label">Иные организации <span class="conditions__row-note">(стоимость за одного участника)</span></span>
                                <span class="conditions__row-price">47 250 &#8381;<span class="conditions__row-note">(НДС 5%)</span></span>
                            </div>
                            <div class="conditions__row">
                                <span class="conditions__row-label">Члены АПКИТ и члены АЗИ <span class="conditions__row-note">(стоимость за одного участника)</span></span>
                                <span class="conditions__row-price">39 900 &#8381;<span class="conditions__row-note">(НДС 5%)</span></span>
                            </div>
                            <div class="conditions__row">
                                <span class="conditions__row-label">Представители центров и организаций ДПО</span>
                                <span class="conditions__row-price">23 100 &#8381;<span class="conditions__row-note">(НДС 5%)</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- What's included -->
            <div class="card">
                <h3>В билет входит</h3>
                <ul class="conditions__includes">
                    <li><span class="conditions__dot"></span> Участие во всех мероприятиях конференции</li>
                    <li><span class="conditions__dot"></span> Информационные материалы</li>
                    <li><span class="conditions__dot"></span> Питание по программе</li>
                </ul>
            </div>

            <!-- Disclaimer -->
            <div class="conditions__disclaimer">
                <?= $alertIcon ?>
                <p>Организатор вправе отказать в участии без объяснения причин.</p>
            </div>
        </div>
    </div>
</section>
