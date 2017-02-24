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

    public $students = [
            [471908, 'Randy Perez', '1.60'],
            [957625, 'Alice Brown', '3.50'],
            [909401, 'Maria Russell', '2.20'],
            [780367, 'Shirley Evans', '2.72'],
            [841786, 'Daniel Bell', '3.50'],
        ];

    public function setUp()
    {
        parent::setUp();
        $request = new Request();
        $response = new Response();
        $this->controller = $this->getMockBuilder('Cake\Controller\Controller')
            ->setConstructorArgs([$request, $response])
            ->setMethods(null)
            ->getMock();
        $registry = new ComponentRegistry($this->controller);
        $this->component = new PercentileComponent($registry);
    }

    /**
     * Testing Percentile Rank different GPA Mark
     *
     */

     public function testStudentsPercentile()
    {
      $results = ['1.60'=>1, '3.50'=>2, '2.20'=>1,'2.72'=>1];
      $studentsArray = $this->students;

      if(!empty($studentsArray))
      {
            $studentsTotal = count($studentsArray);
            $sameGpa = $this->component->getSameGpa($studentsArray,"2");  //Call getSameGpa method in the percentile component.

            $percentile = $this->component->getPercentile($studentsArray,$studentsTotal,$sameGpa,'1.60');
            //Call getPercentile method in the percentile component.

            $this->assertEquals(10, $percentile);
      }
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->component, $this->controller);
    }
}
?>
