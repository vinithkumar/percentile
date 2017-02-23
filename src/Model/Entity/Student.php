<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;


/**
 * Student model
 *
 * @package students
 * @subpackage students.models
 */

class Student extends Entity {

	public $name = 'Student';

 /**
 * Get the string contents and convert to array format.
 *
 *
 * @name getDataArray
 * @return students contents Array format
 */
    public function getDataArray($studentData)
    {
      if (!empty($studentData))
      {
        try
        {
            foreach ($studentData as $key => $value) {
               if(!empty($value))
               {
                $studentData[$key] = explode(",",utf8_encode($value));
               }
            }

           $err = true;

           foreach ($studentData as $key => $value)
           {
              if($studentData[$key] != '')
              {
               if(!empty($value[2]))
               {
                $studentData[$key][2] = number_format((float)$value[2], 2, '.', '');
               } else {
                 $err = false;
               }
              }
           }
           if($err)
           {
              return $studentData;
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


/**
 * Upload files - ajax concept - txt or csv files.
 *
 *
 * @name uploadStudentsFiles
 * @return Uploaded File name with path.
 */

    public function uploadStudentsFiles($files)
    {

      if(isset($files["FileInput"]) && $files["FileInput"]["error"]== UPLOAD_ERR_OK)
      {
            ############ Edit settings ##############
            $UploadDirectory    = WWW_ROOT.'files/'; //specify upload directory ends with / (slash)
            ##########################################
            //check if this is an ajax request
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
             die();
            }

            //Is file size is less than allowed size.
            if ($files["FileInput"]["size"] > 5242880) {
             die("File size is too big!");
            }

            //allowed file type Server side check
            switch(strtolower($files['FileInput']['type']))
            {
                //allowed file types
                case 'text/plain':
                case 'application/octet-stream':
                    break;
                default:
                    die('Unsupported File!'); //output error
            }

            $File_Name          = strtolower($files['FileInput']['name']);
            $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
            $Random_Number      = rand(0, 5234678); //Random number to be added to name.
            $NewFileName         = $Random_Number.$File_Ext; //new file name

            if(move_uploaded_file($files['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
            {
                 $file = WWW_ROOT.'files/'.$NewFileName;
                 return $file;
            }else{
                die('error uploading File!');
            }
        }
        else
        {
            die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
        }
    }

}