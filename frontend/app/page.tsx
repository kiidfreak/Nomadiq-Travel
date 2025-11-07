import Hero from '@/components/Hero'
import Stats from '@/components/Stats'
import FloatingMemories from '@/components/FloatingMemories'
import Packages from '@/components/Packages'
import Testimonials from '@/components/Testimonials'
import CTA from '@/components/CTA'
import Footer from '@/components/Footer'
import Header from '@/components/Header'

export default function Home() {
  return (
    <main className="min-h-screen">
      <Header />
      <Hero />
      <Stats />
      <FloatingMemories />
      <Packages />
      <Testimonials />
      <CTA />
      <Footer />
    </main>
  )
}

