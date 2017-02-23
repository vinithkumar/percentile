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
/**
 * Cake Development Corporation.
 *
 * PercntileComponent
 *
 * Helps handle calculate percentile rank.
 * The percentile rank of a score is the percentage of scores in its frequency distribution that are equal to or lower than it. For example, a test score that is greater than or equal to 75% of the scores of people taking the test is said to be at the 75th percentile, where 75 is the percentile rank. In educational measurement, a range of percentile ranks, often appearing on a score report, that shows the range within which the test taker’s “true” percentile rank probably occurs. The “true” value refers to the rank the test taker would obtain if there were no random errors involved in the testing process.
 *
 *
 * The mathematical formula is (cl+.5*fi/N)*100%
 *
 * @package students
 * @subpackage students.controllers.components
 */
    class PercentileComponent extends Component {

        /**
        * Handle to find percentile rank.
        *
        * @param int $totalStudents
        * @param Array $studentData, $sameGpa, $gpa
        * @return percentile rank details.
        */
        public function getPercentile($studentData, $totalStudents=0,$sameGpa=array(),$gpa=0)
        {
          if (!empty($studentData))
          {
            try {
                // Below logic is based on the formula (cl+.5*fi/N)*100% for percentile calculation, here cl is the count of all scores less than the score of interest.
                // fi is the frequency of the score of interest, and N is the number of examinees in the sample
                if(!empty($sameGpa))
                {
                    $y=$gpa;
                    if (array_key_exists($gpa,$sameGpa)){
                       $percentile = ( ( count(array_filter($studentData, function ($x) use ($y) { return $x["2"] < $y; })) + (0.5 * $sameGpa[$gpa] ) )/( $totalStudents )  ) * 100;
                       return $percentile;
                    }
                }
            } catch (Exception $e) {
              throw new Exception($e->getMessage());
            }
          }
        }

        /**
        * Handle to find same GPA values.
        *
        * @param Array $studentData
        * @return Array.
        */
        public function getSameGpa($studentData)
        {
            $sameGpa = array_count_values(
                            array_map(function($value)
                            {
                                return $value['2'];
                            }, $studentData)
                       );
            return $sameGpa;
        }

    }
?>

