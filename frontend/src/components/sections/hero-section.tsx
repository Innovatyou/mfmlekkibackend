"use client";

import Link from "next/link";
import { motion } from "framer-motion";
import type { LandingContent } from "@/lib/api";
import { HeroBackground } from "@/components/hero/hero-background";

export function HeroSection({ content }: { content: LandingContent }) {
  return (
    <section className="relative flex min-h-[92vh] w-full items-center overflow-hidden bg-background">
      <div className="absolute inset-0 z-0">
        <HeroBackground primaryColor={content.primary_color} />
      </div>

      {/* Legibility scrim, theme-aware */}
      <div className="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-background via-background/40 to-background/10" />

      <div className="relative z-10 mx-auto w-full max-w-4xl px-4 py-32 text-center sm:px-6 lg:px-8">
        <motion.h1
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, ease: [0.22, 1, 0.36, 1] }}
          className="text-balance font-heading text-4xl font-semibold leading-tight text-foreground sm:text-5xl md:text-6xl"
        >
          {content.hero_title}
        </motion.h1>
        <motion.p
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, delay: 0.12, ease: [0.22, 1, 0.36, 1] }}
          className="mx-auto mt-6 max-w-2xl text-balance text-lg text-muted-foreground sm:text-xl"
        >
          {content.hero_subtitle}
        </motion.p>
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, delay: 0.24, ease: [0.22, 1, 0.36, 1] }}
          className="mt-10 flex flex-wrap items-center justify-center gap-4"
        >
          <a
            href={content.hero_cta_link || "#service-times"}
            className="rounded-full bg-[var(--primary)] px-7 py-3.5 text-sm font-semibold text-[var(--primary-foreground)] shadow-lg shadow-[color-mix(in_srgb,var(--primary)_35%,transparent)] transition-transform hover:scale-105"
          >
            {content.hero_cta_text || "Plan Your Visit"}
          </a>
          <Link
            href="/become-a-member"
            className="rounded-full border border-border bg-surface/70 px-7 py-3.5 text-sm font-semibold text-foreground backdrop-blur-sm transition-colors hover:border-[var(--primary)] hover:text-[var(--primary)]"
          >
            Become a Member
          </Link>
        </motion.div>
      </div>
    </section>
  );
}
