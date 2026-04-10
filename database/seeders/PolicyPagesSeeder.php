<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class PolicyPagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title'            => 'Privacy Policy',
                'slug'             => 'privacy-policy',
                'meta_description' => 'Read Bird Haven\'s privacy policy to understand how we collect, use, and protect your personal information.',
                'is_published'     => true,
                'content'          => '<h2>1. Information We Collect</h2>
<p>When you create an account or place an order at Bird Haven, we collect the following personal information:</p>
<ul>
<li>Full name and email address</li>
<li>Shipping address and phone number</li>
<li>Payment method details (JazzCash number, bank reference — we do not store card numbers)</li>
<li>Order history and purchase preferences</li>
<li>Device and browser information for security purposes</li>
</ul>

<h2>2. How We Use Your Information</h2>
<p>We use your personal information to:</p>
<ul>
<li>Process and fulfill your orders</li>
<li>Send order confirmation and shipping updates via email</li>
<li>Provide customer support</li>
<li>Improve our website and services</li>
<li>Comply with legal obligations under Pakistani law</li>
</ul>
<p>We do <strong>not</strong> sell, rent, or share your personal information with third parties for marketing purposes.</p>

<h2>3. Data Security</h2>
<p>We implement industry-standard security measures including SSL encryption, hashed passwords, and two-factor authentication to protect your account. Despite these measures, no method of transmission over the internet is 100% secure.</p>

<h2>4. Cookies</h2>
<p>We use cookies and session storage to keep you signed in, remember your cart, and improve your browsing experience. You may disable cookies in your browser settings, though some features may not function properly.</p>

<h2>5. Data Retention</h2>
<p>We retain your personal data for as long as your account is active or as required to fulfill orders and legal obligations. You may request deletion of your account and data by contacting us.</p>

<h2>6. Your Rights</h2>
<p>You have the right to access, correct, or delete your personal data. To make such a request, <a href="/contact">contact us here</a>.</p>

