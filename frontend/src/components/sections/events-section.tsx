"use client";

import type { ChurchEvent, LandingContent } from "@/lib/api";
import { Reveal } from "@/components/ui/reveal";
import { TiltCard } from "@/components/ui/tilt-card";
import { MediaImage } from "@/components/ui/media-image";
import { formatDate, hasText, truncate } from "@/lib/utils";

export function EventsSection({ content, events }: { content: LandingContent; events: ChurchEvent[] }) {
  return (
    <section id="events" className="bg-background py-24">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <Reveal className="mx-auto max-w-2xl text-center">
          <h2 className="font-heading text-3xl font-semibold text-foreground sm:text-4xl">
            {content.events_title}
          </h2>
          {hasText(content.events_subtitle) && (
            <p className="mt-4 text-lg text-muted-foreground">{content.events_subtitle}</p>
          )}
        </Reveal>

        {events.length === 0 ? (
          <p className="mt-12 text-center text-muted-foreground">
            No upcoming events posted yet — check back soon.
          </p>
        ) : (
          <div
            className="mt-14 grid justify-center gap-6"
            style={{ gridTemplateColumns: "repeat(auto-fit, minmax(280px, 360px))" }}
          >
            {events.map((event, i) => (
              <Reveal key={`${event.id}-${i}`} delay={Math.min(i * 0.06, 0.3)}>
                <TiltCard className="h-full overflow-hidden rounded-2xl border border-border bg-surface">
                  <div className="relative aspect-[16/10] w-full overflow-hidden">
                    <MediaImage
                      src={event.thumbnail}
                      alt={event.title}
                      className="h-full w-full object-cover"
                      fallback={
                        <div
                          className="flex h-full w-full items-center justify-center"
                          style={{
                            backgroundImage:
                              "linear-gradient(135deg, color-mix(in srgb, var(--primary) 45%, transparent), color-mix(in srgb, var(--primary) 10%, transparent))",
                          }}
                        >
                          <CalendarIcon className="h-9 w-9 text-[var(--primary-foreground)] opacity-80" />
                        </div>
                      }
                    />
                  </div>
                  <div className="p-6">
                    {hasText(event.date) && (
                      <p className="text-xs font-semibold uppercase tracking-wide text-[var(--primary)]">
                        {formatDate(event.date)}
                      </p>
                    )}
                    <h3 className="mt-2 font-heading text-lg font-semibold text-foreground">
                      {event.title}
                    </h3>
                    {hasText(event.description) && (
                      <p className="mt-2 text-sm leading-relaxed text-muted-foreground">
                        {truncate(event.description, 120)}
                      </p>
                    )}
                  </div>
                </TiltCard>
              </Reveal>
            ))}
          </div>
        )}
      </div>
    </section>
  );
}

function CalendarIcon({ className }: { className?: string }) {
  return (
    <svg viewBox="0 0 24 24" fill="none" className={className} aria-hidden="true">
      <rect x="3.5" y="5" width="17" height="15.5" rx="2.2" stroke="currentColor" strokeWidth="1.6" />
      <path d="M3.5 9.5h17M8 3v3.5M16 3v3.5" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" />
    </svg>
  );
}
