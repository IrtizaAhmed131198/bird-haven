<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\View\View;

class ShippingController extends Controller
{
    public function index(): View
    {
        $shipment = Shipment::where('user_id', auth()->id())
            ->latest()
            ->first();

        [$trackingSteps, $journeyLog] = $this->buildTrackingData($shipment);

        return view('pages.shipping-tracking', compact('shipment', 'trackingSteps', 'journeyLog'));
    }

    public function show(int $shipment): View
    {
        $shipment = Shipment::where('user_id', auth()->id())
            ->findOrFail($shipment);

        [$trackingSteps, $journeyLog] = $this->buildTrackingData($shipment);

        return view('pages.shipping-tracking', compact('shipment', 'trackingSteps', 'journeyLog'));
    }

    private function buildTrackingData(?Shipment $shipment): array
    {
        $currentStage = $shipment?->stage ?? 'in_flight';

        $stages = ['hatchery', 'health', 'in_flight', 'local', 'delivered'];
        $currentIndex = array_search($currentStage, $stages) ?: 2;

        $trackingSteps = [
            ['icon' => 'egg',             'label' => 'Hatchery Preparation', 'date' => $shipment?->hatchery_date    ?? 'Completed',         'done' => $currentIndex >= 0, 'active' => $currentIndex === 0],
            ['icon' => 'health_and_safety','label' => 'Health Clearance',    'date' => $shipment?->health_date      ?? 'Completed',         'done' => $currentIndex >= 1, 'active' => $currentIndex === 1],
            ['icon' => 'flight',           'label' => 'In Flight',           'date' => $shipment?->flight_date      ?? 'Current Stage',     'done' => $currentIndex >= 2, 'active' => $currentIndex === 2],
            ['icon' => 'potted_plant',     'label' => 'Local Sanctuary',     'date' => $shipment?->local_date       ?? 'Estimated Soon',    'done' => $currentIndex >= 3, 'active' => $currentIndex === 3],
            ['icon' => 'home_pin',         'label' => 'Home Delivery',       'date' => $shipment?->delivery_date    ?? 'Estimated Soon',    'done' => $currentIndex >= 4, 'active' => $currentIndex === 4],
        ];

        $journeyLog = [
            ['date' => $shipment?->hatchery_date ?? 'Pending', 'event' => 'Hatchery Preparation Complete', 'location' => 'Origin Sanctuary',             'done' => $currentIndex >= 0],
            ['date' => $shipment?->health_date   ?? 'Pending', 'event' => 'Health & Wellness Cleared',     'location' => 'Certified Veterinary Facility', 'done' => $currentIndex >= 1],
            ['date' => $shipment?->flight_date   ?? 'Pending', 'event' => 'Climate-Controlled Transit',    'location' => 'In Flight',                    'done' => $currentIndex >= 2],
            ['date' => $shipment?->local_date    ?? 'Pending', 'event' => 'Arrived at Local Sanctuary',    'location' => 'Regional Hub',                 'done' => $currentIndex >= 3],
            ['date' => $shipment?->delivery_date ?? 'Pending', 'event' => 'Delivered to Guardian',         'location' => 'Your Home',                    'done' => $currentIndex >= 4],
        ];

        return [$trackingSteps, $journeyLog];
    }
}
