import type { MetadataRoute } from "next";
import { getLandingContent } from "@/lib/api";

const SITE_URL = (process.env.NEXT_PUBLIC_SITE_URL || "http://localhost:3000").replace(/\/+$/, "");

/**
 * Respects the admin's "Allow search engines to index this site" toggle
 * (Website → Content Editor → SEO tab) — turned off, this blocks the whole
 * site from crawlers, handy while building/testing.
 */
export default async function robots(): Promise<MetadataRoute.Robots> {
  const { data } = await getLandingContent();
  const allowed = data.content.seo_robots_index;

  return {
    rules: {
      userAgent: "*",
      ...(allowed ? { allow: "/" } : { disallow: "/" }),
    },
    sitemap: `${SITE_URL}/sitemap.xml`,
  };
}
