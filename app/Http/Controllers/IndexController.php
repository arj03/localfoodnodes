<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests;
use App\Node\Node;
use App\Node\NodeCalendar;
use App\User\User;
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

        return view('public.index', [
            'metrics' => $metrics,
            'nodes' => $nodes,
            'events' => $events
        ]);
    }

    /**
     * Show content of a node and connected producers
     */
    public function node(Request $request, $id)
    {
        $node = Node::where('id', $id)->with('producerLinksRelationship', 'productsRelationship')->first();
        $producers = ProducerNodeLink::where('node_id', $id)->get()->map->getProducer();
        $products = $node->products();

        $productFilter = new ProductFilter($products, $request);
        $filteredProducts = $productFilter->filterDate()->filterTags()->get();
        $calendarMonth = $productFilter->getMonthDate();
        $calendar = new NodeCalendar($node);

        return view('public.node.node', [
            'node' => $node,
            'products' => $filteredProducts,
            'calendar'=> $calendar->get($request),
            'calendarMonth' => $calendarMonth,
            'tags' => $productFilter->getTagFilter($request),
        ]);
    }

    public function nodeProduct(Request $request, $nodeId, $productId)
    {
        $node = Node::find($nodeId);
        $product = Product::where('id', $productId)->with('productionsRelationship')->first();
        $producer = Producer::where('id', $product->producer_id)->first();

        return view('public.product.product', [
            'node' => $node,
            'product' => $product,
            'producer' => $producer
        ]);
    }

    /**
     * Show producer content
     */
    public function producer(Request $request, $id)
    {
        $producer = Producer::find($id);

        return view('public.producer.producer', [
            'producer' => $producer,
        ]);
    }

    public function producerProduct(Request $request, $producerId, $productId)
    {
        $producer = Producer::find($producerId);
        $product = Product::where('id', $productId)->with('productionsRelationship')->first();

        return view('public.product.product', [
            'product' => $product,
            'producer' => $producer
        ]);
    }

    public function event(Request $request, $eventId)
    {
        $event = Event::find($eventId);
        return view('public.event', [
            'event' => $event
        ]);
    }

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
