/**
 * Typed API client for the CodeIgniter 4 church backend.
 *
 * Base URL is read from NEXT_PUBLIC_API_URL (see .env.local.example).
 * Every function here is defensive: network/parse failures never throw
 * out to a page render unless explicitly documented, so the site can
 * always render *something* even if the backend is unreachable.
 */

export const API_URL = (
  process.env.NEXT_PUBLIC_API_URL || "http://localhost/churchbackend"
).replace(/\/+$/, "");

/* -------------------------------------------------------------------------- */
/* Types — mirror the exact JSON shapes documented by the backend contract.   */
/* -------------------------------------------------------------------------- */

export interface Church {
  name: string;
  logo: string | null;
}

export interface SocialSettings {
  facebook: string;
  twitter: string;
  instagram: string;
  youtube: string;
  website: string;
}

export interface LandingContent {
  header_logo: string;
  header_text: string;
  favicon_image: string;
  favicon_text: string;
  hero_title: string;
  hero_subtitle: string;
  hero_image: string;
  hero_cta_text: string;
  hero_cta_link: string;
  about_title: string;
  about_content: string;
  about_image: string;
  service_times_title: string;
  service_times_subtitle: string;
  events_title: string;
  events_subtitle: string;
  sermons_title: string;
  sermons_subtitle: string;
  live_title: string;
  live_subtitle: string;
  live_offline_message: string;
  gallery_title: string;
  gallery_subtitle: string;
  leadership_title: string;
  leadership_subtitle: string;
  contact_title: string;
  contact_address: string;
  contact_phone: string;
  contact_email: string;
  contact_map_embed: string;
  contact_form_title: string;
  contact_form_subtitle: string;
  signup_title: string;
  signup_subtitle: string;
  footer_text: string;
  primary_color: string;
  web_app_url: string;
  web_app_login_text: string;
  android_app_url: string;
  ios_app_url: string;
  app_download_title: string;
  app_download_subtitle: string;
  seo_meta_title: string;
  seo_meta_description: string;
  seo_meta_keywords: string;
  seo_og_image: string;
  seo_twitter_handle: string;
  seo_google_site_verification: string;
  seo_google_analytics_id: string;
  seo_robots_index: boolean;
  show_hero: boolean;
  show_about: boolean;
  show_service_times: boolean;
  show_events: boolean;
  show_sermons: boolean;
  show_live: boolean;
  show_gallery: boolean;
  show_leadership: boolean;
  show_contact: boolean;
  show_contact_form: boolean;
  show_signup: boolean;
  show_app_download: boolean;
}

export interface ServiceTime {
  id: number;
  name: string;
  day_of_week: string;
  time_label: string;
  location: string;
  description: string;
}

export interface ChurchEvent {
  id: number;
  title: string;
  description: string;
  date: string;
  thumbnail: string;
}

export interface Sermon {
  id: number;
  title: string;
  type: "audio" | "video";
  category: string;
  cover_photo: string;
  source: string;
}

export interface GalleryImage {
  image: string;
  title: string;
}

export type LiveStreamSource = "youtube" | "facebook" | "m3u8" | "rtmp";

export interface LiveStream {
  title: string;
  description: string;
  source: LiveStreamSource;
  link: string;
  cover_photo: string;
}

export interface LeadershipMember {
  id: number;
  name: string;
  role_title: string;
  bio: string;
  photo: string;
}

export interface LandingContentResponse {
  church: Church;
  settings: SocialSettings;
  content: LandingContent;
  serviceTimes: ServiceTime[];
  events: ChurchEvent[];
  sermons: Sermon[];
  gallery: GalleryImage[];
  leadership: LeadershipMember[];
  live: LiveStream | null;
}

export type MembershipFieldType =
  | "text"
  | "email"
  | "tel"
  | "textarea"
  | "date"
  | "select"
  | "radio"
  | "checkbox";

export interface MembershipField {
  id: number;
  field_key: string;
  label: string;
  field_type: MembershipFieldType;
  placeholder: string | null;
  help_text: string | null;
  options: string[] | null;
  required: boolean;
  sort_order: number;
}

