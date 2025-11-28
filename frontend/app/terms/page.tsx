import Header from '@/components/Header'
import Footer from '@/components/Footer'

export default function TermsPage() {
    return (
        <main className="min-h-screen pt-20">
            <Header />
            <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                {/* Hero Section */}
                <div className="text-center mb-16">
                    <h1 className="text-5xl md:text-6xl font-serif text-nomadiq-black mb-6">
                        Terms & Conditions
                    </h1>
                    <p className="text-lg text-nomadiq-black/70">
                        Last updated: November 28, 2025
                    </p>
                </div>

                {/* Content */}
                <div className="prose prose-lg max-w-none">
                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">1. Acceptance of Terms</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            By accessing and using the Nomadiq website and services, you accept and agree to be
                            bound by these Terms and Conditions. If you do not agree with any part of these terms,
                            you may not access our services.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">2. Booking and Reservations</h2>

                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3">2.1 Booking Process</h3>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            All bookings are subject to availability. A booking is confirmed only when you receive
                            a confirmation email from Nomadiq with your booking reference number.
                        </p>

                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3">2.2 Payment</h3>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            Payment terms vary by package:
                        </p>
                        <ul className="list-disc pl-6 space-y-2 text-nomadiq-black/80 mb-4">
                            <li>Full payment is required at the time of booking for experiences under $500</li>
                            <li>For packages over $500, a 50% deposit is required to secure your booking, with the balance due 30 days prior to departure</li>
                            <li>Packages booked within 30 days of departure require full payment</li>
                        </ul>

                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3">2.3 Pricing</h3>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            All prices are quoted in USD unless otherwise stated. Prices are subject to change
                            until booking is confirmed. We reserve the right to correct pricing errors.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">3. Cancellation and Refunds</h2>

                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3">3.1 Cancellation by Customer</h3>
                        <ul className="list-disc pl-6 space-y-2 text-nomadiq-black/80 mb-4">
                            <li><strong>More than 60 days before departure:</strong> Full refund minus 10% administration fee</li>
                            <li><strong>30-60 days before departure:</strong> 50% refund</li>
                            <li><strong>15-29 days before departure:</strong> 25% refund</li>
                            <li><strong>Less than 15 days before departure:</strong> No refund</li>
                        </ul>

                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3">3.2 Cancellation by Nomadiq</h3>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            We reserve the right to cancel any booking due to circumstances beyond our control
                            (weather, safety concerns, insufficient bookings, etc.). In such cases, you will
                            receive a full refund or the option to reschedule.
                        </p>

                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3">3.3 Modifications</h3>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            Changes to confirmed bookings are subject to availability and may incur additional
                            fees. Contact us at least 14 days before your departure to request modifications.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">4. Traveler Responsibilities</h2>

                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3">4.1 Travel Documents</h3>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            You are responsible for ensuring you have valid passports, visas, and any required
                            health certificates. Nomadiq is not liable for any issues arising from invalid or
                            missing travel documents.
                        </p>

                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3">4.2 Health and Fitness</h3>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            You must inform us of any medical conditions, dietary requirements, or physical
                            limitations that may affect your participation in activities. Some experiences may
                            have specific health and fitness requirements.
                        </p>

                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3">4.3 Conduct</h3>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            You agree to conduct yourself in a respectful manner towards guides, local communities,
                            wildlife, and other travelers. We reserve the right to refuse service or terminate
                            your participation if your conduct is deemed disruptive or harmful.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">5. Insurance</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            We strongly recommend that all travelers obtain comprehensive travel insurance
                            covering trip cancellation, medical expenses, emergency evacuation, and personal
                            belongings. Nomadiq is not responsible for any losses not covered by insurance.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">6. Liability</h2>

                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3">6.1 Limitation of Liability</h3>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            Nomadiq acts as an agent for various service providers (hotels, transportation,
                            activity operators). While we carefully select our partners, we are not liable for
                            their actions, omissions, or failures to deliver services.
                        </p>

                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3">6.2 Force Majeure</h3>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            We are not liable for any failure to perform our obligations due to circumstances
                            beyond our control, including but not limited to natural disasters, war, terrorism,
                            pandemics, or government restrictions.
                        </p>

                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3">6.3 Personal Belongings</h3>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            You are responsible for your personal belongings at all times. Nomadiq is not liable
                            for any loss, damage, or theft of personal property.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">7. Photography and Media</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            By participating in our experiences, you consent to Nomadiq using photographs and
                            videos taken during your trip for promotional purposes, unless you explicitly opt out.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">8. Intellectual Property</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            All content on the Nomadiq website, including text, images, logos, and design, is
                            protected by copyright and other intellectual property rights. You may not reproduce,
                            distribute, or use our content without written permission.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">9. Complaints and Disputes</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            If you have any complaints during your trip, please inform your guide or contact us
                            immediately so we can address the issue. Any complaints after your return must be
                            submitted in writing within 30 days.
                        </p>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            These terms are governed by the laws of Kenya. Any disputes will be subject to the
                            exclusive jurisdiction of Kenyan courts.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">10. Changes to Terms</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            We reserve the right to modify these Terms and Conditions at any time. Changes will
                            be effective immediately upon posting on our website. Your continued use of our
                            services constitutes acceptance of the updated terms.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">11. Contact Information</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            For questions about these Terms and Conditions, please contact us:
                        </p>
                        <div className="bg-nomadiq-sand/30 rounded-lg p-6">
                            <p className="text-nomadiq-black/80 mb-2"><strong>Email:</strong> info@nomadiq.com</p>
                            <p className="text-nomadiq-black/80 mb-2"><strong>Phone:</strong> +254 700 757 129</p>
                            <p className="text-nomadiq-black/80"><strong>Address:</strong> Nairobi, Kenya</p>
                        </div>
                    </section>
                </div>
            </div>
            <Footer />
        </main>
    )
}
