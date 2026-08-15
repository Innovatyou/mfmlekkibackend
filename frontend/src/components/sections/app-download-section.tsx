"use client";

import type { ReactNode } from "react";
import type { LandingContent } from "@/lib/api";
import { Reveal } from "@/components/ui/reveal";
import { hasText } from "@/lib/utils";

export function AppDownloadSection({ content }: { content: LandingContent }) {
  const hasAndroid = hasText(content.android_app_url);
  const hasIos = hasText(content.ios_app_url);

  if (!hasAndroid && !hasIos) return null;

  return (
    <section id="get-app" className="bg-surface-muted py-24">
      <div className="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
        <Reveal>
          <span className="inline-flex rounded-full bg-[color-mix(in_srgb,var(--primary)_12%,transparent)] px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[var(--primary)]">
            Mobile App
          </span>
          <h2 className="mt-4 font-heading text-3xl font-semibold text-foreground sm:text-4xl">
            {content.app_download_title}
          </h2>
          {hasText(content.app_download_subtitle) && (
            <p className="mx-auto mt-4 max-w-xl text-lg text-muted-foreground">
              {content.app_download_subtitle}
            </p>
          )}
          <div className="mt-10 flex flex-wrap items-center justify-center gap-4">
            {hasIos && (
              <StoreBadge
                href={content.ios_app_url}
                icon={<AppleIcon className="h-7 w-7" />}
                eyebrow="Download on the"
                label="App Store"
              />
            )}
            {hasAndroid && (
              <StoreBadge
                href={content.android_app_url}
                icon={<PlayIcon className="h-6 w-6" />}
                eyebrow="GET IT ON"
                label="Google Play"
              />
            )}
          </div>
        </Reveal>
      </div>
    </section>
  );
}

function StoreBadge({
  href,
  icon,
  eyebrow,
  label,
}: {
  href: string;
  icon: ReactNode;
  eyebrow: string;
  label: string;
}) {
  return (
    <a
      href={href}
      target="_blank"
      rel="noopener noreferrer"
      className="flex items-center gap-3 rounded-2xl border border-white/10 bg-[#111114] px-6 py-3.5 text-left text-white shadow-lg transition-transform hover:scale-105"
    >
      <span className="shrink-0 text-white">{icon}</span>
      <span>
        <span className="block text-[10px] font-medium uppercase tracking-wider text-white/65">
          {eyebrow}
        </span>
        <span className="block font-heading text-base font-semibold leading-tight">{label}</span>
      </span>
    </a>
  );
}

function AppleIcon({ className }: { className?: string }) {
  return (
    <svg viewBox="0 0 24 24" fill="currentColor" className={className} aria-hidden="true">
      <path d="M16.365 1.43c0 1.14-.417 2.06-1.25 2.76-.833.7-1.72 1.05-2.66.95-.116-1.1.31-2.02 1.14-2.76.75-.66 1.62-1.03 2.62-1.1.1.05.15.1.15.15Zm3.815 16.86c-.42.98-.94 1.9-1.56 2.76-.85 1.2-1.55 2.03-2.09 2.5-.83.75-1.72 1.14-2.66 1.16-.68.02-1.5-.19-2.44-.63-.95-.44-1.82-.65-2.61-.65-.83 0-1.72.21-2.68.65-.96.44-1.73.67-2.32.69-.9.04-1.8-.36-2.71-1.2-.58-.5-1.31-1.36-2.19-2.6-.94-1.32-1.72-2.86-2.32-4.61-.65-1.9-.98-3.74-.98-5.52 0-2.04.44-3.8 1.32-5.28.86-1.44 2-2.58 3.42-3.41 1.31-.77 2.7-1.16 4.19-1.19.72-.02 1.66.22 2.83.72 1.13.48 1.86.72 2.19.72.25 0 1.07-.28 2.44-.85 1.29-.52 2.38-.74 3.28-.66 2.42.2 4.24 1.15 5.45 2.85-2.17 1.31-3.24 3.15-3.22 5.5.02 1.83.68 3.35 1.98 4.55.59.56 1.25.99 1.98 1.3-.16.46-.33.9-.51 1.31Z" />
    </svg>
  );
}

function PlayIcon({ className }: { className?: string }) {
  return (
    <svg viewBox="0 0 24 24" fill="none" className={className} aria-hidden="true">
      <path d="M4.5 3.5c-.3.3-.5.7-.5 1.2v14.6c0 .5.2.9.5 1.2l.1.1L13 12l-8.4-8.6-.1.1Z" fill="#00D8FF" />
      <path d="M13 12 4.6 3.4c.2-.1.5-.2.7-.2.3 0 .6.1.9.2l10.1 5.8L13 12Z" fill="#00F076" />
      <path d="M16.3 15.2 13 12l3.3-3.3 2.4 1.4c.7.4 1.1.9 1.1 1.5s-.4 1.1-1.1 1.5l-2.4 1.1Z" fill="#FFCF00" />
      <path d="M4.6 20.6c-.2-.1-.5-.2-.7-.2l9.1-9 3.3 3.3-10.1 5.8c-.3.1-.6.2-.9.1Z" fill="#FF3A44" />
    </svg>
  );
}
