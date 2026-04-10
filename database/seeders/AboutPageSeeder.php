<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        CmsPage::updateOrCreate(
            ['slug' => 'about'],
            [
                'title'            => 'About BirdHaven',
                'content'          => '',
                'meta_description' => 'Learn about BirdHaven — our story, our team, and our ethical commitment to avian guardianship.',
                'is_published'     => true,
                'meta'             => [
                    // ── Hero ────────────────────────────────────────
                    'hero_headline'        => 'Elevating the Standard of Avian Stewardship',
                    'hero_intro'           => 'Founded in the pursuit of harmony between humans and the sky\'s most brilliant creatures, Bird Haven is more than a sanctuary—it\'s a commitment to a higher ethical standard.',
                    'mobile_hero_headline' => 'Wings of Change',
                    'mobile_hero_intro'    => 'We believe every feather tells a story. Bird Haven began as a promise to honor the ancient bond between humans and birds.',

                    // ── Story ────────────────────────────────────────
                    'story_title'  => 'A Legacy in Flight',
                    'story_body_1' => 'It began with a single rescue—a Macaw named \'Ciel\'. What we discovered was a gap in the global understanding of avian needs. Traditional retail environments prioritized aesthetic over wellbeing. We chose a different path.',
                    'story_body_2' => 'Today, we represent a global network of ethical guardians, researchers, and artisans dedicated to the preservation and dignified care of every wing that enters our archive.',
                    'story_banner'       => null,
                    'story_banner_child' => null,

                    // ── Ethical Mandate ──────────────────────────────
                    'mandate_quote' => 'We believe that guardianship is not ownership. Our mission is to provide environments that reflect the complexity of natural habitats, ensuring every bird thrives physically and emotionally through research-backed enrichment.',
                    'mandate_stats' => [
                        ['value' => '500+', 'label' => 'Species Documented'],
                        ['value' => '12K+', 'label' => 'Ethical Guardians'],
                        ['value' => '98%',  'label' => 'Welfare Rating'],
                    ],

                    // ── Team ────────────────────────────────────────
                    'team' => [
                        [
                            'name'  => 'Dr. Elena Marsh',
                            'role'  => 'Chief Avian Behaviorist',
                            'bio'   => 'With 20 years of field research, Elena leads our species enrichment programs.',
                            'image' => null,
                        ],
                        [
                            'name'  => 'Marcus Vane',
                            'role'  => 'Master Habitat Artisan',
                            'bio'   => 'Marcus designs custom sanctuary environments for each species in our care.',
                            'image' => null,
                        ],
                        [
                            'name'  => 'Dr. Amara Okello',
                            'role'  => 'Veterinary Specialist',
                            'bio'   => 'Amara oversees all health protocols and ethical sourcing certifications.',
                            'image' => null,
                        ],
                    ],

                    // ── CTA ─────────────────────────────────────────
                    'cta_title'    => 'Join the Sanctuary Community',
                    'cta_subtitle' => 'Receive quarterly journals on avian health, species preservation, and ethical stewardship.',
                ],
            ]
        );
    }
}
