import { Navigation } from "@/components/conference/navigation";
import { HeroSection } from "@/components/conference/hero-section";
import { AboutSection } from "@/components/conference/about-section";
import { GoalsSection } from "@/components/conference/goals-section";
import { SpeakersSection } from "@/components/conference/speakers-section";
import { ScheduleSection } from "@/components/conference/schedule-section";
import { PartnersSection } from "@/components/conference/partners-section";
import { GallerySection } from "@/components/conference/gallery-section";
import { ConditionsSection } from "@/components/conference/conditions-section";
import { RegistrationSection } from "@/components/conference/registration-section";
import { VenueSection } from "@/components/conference/venue-section";
import { Footer } from "@/components/conference/footer";

export default function ConferencePage() {
  return (
    <>
      <Navigation />
      <main>
        <HeroSection />
        <AboutSection />
        <GoalsSection />
        <SpeakersSection />
        <ScheduleSection />
        <PartnersSection />
        <GallerySection />
        <ConditionsSection />
        <RegistrationSection />
        <VenueSection />
      </main>
      <Footer />
    </>
  );
}
