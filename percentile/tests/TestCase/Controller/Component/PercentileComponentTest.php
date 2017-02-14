<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\PercentileComponent;
use Cake\Controller\ComponentRegistry;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\TestSuite\TestCase;


class PercentileComponentTest extends TestCase
{
    public $component = null;
    public $controller = null;

    public function setUp()
    {
        parent::setUp();
    }

    public $students = [
            [471908, 'Randy Perez', '1.60'],
            [957625, 'Alice Brown', '3.50'],
            [909401, 'Maria Russell', '2.20'],
            [780367, 'Shirley Evans', '2.72'],
            [841786, 'Daniel Bell', '3.50'],
        ];
    /**
     * Testing getting interest frequency
     *
     * @return void
     */
    public function testPercentileSameGpa()
    {
      $results = ['1.60'=>1, '3.50'=>2, '2.20'=>1,'2.72'=>1];
      $sameGpa = array_count_values(
                            array_map(function($value)
                            {
                                return $value['2'];
                            }, $this->students)
                       );
      $this->assertEquals($results,$sameGpa);
    }

    /**
     * Testing getting percentile
     *
     * @return void
     */
    public function testPercentileRank()
    {
        $results = ['1.60'=>1, '3.50'=>2, '2.20'=>1,'2.72'=>1];
        $sameGpa = array_count_values(
                        array_map(function($value)
                        {
                            return $value['2'];
                        }, $this->students));
        $y=2.20;
        $lessScoresArr = array_filter($this->students, function ($student) use ($y) {
                                                        return floatval($student[2]) < floatval($y);
                                                    }) ;
        //calculating percentile
        $percentile = ((count($lessScoresArr)+(0.5 * 1))/5)*100;
        $percentilePercentage1 = round($percentile, 2);
        $this->assertEquals(30, $percentilePercentage1);
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
?>
