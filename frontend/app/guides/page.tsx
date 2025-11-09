import Header from '@/components/Header'
import Footer from '@/components/Footer'
import { Users, Award, MapPin, Heart, Star, BookOpen, TrendingUp } from 'lucide-react'

export default function GuidesPage() {
  return (
    <div className="min-h-screen bg-nomadiq-bone">
      <Header />
      <main className="pt-20">
        {/* Hero Section */}
        <section className="bg-gradient-to-br from-nomadiq-copper/20 via-nomadiq-bone to-nomadiq-orange/20 py-20">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center max-w-3xl mx-auto">
              <div className="inline-block p-4 bg-nomadiq-copper/20 rounded-full mb-6">
                <Users className="w-12 h-12 text-nomadiq-copper" />
              </div>
              <h1 className="text-4xl md:text-5xl font-serif font-bold mb-6 text-nomadiq-black">
                Meet Our Guides
              </h1>
              <p className="text-xl text-nomadiq-black/70 mb-8">
                Our guides are the heart of Nomadiq. Local experts, passionate storytellers, and dedicated conservationists who bring Kenya's coastal regions to life with knowledge, enthusiasm, and genuine care.
              </p>
            </div>
          </div>
        </section>

        {/* Our Guide Philosophy */}
        <section className="py-16 bg-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-12">
              <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4 text-nomadiq-black">
                Local Expertise, Global Perspective
              </h2>
              <p className="text-lg text-nomadiq-black/70 max-w-2xl mx-auto">
                We're committed to hiring and empowering local guides who know these places intimately. Their stories, knowledge, and passion make every journey unforgettable.
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div className="bg-nomadiq-bone rounded-2xl p-8 border border-nomadiq-sand/30">
                <div className="w-16 h-16 bg-nomadiq-copper/20 rounded-full flex items-center justify-center mb-6">
                  <MapPin className="w-8 h-8 text-nomadiq-copper" />
                </div>
                <h3 className="text-xl font-serif font-bold mb-4 text-nomadiq-black">
                  Local Knowledge
                </h3>
                <p className="text-nomadiq-black/70">
                  Our guides are from the communities we visit. They know the hidden gems, the best times to visit, and the stories that bring each place to life.
                </p>
              </div>

              <div className="bg-nomadiq-bone rounded-2xl p-8 border border-nomadiq-sand/30">
                <div className="w-16 h-16 bg-nomadiq-teal/20 rounded-full flex items-center justify-center mb-6">
                  <Award className="w-8 h-8 text-nomadiq-teal" />
                </div>
                <h3 className="text-xl font-serif font-bold mb-4 text-nomadiq-black">
                  Certified & Trained
                </h3>
                <p className="text-nomadiq-black/70">
                  All our guides are certified, trained in safety protocols, first aid, and conservation practices. We invest in their professional development continuously.
                </p>
              </div>

              <div className="bg-nomadiq-bone rounded-2xl p-8 border border-nomadiq-sand/30">
                <div className="w-16 h-16 bg-nomadiq-orange/20 rounded-full flex items-center justify-center mb-6">
                  <Heart className="w-8 h-8 text-nomadiq-orange" />
                </div>
                <h3 className="text-xl font-serif font-bold mb-4 text-nomadiq-black">
                  Passionate Storytellers
                </h3>
                <p className="text-nomadiq-black/70">
                  Beyond knowledge, our guides are passionate about sharing Kenya's culture, wildlife, and conservation efforts. They make every experience meaningful.
                </p>
              </div>
            </div>
          </div>
        </section>

        {/* Guide Profiles - Placeholder */}
        <section className="py-16 bg-gradient-to-br from-nomadiq-teal/10 to-nomadiq-sky/10">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-12">
              <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4 text-nomadiq-black">
                Featured Guides
              </h2>
              <p className="text-lg text-nomadiq-black/70">
                Meet some of the incredible people who make Nomadiq experiences unforgettable
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              {/* Placeholder Guide Cards */}
              {[1, 2, 3].map((i) => (
                <div key={i} className="bg-white rounded-xl p-6 border border-nomadiq-sand/30">
                  <div className="w-20 h-20 bg-gradient-to-br from-nomadiq-copper to-nomadiq-orange rounded-full flex items-center justify-center mb-4 mx-auto">
                    <Users className="w-10 h-10 text-white" />
                  </div>
                  <h3 className="text-xl font-serif font-bold text-center mb-2 text-nomadiq-black">
                    Guide Profile Coming Soon
                  </h3>
                  <p className="text-center text-sm text-nomadiq-black/60 mb-4">
                    Watamu, Kenya
                  </p>
                  <div className="flex justify-center items-center space-x-1 mb-4">
                    {[1, 2, 3, 4, 5].map((star) => (
                      <Star key={star} className="w-4 h-4 fill-yellow-400 text-yellow-400" />
                    ))}
                  </div>
                  <p className="text-center text-sm text-nomadiq-black/70">
                    Specializing in marine life, local culture, and adventure activities
                  </p>
                </div>
              ))}
            </div>

            <div className="mt-12 text-center">
              <div className="bg-white rounded-xl p-8 border border-nomadiq-sand/30 max-w-2xl mx-auto">
                <BookOpen className="w-12 h-12 text-nomadiq-copper mx-auto mb-4" />
                <h3 className="text-2xl font-serif font-bold mb-4 text-nomadiq-black">
                  Guide Profiles Coming Soon
                </h3>
                <p className="text-nomadiq-black/70 mb-6">
                  We're currently building out our guide profiles section. Soon, you'll be able to learn about each guide's background, expertise, and the unique experiences they offer.
                </p>
                <div className="flex items-center justify-center text-nomadiq-copper font-semibold">
                  <TrendingUp className="w-5 h-5 mr-2" />
                  <span>Launching Q2 2026</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* Becoming a Guide */}
        <section className="py-16 bg-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-12">
              <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4 text-nomadiq-black">
                Become a Nomadiq Guide
              </h2>
              <p className="text-lg text-nomadiq-black/70 max-w-2xl mx-auto">
                We're always looking for passionate, knowledgeable guides to join our team. If you love Kenya's coast and want to share it with the world, we'd love to hear from you.
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
              <div className="bg-nomadiq-bone rounded-xl p-6">
                <h3 className="text-lg font-semibold text-nomadiq-black mb-3">
                  What We're Looking For
                </h3>
                <ul className="space-y-2 text-nomadiq-black/70">
                  <li className="flex items-start">
                    <span className="text-nomadiq-copper mr-2">•</span>
                    <span>Deep knowledge of Kenya's coastal regions</span>
                  </li>
                  <li className="flex items-start">
                    <span className="text-nomadiq-copper mr-2">•</span>
                    <span>Certification in guiding, first aid, or relevant field</span>
                  </li>
                  <li className="flex items-start">
                    <span className="text-nomadiq-copper mr-2">•</span>
                    <span>Passion for conservation and community empowerment</span>
                  </li>
                  <li className="flex items-start">
                    <span className="text-nomadiq-copper mr-2">•</span>
                    <span>Excellent communication and storytelling skills</span>
                  </li>
                  <li className="flex items-start">
                    <span className="text-nomadiq-copper mr-2">•</span>
                    <span>Fluency in English and Swahili (other languages a plus)</span>
                  </li>
                </ul>
              </div>

              <div className="bg-nomadiq-bone rounded-xl p-6">
                <h3 className="text-lg font-semibold text-nomadiq-black mb-3">
                  What We Offer
                </h3>
                <ul className="space-y-2 text-nomadiq-black/70">
                  <li className="flex items-start">
                    <span className="text-nomadiq-copper mr-2">•</span>
                    <span>Competitive compensation and benefits</span>
                  </li>
                  <li className="flex items-start">
                    <span className="text-nomadiq-copper mr-2">•</span>
                    <span>Ongoing training and professional development</span>
                  </li>
                  <li className="flex items-start">
                    <span className="text-nomadiq-copper mr-2">•</span>
                    <span>Support for certification and skill building</span>
                  </li>
                  <li className="flex items-start">
                    <span className="text-nomadiq-copper mr-2">•</span>
                    <span>Flexible schedules and work-life balance</span>
                  </li>
                  <li className="flex items-start">
                    <span className="text-nomadiq-copper mr-2">•</span>
                    <span>Opportunity to work in beautiful locations</span>
                  </li>
                </ul>
              </div>
            </div>

            <div className="mt-12 text-center">
              <a
                href="/careers"
                className="inline-block px-8 py-4 bg-nomadiq-copper text-white font-semibold rounded-lg hover:bg-nomadiq-copper/90 transition-colors"
              >
                View Open Guide Positions
              </a>
            </div>
          </div>
        </section>

        {/* Growth & Training */}
        <section className="py-16 bg-gradient-to-r from-nomadiq-copper to-nomadiq-orange text-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-12">
              <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4">
                Growing Our Guide Network
              </h2>
              <p className="text-xl text-white/90">
                We're expanding our team and investing in guide development
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center">
                <div className="text-3xl font-serif font-bold mb-2">30+</div>
                <div className="text-sm text-white/80">Active guides</div>
                <div className="text-xs text-white/60 mt-2">Target: 50+ by end of 2026</div>
              </div>
              <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center">
                <div className="text-3xl font-serif font-bold mb-2">100%</div>
                <div className="text-sm text-white/80">Local hiring</div>
                <div className="text-xs text-white/60 mt-2">Empowering Kenyan talent</div>
              </div>
              <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center">
                <div className="text-3xl font-serif font-bold mb-2">4.9/5</div>
                <div className="text-sm text-white/80">Average guide rating</div>
                <div className="text-xs text-white/60 mt-2">From 500+ traveler reviews</div>
              </div>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </div>
  )
}

