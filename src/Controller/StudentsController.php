<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use App\Model\Entity\Student;

/**
 * Students content controller. This class contain about student details.
 *
 * This file will render views from views/students/
 *
 *
 * CakePHP(tm) : Rapid Development Framework
 *
 * PHP version 5
 *
 * Students Controller
 *
 * @package students
 * @subpackage students.controllers
*/


class StudentsController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Students';


    /* Load the  components for use */
    public $components = array('Data','Percentile');


    public function initialize()
    {
       parent::initialize();
       $studentModel = new Student(); //Student Entity Object
       $this->Student = $studentModel;
    }

/**
 * Find students percentile rank.
 * We have two options - Sample Date and Custom Data. While selecting Custom Data to upload students details.
 * File format - txt or csv files.
 *
 *
 * @name index
 * @return students result Array
 */
   public function index()
   {
      try
      {
        $file = WWW_ROOT.'files/students.txt';   //Support txt or csv files.

        //Call Data Component
        $studentData = $this->Data->getFileData($file);
        $this->set('studentData',$studentData);

        //Call Student Entity
        $studentsDataArray = $this->Student->getDataArray($studentData);

        $students = $this->getStudentsPercentileRank($studentsDataArray);
        $this->set('students',$students);

      }catch (Exception $e)
      {
       throw new Exception($e->getMessage());
      }

      $this->set('title_for_layout', __('Students Home Page'));
   }

 /**
* Calculate Students Percentile Rank.
* Call Percentile Component
*
*
* @name getStudentsPercentileRank
* @return students Percentile Rank Results Array
*/

   public function getStudentsPercentileRank($studentsDataArray)
   {
        $students = array();
        if(!empty($studentsDataArray))
        {
            $studentsTotal = count($studentsDataArray);
            $sameGpa = $this->Percentile->getSameGpa($studentsDataArray,"2");  //Call getSameGpa method in the percentile component.

               foreach ($studentsDataArray as $key => $value)
               {
                   if(!empty($value))
                   {
                    $gpa = utf8_encode($value['2']);
                    $percentile = $this->Percentile->getPercentile($studentsDataArray,$studentsTotal,$sameGpa,$gpa);
                    //Percentile calculation is done in Helper so that it can be reused.

                    // Removing special character from name
                    $students[$key]['name'] = preg_replace('/[^A-Za-z0-9\ ]/', '', $value ['1']);
                    $students[$key]['percentile'] = $percentile;
                    $students[$key]['gpa'] = preg_replace( '/[^0-9\.]/', '',$value['2']);
                   }
                }
        }
        return  $students;
   }

/**
* Ajax Upload students details to find percentile rank.
* File format - txt or csv files.
*
*
* @name ajaxUploadStudentsDetails
* @return students result Array
*/

   public function ajaxUploadStudentsDetails()
   {
        $file = $this->Student->uploadStudentsFiles($_FILES);
        //Call Data Component
        $studentData = $this->Data->getFileData($file);
        $this->set('studentData',$studentData);

        //Call Student Entity - convert an array.
        $studentsDataArray = $this->Student->getDataArray($studentData);

        $students = $this->getStudentsPercentileRank($studentsDataArray);
        $this->set('students',$students);
   }

}
