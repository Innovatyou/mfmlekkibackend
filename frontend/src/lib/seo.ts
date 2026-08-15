import type { Metadata } from "next";
import type { LandingContentResponse } from "@/lib/api";
import { hasText } from "@/lib/utils";

/**
 * Builds Next.js Metadata from the church's admin-configured SEO settings,
 * with sensible fallbacks to existing content fields so a church that hasn't
 * touched the SEO tab yet still gets a reasonable title/description.
 */
export function buildMetadata(
  data: LandingContentResponse,
  overrides?: { title?: string; description?: string }
): Metadata {
  const { church, content } = data;
  const name = church.name || "Our Church";

  const title =
    overrides?.title || (hasText(content.seo_meta_title) ? content.seo_meta_title : `${name} | Home`);
  const description =
    overrides?.description ||
    (hasText(content.seo_meta_description)
      ? content.seo_meta_description
      : hasText(content.hero_subtitle)
        ? content.hero_subtitle
        : `Welcome to ${name}.`);

  const ogImage = hasText(content.seo_og_image)
    ? content.seo_og_image
    : hasText(content.hero_image)
      ? content.hero_image
      : undefined;

  return {
    title,
    description,
    ...(hasText(content.seo_meta_keywords)
      ? {
          keywords: content.seo_meta_keywords
            .split(",")
            .map((k) => k.trim())
            .filter(Boolean),
        }
      : {}),
    openGraph: {
      title,
      description,
      siteName: name,
      type: "website",
      ...(ogImage ? { images: [{ url: ogImage }] } : {}),
    },
    twitter: {
      card: "summary_large_image",
      title,
      description,
      ...(hasText(content.seo_twitter_handle) ? { site: content.seo_twitter_handle } : {}),
      ...(ogImage ? { images: [ogImage] } : {}),
    },
    robots: {
      index: content.seo_robots_index,
      follow: content.seo_robots_index,
    },
    ...(hasText(content.seo_google_site_verification)
      ? { verification: { google: content.seo_google_site_verification } }
      : {}),
  };
}

/** GA4 measurement IDs look like G-XXXXXXXXXX (older UA-XXXXXXX-X still floating around too). */
const GA_ID_PATTERN = /^[A-Z0-9-]{6,20}$/;

export function isValidAnalyticsId(id: string): boolean {
  return hasText(id) && GA_ID_PATTERN.test(id);
}

/**
 * schema.org Organization structured data for the homepage — helps search
 * engines understand the church's name, logo, contact info and social
 * profiles for richer search result panels.
 */
export function buildOrganizationJsonLd(data: LandingContentResponse): Record<string, unknown> {
  const { church, settings, content } = data;
  const sameAs = [settings.facebook, settings.twitter, settings.instagram, settings.youtube, settings.website].filter(
    hasText
  );

  return {
    "@context": "https://schema.org",
    "@type": "Church",
    name: church.name || "Our Church",
    ...(church.logo ? { logo: church.logo, image: church.logo } : {}),
    ...(hasText(content.contact_address) ? { address: content.contact_address } : {}),
    ...(hasText(content.contact_phone) ? { telephone: content.contact_phone } : {}),
    ...(hasText(content.contact_email) ? { email: content.contact_email } : {}),
    ...(sameAs.length > 0 ? { sameAs } : {}),
  };
}
