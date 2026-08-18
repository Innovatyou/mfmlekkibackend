"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import clsx from "clsx";
import type { Church, LandingContent } from "@/lib/api";
import { API_URL } from "@/lib/api";
import { Avatar } from "@/components/ui/avatar";
import { ThemeToggle } from "@/components/ui/theme-toggle";
import { hasText } from "@/lib/utils";

interface NavLinkDef {
  href: string;
  label: string;
  show: boolean;
}

export function Navbar({
  church,
  content,
  isLive = false,
}: {
  church: Church;
  content: LandingContent;
  isLive?: boolean;
}) {
  const [open, setOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const brandName = hasText(content.header_text) ? content.header_text : church.name;
  const brandLogo = hasText(content.header_logo) ? content.header_logo : church.logo;

  useEffect(() => {
    function onScroll() {
      setScrolled(window.scrollY > 12);
    }
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  const links: NavLinkDef[] = [
    { href: "/#live", label: "Live", show: content.show_live },
    { href: "/#about", label: "About", show: content.show_about },
    { href: "/#service-times", label: "Service Times", show: content.show_service_times },
    { href: "/#events", label: "Events", show: content.show_events },
    { href: "/#sermons", label: "Sermons", show: content.show_sermons },
    { href: "/#gallery", label: "Gallery", show: content.show_gallery },
    { href: "/#leadership", label: "Leadership", show: content.show_leadership },
    {
      href: "/#get-app",
      label: "App",
      show: content.show_app_download && (hasText(content.android_app_url) || hasText(content.ios_app_url)),
    },
    { href: "/#contact", label: "Contact", show: content.show_contact },
  ].filter((link) => link.show);

  return (
    <header
      className={clsx(
        "sticky top-0 z-50 w-full border-b transition-colors",
        scrolled
          ? "border-border bg-background/85 backdrop-blur-md"
          : "border-transparent bg-background/40 backdrop-blur-sm"
      )}
    >
      <div className="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <Link href="/" className="flex min-w-0 items-center gap-3">
          <Avatar src={brandLogo} name={brandName} className="h-9 w-9 shrink-0 text-sm" />
          <span className="truncate font-heading text-lg font-semibold text-foreground">
            {brandName}
          </span>
        </Link>

        <nav className="hidden items-center gap-6 lg:flex">
          {links.map((link) => (
            <Link
              key={link.href}
              href={link.href}
              className="flex items-center gap-1.5 text-sm font-medium text-muted-foreground transition-colors hover:text-[var(--primary)]"
            >
              {link.label === "Live" && isLive && <LiveDot />}
              {link.label}
            </Link>
          ))}
        </nav>

        <div className="hidden items-center gap-3 lg:flex">
          <ThemeToggle />
          <a
            href={`${API_URL}/login`}
            className="text-sm font-medium text-muted-foreground transition-colors hover:text-[var(--primary)]"
          >
            Admin Login
          </a>
          {hasText(content.web_app_url) && (
            <a
              href={content.web_app_url}
              target="_blank"
              rel="noopener noreferrer"
              className="rounded-full border border-border px-4 py-2 text-sm font-semibold text-foreground transition-colors hover:border-[var(--primary)] hover:text-[var(--primary)]"
            >
              {content.web_app_login_text || "Member Login"}
            </a>
          )}
          <Link
            href="/become-a-member"
            className="rounded-full bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-[var(--primary-foreground)] shadow-sm transition-transform hover:scale-105"
          >
            Become a Member
          </Link>
        </div>

        <div className="flex items-center gap-2 lg:hidden">
          <ThemeToggle />
          <button
            type="button"
            aria-label={open ? "Close menu" : "Open menu"}
            aria-expanded={open}
            onClick={() => setOpen((v) => !v)}
            className="inline-flex h-9 w-9 items-center justify-center rounded-full border border-border text-foreground"
          >
            <svg viewBox="0 0 24 24" fill="none" className="h-5 w-5" aria-hidden="true">
              {open ? (
                <path
                  d="M6 6l12 12M18 6L6 18"
                  stroke="currentColor"
                  strokeWidth="1.8"
                  strokeLinecap="round"
                />
              ) : (
                <path
                  d="M4 7h16M4 12h16M4 17h16"
                  stroke="currentColor"
                  strokeWidth="1.8"
                  strokeLinecap="round"
                />
              )}
            </svg>
          </button>
        </div>
      </div>

      {open && (
        <div className="border-t border-border bg-background px-4 pb-6 pt-2 lg:hidden">
          <nav className="flex flex-col gap-1">
            {links.map((link) => (
              <Link
                key={link.href}
                href={link.href}
                onClick={() => setOpen(false)}
                className="flex items-center gap-1.5 rounded-lg px-3 py-2.5 text-sm font-medium text-muted-foreground transition-colors hover:bg-surface-muted hover:text-[var(--primary)]"
              >
                {link.label === "Live" && isLive && <LiveDot />}
                {link.label}
              </Link>
            ))}
            <a
              href={`${API_URL}/login`}
              className="rounded-lg px-3 py-2.5 text-sm font-medium text-muted-foreground transition-colors hover:bg-surface-muted hover:text-[var(--primary)]"
            >
              Admin Login
            </a>
            {hasText(content.web_app_url) && (
              <a
                href={content.web_app_url}
                target="_blank"
                rel="noopener noreferrer"
                onClick={() => setOpen(false)}
                className="rounded-lg px-3 py-2.5 text-sm font-medium text-muted-foreground transition-colors hover:bg-surface-muted hover:text-[var(--primary)]"
              >
                {content.web_app_login_text || "Member Login"}
              </a>
            )}
            <Link
              href="/become-a-member"
              onClick={() => setOpen(false)}
              className="mt-2 rounded-full bg-[var(--primary)] px-4 py-2.5 text-center text-sm font-semibold text-[var(--primary-foreground)]"
            >
              Become a Member
            </Link>
          </nav>
        </div>
      )}
    </header>
  );
}

function LiveDot() {
  return (
    <span className="relative flex h-2 w-2">
      <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-500 opacity-75" />
      <span className="relative inline-flex h-2 w-2 rounded-full bg-red-500" />
    </span>
  );
}
