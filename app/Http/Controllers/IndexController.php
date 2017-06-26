<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests;
use App\Node\Node;
use App\Node\NodeCalendar;
use App\User\User;
use App\User\UserMembershipPayment;
use App\Producer\Producer;
use App\Producer\ProducerNodeLink;
use App\Product\Product;
use App\Product\ProductNodeDeliveryLink;
use App\Product\ProductProduction;
use App\Event\Event;
use App\Donation;

use App\Product\ProductFilter;

use DateTime;

class IndexController extends Controller
{
    /**
     * Show all nodes
     */
    public function index(Request $request)
    {
        $metrics = $this->getFrontpageMetrics();

        $nodes = null;
        if (Auth::user()) {
            $nodes = Auth::user()->nodeLinks()->map(function($nodeLink) {
                return $nodeLink->getNode();
            });
        }

        $events = Event::where('start_datetime', '>', date('Y-m-d', time()))->limit(5)->orderBy('start_datetime')->get();

        $users = User::with(['membershipPaymentsRelationship'])->get();
        $members = $users->filter(function($user) {
            return $user->isMember(true);
        })->count();

        $allPayments = UserMembershipPayment::get();
        $totalMembershipPayments = $allPayments->map(function($payment) {
            return ($payment->amount > 2) ? $payment->amount : null;
        })->filter()->sum();

        $totalPayingMembers = $allPayments->unique('user_id')->count();
        $averageMembershipPayments = $members === 0 ? 0 : $totalMembershipPayments / $totalPayingMembers;

        return view('public.index', [
            'metrics' => $metrics,
            'nodes' => $nodes,
            'events' => $events,
            'members' => $members,
            'averageMembership' => round($averageMembershipPayments)
        ]);
    }

    /**
     * Show content of a node and connected producers
     */
    public function node(Request $request, $id)
    {
        $node = Node::where('id', $id)->with('producerLinksRelationship', 'productNodeDeliveryLinksRelationship')->first();
        $producers = ProducerNodeLink::where('node_id', $id)->get()->map->getProducer();
        $products = $node->products();

        $productFilter = new ProductFilter($products, $request);
        $filteredProducts = $productFilter->filterDate()->filterTags()->get();
        $calendarMonth = $productFilter->getMonthDate();
        $calendar = new NodeCalendar($node);

        $date = null;
        if ($request->has('date')) {
            try {
                $date = new \DateTime($request->get('date'));
            } catch (\Exception $e) {}
        }

        $events = $node->getAllEvents($date);

        $fbMeta = [
            'fbUrl' => app('url')->to($node->permalink()->url),
            'fbTitle' => trim($node->name),
            'fbDescription' => trim(strip_tags($node->info)),
            'fbImage' => $node->images()->count() > 0 ? $node->images()->first()->url('small') : null
        ];

        return view('public.node.node', [
            'node' => $node,
            'events' => $events,
            'products' => $filteredProducts,
            'calendar'=> $calendar->get($request),
            'calendarMonth' => $calendarMonth,
            'tags' => $productFilter->getTagFilter($request),
        ] + $fbMeta);
    }

    /**
     * Node product action.
     */
    public function nodeProduct(Request $request, $nodeId, $productId)
    {
        $node = Node::find($nodeId);
        $product = Product::where('id', $productId)->with('productionsRelationship')->first();
        $producer = Producer::where('id', $product->producer_id)->first();

        $fbUrl = $node->permalink()->url . $product->permalink()->url;
        $fbMeta = [
            'fbUrl' => app('url')->to($fbUrl),
            'fbTitle' => trim($product->name),
            'fbDescription' => trim(strip_tags($product->info)),
            'fbImage' => $product->images()->count() > 0 ? $product->images()->first()->url('small') : null
        ];

        return view('public.product.product', [
            'node' => $node,
            'product' => $product,
            'producer' => $producer
        ] + $fbMeta);
    }

    /**
     * Producer action.
     */
    public function producer(Request $request, $id)
    {
        $producer = Producer::find($id);

        $fbMeta = [
            'fbUrl' => app('url')->to($producer->permalink()->url),
            'fbTitle' => trim($producer->name),
            'fbDescription' => trim(strip_tags($producer->info)),
            'fbImage' => $producer->images()->count() > 0 ? $producer->images()->first()->url('small') : null
        ];

        return view('public.producer.producer', [
            'producer' => $producer,
        ] + $fbMeta);
    }

    /**
     * Producer product action.
     */
    public function producerProduct(Request $request, $producerId, $productId)
    {
        $producer = Producer::find($producerId);
        $product = Product::where('id', $productId)->with('productionsRelationship')->first();

        $fbUrl = $node->permalink()->url . $product->permalink()->url;
        $fbMeta = [
            'fbUrl' => app('url')->to($fbUrl),
            'fbTitle' => trim($product->name),
            'fbDescription' => trim(strip_tags($product->info)),
            'fbImage' => $product->images()->count() > 0 ? $product->images()->first()->url('small') : null
        ];

        return view('public.product.product', [
            'product' => $product,
            'producer' => $producer
        ] + $fbMeta);
    }

    /**
     * Event action.
     */
    public function event(Request $request, $eventId)
    {
        $event = Event::find($eventId);

        $fbMeta = [
            'fbUrl' => app('url')->to($event->permalink()->url),
            'fbTitle' => trim($event->name),
            'fbDescription' => trim(strip_tags($event->info)),
            'fbImage' => $event->images()->count() > 0 ? $event->images()->first()->url('small') : null
        ];

        return view('public.event', [
            'event' => $event
        ] + $fbMeta);
    }

    /**
     * Get frontpage metrics.
     *
     * @return array
     */
    private function getFrontpageMetrics()
    {
        return [
            'userCount' => User::all()->count(),
            'producerCount' => Producer::all()->count(),
            'nodeCount' => Node::all()->count(),
        ];
    }

    // Static pages

    public function findOutMore()
    {
        return view('public.pages.find-out-more');
    }
}
