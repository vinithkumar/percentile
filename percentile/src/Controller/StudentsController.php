<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

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
    public $components = array('Percentile');

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
         $files = WWW_ROOT.'files/students.txt';
         if (file_exists($files))
        {
           $studentData = explode("\n", file_get_contents($files,true));
           $this->set('studentData',$studentData);

           $students = $this->getStudentsRank($studentData,$type = 'text/plain');
           $this->set('students',$students);
        } else
        {
          throw new Exception("File not exist");
        }

      }catch (Exception $e)
      {
       throw new Exception($e->getMessage());
      }

      $this->set('title_for_layout', __('Students Home Page'));
   }

/**
 * Upload students details to find percentile rank.
 * File format - txt or csv files.
 *
 *
 * @name uploadStudentsDetails
 * @return students result Array
 */

   public function uploadStudentsDetails()
   {


       if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
      {
            ############ Edit settings ##############
            $UploadDirectory    = WWW_ROOT.'files/'; //specify upload directory ends with / (slash)
            ##########################################
            //check if this is an ajax request
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
             die();
            }

            //Is file size is less than allowed size.
            if ($_FILES["FileInput"]["size"] > 5242880) {
             die("File size is too big!");
            }

            //allowed file type Server side check
            switch(strtolower($_FILES['FileInput']['type']))
            {
                //allowed file types
                case 'text/plain':
                case 'application/octet-stream':
                    break;
                default:
                    die('Unsupported File!'); //output error
            }

            $File_Name          = strtolower($_FILES['FileInput']['name']);
            $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
            $Random_Number      = rand(0, 5234678); //Random number to be added to name.
            $NewFileName         = $Random_Number.$File_Ext; //new file name

            if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
            {
                if($_FILES['FileInput']['type'] == 'text/plain')    //Text File
                {
                 $studentData = explode("\n", file_get_contents(WWW_ROOT.'files/'.$NewFileName,true));
                 $students = $this->getStudentsRank($studentData,$_FILES['FileInput']['type']);
                }else   //CSV File
                {
                 $studentData = $this->importCSV(WWW_ROOT. DS .'files'. DS .$NewFileName,true);
                 $students = $this->getStudentsRank($studentData,$_FILES['FileInput']['type']);
                }

                $this->set('students',$students);
            }else{
                die('error uploading File!');
            }
        }
        else
        {
            die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
        }
    }

 /**
 * Call percentile component to get the rank details.
 *
 *
 * @name getStudentsRank
 * @return students result Array
 */
    public function getStudentsRank($studentData, $type = null)
    {
      if (!empty($studentData))
      {
        try
        {
           if($type == 'text/plain') //Only for Text File
           {
                foreach ($studentData as $key => $value) {
                   if(!empty($value))
                   {
                    $studentData[$key] = explode(",",utf8_encode($value));
                   }
                }
           }
           $err = true;
           foreach ($studentData as $key => $value) {
               if(!empty($value[2]))
               {
                $studentData[$key][2] = number_format((float)$value[2], 2, '.', '');
               } else {
                 $err = false;
               }
           }
           if($err)
           {
               $studentsTotal = count($studentData);
               $sameGpa = $this->Percentile->getSameGpa($studentData,"2");  //Call getSameGpa method in the percentile component.

               foreach ($studentData as $key => $value)
               {
                   if(!empty($value))
                   {
                    $gpa = utf8_encode($value['2']);
                    $percentile = $this->Percentile->getPercentile($studentData,$studentsTotal,$sameGpa,$gpa); //Percentile calculation is done in Helper so that it can be reused.
                    // Removing special character from name
                    $students[$key]['name'] = preg_replace('/[^A-Za-z0-9\ ]/', '', $value ['1']);
                    $students[$key]['percentile'] = $percentile;
                    $students[$key]['gpa'] = preg_replace( '/[^0-9\.]/', '',$value['2']);
                   }
                }
              return $students;
           } else
           {
             echo "Unknown Input Format";
           }
          }
          catch (Exception $e) {
              throw new Exception($e->getMessage());
          }
      }
    }

}
