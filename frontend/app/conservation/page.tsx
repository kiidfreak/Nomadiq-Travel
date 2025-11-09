import Header from '@/components/Header'
import Footer from '@/components/Footer'
import { Heart, Trees, Users, Coins, Leaf, Target, TrendingUp, Globe } from 'lucide-react'

export default function ConservationPage() {
  return (
    <div className="min-h-screen bg-nomadiq-bone">
      <Header />
      <main className="pt-20">
        {/* Hero Section */}
        <section className="bg-gradient-to-br from-nomadiq-teal/20 via-nomadiq-bone to-nomadiq-sky/20 py-20">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center max-w-3xl mx-auto">
              <div className="inline-block p-4 bg-nomadiq-teal/20 rounded-full mb-6">
                <Heart className="w-12 h-12 text-nomadiq-teal" />
              </div>
              <h1 className="text-4xl md:text-5xl font-serif font-bold mb-6 text-nomadiq-black">
                Conservation & Sustainability
              </h1>
              <p className="text-xl text-nomadiq-black/70 mb-8">
                At Nomadiq, we believe that travel should leave a positive impact on the places we visit. Our commitment to conservation, community empowerment, and environmental stewardship drives everything we do.
              </p>
            </div>
          </div>
        </section>

        {/* Our Commitment */}
        <section className="py-16 bg-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-12">
              <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4 text-nomadiq-black">
                Our Commitment
              </h2>
              <p className="text-lg text-nomadiq-black/70 max-w-2xl mx-auto">
                We're building a sustainable tourism model that protects Kenya's natural wonders while empowering local communities and creating lasting positive change.
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div className="bg-nomadiq-bone rounded-2xl p-8 border border-nomadiq-sand/30">
                <div className="w-16 h-16 bg-nomadiq-teal/20 rounded-full flex items-center justify-center mb-6">
                  <Trees className="w-8 h-8 text-nomadiq-teal" />
                </div>
                <h3 className="text-xl font-serif font-bold mb-4 text-nomadiq-black">
                  Wildlife Protection
                </h3>
                <p className="text-nomadiq-black/70 mb-4">
                  10% of every booking goes directly to local conservation initiatives, wildlife protection programs, and habitat restoration projects across Kenya's coastal regions.
                </p>
                <div className="flex items-center text-nomadiq-teal font-semibold">
                  <TrendingUp className="w-5 h-5 mr-2" />
                  <span>Growing impact in 2026</span>
                </div>
              </div>

              <div className="bg-nomadiq-bone rounded-2xl p-8 border border-nomadiq-sand/30">
                <div className="w-16 h-16 bg-nomadiq-copper/20 rounded-full flex items-center justify-center mb-6">
                  <Users className="w-8 h-8 text-nomadiq-copper" />
                </div>
                <h3 className="text-xl font-serif font-bold mb-4 text-nomadiq-black">
                  Community Empowerment
                </h3>
                <p className="text-nomadiq-black/70 mb-4">
                  We partner with local communities to create sustainable employment opportunities, support local businesses, and ensure that tourism benefits those who call these places home.
                </p>
                <div className="flex items-center text-nomadiq-copper font-semibold">
                  <TrendingUp className="w-5 h-5 mr-2" />
                  <span>Expanding partnerships</span>
                </div>
              </div>

              <div className="bg-nomadiq-bone rounded-2xl p-8 border border-nomadiq-sand/30">
                <div className="w-16 h-16 bg-nomadiq-orange/20 rounded-full flex items-center justify-center mb-6">
                  <Leaf className="w-8 h-8 text-nomadiq-orange" />
                </div>
                <h3 className="text-xl font-serif font-bold mb-4 text-nomadiq-black">
                  Carbon Credits & ESG
                </h3>
                <p className="text-nomadiq-black/70 mb-4">
                  We're developing carbon credit programs and ESG (Environmental, Social, Governance) initiatives to offset our footprint and create verifiable positive environmental impact.
                </p>
                <div className="flex items-center text-nomadiq-orange font-semibold">
                  <Target className="w-5 h-5 mr-2" />
                  <span>Launching Q2 2026</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* Impact Stats */}
        <section className="py-16 bg-gradient-to-br from-nomadiq-teal/10 to-nomadiq-sky/10">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-12">
              <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4 text-nomadiq-black">
                Our Impact
              </h2>
              <p className="text-lg text-nomadiq-black/70">
                Building towards measurable, meaningful change
              </p>
            </div>

            <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
              <div className="bg-white rounded-xl p-6 text-center border border-nomadiq-sand/30">
                <div className="text-3xl font-serif font-bold text-nomadiq-teal mb-2">10%</div>
                <div className="text-sm text-nomadiq-black/70">Of revenue to conservation</div>
              </div>
              <div className="bg-white rounded-xl p-6 text-center border border-nomadiq-sand/30">
                <div className="text-3xl font-serif font-bold text-nomadiq-copper mb-2">50+</div>
                <div className="text-sm text-nomadiq-black/70">Local partners supported</div>
              </div>
              <div className="bg-white rounded-xl p-6 text-center border border-nomadiq-sand/30">
                <div className="text-3xl font-serif font-bold text-nomadiq-orange mb-2">100+</div>
                <div className="text-sm text-nomadiq-black/70">Jobs created (target 2026)</div>
              </div>
              <div className="bg-white rounded-xl p-6 text-center border border-nomadiq-sand/30">
                <div className="text-3xl font-serif font-bold text-nomadiq-teal mb-2">2026</div>
                <div className="text-sm text-nomadiq-black/70">Carbon neutral goal</div>
              </div>
            </div>
          </div>
        </section>

        {/* Future Plans */}
        <section className="py-16 bg-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-12">
              <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4 text-nomadiq-black">
                What's Coming
              </h2>
              <p className="text-lg text-nomadiq-black/70 max-w-2xl mx-auto">
                We're growing fast and expanding our impact. Here's what we're building for the future.
              </p>
            </div>

            <div className="space-y-6 max-w-3xl mx-auto">
              <div className="bg-nomadiq-bone rounded-xl p-6 border-l-4 border-nomadiq-teal">
                <div className="flex items-start">
                  <Coins className="w-6 h-6 text-nomadiq-teal mr-4 mt-1 flex-shrink-0" />
                  <div>
                    <h3 className="text-lg font-semibold text-nomadiq-black mb-2">
                      Carbon Credit Marketplace (Q2 2026)
                    </h3>
                    <p className="text-nomadiq-black/70">
                      We're launching a carbon credit program that allows travelers to offset their journey's carbon footprint. Each credit will directly fund mangrove restoration, reforestation, and clean energy projects in Kenya.
                    </p>
                  </div>
                </div>
              </div>

              <div className="bg-nomadiq-bone rounded-xl p-6 border-l-4 border-nomadiq-copper">
                <div className="flex items-start">
                  <Globe className="w-6 h-6 text-nomadiq-copper mr-4 mt-1 flex-shrink-0" />
                  <div>
                    <h3 className="text-lg font-semibold text-nomadiq-black mb-2">
                      ESG Reporting & Transparency (Q3 2026)
                    </h3>
                    <p className="text-nomadiq-black/70">
                      We're developing comprehensive ESG reporting that tracks our environmental impact, social contributions, and governance practices. You'll be able to see exactly where your conservation contributions go and the measurable impact they create.
                    </p>
                  </div>
                </div>
              </div>

              <div className="bg-nomadiq-bone rounded-xl p-6 border-l-4 border-nomadiq-orange">
                <div className="flex items-start">
                  <Users className="w-6 h-6 text-nomadiq-orange mr-4 mt-1 flex-shrink-0" />
                  <div>
                    <h3 className="text-lg font-semibold text-nomadiq-black mb-2">
                      Community Investment Fund (2026)
                    </h3>
                    <p className="text-nomadiq-black/70">
                      We're establishing a community investment fund that provides microloans, business training, and resources to local entrepreneurs. This creates sustainable economic opportunities that extend far beyond tourism.
                    </p>
                  </div>
                </div>
              </div>

              <div className="bg-nomadiq-bone rounded-xl p-6 border-l-4 border-nomadiq-teal">
                <div className="flex items-start">
                  <Target className="w-6 h-6 text-nomadiq-teal mr-4 mt-1 flex-shrink-0" />
                  <div>
                    <h3 className="text-lg font-semibold text-nomadiq-black mb-2">
                      Conservation Partnerships (Ongoing)
                    </h3>
                    <p className="text-nomadiq-black/70">
                      We're continuously forming partnerships with local and international conservation organizations to expand our impact. From marine protected areas to wildlife corridors, we're investing in Kenya's natural heritage.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* CTA */}
        <section className="py-16 bg-gradient-to-r from-nomadiq-copper to-nomadiq-orange text-white">
          <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4">
              Travel with Purpose
            </h2>
            <p className="text-xl mb-8 text-white/90">
              Every journey with Nomadiq contributes to conservation, community empowerment, and sustainable tourism. Join us in creating positive change.
            </p>
            <a
              href="/packages"
              className="inline-block px-8 py-4 bg-white text-nomadiq-copper font-semibold rounded-lg hover:shadow-xl hover:scale-105 transition-all duration-300 text-lg"
            >
              Explore Our Experiences
            </a>
          </div>
        </section>
      </main>
      <Footer />
    </div>
  )
}

