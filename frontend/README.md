# Church Website — Frontend

A standalone Next.js (App Router) public website for the church, consuming the CodeIgniter 4
backend's JSON API. This app is completely independent from the PHP admin dashboard — it only
talks to it over HTTP.

## What this is

A warm, modern church marketing site built with Next.js 16, TypeScript, and Tailwind CSS 4. The
homepage is composed of content-driven sections (hero, about, service times, events, sermons,
gallery, leadership, a "become a member" CTA band, and contact) that are fetched from the backend
and can be individually shown/hidden by the admin. The hero centerpiece is a real, interactive
WebGL scene built with `@react-three/fiber`/`three.js` — a slowly rotating distorted icosahedron
with an ambient particle field and drifting colored lights that subtly follow the mouse — themed
to the church's configurable brand color and adapted for light/dark mode (with a graceful static
gradient fallback if WebGL isn't available). Cards throughout the site (events, sermons,
leadership) use a lightweight CSS-only 3D tilt effect on hover. The `/become-a-member` page is a
separate, fast-loading route with a form whose fields are entirely driven by the backend — the
admin can add, remove, reorder, or retype fields from their dashboard and this page will always
render whatever comes back, with client-side validation, loading/error/success states, and a
proper "thank you, pending review" confirmation screen.

## Getting started

```bash
npm install
cp .env.local.example .env.local   # then edit NEXT_PUBLIC_API_URL if needed
npm run dev
```

Open [http://localhost:3000](http://localhost:3000).

## Environment variables

| Variable | Description | Default (fallback baked into the app) |
| --- | --- | --- |
| `NEXT_PUBLIC_API_URL` | Base URL of the CodeIgniter backend (no trailing slash needed) | `http://localhost/churchbackend` |

See `.env.local.example`.

## Scripts

- `npm run dev` — start the dev server (Turbopack, port 3000)
- `npm run build` — production build (type-checked)
- `npm run start` — serve the production build
- `npm run lint` — ESLint

## Notes on resilience

- If the backend is unreachable, the homepage still renders (using built-in placeholder content)
  with a small notice instead of crashing.
- The "Become a Member" page shows an explicit error state with a retry button if the form
  definition can't be loaded, and shows inline errors (preserving entered values) if submission
  fails.
- Every image field from the API (logo, hero/about images, thumbnails, photos, cover art) has a
  graceful fallback (gradient block or initials avatar) if it's empty or fails to load at runtime.