export interface MembershipFormResponse {
  fields: MembershipField[];
}

export type JoinChurchPayload = Record<string, string | string[]>;

export interface JoinChurchResponse {
  status: "ok" | "error";
  message: string;
}

/* -------------------------------------------------------------------------- */
/* Fallback / placeholder data — used whenever the backend can't be reached   */
/* so the marketing site still renders something coherent.                    */
/* -------------------------------------------------------------------------- */

export const DEFAULT_PRIMARY_COLOR = "#c2410c";

export const FALLBACK_LANDING_CONTENT: LandingContentResponse = {
  church: { name: "Our Church", logo: null },
  settings: {
    facebook: "",
    twitter: "",
    instagram: "",
    youtube: "",
    website: "",
  },
  content: {
    header_logo: "",
    header_text: "",
    favicon_image: "",
    favicon_text: "",
    hero_title: "You Are Welcome Here",
    hero_subtitle:
      "A warm community gathered around faith, hope, and love — join us this week.",
    hero_image: "",
    hero_cta_text: "Plan Your Visit",
    hero_cta_link: "#service-times",
    about_title: "Who We Are",
    about_content:
      "We're a community of believers dedicated to worship, fellowship, and service.\nOur doors — and hearts — are open to everyone, wherever you are on your journey.",
    about_image: "",
    service_times_title: "Service Times",
    service_times_subtitle: "Join us for worship throughout the week.",
    events_title: "Upcoming Events",
    events_subtitle: "See what's happening in our community.",
    sermons_title: "Recent Sermons",
    sermons_subtitle: "Catch up on the latest messages.",
    live_title: "Join Us Live",
    live_subtitle: "Tune in to our live service, wherever you are.",
    live_offline_message:
      "We're not live right now — check our service times below and join us then.",
    gallery_title: "Gallery",
    gallery_subtitle: "Moments from our community.",
    leadership_title: "Our Leadership",
    leadership_subtitle: "Meet the people who guide our church.",
    contact_title: "Get In Touch",
    contact_address: "",
    contact_phone: "",
    contact_email: "",
    contact_map_embed: "",
    contact_form_title: "Send Us a Message",
    contact_form_subtitle: "We'd love to hear from you — we'll get back to you soon.",
    signup_title: "Become Part of Our Family",
    signup_subtitle: "We'd love to have you join our church community.",
    footer_text: "",
    primary_color: DEFAULT_PRIMARY_COLOR,
    web_app_url: "",
    web_app_login_text: "Member Login",
    android_app_url: "",
    ios_app_url: "",
    app_download_title: "Get Our App",
    app_download_subtitle: "Take church with you wherever you go",
    seo_meta_title: "",
    seo_meta_description: "",
    seo_meta_keywords: "",
    seo_og_image: "",
    seo_twitter_handle: "",
    seo_google_site_verification: "",
    seo_google_analytics_id: "",
    seo_robots_index: true,
    show_hero: true,
    show_about: true,
    show_service_times: true,
    show_events: true,
    show_sermons: true,
    show_live: true,
    show_gallery: true,
    show_leadership: true,
    show_contact: true,
    show_contact_form: true,
    show_signup: true,
    show_app_download: true,
  },
  serviceTimes: [],
  events: [],
  sermons: [],
  gallery: [],
  leadership: [],
  live: null,
};

/* -------------------------------------------------------------------------- */
/* Helpers                                                                     */
/* -------------------------------------------------------------------------- */

function toBool(value: unknown): boolean {
  if (typeof value === "boolean") return value;
  if (typeof value === "number") return value !== 0;
  if (typeof value === "string") return value === "1" || value.toLowerCase() === "true";
  return Boolean(value);
}

function toStr(value: unknown, fallback = ""): string {
  if (typeof value === "string") return value;
  if (value === null || value === undefined) return fallback;
  return String(value);
}

function toArr<T>(value: unknown): T[] {
  return Array.isArray(value) ? (value as T[]) : [];
}

