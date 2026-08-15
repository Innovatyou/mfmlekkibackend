"use client";

import Link from "next/link";
import type { LandingContent } from "@/lib/api";
import { Reveal } from "@/components/ui/reveal";
import { hasText } from "@/lib/utils";

export function CtaBandSection({ content }: { content: LandingContent }) {
  return (
    <section className="relative overflow-hidden py-24">
      <div
        className="absolute inset-0"
        style={{
          backgroundImage:
            "linear-gradient(120deg, var(--primary), color-mix(in srgb, var(--primary) 60%, #1c1917))",
        }}
      />
      <Reveal className="relative mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
        <h2 className="font-heading text-3xl font-semibold text-[var(--primary-foreground)] sm:text-4xl">
          {content.signup_title}
        </h2>
        {hasText(content.signup_subtitle) && (
          <p className="mt-4 text-lg text-[color-mix(in_srgb,var(--primary-foreground)_85%,transparent)]">
            {content.signup_subtitle}
          </p>
        )}
        <div className="mt-9 flex flex-wrap items-center justify-center gap-4">
          <Link
            href="/become-a-member"
            className="rounded-full bg-[var(--primary-foreground)] px-7 py-3.5 text-sm font-semibold text-[var(--primary)] shadow-lg transition-transform hover:scale-105"
          >
            Become a Member
          </Link>
          {content.show_contact && (
            <a
              href="#contact"
              className="rounded-full border border-[color-mix(in_srgb,var(--primary-foreground)_45%,transparent)] px-7 py-3.5 text-sm font-semibold text-[var(--primary-foreground)] transition-colors hover:bg-[color-mix(in_srgb,var(--primary-foreground)_12%,transparent)]"
            >
              Get in Touch
            </a>
          )}
        </div>
      </Reveal>
    </section>
  );
}
