"use client";

import Link from "next/link";
import { motion } from "framer-motion";
import type { LandingContent } from "@/lib/api";
import { HeroBackground } from "@/components/hero/hero-background";

export function HeroSection({ content }: { content: LandingContent }) {
  return (
    <section className="relative flex min-h-[92vh] w-full items-center overflow-hidden bg-background">
      <div className="absolute inset-0 z-0">
        <HeroBackground primaryColor={content.primary_color} imageUrl={content.hero_image} />
      </div>

      {content.hero_image ? (
        <div
          className="pointer-events-none absolute inset-0 z-[1]"
          style={{ backgroundColor: `rgba(0, 0, 0, ${content.hero_overlay_opacity / 100})` }}
        />
      ) : (
        <div className="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-background via-background/40 to-background/10" />
      )}

      <div className="relative z-10 mx-auto w-full max-w-4xl px-4 py-32 text-center sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ duration: 0.7 }}
          className="mb-7 flex items-center justify-center gap-3"
          style={{ color: content.hero_text_color }}
          aria-hidden="true"
        >
          <span className="h-px w-10 bg-[color-mix(in_srgb,var(--primary)_55%,transparent)]" />
          <OrnamentIcon className="h-4 w-4" />
          <span className="h-px w-10 bg-[color-mix(in_srgb,var(--primary)_55%,transparent)]" />
        </motion.div>
        <motion.h1
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, ease: [0.22, 1, 0.36, 1] }}
          className="text-balance font-heading text-4xl font-semibold leading-[1.08] text-foreground sm:text-5xl md:text-[3.75rem]"
          style={{ color: content.hero_text_color }}
        >
          {content.hero_title}
        </motion.h1>
        <motion.p
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, delay: 0.12, ease: [0.22, 1, 0.36, 1] }}
          className="mx-auto mt-6 max-w-2xl text-balance text-lg text-muted-foreground sm:text-xl"
          style={{ color: content.hero_text_color }}
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

      <motion.div
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ duration: 1, delay: 0.8 }}
        className="pointer-events-none absolute inset-x-0 bottom-7 z-10 hidden justify-center sm:flex"
        aria-hidden="true"
      >
        <span className="flex h-9 w-9 animate-bounce items-center justify-center rounded-full border border-border/70 text-muted-foreground">
          <svg viewBox="0 0 24 24" fill="none" className="h-4 w-4">
            <path d="M6 9l6 6 6-6" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </span>
      </motion.div>
    </section>
  );
}

function OrnamentIcon({ className }: { className?: string }) {
  return (
    <svg viewBox="0 0 24 24" fill="currentColor" className={className} aria-hidden="true">
      <path d="M12 0c.6 4.6 2 8.2 4.2 10.4S20.6 13.6 24 14c-4.6.6-8.2 2-10.4 4.2S13.6 20.6 13 24c-.6-4.6-2-8.2-4.2-10.4S3.4 10.4 0 10c4.6-.6 8.2-2 10.4-4.2S10.4 3.4 12 0Z" />
    </svg>
  );
}
