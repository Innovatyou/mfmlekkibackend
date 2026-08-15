"use client";

import type { LandingContent, ServiceTime } from "@/lib/api";
import { Reveal } from "@/components/ui/reveal";
import { TiltCard } from "@/components/ui/tilt-card";
import { hasText } from "@/lib/utils";

export function ServiceTimesSection({
  content,
  serviceTimes,
}: {
  content: LandingContent;
  serviceTimes: ServiceTime[];
}) {
  return (
    <section id="service-times" className="bg-surface-muted py-24">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <Reveal className="mx-auto max-w-2xl text-center">
          <h2 className="font-heading text-3xl font-semibold text-foreground sm:text-4xl">
            {content.service_times_title}
          </h2>
          {hasText(content.service_times_subtitle) && (
            <p className="mt-4 text-lg text-muted-foreground">{content.service_times_subtitle}</p>
          )}
        </Reveal>

        {serviceTimes.length === 0 ? (
          <p className="mt-12 text-center text-muted-foreground">
            Service times will be posted here soon — check back shortly.
          </p>
        ) : (
          <div
            className="mt-14 grid justify-center gap-6"
            style={{ gridTemplateColumns: "repeat(auto-fit, minmax(280px, 340px))" }}
          >
            {serviceTimes.map((service, i) => (
              <Reveal key={`${service.id}-${i}`} delay={Math.min(i * 0.06, 0.3)}>
                <TiltCard className="h-full rounded-2xl border border-border bg-surface p-7">
                  <span className="inline-flex rounded-full bg-[color-mix(in_srgb,var(--primary)_14%,transparent)] px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[var(--primary)]">
                    {service.day_of_week}
                  </span>
                  <h3 className="mt-4 font-heading text-xl font-semibold text-foreground">
                    {service.name}
                  </h3>
                  <p className="mt-1 text-sm font-medium text-[var(--primary)]">{service.time_label}</p>
                  {hasText(service.location) && (
                    <p className="mt-3 text-sm text-muted-foreground">{service.location}</p>
                  )}
                  {hasText(service.description) && (
                    <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
                      {service.description}
                    </p>
                  )}
                </TiltCard>
              </Reveal>
            ))}
          </div>
        )}
      </div>
    </section>
  );
}
