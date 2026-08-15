"use client";

import type { LandingContent } from "@/lib/api";
import { Reveal } from "@/components/ui/reveal";
import { ContactForm } from "@/components/contact/contact-form";
import { hasText } from "@/lib/utils";

export function ContactSection({ content }: { content: LandingContent }) {
  const hasDetails =
    hasText(content.contact_address) || hasText(content.contact_phone) || hasText(content.contact_email);
  const hasMap = hasText(content.contact_map_embed);
  const showInfo = hasDetails || hasMap;
  const showForm = content.show_contact_form;

  return (
    <section id="contact" className="bg-background py-24">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <Reveal className="mx-auto max-w-2xl text-center">
          <h2 className="font-heading text-3xl font-semibold text-foreground sm:text-4xl">
            {content.contact_title}
          </h2>
        </Reveal>

        <div
          className={
            showInfo && showForm
              ? "mt-14 grid grid-cols-1 gap-10 lg:grid-cols-2"
              : "mt-14 flex justify-center"
          }
        >
          {showInfo && (
            <Reveal className={`space-y-6 ${!showForm ? "w-full max-w-xl" : ""}`}>
              {hasText(content.contact_address) && (
                <ContactRow icon={<PinIcon />} label="Address" value={content.contact_address} />
              )}
              {hasText(content.contact_phone) && (
                <ContactRow
                  icon={<PhoneIcon />}
                  label="Phone"
                  value={content.contact_phone}
                  href={`tel:${content.contact_phone}`}
                />
              )}
              {hasText(content.contact_email) && (
                <ContactRow
                  icon={<MailIcon />}
                  label="Email"
                  value={content.contact_email}
                  href={`mailto:${content.contact_email}`}
                />
              )}
              {hasMap && (
                <div className="overflow-hidden rounded-2xl border border-border [&_iframe]:h-full [&_iframe]:w-full [&_iframe]:min-h-[280px]">
                  <div dangerouslySetInnerHTML={{ __html: content.contact_map_embed }} />
                </div>
              )}
            </Reveal>
          )}

          {showForm && (
            <Reveal delay={0.1} className={!showInfo ? "w-full max-w-xl" : ""}>
              {(hasText(content.contact_form_title) || hasText(content.contact_form_subtitle)) && (
                <div className="mb-5">
                  {hasText(content.contact_form_title) && (
                    <h3 className="font-heading text-xl font-semibold text-foreground">
                      {content.contact_form_title}
                    </h3>
                  )}
                  {hasText(content.contact_form_subtitle) && (
                    <p className="mt-1.5 text-sm text-muted-foreground">{content.contact_form_subtitle}</p>
                  )}
                </div>
              )}
              <ContactForm />
            </Reveal>
          )}
        </div>

        {!showInfo && !showForm && (
          <p className="mt-6 text-center text-muted-foreground">
            Contact details will be added here soon.
          </p>
        )}
      </div>
    </section>
  );
}

function ContactRow({
  icon,
  label,
  value,
  href,
}: {
  icon: React.ReactNode;
  label: string;
  value: string;
  href?: string;
}) {
  const content = (
    <div className="flex items-start gap-4 rounded-2xl border border-border bg-surface p-5">
      <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[color-mix(in_srgb,var(--primary)_14%,transparent)] text-[var(--primary)]">
        {icon}
      </span>
      <div>
        <p className="text-xs font-semibold uppercase tracking-wide text-muted-foreground">{label}</p>
        <p className="mt-0.5 whitespace-pre-line text-foreground">{value}</p>
      </div>
    </div>
  );
  return href ? (
    <a href={href} className="block transition-opacity hover:opacity-80">
      {content}
    </a>
  ) : (
    content
  );
}

function PinIcon() {
  return (
    <svg viewBox="0 0 24 24" fill="none" className="h-5 w-5" aria-hidden="true">
      <path
        d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z"
        stroke="currentColor"
        strokeWidth="1.6"
      />
      <circle cx="12" cy="9.5" r="2.4" stroke="currentColor" strokeWidth="1.6" />
    </svg>
  );
}

function PhoneIcon() {
  return (
    <svg viewBox="0 0 24 24" fill="none" className="h-5 w-5" aria-hidden="true">
      <path
        d="M5 4.5h3.2l1.4 4-2 1.4a11.5 11.5 0 0 0 5.5 5.5l1.4-2 4 1.4V18a1.5 1.5 0 0 1-1.6 1.5A15.5 15.5 0 0 1 3.5 6.1 1.5 1.5 0 0 1 5 4.5Z"
        stroke="currentColor"
        strokeWidth="1.6"
        strokeLinejoin="round"
      />
    </svg>
  );
}

function MailIcon() {
  return (
    <svg viewBox="0 0 24 24" fill="none" className="h-5 w-5" aria-hidden="true">
      <rect x="3.5" y="5.5" width="17" height="13" rx="2" stroke="currentColor" strokeWidth="1.6" />
      <path d="M4.5 7l7.5 6 7.5-6" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" />
    </svg>
  );
}
