"use client";

import type { LandingContent, LeadershipMember } from "@/lib/api";
import { Reveal } from "@/components/ui/reveal";
import { TiltCard } from "@/components/ui/tilt-card";
import { Avatar } from "@/components/ui/avatar";
import { hasText, truncate } from "@/lib/utils";

export function LeadershipSection({
  content,
  leadership,
}: {
  content: LandingContent;
  leadership: LeadershipMember[];
}) {
  return (
    <section id="leadership" className="bg-surface-muted py-24">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <Reveal className="mx-auto max-w-2xl text-center">
          <h2 className="font-heading text-3xl font-semibold text-foreground sm:text-4xl">
            {content.leadership_title}
          </h2>
          {hasText(content.leadership_subtitle) && (
            <p className="mt-4 text-lg text-muted-foreground">{content.leadership_subtitle}</p>
          )}
        </Reveal>

        {leadership.length === 0 ? (
          <p className="mt-12 text-center text-muted-foreground">
            Our leadership profiles will be added here soon.
          </p>
        ) : (
          <div className="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {leadership.map((leader, i) => (
              <Reveal key={`${leader.id}-${i}`} delay={Math.min(i * 0.06, 0.3)}>
                <TiltCard className="flex h-full flex-col items-center rounded-2xl border border-border bg-surface p-8 text-center">
                  <Avatar src={leader.photo} name={leader.name} className="h-24 w-24 text-2xl" />
                  <h3 className="mt-5 font-heading text-lg font-semibold text-foreground">
                    {leader.name}
                  </h3>
                  <p className="mt-1 text-sm font-medium text-[var(--primary)]">{leader.role_title}</p>
                  {hasText(leader.bio) && (
                    <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
                      {truncate(leader.bio, 140)}
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
