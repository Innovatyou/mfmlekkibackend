import { getLandingContent } from "@/lib/api";
import { Navbar } from "@/components/layout/navbar";
import { Footer } from "@/components/layout/footer";
import { HeroSection } from "@/components/sections/hero-section";
import { LiveSection } from "@/components/sections/live-section";
import { AboutSection } from "@/components/sections/about-section";
import { ServiceTimesSection } from "@/components/sections/service-times-section";
import { EventsSection } from "@/components/sections/events-section";
import { SermonsSection } from "@/components/sections/sermons-section";
import { GallerySection } from "@/components/sections/gallery-section";
import { LeadershipSection } from "@/components/sections/leadership-section";
import { CtaBandSection } from "@/components/sections/cta-band-section";
import { AppDownloadSection } from "@/components/sections/app-download-section";
import { ContactSection } from "@/components/sections/contact-section";

export default async function HomePage() {
  const { data, error } = await getLandingContent();
  const { church, settings, content, serviceTimes, events, sermons, gallery, leadership, live } =
    data;

  return (
    <div className="flex min-h-screen flex-col">
      <Navbar church={church} content={content} isLive={Boolean(live)} />

      <main className="flex-1">
        {error && (
          <div className="bg-amber-100 px-4 py-2 text-center text-xs text-amber-900 dark:bg-amber-900/30 dark:text-amber-200">
            Showing placeholder content — the church server couldn&apos;t be reached.
          </div>
        )}

        {content.show_hero && <HeroSection content={content} />}
        {content.show_live && <LiveSection content={content} live={live} />}
        {content.show_about && <AboutSection content={content} />}
        {content.show_service_times && (
          <ServiceTimesSection content={content} serviceTimes={serviceTimes} />
        )}
        {content.show_events && <EventsSection content={content} events={events} />}
        {content.show_sermons && <SermonsSection content={content} sermons={sermons} />}
        {content.show_gallery && <GallerySection content={content} gallery={gallery} />}
        {content.show_leadership && (
          <LeadershipSection content={content} leadership={leadership} />
        )}
        {content.show_signup && <CtaBandSection content={content} />}
        {content.show_app_download && <AppDownloadSection content={content} />}
        {content.show_contact && <ContactSection content={content} />}
      </main>

      <Footer church={church} settings={settings} content={content} />
    </div>
  );
}