function normalizeLandingContent(raw: unknown): LandingContentResponse {
  const data = (raw && typeof raw === "object" ? raw : {}) as Record<string, unknown>;
  const church = (data.church ?? {}) as Record<string, unknown>;
  const settings = (data.settings ?? {}) as Record<string, unknown>;
  const content = (data.content ?? {}) as Record<string, unknown>;

  return {
    church: {
      name: toStr(church.name, FALLBACK_LANDING_CONTENT.church.name),
      logo: church.logo ? toStr(church.logo) : null,
    },
    settings: {
      facebook: toStr(settings.facebook),
      twitter: toStr(settings.twitter),
      instagram: toStr(settings.instagram),
      youtube: toStr(settings.youtube),
      website: toStr(settings.website),
    },
    content: {
      header_logo: toStr(content.header_logo),
      header_text: toStr(content.header_text),
      favicon_image: toStr(content.favicon_image),
      favicon_text: toStr(content.favicon_text),
      hero_title: toStr(content.hero_title, FALLBACK_LANDING_CONTENT.content.hero_title),
      hero_subtitle: toStr(content.hero_subtitle, FALLBACK_LANDING_CONTENT.content.hero_subtitle),
      hero_image: toStr(content.hero_image),
      hero_cta_text: toStr(content.hero_cta_text, FALLBACK_LANDING_CONTENT.content.hero_cta_text),
      hero_cta_link: toStr(content.hero_cta_link, FALLBACK_LANDING_CONTENT.content.hero_cta_link),
      about_title: toStr(content.about_title, FALLBACK_LANDING_CONTENT.content.about_title),
      about_content: toStr(content.about_content, FALLBACK_LANDING_CONTENT.content.about_content),
      about_image: toStr(content.about_image),
      service_times_title: toStr(
        content.service_times_title,
        FALLBACK_LANDING_CONTENT.content.service_times_title
      ),
      service_times_subtitle: toStr(content.service_times_subtitle),
      events_title: toStr(content.events_title, FALLBACK_LANDING_CONTENT.content.events_title),
      events_subtitle: toStr(content.events_subtitle),
      sermons_title: toStr(content.sermons_title, FALLBACK_LANDING_CONTENT.content.sermons_title),
      sermons_subtitle: toStr(content.sermons_subtitle),
      live_title: toStr(content.live_title, FALLBACK_LANDING_CONTENT.content.live_title),
      live_subtitle: toStr(content.live_subtitle, FALLBACK_LANDING_CONTENT.content.live_subtitle),
      live_offline_message: toStr(
        content.live_offline_message,
        FALLBACK_LANDING_CONTENT.content.live_offline_message
      ),
      gallery_title: toStr(content.gallery_title, FALLBACK_LANDING_CONTENT.content.gallery_title),
      gallery_subtitle: toStr(content.gallery_subtitle),
      leadership_title: toStr(
        content.leadership_title,
        FALLBACK_LANDING_CONTENT.content.leadership_title
      ),
      leadership_subtitle: toStr(content.leadership_subtitle),
      contact_title: toStr(content.contact_title, FALLBACK_LANDING_CONTENT.content.contact_title),
      contact_address: toStr(content.contact_address),
      contact_phone: toStr(content.contact_phone),
      contact_email: toStr(content.contact_email),
      contact_map_embed: toStr(content.contact_map_embed),
      contact_form_title: toStr(
        content.contact_form_title,
        FALLBACK_LANDING_CONTENT.content.contact_form_title
      ),
      contact_form_subtitle: toStr(content.contact_form_subtitle),
      signup_title: toStr(content.signup_title, FALLBACK_LANDING_CONTENT.content.signup_title),
      signup_subtitle: toStr(content.signup_subtitle),
      footer_text: toStr(content.footer_text),
      primary_color: toStr(content.primary_color, DEFAULT_PRIMARY_COLOR) || DEFAULT_PRIMARY_COLOR,
      web_app_url: toStr(content.web_app_url),
      web_app_login_text: toStr(
        content.web_app_login_text,
        FALLBACK_LANDING_CONTENT.content.web_app_login_text
      ),
      android_app_url: toStr(content.android_app_url),
      ios_app_url: toStr(content.ios_app_url),
      app_download_title: toStr(
        content.app_download_title,
        FALLBACK_LANDING_CONTENT.content.app_download_title
      ),
      app_download_subtitle: toStr(content.app_download_subtitle),
      seo_meta_title: toStr(content.seo_meta_title),
      seo_meta_description: toStr(content.seo_meta_description),
      seo_meta_keywords: toStr(content.seo_meta_keywords),
      seo_og_image: toStr(content.seo_og_image),
      seo_twitter_handle: toStr(content.seo_twitter_handle),
      seo_google_site_verification: toStr(content.seo_google_site_verification),
      seo_google_analytics_id: toStr(content.seo_google_analytics_id),
      seo_robots_index: toBool(content.seo_robots_index ?? true),
      show_hero: toBool(content.show_hero ?? true),
      show_about: toBool(content.show_about ?? true),
      show_service_times: toBool(content.show_service_times ?? true),
      show_events: toBool(content.show_events ?? true),
      show_sermons: toBool(content.show_sermons ?? true),
      show_live: toBool(content.show_live ?? true),
      show_gallery: toBool(content.show_gallery ?? true),
      show_leadership: toBool(content.show_leadership ?? true),
      show_contact: toBool(content.show_contact ?? true),
      show_contact_form: toBool(content.show_contact_form ?? true),
      show_signup: toBool(content.show_signup ?? true),
      show_app_download: toBool(content.show_app_download ?? true),
    },
    serviceTimes: toArr<Record<string, unknown>>(data.serviceTimes).map((s) => ({
      id: Number(s.id) || 0,
      name: toStr(s.name),
      day_of_week: toStr(s.day_of_week),
      time_label: toStr(s.time_label),
      location: toStr(s.location),
      description: toStr(s.description),
    })),
    events: toArr<Record<string, unknown>>(data.events).map((e) => ({
      id: Number(e.id) || 0,
      title: toStr(e.title),
      description: toStr(e.description),
      date: toStr(e.date),
      thumbnail: toStr(e.thumbnail),
    })),
    sermons: toArr<Record<string, unknown>>(data.sermons).map((s) => ({
      id: Number(s.id) || 0,
      title: toStr(s.title),
      type: s.type === "audio" ? "audio" : "video",
      category: toStr(s.category),
      cover_photo: toStr(s.cover_photo),
      source: toStr(s.source),
    })),
    gallery: toArr<Record<string, unknown>>(data.gallery).map((g) => ({
      image: toStr(g.image),
      title: toStr(g.title),
    })),
    leadership: toArr<Record<string, unknown>>(data.leadership).map((l) => ({
      id: Number(l.id) || 0,
      name: toStr(l.name),
      role_title: toStr(l.role_title),
      bio: toStr(l.bio),
      photo: toStr(l.photo),
    })),
    live: normalizeLive(data.live),
  };
}

