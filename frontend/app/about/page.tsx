import Header from '@/components/Header'
import Footer from '@/components/Footer'

export default function AboutPage() {
  return (
    <main className="min-h-screen pt-20">
      <Header />
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {/* Hero Section */}
        <div className="text-center mb-16">
          <h1 className="text-5xl md:text-6xl font-serif text-nomadiq-black mb-6">
            About Nomadiq
          </h1>
          <p className="text-xl text-nomadiq-black/70 max-w-2xl mx-auto">
            Live. Connect. Belong.
          </p>
        </div>

        {/* Mission Section */}
        <section className="mb-16">
          <h2 className="text-3xl font-serif text-nomadiq-black mb-6">Our Mission</h2>
          <p className="text-lg text-nomadiq-black/80 leading-relaxed mb-4">
            Nomadiq is a premium coastal lifestyle brand that curates unforgettable experiences 
            along Kenya's stunning coastline. We believe in authentic travel that connects you 
            with local culture, pristine beaches, and hidden gems that most travelers never discover.
          </p>
          <p className="text-lg text-nomadiq-black/80 leading-relaxed">
            Our mission is to create meaningful connections between travelers and the coastal 
            communities of Watamu, Malindi, and Lamu, while preserving the natural beauty and 
            cultural heritage of these incredible destinations.
          </p>
        </section>

        {/* Values Section */}
        <section className="mb-16">
          <h2 className="text-3xl font-serif text-nomadiq-black mb-6">Our Values</h2>
          <div className="grid md:grid-cols-3 gap-8">
            <div>
              <h3 className="text-xl font-serif text-nomadiq-copper mb-3">Authenticity</h3>
              <p className="text-nomadiq-black/70">
                We curate experiences that are genuine, respectful, and true to the local culture 
                and environment.
              </p>
            </div>
            <div>
              <h3 className="text-xl font-serif text-nomadiq-copper mb-3">Sustainability</h3>
              <p className="text-nomadiq-black/70">
                We're committed to responsible tourism that benefits local communities and 
                protects our coastal ecosystems.
              </p>
            </div>
            <div>
              <h3 className="text-xl font-serif text-nomadiq-copper mb-3">Connection</h3>
              <p className="text-nomadiq-black/70">
                We believe travel is about creating lasting connections—with places, people, 
                and yourself.
              </p>
            </div>
          </div>
        </section>

        {/* Story Section */}
        <section className="mb-16">
          <h2 className="text-3xl font-serif text-nomadiq-black mb-6">Our Story</h2>
          <p className="text-lg text-nomadiq-black/80 leading-relaxed mb-4">
            Nomadiq was born from a deep love for Kenya's coastal regions and a desire to share 
            these hidden treasures with the world. What started as a passion for exploring 
            pristine beaches, vibrant mangroves, and local communities has evolved into a 
            curated collection of premium experiences.
          </p>
          <p className="text-lg text-nomadiq-black/80 leading-relaxed">
            We work closely with local guides, communities, and partners to ensure every 
            experience is authentic, respectful, and unforgettable. From sunset dhow rides to 
            cultural immersions, every journey with Nomadiq is designed to leave you with 
            memories that last a lifetime.
          </p>
        </section>

        {/* Team Section */}
        <section className="mb-16">
          <h2 className="text-3xl font-serif text-nomadiq-black mb-6">Why Choose Nomadiq</h2>
          <ul className="space-y-4 text-lg text-nomadiq-black/80">
            <li className="flex items-start">
              <span className="text-nomadiq-copper mr-3">✓</span>
              <span>Expert local guides who know the hidden gems</span>
            </li>
            <li className="flex items-start">
              <span className="text-nomadiq-copper mr-3">✓</span>
              <span>Curated experiences that go beyond the tourist trail</span>
            </li>
            <li className="flex items-start">
              <span className="text-nomadiq-copper mr-3">✓</span>
              <span>Commitment to sustainable and responsible tourism</span>
            </li>
            <li className="flex items-start">
              <span className="text-nomadiq-copper mr-3">✓</span>
              <span>Premium accommodations and authentic local experiences</span>
            </li>
            <li className="flex items-start">
              <span className="text-nomadiq-copper mr-3">✓</span>
              <span>Personalized service from booking to return</span>
            </li>
          </ul>
        </section>

        {/* CTA Section */}
        <section className="text-center bg-nomadiq-sand/30 rounded-lg p-8">
          <h2 className="text-3xl font-serif text-nomadiq-black mb-4">
            Ready to Start Your Adventure?
          </h2>
          <p className="text-lg text-nomadiq-black/70 mb-6">
            Explore our curated experiences and discover the magic of Kenya's coast.
          </p>
          <a
            href="/packages"
            className="inline-block px-8 py-3 bg-nomadiq-copper text-white font-medium rounded-lg hover:bg-nomadiq-copper/90 transition-colors"
          >
            View Experiences
          </a>
        </section>
      </div>
      <Footer />
    </main>
  )
}

