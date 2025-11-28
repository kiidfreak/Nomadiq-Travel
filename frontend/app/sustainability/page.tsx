import Header from '@/components/Header'
import Footer from '@/components/Footer'
import Link from 'next/link'

export default function SustainabilityPage() {
    return (
        <main className="min-h-screen pt-20">
            <Header />
            <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                {/* Hero Section */}
                <div className="text-center mb-16">
                    <h1 className="text-5xl md:text-6xl font-serif text-nomadiq-black mb-6">
                        Sustainability & Conservation
                    </h1>
                    <p className="text-xl text-nomadiq-black/70 max-w-3xl mx-auto">
                        Our commitment to protecting Kenya's coastal ecosystems and empowering local communities
                    </p>
                </div>

                {/* Mission Statement */}
                <section className="mb-16 bg-gradient-to-br from-nomadiq-copper/10 to-nomadiq-orange/10 rounded-2xl p-8">
                    <h2 className="text-3xl font-serif text-nomadiq-black mb-4">Our Commitment</h2>
                    <p className="text-lg text-nomadiq-black/80 leading-relaxed">
                        At Nomadiq, we believe that travel should enrich both the traveler and the destination.
                        We are deeply committed to sustainable tourism practices that preserve the natural beauty
                        of Kenya's coast, protect its wildlife, and create meaningful benefits for local communities.
                    </p>
                </section>

                {/* Environmental Conservation */}
                <section className="mb-12">
                    <h2 className="text-3xl font-serif text-nomadiq-black mb-6">Environmental Conservation</h2>

                    <div className="space-y-6">
                        <div className="border-l-4 border-nomadiq-copper pl-6">
                            <h3 className="text-xl font-serif text-nomadiq-copper mb-3">Marine Protection</h3>
                            <p className="text-nomadiq-black/80 leading-relaxed mb-3">
                                We partner with local marine conservation organizations to protect coral reefs,
                                sea turtles, and marine ecosystems. A portion of every booking directly supports
                                marine conservation efforts in Watamu Marine National Park.
                            </p>
                            <ul className="list-disc pl-6 space-y-2 text-nomadiq-black/70">
                                <li>Support for coral reef restoration projects</li>
                                <li>Sea turtle monitoring and protection programs</li>
                                <li>Beach cleanup initiatives</li>
                                <li>Marine debris reduction programs</li>
                            </ul>
                        </div>

                        <div className="border-l-4 border-nomadiq-copper pl-6">
                            <h3 className="text-xl font-serif text-nomadiq-copper mb-3">Wildlife Protection</h3>
                            <p className="text-nomadiq-black/80 leading-relaxed mb-3">
                                We collaborate with organizations like the Watamu Turtle Watch and Local Ocean
                                Conservation to protect endangered species and their habitats.
                            </p>
                            <ul className="list-disc pl-6 space-y-2 text-nomadiq-black/70">
                                <li>Zero harassment policy for wildlife encounters</li>
                                <li>Education programs about responsible wildlife viewing</li>
                                <li>Support for anti-poaching efforts</li>
                                <li>Habitat preservation initiatives</li>
                            </ul>
                        </div>

                        <div className="border-l-4 border-nomadiq-copper pl-6">
                            <h3 className="text-xl font-serif text-nomadiq-copper mb-3">Carbon Footprint</h3>
                            <p className="text-nomadiq-black/80 leading-relaxed mb-3">
                                We're committed to minimizing our environmental impact through various initiatives:
                            </p>
                            <ul className="list-disc pl-6 space-y-2 text-nomadiq-black/70">
                                <li>Carbon offset programs for all transportation</li>
                                <li>Partnering with eco-friendly accommodations</li>
                                <li>Promoting sustainable transportation options (dhows, bicycles)</li>
                                <li>Single-use plastic elimination in all our operations</li>
                            </ul>
                        </div>
                    </div>
                </section>

                {/* Community Empowerment */}
                <section className="mb-12">
                    <h2 className="text-3xl font-serif text-nomadiq-black mb-6">Community Empowerment</h2>

                    <div className="space-y-6">
                        <div className="bg-nomadiq-sand/20 rounded-lg p-6">
                            <h3 className="text-xl font-serif text-nomadiq-copper mb-3">Local Employment</h3>
                            <p className="text-nomadiq-black/80 leading-relaxed">
                                100% of our guides, drivers, and support staff are local residents. We provide
                                fair wages, training opportunities, and career development programs to ensure
                                sustainable livelihoods for coastal communities.
                            </p>
                        </div>

                        <div className="bg-nomadiq-sand/20 rounded-lg p-6">
                            <h3 className="text-xl font-serif text-nomadiq-copper mb-3">Cultural Preservation</h3>
                            <p className="text-nomadiq-black/80 leading-relaxed">
                                We work with local communities to preserve traditional Swahili culture, crafts,
                                and knowledge. Our experiences showcase authentic cultural practices while ensuring
                                communities benefit economically from sharing their heritage.
                            </p>
                        </div>

                        <div className="bg-nomadiq-sand/20 rounded-lg p-6">
                            <h3 className="text-xl font-serif text-nomadiq-copper mb-3">Community Projects</h3>
                            <p className="text-nomadiq-black/80 leading-relaxed mb-3">
                                We support local development initiatives including:
                            </p>
                            <ul className="list-disc pl-6 space-y-2 text-nomadiq-black/70">
                                <li>Education programs for children in coastal communities</li>
                                <li>Clean water and sanitation projects</li>
                                <li>Skills training for sustainable livelihoods</li>
                                <li>Support for local artisans and craftspeople</li>
                            </ul>
                        </div>
                    </div>
                </section>

                {/* Responsible Travel Guidelines */}
                <section className="mb-12">
                    <h2 className="text-3xl font-serif text-nomadiq-black mb-6">Responsible Travel Guidelines</h2>
                    <p className="text-nomadiq-black/80 leading-relaxed mb-6">
                        We ask all our travelers to commit to these principles:
                    </p>

                    <div className="grid md:grid-cols-2 gap-6">
                        <div className="space-y-4">
                            <div className="flex items-start">
                                <span className="text-2xl mr-3">🌊</span>
                                <div>
                                    <h4 className="font-semibold text-nomadiq-black mb-1">Respect Marine Life</h4>
                                    <p className="text-nomadiq-black/70 text-sm">
                                        Maintain safe distances, never touch coral or marine animals, and use reef-safe sunscreen
                                    </p>
                                </div>
                            </div>

                            <div className="flex items-start">
                                <span className="text-2xl mr-3">♻️</span>
                                <div>
                                    <h4 className="font-semibold text-nomadiq-black mb-1">Minimize Waste</h4>
                                    <p className="text-nomadiq-black/70 text-sm">
                                        Carry reusable water bottles, avoid single-use plastics, and properly dispose of all waste
                                    </p>
                                </div>
                            </div>

                            <div className="flex items-start">
                                <span className="text-2xl mr-3">🤝</span>
                                <div>
                                    <h4 className="font-semibold text-nomadiq-black mb-1">Support Local</h4>
                                    <p className="text-nomadiq-black/70 text-sm">
                                        Buy from local artisans, eat at local restaurants, and respect cultural traditions
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div className="space-y-4">
                            <div className="flex items-start">
                                <span className="text-2xl mr-3">🌿</span>
                                <div>
                                    <h4 className="font-semibold text-nomadiq-black mb-1">Tread Lightly</h4>
                                    <p className="text-nomadiq-black/70 text-sm">
                                        Stay on designated paths, avoid disturbing wildlife habitats, and leave no trace
                                    </p>
                                </div>
                            </div>

                            <div className="flex items-start">
                                <span className="text-2xl mr-3">💧</span>
                                <div>
                                    <h4 className="font-semibold text-nomadiq-black mb-1">Conserve Resources</h4>
                                    <p className="text-nomadiq-black/70 text-sm">
                                        Use water wisely, minimize energy consumption, and choose eco-friendly options
                                    </p>
                                </div>
                            </div>

                            <div className="flex items-start">
                                <span className="text-2xl mr-3">📚</span>
                                <div>
                                    <h4 className="font-semibold text-nomadiq-black mb-1">Learn & Share</h4>
                                    <p className="text-nomadiq-black/70 text-sm">
                                        Educate yourself about local ecosystems and share sustainable practices with others
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Partnerships */}
                <section className="mb-12">
                    <h2 className="text-3xl font-serif text-nomadiq-black mb-6">Our Partners</h2>
                    <p className="text-nomadiq-black/80 leading-relaxed mb-6">
                        We're proud to collaborate with leading conservation and community organizations:
                    </p>

                    <div className="grid md:grid-cols-2 gap-4">
                        <div className="border border-nomadiq-copper/20 rounded-lg p-4">
                            <h4 className="font-semibold text-nomadiq-copper mb-2">Watamu Turtle Watch</h4>
                            <p className="text-nomadiq-black/70 text-sm">
                                Protecting sea turtles and their nesting sites along the Kenyan coast
                            </p>
                        </div>

                        <div className="border border-nomadiq-copper/20 rounded-lg p-4">
                            <h4 className="font-semibold text-nomadiq-copper mb-2">Local Ocean Conservation</h4>
                            <p className="text-nomadiq-black/70 text-sm">
                                Marine research and community-based conservation initiatives
                            </p>
                        </div>

                        <div className="border border-nomadiq-copper/20 rounded-lg p-4">
                            <h4 className="font-semibold text-nomadiq-copper mb-2">Kenya Wildlife Service</h4>
                            <p className="text-nomadiq-black/70 text-sm">
                                Protecting Kenya's marine parks and wildlife habitats
                            </p>
                        </div>

                        <div className="border border-nomadiq-copper/20 rounded-lg p-4">
                            <h4 className="font-semibold text-nomadiq-copper mb-2">Community Groups</h4>
                            <p className="text-nomadiq-black/70 text-sm">
                                Local cooperatives, artisan groups, and cultural organizations
                            </p>
                        </div>
                    </div>
                </section>

                {/* Impact Metrics */}
                <section className="mb-12 bg-nomadiq-black text-white rounded-2xl p-8">
                    <h2 className="text-3xl font-serif mb-6">Our Impact in 2024</h2>
                    <div className="grid md:grid-cols-3 gap-8 text-center">
                        <div>
                            <div className="text-4xl font-bold text-nomadiq-copper mb-2">$50,000+</div>
                            <p className="text-white/70">Contributed to conservation projects</p>
                        </div>
                        <div>
                            <div className="text-4xl font-bold text-nomadiq-copper mb-2">150+</div>
                            <p className="text-white/70">Local jobs created and supported</p>
                        </div>
                        <div>
                            <div className="text-4xl font-bold text-nomadiq-copper mb-2">100%</div>
                            <p className="text-white/70">Carbon offset for all our trips</p>
                        </div>
                    </div>
                </section>

                {/* CTA */}
                <section className="text-center bg-nomadiq-sand/30 rounded-2xl p-8">
                    <h2 className="text-3xl font-serif text-nomadiq-black mb-4">
                        Travel with Purpose
                    </h2>
                    <p className="text-lg text-nomadiq-black/70 mb-6">
                        Join us in creating positive change while experiencing the magic of Kenya's coast
                    </p>
                    <Link
                        href="/packages"
                        className="inline-block px-8 py-3 bg-nomadiq-copper text-white font-medium rounded-lg hover:bg-nomadiq-copper/90 transition-colors"
                    >
                        Explore Sustainable Experiences
                    </Link>
                </section>
            </div>
            <Footer />
        </main>
    )
}
