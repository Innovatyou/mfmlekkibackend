"use client";

import { motion } from "framer-motion";
import type { LandingContent } from "@/lib/api";
import { hasText } from "@/lib/utils";

/**
 * A fast, static-gradient header for the standalone Become a Member page.
 * Deliberately skips the full WebGL scene — this page should load quickly
 * and stay focused on the form.
 */
export function MembershipHeader({ content }: { content: LandingContent }) {
  const title = hasText(content.signup_title) ? content.signup_title : "Become a Member";
  const subtitle = hasText(content.signup_subtitle)
    ? content.signup_subtitle
    : "We'd love to have you join our church family. Fill out the form below and we'll follow up soon.";

  return (
    <section className="relative overflow-hidden py-20 sm:py-28">
      <div
        className="absolute inset-0"
        style={{
          backgroundImage:
            "radial-gradient(circle at 20% 15%, color-mix(in srgb, var(--primary) 26%, transparent), transparent 55%), radial-gradient(circle at 85% 85%, color-mix(in srgb, var(--primary) 16%, transparent), transparent 55%)",
        }}
      />
      <motion.div
        initial={{ opacity: 0, y: 16 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.6, ease: [0.22, 1, 0.36, 1] }}
        className="relative mx-auto max-w-2xl px-4 text-center sm:px-6 lg:px-8"
      >
        <h1 className="text-balance font-heading text-4xl font-semibold text-foreground sm:text-5xl">
          {title}
        </h1>
        <p className="mt-5 text-balance text-lg text-muted-foreground">{subtitle}</p>
      </motion.div>
    </section>
  );
}