<h2>7. Contact</h2>
<p>Questions about this policy? Reach us via our <a href="/contact">contact page</a>.</p>',
            ],
            [
                'title'            => 'Terms of Service',
                'slug'             => 'terms',
                'meta_description' => 'Bird Haven terms of service — your rights and responsibilities as a customer and guardian.',
                'is_published'     => true,
                'content'          => '<h2>1. Acceptance of Terms</h2>
<p>By accessing or using Bird Haven\'s website and services, you agree to be bound by these Terms of Service. If you do not agree, please do not use our services.</p>

<h2>2. Account Responsibility</h2>
<p>You are responsible for maintaining the confidentiality of your account credentials. Bird Haven is not liable for any loss resulting from unauthorized use of your account. Notify us immediately if you suspect unauthorized access.</p>

<h2>3. Orders and Payments</h2>
<ul>
<li>All prices are listed in USD. Orders are subject to availability.</li>
<li>We reserve the right to cancel any order at our discretion with a full refund.</li>
<li>For JazzCash payments, the transaction is final once confirmed by the payment gateway.</li>
<li>For bank transfers, orders are processed after payment verification (typically 1–2 business days).</li>
<li>Cash on Delivery orders must be paid in full upon receiving the shipment.</li>
</ul>

<h2>4. Shipping and Delivery</h2>
<p>We ship across Pakistan. Delivery timelines are estimates and may vary due to regional logistics, weather, or circumstances beyond our control. Bird Haven is not responsible for delays caused by courier services.</p>

<h2>5. Returns and Refunds</h2>
<p>Due to the living nature of our products (birds), all sales are final once a bird has been delivered and accepted. If an animal arrives in poor health due to our negligence, please contact us within 24 hours with photographic evidence for a resolution.</p>

<h2>6. Prohibited Use</h2>
<p>You may not use Bird Haven to engage in any unlawful activity, resell animals in ways that violate Pakistani wildlife regulations, or misrepresent yourself when placing orders.</p>

<h2>7. Limitation of Liability</h2>
<p>Bird Haven\'s liability is limited to the purchase price of the product in question. We are not liable for indirect, incidental, or consequential damages arising from your use of our services.</p>

<h2>8. Changes to Terms</h2>
<p>We may update these terms at any time. Continued use of the website constitutes acceptance of the revised terms.</p>

<h2>9. Governing Law</h2>
<p>These terms are governed by the laws of Pakistan. Any disputes shall be subject to the exclusive jurisdiction of courts in Pakistan.</p>',
            ],
            [
                'title'            => 'Ethical Sourcing',
                'slug'             => 'ethical-sourcing',
                'meta_description' => 'Learn how Bird Haven sources every bird ethically — licensed breeders, CITES compliance, and conservation-first practices.',
                'is_published'     => true,
                'content'          => '<h2>Our Breeder Network</h2>
<p>Every bird at Bird Haven comes from a verified breeder or aviary that has passed our vetting process. We require proof of legal ownership, breeding licences, and a facility inspection before approving any supplier.</p>
<p>We do not source birds from wild capture or from markets where animal welfare standards cannot be verified.</p>

<h2>CITES and Pakistani Wildlife Law</h2>
<p>Bird Haven fully complies with the Convention on International Trade in Endangered Species (CITES) and all applicable Pakistani wildlife protection legislation. Species listed under CITES Appendix I are never traded. Appendix II species are sourced only with the appropriate permits.</p>

<h2>Health Certification</h2>
<p>All birds undergo a veterinary health examination and receive documentation before being listed for sale. Each purchase includes a health certificate from a licensed avian veterinarian.</p>

<h2>Shipping Welfare Standards</h2>
<p>We only ship birds in IATA-approved carriers with adequate ventilation, temperature control, and no more than 4 hours of transit time per journey. Birds are never shipped in extreme weather conditions.</p>

<h2>Conservation Contribution</h2>
<p>A percentage of every sale is donated to avian conservation programmes within Pakistan, including wetland habitat protection and education initiatives for local communities.</p>

<h2>Report a Concern</h2>
<p>If you have reason to believe any bird offered on Bird Haven was illegally sourced or subjected to inhumane treatment, please <a href="/contact">contact us immediately</a>. We take every report seriously.</p>',
            ],
            [
                'title'            => 'Ethical Care Agreement',
                'slug'             => 'ethical-care',
                'meta_description' => 'By purchasing from Bird Haven you enter this Ethical Care Agreement — a pledge to provide humane, responsible guardianship.',
                'is_published'     => true,
                'content'          => '<h2>1. Commitment to Wellbeing</h2>
<p>As a guardian, you commit to providing your bird with a spacious, clean, and stimulating environment. This includes appropriate cage size, perches of varying diameters, foraging opportunities, and daily social interaction.</p>

<h2>2. Proper Nutrition</h2>
<p>You agree to feed your bird a species-appropriate diet that goes beyond seeds alone. Fresh fruits, vegetables, pellets, and clean water must be available daily. Species-specific dietary guides are included with every purchase.</p>

<h2>3. Veterinary Care</h2>
<p>You agree to seek qualified avian veterinary care when your bird shows signs of illness, injury, or distress. Annual wellness checks are strongly encouraged.</p>

<h2>4. No Resale or Breeding Without Authorization</h2>
<p>Birds purchased from Bird Haven are for personal guardianship only. Resale or use in commercial breeding programmes without explicit written consent from Bird Haven is prohibited.</p>

<h2>5. Reporting Concerns</h2>
<p>If you are no longer able to care for your bird, please contact Bird Haven before rehoming. We maintain a network of vetted guardians and can assist in safe transitions rather than abandonment.</p>

<h2>6. Legal Compliance</h2>
<p>You agree to comply with all applicable Pakistani laws and international regulations regarding the keeping of exotic birds, including CITES requirements where applicable.</p>

<p><strong>By completing a purchase, you confirm that you have read and agreed to this Ethical Care Agreement.</strong> Violation may result in account suspension and, in severe cases, referral to wildlife protection authorities.</p>',
            ],
        ];

        foreach ($pages as $data) {
            CmsPage::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
