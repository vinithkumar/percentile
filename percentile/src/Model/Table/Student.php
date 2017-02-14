<?php
namespace App\Model\Table;


//Include Vendor csvimporter file
//App::import('Vendor', 'csvimporter', array('file'=>'csvimporter.php'));

/**
 * Student model
 *
 * @package students
 * @subpackage students.models
 */

class StudentTable extends Table {

	public $name = 'Student';


    /**
    * Import data from CSV file.
    *
    *
    * @name importCSV
    * @return students result Array
    */

    public function importCSV($file)
    {  echo "Test"; exit();
        require_once(ROOT . DS . 'vendor' . DS  .  'csvimporter.php');
        //check file exist
        if(!file_exists($file))
        {
            //throw new exception("File not available");
        }
        elseif(!is_readable($file))
        {
           // throw new exception("File not Redable");
        }
        //Using CSV Importer class to convert csv data to array
        $csvImporter = new csvImporter($file,false,",");
        $studentData = $csvImporter->get();
        //remove empty values
        $studentFilteredData = array_filter(array_map('array_filter', $studentData));
        // Check data is available
        if(!is_array($studentFilteredData))
        {
           // throw new exception("Data cant be processed");;
        }
        else
        {
            return $studentFilteredData;
        }
    }
}