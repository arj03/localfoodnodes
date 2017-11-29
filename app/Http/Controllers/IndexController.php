<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use LocalFoodNodes\Sdk\LocalFoodNodes;

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
     * @var LocalFoodNodes
     */
    private $api;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->api = new LocalFoodNodes(env('API_URL'), env('PUBLIC_API_CLIENT_ID'), env('PUBLIC_API_SECRET'), env('PUBLIC_API_USERNAME'), env('PUBLIC_API_PASSWORD'));
    }

    /**
     * Proxy for the frontend to communicating with API
     *
     * @param Request $request
     * @return string json
     */
    public function apiProxy(Request $request)
    {
        $method = $request->has('method') ? $request->input('method') : 'get';
        $url = $request->input('url');

        return $this->api->request($method, $url, $request->all());
    }

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
    public function node(Request $request, $nodeId)
    {
        $node = Node::where('id', $nodeId)->with('producerLinksRelationship', 'productNodeDeliveryLinksRelationship')->first();
        $producers = ProducerNodeLink::where('node_id', $nodeId)->get()->map->getProducer();
        $products = $node->products();

        $productFilter = new ProductFilter($products, $request);
        $filteredProducts = $productFilter->filterDate($nodeId)->filterTags()->filterVisibility()->get();
        $calendarMonth = $productFilter->getMonthDate();
        $calendar = new NodeCalendar($node);

        $date = null;
        if ($request->has('date')) {
            $date = new \DateTime($request->get('date'));
        }

        $events = $node->getAllEvents($date);

        $shareMeta = [
            'shareUrl' => $this->shareUrl($node->permalink()->url),
            'shareTitle' => trim($node->name),
            'shareDescription' => trim(strip_tags($node->info)),
            'shareImage' => $node->images()->count() > 0 ? $node->images()->first()->url('small') : null
        ];

        return view('public.node.node', [
            'node' => $node,
            'events' => $events,
            'products' => $filteredProducts->sortBy('name')->values(),
            'calendar'=> $calendar->get($request),
            'calendarMonth' => $calendarMonth,
            'date' => $date,
            'tags' => $productFilter->getTagFilter($request),
        ] + $shareMeta);
    }

    /**
     * Node product action.
     */
    public function nodeProduct(Request $request, $nodeId, $productId)
    {
        $node = Node::find($nodeId);
        $product = Product::where('id', $productId)->with('productionsRelationship')->first();

        $producer = Producer::where('id', $product->producer_id)->first();

        $shareMeta = [
            'shareUrl' => $this->shareUrl($node->permalink()->url, $product->permalink()->url),
            'shareTitle' => trim($product->name),
            'shareDescription' => trim(strip_tags($product->info)),
            'shareImage' => $product->images()->count() > 0 ? $product->images()->first()->url('small') : null
        ];

        return view('public.product.product', [
            'node' => $node,
            'product' => $product,
            'producer' => $producer
        ] + $shareMeta);
    }

    /**
     * Producer action.
     */
    public function producer(Request $request, $id)
    {
        $producer = Producer::find($id);

        $shareMeta = [
            'shareUrl' => $this->shareUrl($producer->permalink()->url),
            'shareTitle' => trim($producer->name),
            'shareDescription' => trim(strip_tags($producer->info)),
            'shareImage' => $producer->images()->count() > 0 ? $producer->images()->first()->url('small') : null
        ];

        return view('public.producer.producer', [
            'producer' => $producer,
        ] + $shareMeta);
    }

    /**
     * Producer product action.
     */
    public function producerProduct(Request $request, $producerId, $productId)
    {
        $producer = Producer::find($producerId);
        $product = Product::where('id', $productId)->with('productionsRelationship')->first();

        $shareMeta = [
            'shareUrl' => $this->shareUrl($producer->permalink()->url, $product->permalink()->url),
            'shareTitle' => trim($product->name),
            'shareDescription' => trim(strip_tags($product->info)),
            'shareImage' => $product->images()->count() > 0 ? $product->images()->first()->url('small') : null
        ];

        return view('public.product.product', [
            'product' => $product,
            'producer' => $producer
        ] + $shareMeta);
    }

    /**
     * Event action.
     */
    public function event(Request $request, $eventId)
    {
        $event = Event::find($eventId);

        $shareMeta = [
            'shareUrl' => $this->shareUrl($event->permalink()->url),
            'shareTitle' => trim($event->name),
            'shareDescription' => trim(strip_tags($event->info)),
            'shareImage' => $event->images()->count() > 0 ? $event->images()->first()->url('small') : null
        ];

        return view('public.event', [
            'event' => $event
        ] + $shareMeta);
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

    public function findOutMore()
    {
        return view('public.pages.find-out-more');
    }

    /**
     * Get share url with language.
     *
     * @param string $firstUrl
     * @param string $secondUrl
     * @return string
     */
    private function shareUrl($firstUrl, $secondUrl = null)
    {
        $shareUrl = $firstUrl;

        if ($secondUrl) {
            $shareUrl .= $secondUrl;
        }

        $shareUrl .= '?lang=' . $this->getLang();

        return app('url')->to($shareUrl);
    }
}
