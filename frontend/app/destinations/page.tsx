import Header from '@/components/Header'
import Footer from '@/components/Footer'
import Link from 'next/link'

export default function DestinationsPage() {
    const destinations = [
        {
            name: 'Watamu',
            description: 'A pristine coastal paradise known for its white sand beaches, vibrant marine life, and lush mangrove forests.',
            image: '🏖️',
            highlights: ['Marine National Park', 'Bio-Ken Snake Farm', 'Gede Ruins', 'Mida Creek']
        },
        {
            name: 'Malindi',
            description: 'Historic coastal town blending Swahili culture, Italian influences, and stunning natural beauty.',
            image: '🌴',
            highlights: ['Vasco da Gama Pillar', 'Marine Park', 'Falconry', 'Hell\'s Kitchen']
        },
        {
            name: 'Lamu',
            description: 'UNESCO World Heritage site, preserving centuries-old Swahili architecture and culture.',
            image: '⛵',
            highlights: ['Old Town', 'Dhow Safaris', 'Shela Beach', 'Traditional Swahili Cuisine']
        },
        {
            name: 'Diani',
            description: 'Kenya\'s most famous beach destination with crystal-clear waters and powdery white sand.',
            image: '🌊',
            highlights: ['Kite Surfing', 'Colobus Conservation', 'Shimba Hills', 'Diving & Snorkeling']
        }
    ]

    return (
        <main className="min-h-screen pt-20">
            <Header />
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                {/* Hero Section */}
                <div className="text-center mb-16">
                    <h1 className="text-5xl md:text-6xl font-serif text-nomadiq-black mb-6">
                        Our Destinations
                    </h1>
                    <p className="text-xl text-nomadiq-black/70 max-w-3xl mx-auto">
                        Explore the breathtaking coastal destinations of Kenya. From pristine beaches to
                        historic towns, each location offers unique experiences waiting to be discovered.
                    </p>
                </div>

                {/* Destinations Grid */}
                <div className="grid md:grid-cols-2 gap-12 mb-16">
                    {destinations.map((destination, index) => (
                        <div
                            key={index}
                            className="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300"
                        >
                            {/* Icon/Image */}
                            <div className="bg-gradient-to-br from-nomadiq-copper to-nomadiq-orange p-12 text-center">
                                <span className="text-8xl">{destination.image}</span>
                            </div>

                            {/* Content */}
                            <div className="p-8">
                                <h2 className="text-3xl font-serif text-nomadiq-black mb-4">
                                    {destination.name}
                                </h2>
                                <p className="text-nomadiq-black/70 mb-6 leading-relaxed">
                                    {destination.description}
                                </p>

                                {/* Highlights */}
                                <div className="mb-6">
                                    <h3 className="font-serif text-lg text-nomadiq-copper mb-3">Top Highlights</h3>
                                    <ul className="space-y-2">
                                        {destination.highlights.map((highlight, idx) => (
                                            <li key={idx} className="flex items-center text-nomadiq-black/70">
                                                <span className="text-nomadiq-copper mr-2">•</span>
                                                {highlight}
                                            </li>
                                        ))}
                                    </ul>
                                </div>

                                {/* CTA */}
                                <Link
                                    href="/packages"
                                    className="inline-block px-6 py-3 bg-nomadiq-copper text-white font-medium rounded-lg hover:bg-nomadiq-copper/90 transition-colors"
                                >
                                    View Experiences
                                </Link>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Why Choose Our Destinations */}
                <section className="bg-nomadiq-sand/30 rounded-2xl p-12 text-center">
                    <h2 className="text-3xl font-serif text-nomadiq-black mb-6">
                        Why Travel with Nomadiq?
                    </h2>
                    <div className="grid md:grid-cols-3 gap-8 text-left">
                        <div>
                            <h3 className="text-xl font-serif text-nomadiq-copper mb-3">Local Expertise</h3>
                            <p className="text-nomadiq-black/70">
                                Our guides are born and raised in these regions, offering insider knowledge
                                and authentic experiences you won't find in guidebooks.
                            </p>
                        </div>
                        <div>
                            <h3 className="text-xl font-serif text-nomadiq-copper mb-3">Sustainable Travel</h3>
                            <p className="text-nomadiq-black/70">
                                We partner with local communities and conservation projects to ensure your
                                travel has a positive impact.
                            </p>
                        </div>
                        <div>
                            <h3 className="text-xl font-serif text-nomadiq-copper mb-3">Curated Experiences</h3>
                            <p className="text-nomadiq-black/70">
                                Every destination is carefully selected to provide unique, memorable moments
                                that connect you with the true spirit of the coast.
                            </p>
                        </div>
                    </div>
                </section>
            </div>
            <Footer />
        </main>
    )
}
