"use client";

import React from "react";
import { useState, useCallback, useEffect } from "react";
import { Send, CheckCircle2, Loader2, HelpCircle } from "lucide-react";

type ParticipantType = "education" | "other";
type PaymentType = "organization" | "individual";

function generateCaptcha(): { question: string; answer: number } {
  const a = Math.floor(Math.random() * 20) + 1;
  const b = Math.floor(Math.random() * 20) + 1;
  return { question: `${a} + ${b} = ?`, answer: a + b };
}

function validateInn(inn: string, isOrg: boolean): boolean {
  const digits = inn.replace(/\D/g, "");
  if (isOrg) return digits.length === 10;
  return digits.length === 12;
}

const inputClass =
  "mt-1.5 w-full rounded-md border border-border bg-background px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary";

export function RegistrationSection() {
  const [participantType, setParticipantType] = useState<ParticipantType>("education");
  const [paymentType, setPaymentType] = useState<PaymentType>("organization");
  const [captcha, setCaptcha] = useState<{ question: string; answer: number } | null>(null);
  const [captchaInput, setCaptchaInput] = useState("");
  const [agreedPersonalData, setAgreedPersonalData] = useState(false);
  const [agreedOffer, setAgreedOffer] = useState(false);
  const [wantPartner, setWantPartner] = useState(false);
  const [submitted, setSubmitted] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [innValue, setInnValue] = useState("");
  const [innError, setInnError] = useState<string | null>(null);
  const [showPartnerTooltip, setShowPartnerTooltip] = useState(false);

  useEffect(() => {
    setCaptcha(generateCaptcha());
  }, []);

  const resetCaptcha = useCallback(() => {
    setCaptcha(generateCaptcha());
    setCaptchaInput("");
  }, []);

  const handleInnChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const val = e.target.value.replace(/\D/g, "");
    setInnValue(val);
    setInnError(null);
  };

  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setError(null);
    setInnError(null);

    // Validate INN for "other" participant type
    if (participantType === "other" && innValue) {
      const isOrg = paymentType === "organization";
      if (!validateInn(innValue, isOrg)) {
        setInnError(
          isOrg
            ? "ИНН юридического лица должен содержать 10 цифр"
            : "ИНН физического лица должен содержать 12 цифр"
        );
        return;
      }
    }

    if (!captcha || Number(captchaInput) !== captcha.answer) {
      setError("Неверный ответ. Попробуйте ещё раз.");
      resetCaptcha();
      return;
    }

    if (!agreedPersonalData) {
      setError("Необходимо дать согласие на обработку персональных данных.");
      return;
    }

    if (!agreedOffer) {
      setError("Необходимо принять условия договора-оферты.");
      return;
    }

    setLoading(true);
    setTimeout(() => {
      setLoading(false);
      setSubmitted(true);
    }, 1500);
  };

  if (submitted) {
    return (
      <section
        id="registration"
        className="border-t border-border bg-card py-24"
        aria-labelledby="registration-heading"
      >
        <div className="mx-auto max-w-lg px-6 text-center">
          <CheckCircle2 className="mx-auto h-16 w-16 text-primary" />
          <h2 className="mt-6 text-2xl font-bold text-foreground">
            Регистрация завершена
          </h2>
          <p className="mt-3 text-muted-foreground">
            Благодарим за регистрацию. Подтверждение отправлено на указанный
            адрес электронной почты. Ждём вас на Пленуме ФУМО ВО ИБ.
          </p>
        </div>
      </section>
    );
  }

  return (
    <section
      id="registration"
      className="border-t border-border bg-card py-24"
      aria-labelledby="registration-heading"
    >
      <div className="mx-auto max-w-7xl px-6">
        <div className="mx-auto max-w-2xl text-center">
          <p className="text-xs font-semibold tracking-widest text-primary uppercase">
            Регистрация
          </p>
          <h2
            id="registration-heading"
            className="mt-3 text-balance text-2xl font-bold tracking-tight text-foreground sm:text-3xl md:text-4xl"
          >
            Регистрация на конференцию
          </h2>
          <p className="mt-4 text-muted-foreground">
            Выберите тип участия и заполните форму регистрации.
          </p>
        </div>

        {/* Participant type selector */}
        <div className="mx-auto mt-10 flex max-w-lg justify-center gap-2">
          <button
            type="button"
            onClick={() => setParticipantType("education")}
            className={`flex-1 rounded-md px-4 py-2.5 text-sm font-medium transition-colors ${
              participantType === "education"
                ? "bg-primary text-primary-foreground"
                : "bg-secondary text-muted-foreground hover:text-foreground"
            }`}
          >
            Образовательные организации и ФОИВ
          </button>
          <button
            type="button"
            onClick={() => setParticipantType("other")}
            className={`flex-1 rounded-md px-4 py-2.5 text-sm font-medium transition-colors ${
              participantType === "other"
                ? "bg-primary text-primary-foreground"
                : "bg-secondary text-muted-foreground hover:text-foreground"
            }`}
          >
            Иные организации
          </button>
        </div>

        <form
          onSubmit={handleSubmit}
          className="mx-auto mt-8 max-w-lg space-y-5"
          noValidate
        >
          {/* Common fields */}
          <div>
            <label htmlFor="reg-fullname" className="block text-sm font-medium text-foreground">
              ФИО <span className="text-primary">*</span>
            </label>
            <input
              type="text"
              id="reg-fullname"
              name="fullname"
              required
              className={inputClass}
              placeholder="Иванов Иван Иванович"
            />
          </div>

          <div>
            <label htmlFor="reg-email" className="block text-sm font-medium text-foreground">
              Рабочая электронная почта <span className="text-primary">*</span>
            </label>
            <input
              type="email"
              id="reg-email"
              name="email"
              required
              className={inputClass}
              placeholder="ivanov@university.ru"
            />
          </div>

          <div>
            <label htmlFor="reg-organization" className="block text-sm font-medium text-foreground">
              Организация <span className="text-primary">*</span>
            </label>
            <input
              type="text"
              id="reg-organization"
              name="organization"
              required
              className={inputClass}
              placeholder="Название организации"
            />
          </div>

          <div>
            <label htmlFor="reg-position" className="block text-sm font-medium text-foreground">
              Должность <span className="text-primary">*</span>
            </label>
            <input
              type="text"
              id="reg-position"
              name="position"
              required
              className={inputClass}
              placeholder="Заведующий кафедрой"
            />
          </div>

          <div>
            <label htmlFor="reg-phone" className="block text-sm font-medium text-foreground">
              Телефон <span className="text-primary">*</span>
            </label>
            <input
              type="tel"
              id="reg-phone"
              name="phone"
              required
              className={inputClass}
              placeholder="+7 (999) 123-45-67"
            />
          </div>

          {/* Additional fields for "other" type */}
          {participantType === "other" && (
            <>
              {/* Payment type toggle */}
              <div>
                <p className="mb-2 text-sm font-medium text-foreground">
                  Тип оплаты
                </p>
                <div className="flex gap-2">
                  <button
                    type="button"
                    onClick={() => {
                      setPaymentType("organization");
                      setInnValue("");
                      setInnError(null);
                    }}
                    className={`flex-1 rounded-md px-4 py-2 text-sm font-medium transition-colors ${
                      paymentType === "organization"
                        ? "bg-primary/15 text-primary border border-primary/30"
                        : "bg-secondary text-muted-foreground border border-border hover:text-foreground"
                    }`}
                  >
                    Оплата от организации
                  </button>
                  <button
                    type="button"
                    onClick={() => {
                      setPaymentType("individual");
                      setInnValue("");
                      setInnError(null);
                    }}
                    className={`flex-1 rounded-md px-4 py-2 text-sm font-medium transition-colors ${
                      paymentType === "individual"
                        ? "bg-primary/15 text-primary border border-primary/30"
                        : "bg-secondary text-muted-foreground border border-border hover:text-foreground"
                    }`}
                  >
                    {"Оплата за себя (физлицо)"}
                  </button>
                </div>
              </div>

              {/* INN field */}
              <div>
                <label htmlFor="reg-inn" className="block text-sm font-medium text-foreground">
                  ИНН{" "}
                  <span className="text-xs text-muted-foreground font-normal">
                    ({paymentType === "organization" ? "10 цифр" : "12 цифр"})
                  </span>{" "}
                  <span className="text-primary">*</span>
                </label>
                <input
                  type="text"
                  id="reg-inn"
                  name="inn"
                  required
                  value={innValue}
                  onChange={handleInnChange}
                  maxLength={paymentType === "organization" ? 10 : 12}
                  className={`${inputClass} font-mono ${innError ? "border-destructive focus:border-destructive focus:ring-destructive" : ""}`}
                  placeholder={paymentType === "organization" ? "1234567890" : "123456789012"}
                />
                {innError && (
                  <p className="mt-1 text-xs text-destructive">{innError}</p>
                )}
              </div>

              {/* Partner checkbox */}
              <div className="flex items-start gap-3">
                <input
                  type="checkbox"
                  id="reg-partner"
                  checked={wantPartner}
                  onChange={(e) => setWantPartner(e.target.checked)}
                  className="mt-1 h-4 w-4 rounded border-border accent-primary"
                />
                <label htmlFor="reg-partner" className="flex items-center gap-1.5 text-sm text-muted-foreground">
                  {"Хотим получить статус и возможности партнёра конференции (мастер-класс, профориентационный модуль и т.д.)"}
                  <button
                    type="button"
                    className="relative"
                    onMouseEnter={() => setShowPartnerTooltip(true)}
                    onMouseLeave={() => setShowPartnerTooltip(false)}
                    onClick={() => setShowPartnerTooltip((v) => !v)}
                    aria-label="Подробнее о статусе партнёра"
                  >
                    <HelpCircle className="h-4 w-4 text-muted-foreground hover:text-primary" />
                    {showPartnerTooltip && (
                      <span className="absolute bottom-full left-1/2 z-10 mb-2 -translate-x-1/2 whitespace-nowrap rounded-md border border-border bg-card px-3 py-2 text-xs text-foreground shadow-lg">
                        {"Варианты партнёрства обсуждаются с оператором в отдельном порядке"}
                      </span>
                    )}
                  </button>
                </label>
              </div>
            </>
          )}

          {/* Captcha */}
          <div className="rounded-lg border border-border bg-background/50 p-4">
            <label htmlFor="reg-captcha" className="block text-sm font-medium text-foreground">
              Проверка <span className="text-primary">*</span>
            </label>
            <p className="mt-1 text-sm text-muted-foreground">
              {"Решите: "}
              <span className="font-mono font-semibold text-primary">
                {captcha ? captcha.question : "..."}
              </span>
            </p>
            <input
              type="number"
              id="reg-captcha"
              value={captchaInput}
              onChange={(e) => setCaptchaInput(e.target.value)}
              required
              className="mt-2 w-32 rounded-md border border-border bg-background px-4 py-2.5 text-sm text-foreground focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
              placeholder="Ответ"
            />
          </div>

          {/* Personal data consent */}
          <div className="flex items-start gap-3">
            <input
              type="checkbox"
              id="reg-personal-data"
              checked={agreedPersonalData}
              onChange={(e) => setAgreedPersonalData(e.target.checked)}
              className="mt-1 h-4 w-4 rounded border-border accent-primary"
            />
            <label htmlFor="reg-personal-data" className="text-sm text-muted-foreground">
              {"Даю согласие на обработку персональных данных в соответствии с "}
              <a href="#" className="text-primary underline-offset-2 hover:underline">
                {"Федеральным законом \u2116 152-ФЗ"}
              </a>
              {". "}
              <span className="text-primary">*</span>
            </label>
          </div>

          {/* Offer agreement */}
          <div className="flex items-start gap-3">
            <input
              type="checkbox"
              id="reg-offer"
              checked={agreedOffer}
              onChange={(e) => setAgreedOffer(e.target.checked)}
              className="mt-1 h-4 w-4 rounded border-border accent-primary"
            />
            <label htmlFor="reg-offer" className="text-sm text-muted-foreground">
              {"Принимаю условия "}
              <a href="#" className="text-primary underline-offset-2 hover:underline">
                договора-оферты
              </a>
              {". "}
              <span className="text-primary">*</span>
            </label>
          </div>

          {error && (
            <p className="text-sm text-destructive" role="alert">
              {error}
            </p>
          )}

          <button
            type="submit"
            disabled={loading}
            className="inline-flex w-full items-center justify-center gap-2 rounded-md bg-primary px-6 py-3 text-sm font-semibold text-primary-foreground transition-opacity hover:opacity-90 disabled:opacity-50"
          >
            {loading ? (
              <>
                <Loader2 className="h-4 w-4 animate-spin" />
                Отправка...
              </>
            ) : (
              <>
                <Send className="h-4 w-4" />
                Зарегистрироваться
              </>
            )}
          </button>
        </form>
      </div>
    </section>
  );
}
