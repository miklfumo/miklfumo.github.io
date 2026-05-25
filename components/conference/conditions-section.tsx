import { CheckCircle2, AlertCircle, Ticket } from "lucide-react";

export function ConditionsSection() {
  return (
    <section
      id="conditions"
      className="border-t border-border py-24"
      aria-labelledby="conditions-heading"
    >
      <div className="mx-auto max-w-7xl px-6">
        <div className="mx-auto max-w-2xl text-center">
          <p className="text-xs font-semibold tracking-widest text-primary uppercase">
            Участие
          </p>
          <h2
            id="conditions-heading"
            className="mt-3 text-balance text-3xl font-bold tracking-tight text-foreground sm:text-4xl"
          >
            Условия участия
          </h2>
        </div>

        <div className="mx-auto mt-12 max-w-3xl space-y-6">
          {/* Free participation */}
          <div className="rounded-lg border border-primary/30 bg-primary/5 p-6">
            <div className="flex items-start gap-4">
              <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-primary/10">
                <CheckCircle2 className="h-5 w-5 text-primary" />
              </div>
              <div>
                <h3 className="font-semibold text-foreground">
                  Бесплатное участие
                </h3>
                <p className="mt-2 text-sm text-muted-foreground leading-relaxed">
                  Для представителей федеральных органов исполнительной власти (ФОИВ)
                  и бюджетных научно-образовательных учреждений при обязательной
                  регистрации{" "}
                  <span className="text-xs text-muted-foreground/70">
                    (квота по согласованию с ФУМО ИБ)
                  </span>
                  .
                </p>
              </div>
            </div>
          </div>

          {/* Paid participation */}
          <div className="rounded-lg border border-border bg-card p-6">
            <div className="flex items-start gap-4">
              <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-primary/10">
                <Ticket className="h-5 w-5 text-primary" />
              </div>
              <div className="flex-1">
                <h3 className="font-semibold text-foreground">
                  Платное участие
                </h3>
                <ul className="mt-4 space-y-3">
                  <li className="flex flex-col gap-1 border-b border-border pb-3 sm:flex-row sm:items-baseline sm:justify-between sm:gap-4">
                    <span className="text-sm text-muted-foreground">
                      Иные организации
                    </span>
                    <span className="font-mono text-sm font-semibold text-foreground">
                      {"47 250 \u20BD"}
                      <span className="ml-1 text-xs font-normal text-muted-foreground">
                        (НДС 5%, за одного участника)
                      </span>
                    </span>
                  </li>
                  <li className="flex flex-col gap-1 border-b border-border pb-3 sm:flex-row sm:items-baseline sm:justify-between sm:gap-4">
                    <span className="text-sm text-muted-foreground">
                      {"Члены АПКИТ и члены АЗИ"}
                    </span>
                    <span className="font-mono text-sm font-semibold text-foreground">
                      {"39 900 \u20BD"}
                      <span className="ml-1 text-xs font-normal text-muted-foreground">
                        (НДС 5%, за одного участника)
                      </span>
                    </span>
                  </li>
                  <li className="flex flex-col gap-1 sm:flex-row sm:items-baseline sm:justify-between sm:gap-4">
                    <span className="text-sm text-muted-foreground">
                      {"Представители центров и организаций ДПО"}
                    </span>
                    <span className="font-mono text-sm font-semibold text-foreground">
                      {"23 100 \u20BD"}
                      <span className="ml-1 text-xs font-normal text-muted-foreground">
                        (НДС 5%, за одного участника)
                      </span>
                    </span>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          {/* What's included */}
          <div className="rounded-lg border border-border bg-card p-6">
            <h3 className="font-semibold text-foreground">В билет входит</h3>
            <ul className="mt-3 space-y-2 text-sm text-muted-foreground">
              <li className="flex items-center gap-2">
                <span className="h-1.5 w-1.5 shrink-0 rounded-full bg-primary" />
                Участие во всех мероприятиях конференции
              </li>
              <li className="flex items-center gap-2">
                <span className="h-1.5 w-1.5 shrink-0 rounded-full bg-primary" />
                Информационные материалы
              </li>
              <li className="flex items-center gap-2">
                <span className="h-1.5 w-1.5 shrink-0 rounded-full bg-primary" />
                Питание по программе
              </li>
            </ul>
          </div>

          {/* Disclaimer */}
          <div className="flex items-start gap-3 rounded-lg border border-border bg-background/50 p-4">
            <AlertCircle className="mt-0.5 h-4 w-4 shrink-0 text-muted-foreground" />
            <p className="text-xs text-muted-foreground leading-relaxed">
              Организатор вправе отказать в участии без объяснения причин.
            </p>
          </div>
        </div>
      </div>
    </section>
  );
}
