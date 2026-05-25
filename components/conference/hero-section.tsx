import Image from "next/image";

export function HeroSection() {
  return (
    <section
      id="hero"
      className="relative flex min-h-screen items-center justify-center overflow-hidden"
      aria-label="Главный баннер конференции"
    >
      {/* Background image */}
      <div className="absolute inset-0">
        <Image
          src="/images/hero-bg.jpg"
          alt=""
          fill
          className="object-cover"
          priority
        />
        <div className="absolute inset-0 bg-background/80" />
      </div>

      {/* Content */}
      <div className="relative z-10 mx-auto max-w-4xl px-6 py-32 text-center">
        <div className="inline-flex items-center gap-2 rounded-full border border-primary/30 bg-primary/10 px-4 py-1.5 text-xs font-medium text-primary">
          <span className="h-1.5 w-1.5 rounded-full bg-primary animate-pulse" />
          {"Регистрация открыта"}
        </div>

        <div className="mt-6">
          <p className="text-xs font-medium tracking-[0.3em] text-primary/60 uppercase sm:text-sm">
            {"Всероссийская научно-практическая конференция"}
          </p>
          <div className="mx-auto mt-4 h-px w-16 bg-gradient-to-r from-transparent via-primary/40 to-transparent" />
          <h1 className="mt-5 text-balance font-bold tracking-tight text-foreground">
            <span className="block text-xl sm:text-2xl lg:text-3xl">{"Кадровое обеспечение"}</span>
            <span className="mt-1 block bg-gradient-to-r from-primary via-[hsl(200,90%,60%)] to-primary bg-clip-text text-2xl text-transparent sm:text-3xl lg:text-[2.75rem] lg:leading-tight">
              {"информационной безопасности"}
            </span>
            <span className="mt-1 block text-xl sm:text-2xl lg:text-3xl">{"Российской Федерации"}</span>
          </h1>
        </div>

        <p className="mt-6 text-sm text-muted-foreground/70 font-medium tracking-widest uppercase">
          {"XXX юбилейный Пленум ФУМО ВО ИБ"}
        </p>

        <div className="mt-8 flex flex-wrap items-center justify-center gap-6 text-sm text-muted-foreground">
          <div className="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="text-primary"><rect x="3" y="4" width="18" height="18" rx="2" ry="2" /><line x1="16" y1="2" x2="16" y2="6" /><line x1="8" y1="2" x2="8" y2="6" /><line x1="3" y1="10" x2="21" y2="10" /></svg>
            <time dateTime="2026-10-28">{"28\u201330 октября 2026"}</time>
          </div>
          <div className="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="text-primary"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" /><circle cx="12" cy="10" r="3" /></svg>
            <span>{"РТУ МИРЭА, г. Москва"}</span>
          </div>
        </div>

        <div className="mt-10 flex flex-wrap items-center justify-center gap-4">
          <a
            href="#registration"
            className="inline-flex items-center gap-2 rounded-md bg-primary px-6 py-3 text-sm font-semibold text-primary-foreground transition-opacity hover:opacity-90"
          >
            {"Регистрация"}
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><line x1="5" y1="12" x2="19" y2="12" /><polyline points="12 5 19 12 12 19" /></svg>
          </a>
          <a
            href="#schedule"
            className="inline-flex items-center rounded-md border border-border bg-background/50 px-6 py-3 text-sm font-semibold text-foreground transition-colors hover:bg-secondary"
          >
            {"Программа"}
          </a>
        </div>

        <div className="mt-16 grid grid-cols-3 gap-4 sm:gap-8">
          <div className="text-center">
            <p className="text-2xl font-bold text-primary sm:text-4xl">{"150+"}</p>
            <p className="mt-1 text-xs text-muted-foreground sm:text-sm">{"Образовательных организаций"}</p>
          </div>
          <div className="text-center">
            <p className="text-2xl font-bold text-primary sm:text-4xl">{"50+"}</p>
            <p className="mt-1 text-xs text-muted-foreground sm:text-sm">{"Спикеров"}</p>
          </div>
          <div className="text-center">
            <p className="text-2xl font-bold text-primary sm:text-4xl">{"500+"}</p>
            <p className="mt-1 text-xs text-muted-foreground sm:text-sm">{"Участников"}</p>
          </div>
        </div>
      </div>
    </section>
  );
}
