<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter PDF Library
 *
 * @package			CodeIgniter
 * @subpackage		Libraries
 * @category		Libraries
 * @author			Timchose
 * @license			MIT License
 * @link			https://timchosen.github.io/
 *
 */

require_once(dirname(__FILE__) . '/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf
{
	 public function __construct(){
        
        // include autoloader
        require_once dirname(__FILE__).'/dompdf/autoload.inc.php';
        
        // instantiate and use the dompdf class
        $pdf = new DOMPDF();
        
        $CI =& get_instance();
        $CI->dompdf = $pdf;
        
    }

    public function create($html,$filename)
    {
	    $dompdf = new Dompdf();
	    $dompdf->set_paper('A4', 'portrait');
	    $dompdf->loadHtml($html);
	    $dompdf->render();
	    $dompdf->stream($filename.".pdf",array("Attachment"=>1));
  }
}