<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Accessory;
use App\Models\Bird;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $birds       = Bird::all();
        $accessories = Accessory::all();

        if ($birds->isEmpty()) {
            $this->command->warn('No birds found — run BirdSeeder first.');
            return;
        }

        // ── 1. USERS ─────────────────────────────────────────────────────────
        $users = collect([
            [
                'name'      => 'Sarah Mitchell',
                'email'     => 'sarah.mitchell@example.com',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'James Thornton',
                'email'     => 'james.thornton@example.com',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'Amelia Chen',
                'email'     => 'amelia.chen@example.com',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'Omar Khalil',
                'email'     => 'omar.khalil@example.com',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'Priya Sharma',
                'email'     => 'priya.sharma@example.com',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'Lucas Fernandez',
                'email'     => 'lucas.fernandez@example.com',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'Emily Watson',
                'email'     => 'emily.watson@example.com',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name'      => 'Tariq Hassan',
                'email'     => 'tariq.hassan@example.com',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ],
        ])->map(fn ($data) => User::firstOrCreate(['email' => $data['email']], $data));

        // ── 2. ADDRESSES ─────────────────────────────────────────────────────
        $addressData = [
            'sarah.mitchell@example.com' => [
                ['label' => 'Home',   'name' => 'Sarah Mitchell', 'address' => '14 Elm Street',      'city' => 'Lahore',    'state' => 'Punjab',     'postal_code' => '54000', 'country' => 'Pakistan', 'phone' => '+92-300-1234567', 'is_default' => true],
                ['label' => 'Office', 'name' => 'Sarah Mitchell', 'address' => '3rd Floor, Plaza 22', 'city' => 'Lahore',    'state' => 'Punjab',     'postal_code' => '54600', 'country' => 'Pakistan', 'phone' => '+92-300-1234567', 'is_default' => false],
            ],
            'james.thornton@example.com' => [
                ['label' => 'Home',   'name' => 'James Thornton', 'address' => '88 Garden Town',     'city' => 'Karachi',   'state' => 'Sindh',      'postal_code' => '74400', 'country' => 'Pakistan', 'phone' => '+92-321-9876543', 'is_default' => true],
            ],
            'amelia.chen@example.com' => [
                ['label' => 'Home',   'name' => 'Amelia Chen',    'address' => '7 Gulberg III',       'city' => 'Lahore',    'state' => 'Punjab',     'postal_code' => '54660', 'country' => 'Pakistan', 'phone' => '+92-333-5551212', 'is_default' => true],
            ],
            'omar.khalil@example.com' => [
                ['label' => 'Home',   'name' => 'Omar Khalil',    'address' => '22 F-7/2',            'city' => 'Islamabad', 'state' => 'ICT',        'postal_code' => '44000', 'country' => 'Pakistan', 'phone' => '+92-312-7778899', 'is_default' => true],
                ['label' => 'Work',   'name' => 'Omar Khalil',    'address' => 'Blue Area, Block C',  'city' => 'Islamabad', 'state' => 'ICT',        'postal_code' => '44010', 'country' => 'Pakistan', 'phone' => '+92-312-7778899', 'is_default' => false],
            ],
            'priya.sharma@example.com' => [
                ['label' => 'Home',   'name' => 'Priya Sharma',   'address' => '5 DHA Phase 4',       'city' => 'Karachi',   'state' => 'Sindh',      'postal_code' => '75500', 'country' => 'Pakistan', 'phone' => '+92-345-6543210', 'is_default' => true],
            ],
            'lucas.fernandez@example.com' => [
                ['label' => 'Home',   'name' => 'Lucas Fernandez','address' => '11 Hayatabad Phase 5','city' => 'Peshawar',  'state' => 'KPK',        'postal_code' => '25000', 'country' => 'Pakistan', 'phone' => '+92-333-1112233', 'is_default' => true],
            ],
            'emily.watson@example.com' => [
                ['label' => 'Home',   'name' => 'Emily Watson',   'address' => '9 Bahria Town',       'city' => 'Rawalpindi','state' => 'Punjab',     'postal_code' => '46000', 'country' => 'Pakistan', 'phone' => '+92-301-4445566', 'is_default' => true],
            ],
            'tariq.hassan@example.com' => [
                ['label' => 'Home',   'name' => 'Tariq Hassan',   'address' => '36 Satellite Town',   'city' => 'Quetta',    'state' => 'Balochistan','postal_code' => '87300', 'country' => 'Pakistan', 'phone' => '+92-311-9998877', 'is_default' => true],
            ],
        ];

        foreach ($users as $user) {
            if (isset($addressData[$user->email])) {
                foreach ($addressData[$user->email] as $addr) {
                    Address::firstOrCreate(
                        ['user_id' => $user->id, 'label' => $addr['label']],
                        array_merge($addr, ['user_id' => $user->id])
                    );
                }
            }
        }

        // ── 3. ORDERS ────────────────────────────────────────────────────────
        $statuses        = ['preparing', 'transit', 'arrived', 'delivered', 'delivered', 'delivered'];
        $paymentMethods  = ['cod', 'jazzcash', 'bank_transfer'];
        $paymentStatuses = ['paid', 'paid', 'awaiting_verification', 'pending'];

        $orderScenarios = [
            // [user_email, bird_index, qty, status, payment_method, payment_status, created_days_ago]
            ['sarah.mitchell@example.com',  0,  1, 'delivered',  'jazzcash',      'paid',                  45],
            ['sarah.mitchell@example.com',  3,  1, 'transit',    'cod',           'pending',               5],
            ['james.thornton@example.com',  1,  1, 'delivered',  'bank_transfer', 'paid',                  60],
            ['james.thornton@example.com',  7,  1, 'preparing',  'jazzcash',      'paid',                  2],
            ['amelia.chen@example.com',     2,  1, 'delivered',  'cod',           'paid',                  90],
            ['amelia.chen@example.com',     5,  1, 'delivered',  'jazzcash',      'paid',                  30],
            ['amelia.chen@example.com',     9,  1, 'arrived',    'bank_transfer', 'awaiting_verification', 8],
            ['omar.khalil@example.com',     4,  1, 'delivered',  'cod',           'paid',                  75],
            ['omar.khalil@example.com',    12,  1, 'transit',    'jazzcash',      'paid',                  4],
            ['priya.sharma@example.com',    6,  1, 'delivered',  'bank_transfer', 'paid',                  50],
            ['priya.sharma@example.com',   15,  1, 'cancelled',  'cod',           'pending',               20],
            ['lucas.fernandez@example.com', 8,  1, 'delivered',  'jazzcash',      'paid',                  35],
            ['lucas.fernandez@example.com',11,  1, 'preparing',  'bank_transfer', 'awaiting_verification', 1],
            ['emily.watson@example.com',   10,  1, 'delivered',  'cod',           'paid',                  55],
            ['emily.watson@example.com',   13,  1, 'transit',    'jazzcash',      'paid',                  6],
            ['tariq.hassan@example.com',   14,  1, 'delivered',  'cod',           'paid',                  40],
        ];

        $createdOrders = []; // store for reviews later: [user_email => [bird_id, ...]]

        foreach ($orderScenarios as $scenario) {
            [$email, $birdIdx, $qty, $status, $payMethod, $payStatus, $daysAgo] = $scenario;

            $user = $users->firstWhere('email', $email);
            if (!$user) continue;

            $bird = $birds->get($birdIdx % $birds->count());
            if (!$bird) continue;

            $subtotal = $bird->price * $qty;
            $shipping = 49.00;
            $tax      = round($subtotal * 0.08, 2);
            $total    = $subtotal + $shipping + $tax;

            $addr = $addressData[$email][0] ?? [];

            $order = Order::create([
                'user_id'          => $user->id,
                'status'           => $status,
                'payment_method'   => $payMethod,
                'payment_status'   => $payStatus,
                'transaction_id'   => $payMethod !== 'cod' ? 'TXN-' . strtoupper(substr(uniqid(), -8)) : null,
                'subtotal'         => $subtotal,
                'shipping'         => $shipping,
                'tax'              => $tax,
                'total'            => $total,
                'shipping_name'    => $addr['name']    ?? $user->name,
                'shipping_address' => $addr['address'] ?? '1 Main Street',
                'shipping_city'    => $addr['city']    ?? 'Lahore',
                'shipping_postal'  => $addr['postal_code'] ?? '54000',
                'notes'            => $addr['phone']   ?? '',
                'created_at'       => now()->subDays($daysAgo),
                'updated_at'       => now()->subDays($daysAgo),
            ]);

            OrderItem::create([
                'order_id'  => $order->id,
                'bird_id'   => $bird->id,
                'quantity'  => $qty,
                'price'     => $bird->price,
            ]);

            // Add an accessory to some orders
            if ($daysAgo > 30 && $accessories->isNotEmpty()) {
                $acc = $accessories->random();
                OrderItem::create([
                    'order_id'     => $order->id,
                    'accessory_id' => $acc->id,
                    'quantity'     => 1,
                    'price'        => $acc->price,
                ]);
            }

            // ── Shipment for this order ──────────────────────────────────
            if ($status !== 'cancelled') {
                $this->createShipment($order, $user, $status, $daysAgo);
            }

            if (!isset($createdOrders[$email])) {
                $createdOrders[$email] = [];
            }
            $createdOrders[$email][] = ['bird' => $bird, 'status' => $status];
        }

        // ── 4. REVIEWS ───────────────────────────────────────────────────────
        $reviewBodies = [
            5 => [
                'Absolutely amazing bird! Settled in within two days and is already singing. Worth every penny.',
                'Exceptional quality and health. The bird arrived in perfect condition with all documentation. Highly recommend Bird Haven.',
                'Our family is in love. This bird has brought so much joy to our home. The care guide was incredibly helpful.',
                'Stunning specimen. The feathers are vibrant and the temperament is exactly as described. Will definitely order again.',
                'Bird Haven exceeded all my expectations. Fast shipping, great communication, and a truly beautiful bird.',
            ],
            4 => [
                'Beautiful bird, very healthy and active. Took a few days to warm up but is now very friendly.',
                'Really happy with my purchase. The bird is exactly as described. Shipping was well handled.',
                'Great experience overall. The bird is healthy and gorgeous. Minor delay in shipping but worth the wait.',
                'Very impressed with the quality. The bird is lively and well-socialized. Would order again.',
            ],
            3 => [
                'Nice bird but took longer than expected to arrive. Overall satisfied with the purchase.',
                'Good quality bird. The description was mostly accurate. Communication could have been better.',
                'Decent experience. The bird is healthy but was a bit stressed from transit initially.',
            ],
        ];

        $reviewAssignments = [
            ['sarah.mitchell@example.com',   0,  5, 45],
            ['james.thornton@example.com',   1,  4, 60],
            ['amelia.chen@example.com',      2,  5, 90],
            ['amelia.chen@example.com',      5,  4, 30],
            ['omar.khalil@example.com',      4,  5, 75],
            ['priya.sharma@example.com',     6,  3, 50],
            ['lucas.fernandez@example.com',  8,  4, 35],
            ['emily.watson@example.com',    10,  5, 55],
            ['tariq.hassan@example.com',    14,  4, 40],
            ['sarah.mitchell@example.com',   3,  3,  5], // pending — recent order
        ];

        foreach ($reviewAssignments as [$email, $birdIdx, $rating, $daysAgo]) {
            $user = $users->firstWhere('email', $email);
            $bird = $birds->get($birdIdx % $birds->count());
            if (!$user || !$bird) continue;

            $approved = $daysAgo > 7; // recent reviews pending, older ones approved

            $pool = $reviewBodies[$rating] ?? $reviewBodies[4];
            $body = $pool[array_rand($pool)];

            Review::firstOrCreate(
                ['bird_id' => $bird->id, 'user_id' => $user->id],
                [
                    'rating'     => $rating,
                    'body'       => $body,
                    'approved'   => $approved,
                    'created_at' => now()->subDays($daysAgo),
                    'updated_at' => now()->subDays($daysAgo),
                ]
            );
        }

        // ── 5. WISHLISTS ─────────────────────────────────────────────────────
        $wishlistAssignments = [
            ['sarah.mitchell@example.com',  [2, 5, 8, 14]],
            ['james.thornton@example.com',  [0, 4, 10]],
            ['amelia.chen@example.com',     [1, 7, 12, 18]],
            ['omar.khalil@example.com',     [3, 6, 15]],
            ['priya.sharma@example.com',    [0, 9, 11, 20]],
            ['lucas.fernandez@example.com', [2, 13, 17]],
            ['emily.watson@example.com',    [4, 8, 16]],
            ['tariq.hassan@example.com',    [1, 5, 19]],
        ];

        foreach ($wishlistAssignments as [$email, $birdIndexes]) {
            $user = $users->firstWhere('email', $email);
            if (!$user) continue;

            foreach ($birdIndexes as $idx) {
                $bird = $birds->get($idx % $birds->count());
                if (!$bird) continue;

                Wishlist::firstOrCreate([
                    'user_id' => $user->id,
                    'bird_id' => $bird->id,
                ]);
            }
        }

        // ── 6. CONTACT MESSAGES ──────────────────────────────────────────────
        $contactMessages = [
            [
                'name'       => 'Sarah Mitchell',
                'email'      => 'sarah.mitchell@example.com',
                'topic'      => 'order',
                'subject'    => 'My order hasn\'t arrived yet',
                'message'    => 'Hello, I placed an order about two weeks ago and it still hasn\'t arrived. The tracking page shows "In Flight" but there\'s been no update for 3 days. Could you please check on this for me? My order number should be visible on your end. Thank you.',
                'is_read'    => true,
                'read_at'    => now()->subDays(10),
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(10),
            ],
            [
                'name'       => 'James Thornton',
                'email'      => 'james.thornton@example.com',
                'topic'      => 'bird_care',
                'subject'    => 'Feeding schedule for my new parrot',
                'message'    => 'Hi Bird Haven team! I recently purchased a parrot from your store and I\'m a first-time bird owner. Could you share a recommended feeding schedule and diet plan? The care guide that came with him was helpful but I\'d love more detail on fresh fruits and vegetables.',
                'is_read'    => true,
                'read_at'    => now()->subDays(55),
                'created_at' => now()->subDays(58),
                'updated_at' => now()->subDays(55),
            ],
            [
                'name'       => 'Amelia Chen',
                'email'      => 'amelia.chen@example.com',
                'topic'      => 'general',
                'subject'    => 'Absolutely love Bird Haven!',
                'message'    => 'Just wanted to write in and say how wonderful my experience has been. I\'ve ordered three birds over the past few months and every single one arrived healthy, happy, and exactly as described. Your team\'s packaging is incredible. Will definitely be recommending you to all my friends.',
                'is_read'    => true,
                'read_at'    => now()->subDays(28),
                'created_at' => now()->subDays(29),
                'updated_at' => now()->subDays(28),
            ],
            [
                'name'       => 'Omar Khalil',
                'email'      => 'omar.khalil@example.com',
                'topic'      => 'order',
                'subject'    => 'Request for invoice copy',
                'message'    => 'Assalam o Alaikum, I need a formal invoice for my recent purchase for my company records. Could you email me a PDF copy? The order was placed about 2 months ago. I have the confirmation email but need an official invoice with your business details. Jazak Allah Khair.',
                'is_read'    => false,
                'read_at'    => null,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'name'       => 'Priya Sharma',
                'email'      => 'priya.sharma@example.com',
                'topic'      => 'returns',
                'subject'    => 'Inquiry about return policy',
                'message'    => 'Hello, I received my order but unfortunately the bird seems unwell — it\'s not eating and appears lethargic. I\'ve consulted a vet who confirmed it may have arrived stressed. I would like to understand the return and health guarantee policy. Please advise on next steps.',
                'is_read'    => false,
                'read_at'    => null,
                'created_at' => now()->subDays(18),
                'updated_at' => now()->subDays(18),
            ],
            [
                'name'       => 'Lucas Fernandez',
                'email'      => 'lucas.fernandez@example.com',
                'topic'      => 'bird_care',
                'subject'    => 'Cage size recommendations',
                'message'    => 'Hi, I\'m planning to purchase a macaw from your collection and want to make sure I have the right setup before it arrives. What minimum cage dimensions do you recommend? Also, do you sell any suitable cage accessories or should I source them separately?',
                'is_read'    => true,
                'read_at'    => now()->subDays(30),
                'created_at' => now()->subDays(33),
                'updated_at' => now()->subDays(30),
            ],
            [
                'name'       => 'Tariq Hassan',
                'email'      => 'tariq.hassan@example.com',
                'topic'      => 'general',
                'subject'    => 'Availability of African Grey',
                'message'    => 'Good day. I am very interested in purchasing an African Grey Parrot. I browsed your website but couldn\'t find one currently listed. Do you have any in stock or can I be notified when one becomes available? I am in Quetta and happy to wait for the right bird.',
                'is_read'    => false,
                'read_at'    => null,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'name'       => 'Emily Watson',
                'email'      => 'emily.watson@example.com',
                'topic'      => 'order',
                'subject'    => 'Can I change my delivery address?',
                'message'    => 'Hi team, I just placed an order this morning and realized I entered the wrong delivery address. I\'ve since moved and forgot to update my profile. Is it possible to update the delivery address before the shipment is dispatched? Please let me know urgently. Thanks!',
                'is_read'    => true,
                'read_at'    => now()->subDays(4),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
            ],
            [
                'name'       => 'Zara Malik',
                'email'      => 'zara.malik@gmail.com',
                'topic'      => 'general',
                'subject'    => 'Do you ship internationally?',
                'message'    => 'Hello, I am a Pakistani expat living in Dubai and would love to purchase a bird from Bird Haven for my family back in Lahore as a gift. Do you offer international payment options like credit cards? Also, do you have gift wrapping or any special packaging for gifting?',
                'is_read'    => false,
                'read_at'    => null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'name'       => 'Hamza Iqbal',
                'email'      => 'hamza.iqbal@outlook.com',
                'topic'      => 'bird_care',
                'subject'    => 'Cockatiel not talking after 3 months',
                'message'    => 'I bought a cockatiel from you 3 months ago and despite daily training sessions he still hasn\'t started mimicking words. Is this normal for this species? He is otherwise healthy and very active. Should I be trying different training methods? Any tips from your avian experts would be greatly appreciated.',
                'is_read'    => true,
                'read_at'    => now()->subDays(7),
                'created_at' => now()->subDays(9),
                'updated_at' => now()->subDays(7),
            ],
        ];

        foreach ($contactMessages as $msg) {
            ContactMessage::firstOrCreate(
                ['email' => $msg['email'], 'subject' => $msg['subject']],
                $msg
            );
        }

        // ── 7. NEWSLETTER SUBSCRIBERS ────────────────────────────────────────
        $subscribers = [
            ['email' => 'sarah.mitchell@example.com',  'name' => 'Sarah Mitchell',  'is_active' => true,  'unsubscribed_at' => null,       'created_at' => now()->subDays(45)],
            ['email' => 'james.thornton@example.com',  'name' => 'James Thornton',  'is_active' => true,  'unsubscribed_at' => null,       'created_at' => now()->subDays(60)],
            ['email' => 'amelia.chen@example.com',     'name' => 'Amelia Chen',     'is_active' => true,  'unsubscribed_at' => null,       'created_at' => now()->subDays(90)],
            ['email' => 'omar.khalil@example.com',     'name' => 'Omar Khalil',     'is_active' => true,  'unsubscribed_at' => null,       'created_at' => now()->subDays(75)],
            ['email' => 'priya.sharma@example.com',    'name' => 'Priya Sharma',    'is_active' => false, 'unsubscribed_at' => now()->subDays(5),  'created_at' => now()->subDays(50)],
            ['email' => 'lucas.fernandez@example.com', 'name' => 'Lucas Fernandez', 'is_active' => true,  'unsubscribed_at' => null,       'created_at' => now()->subDays(35)],
            ['email' => 'emily.watson@example.com',    'name' => 'Emily Watson',    'is_active' => true,  'unsubscribed_at' => null,       'created_at' => now()->subDays(55)],
            ['email' => 'tariq.hassan@example.com',    'name' => 'Tariq Hassan',    'is_active' => false, 'unsubscribed_at' => now()->subDays(10), 'created_at' => now()->subDays(40)],
            ['email' => 'zara.malik@gmail.com',        'name' => 'Zara Malik',      'is_active' => true,  'unsubscribed_at' => null,       'created_at' => now()->subDays(2)],
            ['email' => 'hamza.iqbal@outlook.com',     'name' => 'Hamza Iqbal',     'is_active' => true,  'unsubscribed_at' => null,       'created_at' => now()->subDays(9)],
            ['email' => 'nadia.rehman@yahoo.com',      'name' => 'Nadia Rehman',    'is_active' => true,  'unsubscribed_at' => null,       'created_at' => now()->subDays(20)],
            ['email' => 'bilal.chaudhry@gmail.com',    'name' => 'Bilal Chaudhry',  'is_active' => true,  'unsubscribed_at' => null,       'created_at' => now()->subDays(15)],
            ['email' => 'sana.butt@hotmail.com',       'name' => 'Sana Butt',       'is_active' => false, 'unsubscribed_at' => now()->subDays(3),  'created_at' => now()->subDays(25)],
            ['email' => 'fahad.mirza@gmail.com',       'name' => 'Fahad Mirza',     'is_active' => true,  'unsubscribed_at' => null,       'created_at' => now()->subDays(8)],
            ['email' => 'ayesha.siddiqui@gmail.com',   'name' => 'Ayesha Siddiqui', 'is_active' => true,  'unsubscribed_at' => null,       'created_at' => now()->subDays(5)],
        ];

        foreach ($subscribers as $sub) {
            NewsletterSubscriber::firstOrCreate(
                ['email' => $sub['email']],
                array_merge($sub, ['updated_at' => $sub['created_at']])
            );
        }

        $this->command->info('Demo data seeded: 8 users, addresses, ' . count($orderScenarios) . ' orders, reviews, wishlists.');
    }

    private function createShipment(Order $order, $user, string $status, int $daysAgo): void
    {
        // Map order status → shipment stage
        $stageMap = [
            'preparing' => 'hatchery',
            'transit'   => 'in_flight',
            'arrived'   => 'local',
            'delivered' => 'delivered',
        ];

        $stage = $stageMap[$status] ?? 'hatchery';

        // Build date progression based on daysAgo
        $base = now()->subDays($daysAgo);

        $hatcheryDate = $base->copy();
        $healthDate   = in_array($stage, ['health', 'in_flight', 'local', 'delivered']) ? $base->copy()->addDays(1) : null;
        $flightDate   = in_array($stage, ['in_flight', 'local', 'delivered'])           ? $base->copy()->addDays(2) : null;
        $localDate    = in_array($stage, ['local', 'delivered'])                         ? $base->copy()->addDays(4) : null;
        $deliveryDate = $stage === 'delivered'                                           ? $base->copy()->addDays(6) : null;
        $estimated    = $stage === 'delivered'
            ? $base->copy()->addDays(6)
            : now()->addDays(max(1, 6 - $daysAgo));

        Shipment::create([
            'user_id'            => $user->id,
            'order_id'           => $order->id,
            'stage'              => $stage,
            'temperature'        => rand(18, 26) + 0.5,
            'oxygen'             => rand(19, 21) + 0.3,
            'estimated_delivery' => $estimated,
            'hatchery_date'      => $hatcheryDate,
            'health_date'        => $healthDate,
            'flight_date'        => $flightDate,
            'local_date'         => $localDate,
            'delivery_date'      => $deliveryDate,
            'created_at'         => $hatcheryDate,
            'updated_at'         => $deliveryDate ?? $localDate ?? $flightDate ?? $healthDate ?? $hatcheryDate,
        ]);
    }
}
