<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         1.2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Test\TestCase\Controller;

use App\Controller\StudentsController;
use Cake\Core\App;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\TestSuite\IntegrationTestCase;
use Cake\View\Exception\MissingTemplateException;
use App\Controller\Component\PercentileComponent;
use App\Controller\Component\DataComponent;
use Cake\Controller\ComponentRegistry;
use App\Model\Entity\Student;

/**
 * PagesControllerTest class
 */
class StudentsControllerTest extends IntegrationTestCase
{
    public $allstudents = [
            ['Randy Perez', '1.60',10],
            ['Alice Brown', '3.50',80],
            ['Maria Russell', '2.20',30],
            ['Shirley Evans', '2.72',50],
            ['Daniel Bell', '3.5',80],
        ];
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
        $this->data = new DataComponent($registry);
    }

     /**
     * Testing all the students percentile Rank
     *
     * @return void
     */
    public function testAllStudentsRank()
    {
        $this->Students = new StudentsController();
        $file = WWW_ROOT.'files/students_test.txt';   //Support txt or csv files. //Get the Students ID, name & GPA Details

        //Call Data Component
        $studentData = $this->data->getFileData($file);

        $studentModel = new Student();
        //Call Student Entity
        $studentsDataArray = $studentModel->getDataArray($studentData);

        $students = $this->Students->getStudentsPercentileRank($studentsDataArray);
        foreach ($students as $key => $value)
        {
           $students_result[$key] = array($value['name'],$value['gpa'],$value['percentile']);
        }
        
        $this->assertEquals($this->allstudents, $students_result);
    }

     /**
     * Testing single student percentile Rank
     *
     */

     public function testSingleStudentRank()
    {
      $results = ['1.60'=>1, '3.50'=>2, '2.20'=>1,'2.72'=>1];
      $studentsArray = $this->students;

      if(!empty($studentsArray))
      {
            $studentsTotal = count($studentsArray);
            $sameGpa = $this->component->getSameGpa($studentsArray,"2");  //Call getSameGpa method in the percentile component.

            $percentile = $this->component->getPercentile($studentsArray,$studentsTotal,$sameGpa,'2.20');
            //Call getPercentile method in the percentile component.

            $this->assertEquals(30, $percentile);
      }
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->component, $this->controller);
    }

}
