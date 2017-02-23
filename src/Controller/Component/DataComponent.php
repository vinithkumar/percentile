<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Core\App;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Event\EventDispatcherTrait;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

/**
 * Cake Development Corporation.
 *
 * DataComponent
 *
 *
 * @package students
 * @subpackage students.controllers.components
 */
   class DataComponent extends Component {

    /**
     * to get students data
     * @param $file string file to be checked for importing data
     * @return array
     */

        function getFileData($file_path)
        {
           $file = new File($file_path);
           if($file->exists())
           {
               $results = explode("\n", $file->read());
               $data = [];
               foreach ($results as $result)
               {
                 if($result)
                 {
                   $data[] = utf8_encode(trim($result));
                 }
               }
               return $data;
           }else
           {
            throw new Exception("File not exist");
           }
        }

    }
?>