const LIVE_SOURCES: LiveStreamSource[] = ["youtube", "facebook", "m3u8", "rtmp"];

function normalizeLive(raw: unknown): LiveStream | null {
  if (!raw || typeof raw !== "object") return null;
  const l = raw as Record<string, unknown>;
  const link = toStr(l.link);
  if (!link) return null;
  return {
    title: toStr(l.title),
    description: toStr(l.description),
    source: LIVE_SOURCES.includes(toStr(l.source) as LiveStreamSource)
      ? (toStr(l.source) as LiveStreamSource)
      : "youtube",
    link,
    cover_photo: toStr(l.cover_photo),
  };
}

function normalizeFields(raw: unknown): MembershipField[] {
  return toArr<Record<string, unknown>>(raw)
    .map((f) => ({
      id: Number(f.id) || 0,
      field_key: toStr(f.field_key),
      label: toStr(f.label),
      field_type: (["text", "email", "tel", "textarea", "date", "select", "radio", "checkbox"].includes(
        toStr(f.field_type)
      )
        ? toStr(f.field_type)
        : "text") as MembershipFieldType,
      placeholder: f.placeholder ? toStr(f.placeholder) : null,
      help_text: f.help_text ? toStr(f.help_text) : null,
      options: Array.isArray(f.options) ? f.options.map((o) => toStr(o)) : null,
      required: toBool(f.required),
      sort_order: Number(f.sort_order) || 0,
    }))
    .filter((f) => f.field_key)
    .sort((a, b) => a.sort_order - b.sort_order);
}

