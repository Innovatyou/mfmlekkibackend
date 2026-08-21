import type { Church, LandingContent, SocialSettings } from "@/lib/api";
import { MediaImage } from "@/components/ui/media-image";
import { hasText } from "@/lib/utils";

const SOCIAL_ICONS: Record<keyof SocialSettings, (props: { className?: string }) => React.ReactElement> = {
  facebook: (props) => (
    <svg viewBox="0 0 24 24" fill="currentColor" {...props} aria-hidden="true">
      <path d="M13.5 21v-7.9h2.65l.4-3.08H13.5V8.06c0-.89.25-1.5 1.52-1.5h1.63V3.8A21.8 21.8 0 0 0 14.3 3.7c-2.24 0-3.78 1.37-3.78 3.87v2.45H8v3.08h2.52V21h3Z" />
    </svg>
  ),
  twitter: (props) => (
    <svg viewBox="0 0 24 24" fill="currentColor" {...props} aria-hidden="true">
      <path d="M20.5 6.2c-.63.28-1.3.47-2 .56a3.5 3.5 0 0 0 1.53-1.93 6.9 6.9 0 0 1-2.2.85 3.47 3.47 0 0 0-5.9 3.16A9.85 9.85 0 0 1 4.65 5.1a3.47 3.47 0 0 0 1.07 4.63 3.44 3.44 0 0 1-1.57-.43v.04a3.47 3.47 0 0 0 2.78 3.4c-.46.13-.95.15-1.44.06a3.48 3.48 0 0 0 3.24 2.41 6.96 6.96 0 0 1-4.3 1.48c-.28 0-.56-.02-.83-.05a9.8 9.8 0 0 0 5.32 1.56c6.38 0 9.87-5.29 9.87-9.87v-.45c.68-.48 1.26-1.09 1.72-1.78Z" />
    </svg>
  ),
  instagram: (props) => (
    <svg viewBox="0 0 24 24" fill="currentColor" {...props} aria-hidden="true">
      <path d="M12 8.4a3.6 3.6 0 1 0 0 7.2 3.6 3.6 0 0 0 0-7.2Zm0 5.94a2.34 2.34 0 1 1 0-4.68 2.34 2.34 0 0 1 0 4.68Zm4.6-6.08a.84.84 0 1 1-1.68 0 .84.84 0 0 1 1.68 0ZM12 4.9c2.4 0 2.68.01 3.63.05.87.04 1.34.19 1.66.31.42.16.72.36 1.03.67.32.31.51.61.68 1.03.12.32.27.79.31 1.66.04.95.05 1.23.05 3.63 0 2.4-.01 2.68-.05 3.63-.04.87-.19 1.34-.31 1.66-.17.42-.36.72-.68 1.03-.31.32-.61.51-1.03.68-.32.12-.79.27-1.66.31-.95.04-1.23.05-3.63.05-2.4 0-2.68-.01-3.63-.05-.87-.04-1.34-.19-1.66-.31a2.78 2.78 0 0 1-1.03-.68 2.78 2.78 0 0 1-.68-1.03c-.12-.32-.27-.79-.31-1.66-.04-.95-.05-1.23-.05-3.63 0-2.4.01-2.68.05-3.63.04-.87.19-1.34.31-1.66.17-.42.36-.72.68-1.03.31-.31.61-.51 1.03-.67.32-.12.79-.27 1.66-.31.95-.04 1.23-.05 3.63-.05ZM12 3c-2.44 0-2.74.01-3.7.05-.96.05-1.62.2-2.2.43a4.7 4.7 0 0 0-1.7 1.1 4.7 4.7 0 0 0-1.1 1.7c-.23.58-.38 1.24-.43 2.2C3.01 9.44 3 9.74 3 12s.01 2.56.05 3.52c.05.96.2 1.62.43 2.2.24.6.55 1.12 1.1 1.66a4.7 4.7 0 0 0 1.7 1.1c.58.23 1.24.38 2.2.43.96.04 1.26.05 3.52.05s2.56-.01 3.52-.05c.96-.05 1.62-.2 2.2-.43a4.7 4.7 0 0 0 1.66-1.1c.55-.54.86-1.06 1.1-1.66.23-.58.38-1.24.43-2.2.04-.96.05-1.26.05-3.52s-.01-2.56-.05-3.52c-.05-.96-.2-1.62-.43-2.2a4.7 4.7 0 0 0-1.1-1.7 4.7 4.7 0 0 0-1.66-1.1c-.58-.23-1.24-.38-2.2-.43C14.56 3.01 14.26 3 12 3Z" />
    </svg>
  ),
  youtube: (props) => (
    <svg viewBox="0 0 24 24" fill="currentColor" {...props} aria-hidden="true">
      <path d="M21.6 7.7a2.8 2.8 0 0 0-1.97-2C18 5.2 12 5.2 12 5.2s-6 0-7.63.5A2.8 2.8 0 0 0 2.4 7.7 29 29 0 0 0 1.9 12c0 1.44.16 2.88.5 4.3a2.8 2.8 0 0 0 1.97 2c1.63.5 7.63.5 7.63.5s6 0 7.63-.5a2.8 2.8 0 0 0 1.97-2c.34-1.42.5-2.86.5-4.3 0-1.44-.16-2.88-.5-4.3ZM10 15V9l5.2 3-5.2 3Z" />
    </svg>
  ),
  website: (props) => (
    <svg viewBox="0 0 24 24" fill="none" {...props} aria-hidden="true">
      <circle cx="12" cy="12" r="8.5" stroke="currentColor" strokeWidth="1.6" />
      <path
        d="M3.5 12h17M12 3.5c2.2 2.4 3.4 5.4 3.4 8.5s-1.2 6.1-3.4 8.5c-2.2-2.4-3.4-5.4-3.4-8.5s1.2-6.1 3.4-8.5Z"
        stroke="currentColor"
        strokeWidth="1.6"
      />
    </svg>
  ),
};

export function Footer({
  church,
  settings,
  content,
}: {
  church: Church;
  settings: SocialSettings;
  content: LandingContent;
}) {
  const year = new Date().getFullYear();
  const brandLogo = hasText(content.header_logo) ? content.header_logo : church.logo;
  const socialEntries = (Object.keys(settings) as (keyof SocialSettings)[]).filter((key) =>
    hasText(settings[key])
  );

  return (
    <footer className="border-t border-border bg-surface">
      <div className="mx-auto flex max-w-7xl flex-col items-center gap-6 px-4 py-10 text-center sm:px-6 lg:px-8">
        {hasText(brandLogo) && (
          <MediaImage
            src={brandLogo}
            alt={`${church.name} logo`}
            className="max-h-20 w-auto max-w-56 object-contain"
            fallback={null}
          />
        )}

        {socialEntries.length > 0 && (
          <div className="flex items-center gap-3">
            {socialEntries.map((key) => {
              const Icon = SOCIAL_ICONS[key];
              return (
                <a
                  key={key}
                  href={settings[key]}
                  target="_blank"
                  rel="noopener noreferrer"
                  aria-label={key}
                  className="flex h-9 w-9 items-center justify-center rounded-full border border-border text-muted-foreground transition-colors hover:border-[var(--primary)] hover:text-[var(--primary)]"
                >
                  <Icon className="h-4 w-4" />
                </a>
              );
            })}
          </div>
        )}

        <p className="text-sm text-muted-foreground">
          {hasText(content.footer_text) ? content.footer_text : `© ${year} ${church.name}. All rights reserved.`}
        </p>
      </div>
    </footer>
  );
}
