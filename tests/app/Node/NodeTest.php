<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\Node\Node;
use \App\Node\NodeDeliveryDate;

class NodeTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        DB::unprepared(file_get_contents(base_path('tests') . '/fixture.sql'));
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testGetDeliveryDates()
    {
        $node = Node::find('1');
        $this->assertEquals('2017-01-01', $node->season_start);
        $this->assertEquals('2017-12-31', $node->season_end);
        $this->assertEquals('monday', $node->delivery_weekday);
        $this->assertCount(1, $node->getDeliveryDates('2017-01-01', '2017-01-07'));
        $this->assertCount(52, $node->getDeliveryDates('2017-01-01', '2017-12-31'));
        $this->assertCount(52, $node->getDeliveryDates('2016-01-01', '2018-12-31'));
        $this->assertCount(0, $node->getDeliveryDates('2016-01-01', '2016-12-31'));

        $node = Node::find('2');
        $this->assertEquals('2017-05-01', $node->season_start);
        $this->assertEquals('2017-08-31', $node->season_end);
        $this->assertEquals('tuesday', $node->delivery_weekday);
        $this->assertCount(1, $node->getDeliveryDates('2017-05-01', '2017-05-07'));
        $this->assertCount(18, $node->getDeliveryDates('2017-01-01', '2017-12-31'));
        $this->assertCount(18, $node->getDeliveryDates('2016-01-01', '2018-12-31'));
        $this->assertCount(0, $node->getDeliveryDates('2016-01-01', '2016-12-31'));
    }

    public function testGetDeliveryDatesByMonth()
    {
        $node = Node::find('1');
        $this->assertEquals('2017-01-01', $node->season_start);
        $this->assertEquals('2017-12-31', $node->season_end);
        $this->assertEquals('monday', $node->delivery_weekday);
        $this->assertCount(1, $node->getDeliveryDates('2017-01-01', '2017-01-07'));
        $this->assertCount(52, $node->getDeliveryDates('2017-01-01', '2017-12-31'));
        $this->assertCount(52, $node->getDeliveryDates('2016-01-01', '2018-12-31'));
        $this->assertCount(0, $node->getDeliveryDates('2016-01-01', '2016-12-31'));

        $node = Node::find('2');
        $this->assertEquals('2017-05-01', $node->season_start);
        $this->assertEquals('2017-08-31', $node->season_end);
        $this->assertEquals('tuesday', $node->delivery_weekday);
        $this->assertCount(1, $node->getDeliveryDates('2017-05-01', '2017-05-07'));
        $this->assertCount(18, $node->getDeliveryDates('2017-01-01', '2017-12-31'));
        $this->assertCount(18, $node->getDeliveryDates('2016-01-01', '2018-12-31'));
        $this->assertCount(0, $node->getDeliveryDates('2016-01-01', '2016-12-31'));
    }

    public function distance($lat, $lng)
    {

    }

    public function getAverageProducerDistance()
    {

    }
}