/* -------------------------------------------------------------------------- */
/* Fetch functions                                                            */
/* -------------------------------------------------------------------------- */

export interface LandingContentResult {
  data: LandingContentResponse;
  error: string | null;
}

/**
 * Fetches the full homepage content bundle. Never throws — on any network,
 * HTTP, or parse failure it resolves with fallback placeholder content and
 * a populated `error` string so the caller can optionally surface a
 * "content unavailable" notice without breaking the page.
 */
export async function getLandingContent(): Promise<LandingContentResult> {
  try {
    const res = await fetch(`${API_URL}/api/landingContent`, {
      next: { revalidate: 60 },
    });
    if (!res.ok) {
      throw new Error(`Request failed with status ${res.status}`);
    }
    const json = await res.json();
    return { data: normalizeLandingContent(json), error: null };
  } catch (err) {
    return {
      data: FALLBACK_LANDING_CONTENT,
      error: err instanceof Error ? err.message : "Unable to reach the church server.",
    };
  }
}

/**
 * Fetches the dynamic membership form field definitions.
 * Throws on failure so the caller (a client component) can render an
 * explicit error/retry state, per the "Become a Member" page spec.
 */
export async function getMembershipForm(): Promise<MembershipFormResponse> {
  let res: Response;
  try {
    res = await fetch(`${API_URL}/api/membershipForm`, {
      cache: "no-store",
    });
  } catch {
    throw new Error("Could not reach the church server. Please check your connection and try again.");
  }
  if (!res.ok) {
    throw new Error(`Request failed with status ${res.status}.`);
  }
  const json = await res.json();
  return { fields: normalizeFields((json as { fields?: unknown }).fields) };
}

/**
 * Submits the membership application. Never throws — network failures are
 * translated into a `{ status: 'error' }` response so the form can show an
 * inline message and preserve the user's entered values.
 */
export async function submitMembershipForm(
  payload: JoinChurchPayload
): Promise<JoinChurchResponse> {
  try {
    const res = await fetch(`${API_URL}/api/joinChurch`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

    const json = await res.json().catch(() => null);

    if (json && typeof json === "object" && "status" in json) {
      const status = (json as { status?: unknown }).status === "ok" ? "ok" : "error";
      const message =
        typeof (json as { message?: unknown }).message === "string"
          ? (json as { message: string }).message
          : status === "ok"
            ? "Your application has been submitted."
            : "Something went wrong submitting your application.";
      return { status, message };
    }

    if (!res.ok) {
      return { status: "error", message: `Request failed with status ${res.status}.` };
    }

    return { status: "error", message: "Received an unexpected response from the server." };
  } catch {
    return {
      status: "error",
      message: "Could not reach the server. Please check your connection and try again.",
    };
  }
}

export interface ContactPayload {
  name: string;
  email: string;
  phone: string;
  subject: string;
  message: string;
}

export interface ContactResponse {
  status: "ok" | "error";
  message: string;
}

/**
 * Submits the "Get In Touch" contact form. Never throws — network failures
 * are translated into a `{ status: 'error' }` response so the form can show
 * an inline message and preserve the user's entered values.
 */
export async function submitContactForm(payload: ContactPayload): Promise<ContactResponse> {
  try {
    const res = await fetch(`${API_URL}/api/contactUs`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

    const json = await res.json().catch(() => null);

    if (json && typeof json === "object" && "status" in json) {
      const status = (json as { status?: unknown }).status === "ok" ? "ok" : "error";
      const message =
        typeof (json as { message?: unknown }).message === "string"
          ? (json as { message: string }).message
          : status === "ok"
            ? "Thank you — your message has been sent."
            : "Something went wrong sending your message.";
      return { status, message };
    }

    if (!res.ok) {
      return { status: "error", message: `Request failed with status ${res.status}.` };
    }

    return { status: "error", message: "Received an unexpected response from the server." };
  } catch {
    return {
      status: "error",
      message: "Could not reach the server. Please check your connection and try again.",
    };
  }
}
