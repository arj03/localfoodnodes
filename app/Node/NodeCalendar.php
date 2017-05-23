<?php

namespace App\Node;

use Illuminate\Database\Eloquent\Collection;
use App\Event\Event;

class NodeCalendar
{
    private $node;

    /**
     * Constructor.
     *
     * @param Node $node
     */
    public function __construct($node)
    {
        $this->node = $node;
    }

    /**
     * Get calendar.
     *
     * @return Collection
     */
    public function get($request = null)
    {
        $deliveryDates = $this->getDeliveryDates();
        $eventDates = $this->getEventDates();

        $selectedDate = null;
        if ($request && $request->has('date')) {
            $selectedDate = $request->input('date');
        }

        $calendarItems = array_merge($deliveryDates, $eventDates);
        sort($calendarItems);

        if (count($calendarItems) > 0) {
            // Use $firstItem to move calendar to first calendar event
            // $firstItem = new \DateTime($calendarItems[0]);
            $firstDate = new \DateTime(date('Y-m-01'));

            $lastItem = new \DateTime($calendarItems[count($calendarItems) - 1]);
            $lastDate = new \DateTime($lastItem->format('Y-m-' . $lastItem->format('t')));
        } else {
            $daysInMonth = date('t');
            $firstDate = new \DateTime(date('Y-m-01'));
            $lastDate = new \DateTime(date('Y-m-' . $daysInMonth));
        }

        // Create calendar
        $calendar = [];
        $dateInterval = new \DateInterval('P1D');
        $datePeriod = new \DatePeriod($firstDate, $dateInterval, $lastDate);
        $nodeUrl = $this->node->permalink()->url;

        foreach ($datePeriod as $date) {
            $monthKey = $date->format('Y-m-01');
            $monthOffsetStart = date('N', strtotime($monthKey)) - 1;
            $monthOffsetEnd = (date('t', strtotime($monthKey)) + $monthOffsetStart) % 7;

            if ($monthOffsetEnd !== 0) {
                $monthOffsetEnd = 7 - $monthOffsetEnd;
            }

            $dayKey = $date->format('Y-m-d');

            // Create month
            if (!isset($calendar[$monthKey])) {
                $month = [
                    'days' => [],
                    'offsetStart' => $monthOffsetStart,
                    'offsetEnd' => $monthOffsetEnd
                ];

                $calendar[$monthKey] = $month;
            }


            // Create day
            $dayUrl = $nodeUrl . '?date=' . $dayKey;
            if ($selectedDate === $dayKey) {
                $dayUrl = $nodeUrl; // URL to reset selected date
            }

            $dayContent = [
                'events' => [],
                'activities' => [], // Used to generate classes
                'active' => false,
                'url' => $dayUrl
            ];

            $dayContent['activities'][] = 'day';

            // Add delivery to date
            if (in_array($dayKey, $deliveryDates)) {
                $dayContent['events'][] = 'delivery';
                $dayContent['activities'][] = 'delivery';
            }

            // Add event to date
            if (in_array($dayKey, $eventDates)) {
                $dayContent['events'][] = 'event';
                $dayContent['activities'][] = 'event';
            }

            // Strings of all events for date. Used to simply generate classes
            if ($selectedDate === $dayKey) {
                $dayContent['active'] = true;
                $dayContent['activities'][] = 'active';
            }

            $calendar[$monthKey]['days'][$dayKey] = $dayContent;
        }

        return $calendar;
    }

    /**
     * Get event dates.
     *
     * @return Collection
     */
    private function getEventDates()
    {
        $eventOwners = new Collection();
        $eventOwners->push($this->node->id);

        if ($this->node->producerLinks()->count() > 0) {
            $eventOwners->push($this->node->producerLinks()->map->producer_id->toArray());
        }

        $events = Event::whereIn('owner_id', $eventOwners->flatten())->get();

        $eventDates = [];
        $events->each(function($event) use (&$eventDates) {
            foreach ($event->getDatePeriod() as $date) {
                $eventDates[] = $date->format('Y-m-d');
            }
        });

        return $eventDates;
    }

    /**
     * Get delivery dates.
     *
     * @return Colletion
     */
    private function getDeliveryDates()
    {
        $products = $this->node->products();

        if (!$products->isEmpty()) {
            return $products->map(function($product) {
                return $product->deliveryLinks()->where('node_id', $this->node->id)->map(function($deliveryLink) {
                    return $deliveryLink->date('Y-m-d');
                });
            })->flatten()->toArray();
        } else {
            return [];
        }
    }
}
