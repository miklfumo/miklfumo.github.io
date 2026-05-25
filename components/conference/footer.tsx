import { Mail } from "lucide-react";

export function Footer() {
  return (
    <footer className="border-t border-border bg-card" role="contentinfo">
      <div className="mx-auto max-w-7xl px-6 py-16">
        <div className="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
          {/* Brand */}
          <div className="lg:col-span-1">
            <a href="#" className="flex items-center gap-3 text-foreground">
              <div className="flex h-10 w-10 items-center justify-center rounded-md border border-border bg-background">
                <span className="text-xs font-bold text-primary leading-none">
                  {"ФУ"}
                  <br />
                  {"МО"}
                </span>
              </div>
              <span className="text-sm font-bold tracking-tight">
                ФУМО ВО ИБ
              </span>
            </a>
            <p className="mt-3 text-sm text-muted-foreground leading-relaxed">
              {"Всероссийская научно-практическая конференция \u00ABКадровое обеспечение информационной безопасности Российской Федерации\u00BB"}
            </p>
          </div>

          {/* Quick links */}
          <div>
            <h3 className="text-sm font-semibold text-foreground uppercase tracking-wider">
              Конференция
            </h3>
            <ul className="mt-4 space-y-2">
              {[
                { href: "#about", label: "О конференции" },
                { href: "#goals", label: "Цели" },
                { href: "#speakers", label: "Спикеры" },
                { href: "#schedule", label: "Программа" },
                { href: "#gallery", label: "Галерея" },
              ].map((link) => (
                <li key={link.href}>
                  <a
                    href={link.href}
                    className="text-sm text-muted-foreground transition-colors hover:text-primary"
                  >
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Information */}
          <div>
            <h3 className="text-sm font-semibold text-foreground uppercase tracking-wider">
              Информация
            </h3>
            <ul className="mt-4 space-y-2">
              {[
                "Политика конфиденциальности",
                "Договор-оферта",
                "Обработка персональных данных",
                "Условия участия",
              ].map((item) => (
                <li key={item}>
                  <a
                    href="#"
                    className="text-sm text-muted-foreground transition-colors hover:text-primary"
                  >
                    {item}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h3 className="text-sm font-semibold text-foreground uppercase tracking-wider">
              Контакты
            </h3>
            <ul className="mt-4 space-y-3 text-sm text-muted-foreground">
              <li>
                <p className="text-xs text-muted-foreground/70 uppercase tracking-wider">
                  Общие вопросы
                </p>
                <a
                  href="mailto:info@isedu.ru"
                  className="flex items-center gap-2 transition-colors hover:text-primary"
                >
                  <Mail className="h-3.5 w-3.5" />
                  info@isedu.ru
                </a>
              </li>
              <li>
                <p className="text-xs text-muted-foreground/70 uppercase tracking-wider">
                  Партнёрство
                </p>
                <a
                  href="mailto:partners@isedu.ru"
                  className="flex items-center gap-2 transition-colors hover:text-primary"
                >
                  <Mail className="h-3.5 w-3.5" />
                  partners@isedu.ru
                </a>
              </li>
              <li>
                <p className="text-xs text-muted-foreground/70 uppercase tracking-wider">
                  Регистрация
                </p>
                <a
                  href="mailto:reg@isedu.ru"
                  className="flex items-center gap-2 transition-colors hover:text-primary"
                >
                  <Mail className="h-3.5 w-3.5" />
                  reg@isedu.ru
                </a>
              </li>
            </ul>
          </div>
        </div>

        <div className="mt-12 border-t border-border pt-8 text-center">
          <p className="text-xs text-muted-foreground">
            {"\u00A9 2026 Кадры ИБ. Все права защищены."}
          </p>
        </div>
      </div>
    </footer>
  );
}
