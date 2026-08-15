import type { Metadata } from "next";
import { getLandingContent } from "@/lib/api";
import { Navbar } from "@/components/layout/navbar";
import { Footer } from "@/components/layout/footer";
import { MembershipHeader } from "@/components/membership/membership-header";
import { MembershipForm } from "@/components/membership/membership-form";

export async function generateMetadata(): Promise<Metadata> {
  const { data } = await getLandingContent();
  return {
    title: `Become a Member | ${data.church.name || "Our Church"}`,
    description: "Join our church family — fill out a short form to get started.",
  };
}

export default async function BecomeAMemberPage() {
  const { data } = await getLandingContent();
  const { church, settings, content } = data;

  return (
    <div className="flex min-h-screen flex-col">
      <Navbar church={church} content={content} />

      <main className="flex-1">
        <MembershipHeader content={content} />
        <section className="px-4 pb-24 sm:px-6 lg:px-8">
          <MembershipForm />
        </section>
      </main>

      <Footer church={church} settings={settings} content={content} />
    </div>
  );
}
