import Header from '@/components/Header'
import Footer from '@/components/Footer'

export default function PrivacyPage() {
    return (
        <main className="min-h-screen pt-20">
            <Header />
            <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                {/* Hero Section */}
                <div className="text-center mb-16">
                    <h1 className="text-5xl md:text-6xl font-serif text-nomadiq-black mb-6">
                        Privacy Policy
                    </h1>
                    <p className="text-lg text-nomadiq-black/70">
                        Last updated: November 28, 2025
                    </p>
                </div>

                {/* Content */}
                <div className="prose prose-lg max-w-none">
                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">Introduction</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            At Nomadiq, we are committed to protecting your privacy and ensuring the security
                            of your personal information. This Privacy Policy explains how we collect, use,
                            disclose, and safeguard your information when you visit our website or use our services.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">Information We Collect</h2>
                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3">Personal Information</h3>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            We may collect personal information that you voluntarily provide to us when you:
                        </p>
                        <ul className="list-disc pl-6 space-y-2 text-nomadiq-black/80 mb-6">
                            <li>Register for an account</li>
                            <li>Book an experience or package</li>
                            <li>Subscribe to our newsletter</li>
                            <li>Contact us for support or inquiries</li>
                            <li>Participate in surveys or promotions</li>
                        </ul>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            This information may include: name, email address, phone number, postal address,
                            payment information, and travel preferences.
                        </p>

                        <h3 className="text-xl font-serif text-nomadiq-copper mb-3 mt-6">Automatically Collected Information</h3>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            When you visit our website, we automatically collect certain information about your
                            device, including IP address, browser type, operating system, referring URLs, and
                            pages viewed. We use cookies and similar tracking technologies to enhance your experience.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">How We Use Your Information</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            We use the information we collect to:
                        </p>
                        <ul className="list-disc pl-6 space-y-2 text-nomadiq-black/80">
                            <li>Process and fulfill your bookings and reservations</li>
                            <li>Send you confirmations, updates, and important notices</li>
                            <li>Provide customer support and respond to your inquiries</li>
                            <li>Personalize your experience and improve our services</li>
                            <li>Send marketing communications (with your consent)</li>
                            <li>Analyze usage trends and optimize our website</li>
                            <li>Comply with legal obligations and prevent fraud</li>
                        </ul>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">Information Sharing</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            We do not sell your personal information. We may share your information with:
                        </p>
                        <ul className="list-disc pl-6 space-y-2 text-nomadiq-black/80">
                            <li><strong>Service Providers:</strong> Third-party vendors who assist us in operating our business (e.g., payment processors, email service providers)</li>
                            <li><strong>Travel Partners:</strong> Hotels, tour operators, and activity providers necessary to fulfill your bookings</li>
                            <li><strong>Legal Requirements:</strong> When required by law or to protect our rights and safety</li>
                        </ul>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">Data Security</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            We implement appropriate technical and organizational security measures to protect
                            your personal information from unauthorized access, disclosure, alteration, or
                            destruction. However, no method of transmission over the internet is 100% secure,
                            and we cannot guarantee absolute security.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">Your Rights</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            Depending on your location, you may have certain rights regarding your personal information:
                        </p>
                        <ul className="list-disc pl-6 space-y-2 text-nomadiq-black/80">
                            <li>Access and receive a copy of your personal data</li>
                            <li>Correct inaccurate or incomplete information</li>
                            <li>Request deletion of your personal data</li>
                            <li>Object to or restrict certain processing activities</li>
                            <li>Withdraw consent for marketing communications</li>
                            <li>Data portability</li>
                        </ul>
                        <p className="text-nomadiq-black/80 leading-relaxed mt-4">
                            To exercise these rights, please contact us at privacy@nomadiq.com
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">Cookies</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            We use cookies and similar technologies to enhance your browsing experience, analyze
                            traffic, and personalize content. You can manage your cookie preferences through your
                            browser settings, though disabling cookies may affect website functionality.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">Children's Privacy</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            Our services are not directed to children under 18. We do not knowingly collect
                            personal information from children. If you believe we have collected information
                            from a child, please contact us immediately.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">Changes to This Policy</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed">
                            We may update this Privacy Policy from time to time. We will notify you of any
                            changes by posting the new policy on this page and updating the "Last updated" date.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-serif text-nomadiq-black mb-4">Contact Us</h2>
                        <p className="text-nomadiq-black/80 leading-relaxed mb-4">
                            If you have questions or concerns about this Privacy Policy, please contact us:
                        </p>
                        <div className="bg-nomadiq-sand/30 rounded-lg p-6">
                            <p className="text-nomadiq-black/80 mb-2"><strong>Email:</strong> privacy@nomadiq.com</p>
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
