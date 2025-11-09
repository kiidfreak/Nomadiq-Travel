import Header from '@/components/Header'
import Footer from '@/components/Footer'
import { Briefcase, Users, Heart, TrendingUp, MapPin, Clock, Mail, ArrowRight } from 'lucide-react'
import Link from 'next/link'

export default function CareersPage() {
  return (
    <div className="min-h-screen bg-nomadiq-bone">
      <Header />
      <main className="pt-20">
        {/* Hero Section */}
        <section className="bg-gradient-to-br from-nomadiq-copper/20 via-nomadiq-bone to-nomadiq-orange/20 py-20">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center max-w-3xl mx-auto">
              <div className="inline-block p-4 bg-nomadiq-copper/20 rounded-full mb-6">
                <Briefcase className="w-12 h-12 text-nomadiq-copper" />
              </div>
              <h1 className="text-4xl md:text-5xl font-serif font-bold mb-6 text-nomadiq-black">
                Join the Nomadiq Team
              </h1>
              <p className="text-xl text-nomadiq-black/70 mb-8">
                We're growing fast and looking for passionate, dedicated individuals who share our vision of sustainable, meaningful travel. Help us empower communities, protect wildlife, and create unforgettable experiences.
              </p>
            </div>
          </div>
        </section>

        {/* Why Work With Us */}
        <section className="py-16 bg-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-12">
              <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4 text-nomadiq-black">
                Why Nomadiq?
              </h2>
              <p className="text-lg text-nomadiq-black/70 max-w-2xl mx-auto">
                We're not just a travel company—we're a movement dedicated to positive change.
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div className="bg-nomadiq-bone rounded-2xl p-8 border border-nomadiq-sand/30">
                <div className="w-16 h-16 bg-nomadiq-copper/20 rounded-full flex items-center justify-center mb-6">
                  <Heart className="w-8 h-8 text-nomadiq-copper" />
                </div>
                <h3 className="text-xl font-serif font-bold mb-4 text-nomadiq-black">
                  Purpose-Driven Work
                </h3>
                <p className="text-nomadiq-black/70">
                  Every day, you'll contribute to conservation, community empowerment, and sustainable tourism. Your work has a real, measurable impact.
                </p>
              </div>

              <div className="bg-nomadiq-bone rounded-2xl p-8 border border-nomadiq-sand/30">
                <div className="w-16 h-16 bg-nomadiq-teal/20 rounded-full flex items-center justify-center mb-6">
                  <Users className="w-8 h-8 text-nomadiq-teal" />
                </div>
                <h3 className="text-xl font-serif font-bold mb-4 text-nomadiq-black">
                  Growth & Development
                </h3>
                <p className="text-nomadiq-black/70">
                  As we expand in 2026 and beyond, you'll have opportunities to grow your career, learn new skills, and take on leadership roles in a fast-growing company.
                </p>
              </div>

              <div className="bg-nomadiq-bone rounded-2xl p-8 border border-nomadiq-sand/30">
                <div className="w-16 h-16 bg-nomadiq-orange/20 rounded-full flex items-center justify-center mb-6">
                  <TrendingUp className="w-8 h-8 text-nomadiq-orange" />
                </div>
                <h3 className="text-xl font-serif font-bold mb-4 text-nomadiq-black">
                  Local Impact
                </h3>
                <p className="text-nomadiq-black/70">
                  We're committed to hiring locally and empowering Kenyan talent. Join us in creating employment opportunities and building a sustainable tourism industry.
                </p>
              </div>
            </div>
          </div>
        </section>

        {/* Open Positions */}
        <section className="py-16 bg-gradient-to-br from-nomadiq-teal/10 to-nomadiq-sky/10">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-12">
              <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4 text-nomadiq-black">
                Open Positions
              </h2>
              <p className="text-lg text-nomadiq-black/70">
                We're actively hiring for these roles. More positions coming soon as we grow!
              </p>
            </div>

            <div className="space-y-6 max-w-3xl mx-auto">
              <div className="bg-white rounded-xl p-6 border border-nomadiq-sand/30 hover:shadow-lg transition-shadow">
                <div className="flex items-start justify-between mb-4">
                  <div>
                    <h3 className="text-xl font-serif font-bold text-nomadiq-black mb-2">
                      Experienced Tour Guides
                    </h3>
                    <div className="flex flex-wrap gap-4 text-sm text-nomadiq-black/60">
                      <div className="flex items-center">
                        <MapPin className="w-4 h-4 mr-1" />
                        <span>Watamu, Lamu, Mombasa</span>
                      </div>
                      <div className="flex items-center">
                        <Clock className="w-4 h-4 mr-1" />
                        <span>Full-time</span>
                      </div>
                    </div>
                  </div>
                </div>
                <p className="text-nomadiq-black/70 mb-4">
                  We're looking for passionate, knowledgeable guides who can bring Kenya's coastal regions to life. Experience with marine life, local culture, and adventure activities preferred.
                </p>
                <div className="flex items-center text-nomadiq-copper font-semibold">
                  <span>Apply now</span>
                  <ArrowRight className="w-4 h-4 ml-2" />
                </div>
              </div>

              <div className="bg-white rounded-xl p-6 border border-nomadiq-sand/30 hover:shadow-lg transition-shadow">
                <div className="flex items-start justify-between mb-4">
                  <div>
                    <h3 className="text-xl font-serif font-bold text-nomadiq-black mb-2">
                      Operations Manager
                    </h3>
                    <div className="flex flex-wrap gap-4 text-sm text-nomadiq-black/60">
                      <div className="flex items-center">
                        <MapPin className="w-4 h-4 mr-1" />
                        <span>Nairobi / Remote</span>
                      </div>
                      <div className="flex items-center">
                        <Clock className="w-4 h-4 mr-1" />
                        <span>Full-time</span>
                      </div>
                    </div>
                  </div>
                </div>
                <p className="text-nomadiq-black/70 mb-4">
                  Help us scale operations as we grow. You'll coordinate logistics, manage partnerships, and ensure smooth delivery of exceptional travel experiences.
                </p>
                <div className="flex items-center text-nomadiq-copper font-semibold">
                  <span>Apply now</span>
                  <ArrowRight className="w-4 h-4 ml-2" />
                </div>
              </div>

              <div className="bg-white rounded-xl p-6 border border-nomadiq-sand/30 hover:shadow-lg transition-shadow">
                <div className="flex items-start justify-between mb-4">
                  <div>
                    <h3 className="text-xl font-serif font-bold text-nomadiq-black mb-2">
                      Conservation & Sustainability Coordinator
                    </h3>
                    <div className="flex flex-wrap gap-4 text-sm text-nomadiq-black/60">
                      <div className="flex items-center">
                        <MapPin className="w-4 h-4 mr-1" />
                        <span>Nairobi / Coastal Kenya</span>
                      </div>
                      <div className="flex items-center">
                        <Clock className="w-4 h-4 mr-1" />
                        <span>Full-time</span>
                      </div>
                    </div>
                  </div>
                </div>
                <p className="text-nomadiq-black/70 mb-4">
                  Lead our conservation initiatives, carbon credit programs, and ESG reporting. Help us create measurable positive impact and build sustainable tourism partnerships.
                </p>
                <div className="flex items-center text-nomadiq-copper font-semibold">
                  <span>Apply now</span>
                  <ArrowRight className="w-4 h-4 ml-2" />
                </div>
              </div>

              <div className="bg-white rounded-xl p-6 border border-nomadiq-sand/30 hover:shadow-lg transition-shadow">
                <div className="flex items-start justify-between mb-4">
                  <div>
                    <h3 className="text-xl font-serif font-bold text-nomadiq-black mb-2">
                      Community Relations Manager
                    </h3>
                    <div className="flex flex-wrap gap-4 text-sm text-nomadiq-black/60">
                      <div className="flex items-center">
                        <MapPin className="w-4 h-4 mr-1" />
                        <span>Coastal Kenya</span>
                      </div>
                      <div className="flex items-center">
                        <Clock className="w-4 h-4 mr-1" />
                        <span>Full-time</span>
                      </div>
                    </div>
                  </div>
                </div>
                <p className="text-nomadiq-black/70 mb-4">
                  Build and maintain relationships with local communities, manage our community investment fund, and create sustainable employment opportunities.
                </p>
                <div className="flex items-center text-nomadiq-copper font-semibold">
                  <span>Apply now</span>
                  <ArrowRight className="w-4 h-4 ml-2" />
                </div>
              </div>
            </div>

            <div className="mt-12 text-center">
              <div className="bg-nomadiq-bone rounded-xl p-8 border border-nomadiq-sand/30 max-w-2xl mx-auto">
                <h3 className="text-2xl font-serif font-bold mb-4 text-nomadiq-black">
                  Don't see a role that fits?
                </h3>
                <p className="text-nomadiq-black/70 mb-6">
                  We're always looking for talented, passionate people. Send us your resume and tell us how you'd like to contribute to Nomadiq's mission.
                </p>
                <a
                  href="mailto:careers@nomadiq.com"
                  className="inline-flex items-center px-6 py-3 bg-nomadiq-copper text-white font-semibold rounded-lg hover:bg-nomadiq-copper/90 transition-colors"
                >
                  <Mail className="w-5 h-5 mr-2" />
                  Send Your Resume
                </a>
              </div>
            </div>
          </div>
        </section>

        {/* Our Commitment */}
        <section className="py-16 bg-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-12">
              <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4 text-nomadiq-black">
                Our Commitment to You
              </h2>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
              <div className="bg-nomadiq-bone rounded-xl p-6">
                <h3 className="text-lg font-semibold text-nomadiq-black mb-3">
                  Competitive Compensation
                </h3>
                <p className="text-nomadiq-black/70">
                  We offer competitive salaries and benefits packages that reflect your skills and contribution to our mission.
                </p>
              </div>

              <div className="bg-nomadiq-bone rounded-xl p-6">
                <h3 className="text-lg font-semibold text-nomadiq-black mb-3">
                  Professional Development
                </h3>
                <p className="text-nomadiq-black/70">
                  We invest in your growth with training opportunities, skill development programs, and support for continuing education.
                </p>
              </div>

              <div className="bg-nomadiq-bone rounded-xl p-6">
                <h3 className="text-lg font-semibold text-nomadiq-black mb-3">
                  Work-Life Balance
                </h3>
                <p className="text-nomadiq-black/70">
                  We believe in sustainable work practices. Enjoy flexible schedules, generous time off, and the opportunity to work in beautiful locations.
                </p>
              </div>

              <div className="bg-nomadiq-bone rounded-xl p-6">
                <h3 className="text-lg font-semibold text-nomadiq-black mb-3">
                  Impact & Purpose
                </h3>
                <p className="text-nomadiq-black/70">
                  Every team member contributes to our conservation and community empowerment goals. Your work has real, measurable impact.
                </p>
              </div>
            </div>
          </div>
        </section>

        {/* Growth Stats */}
        <section className="py-16 bg-gradient-to-r from-nomadiq-copper to-nomadiq-orange text-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center mb-12">
            <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4">
              Growing Fast in 2026
            </h2>
            <p className="text-xl text-white/90">
              Join us as we expand our team and impact
            </p>
            </div>

            <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
              <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center">
                <div className="text-3xl font-serif font-bold mb-2">50+</div>
                <div className="text-sm text-white/80">Team members (target)</div>
              </div>
              <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center">
                <div className="text-3xl font-serif font-bold mb-2">100+</div>
                <div className="text-sm text-white/80">Jobs created</div>
              </div>
              <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center">
                <div className="text-3xl font-serif font-bold mb-2">15+</div>
                <div className="text-sm text-white/80">New roles in 2026</div>
              </div>
              <div className="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center">
                <div className="text-3xl font-serif font-bold mb-2">5+</div>
                <div className="text-sm text-white/80">New locations</div>
              </div>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </div>
  )
}

