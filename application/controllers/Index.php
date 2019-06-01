<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// use \Dompdf\Dompdf;
// use \Dompdf\Dompdf;
// require_once APPPATH.'/vendor/dompdf/dompdf';
class Index extends My_Controller {
	public function __construct() {
    parent::__construct();
    $this->load->model('Main_model');
    $this->load->helper('url');
  }

	public function home() {
    $this->header($this->site_name." | Promoting Legitimate and Genuine online businesses");
    $this->load->view('index');
    $this->load->view('footer');
  }
  public function signin() {
    $this->header($this->site_name." | Login to your account ");
    $this->load->view('login');
    $this->load->view('footer');
  }
  public function check_seller() {
    $this->header($this->site_name." | Check Seller Status ");
    $this->load->view('check_seller');
    $this->load->view('footer');
  }
   public function register() {
    if($this->session->userdata('loggedin')){
       redirect(site_url('dashboard'));   
    }
    $this->header($this->site_name." | Create New Legit Shop Account ");
    $this->load->view('register');
    $this->load->view('footer');
  }
	public function forgot_password() {
    if($this->session->userdata('loggedin')){
       redirect(site_url('dashboard'));   
    }
    $this->header($this->site_name." | Reset your password  ");
    $this->load->view('reset-password');
    $this->load->view('footer');
  }
  public function pricing() {
    $this->header($this->site_name." | Pricing ");
    $this->load->view('pricing');
    $this->load->view('footer');
  }
  public function intro() {
    $this->load->view('intro');
  }
	public function contact() {
		$this->header($this->site_name." | Contact Us ");
		$this->load->view('contact');
		$this->load->view('footer');
	}

  public function invoice() {
    $user = $this->Main_model->user_details();
    $uid = $user->u_sn;
    $invoice = $this->db->get_where('kyc_payments', array('s_userid'=>$uid))->row();
    $data['user'] = $user;
    $data['invoice'] = $invoice;

    $this->load->view('user/invoice', $data);
  }

  public function about() {
  $this->header($this->site_name." | About CV Bot ");  
  $this->load->view('about');
  $this->load->view('footer');  
  }

  public function docs() {
    $this->header($this->site_name." | Easy Football API Documentation ");
    $this->load->view('docs');
    $this->load->view('footer');
  }

  public function check_session($ses) {
      if($this->session->userdate[$ses]){
        return true;
      } else {
        return false;
      }
  }    
  public function donation() {
    $user= $this->db->get_where('users', array('user_email'=>$this->session->demail))->row();
    if($user){
    $data['user'] = $user;   
    $data['trx_ref'] = "QCV-".$user->user_id."-".time(); } else { $data['trx_ref'] = "QCV-dn0-".time(); $data['user'] = "donor";} 
    $this->header($this->site_name." | Donate to CV Bot ");
    $this->load->view('donate', $data);
    $this->load->view('footer');
    
  }

  public function start_donation() {
    $this->load->helper('form');
    $amt = $this->input->post('amt', true);
    $trx = $this->input->post('trx', true);
    $userid = $this->input->post('userid', true);
    if($this->db->insert('subscriptions', array('s_userid'=>$userid, 's_ref'=>$trx, 's_amt'=>$amt))){
      echo "true";
    }
  }


  public function donation_success(){
    $this->load->library('curl');
    $this->load->helper('form');
    $txref = $_GET['txref'];
    echo $txref;    
      if ($txref) {
      $trnrecord = $this->db->get_where('subscriptions', array('s_ref'=>$txref))->row();
      if ($trnrecord){    
        $ref = $trnrecord->s_ref;
        $amount = $trnrecord->s_amt; //Correct Amount from Server
        $currency = "NGN"; //Correct Currency from Server
        $query = array(
            // "SECKEY" => "FLWSECK-e6db11d1f8a6208de8cb2f94e293450e-X",
            "SECKEY" => "FLWSECK-e6ec633ceeda5974fcb4294ff2d24f66-X",
            "txref" => $ref
        );
        $data_string = json_encode($query);
                      
          // $ch = curl_init('https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/verify');                                                                      
          $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');                                                                      
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                              
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
          $response = curl_exec($ch);
          $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
          $header = substr($response, 0, $header_size);
          $body = substr($response, $header_size);
          curl_close($ch);
          $resp = json_decode($response, true);
          $paymentStatus = $resp['data']['status'];
          $paymentRef = $resp['data']['flwref'];
          $chargeResponsecode = $resp['data']['chargecode'];
          $chargeAmount = $resp['data']['amount'];
          $paymentPlan = $resp['data']['paymentplan'];
          $chargeCurrency = $resp['data']['currency'];
          $startdate = $resp['data']['meta'][0]['updatedAt'];
          $interval = $resp['data']['meta'][0]['metavalue'];
          // echo $paymentStatus = $resp['data']['meta'][0]['updatedAt'];
          if (($chargeResponsecode == "00" || $chargeResponsecode == "0") && ($chargeAmount == $amount)  && ($chargeCurrency == $currency)) {
              $s_userid = $trnrecord->s_userid;
              $startdate1 = strtotime($startdate);
            $updateData = array(
              's_expires'=> "Never",
              's_date'=> $startdate,
                  's_plan'=>"Donation",
              's_flwref'=>$paymentRef,
              's_status'=> $paymentStatus
           );
            $where = 's_id = "'.$trnrecord->s_id.'"';
            // $updatePay = $this->db->update_string('subscriptions', $updateData, $where);
            $updatePay = $this->db->update('subscriptions', $updateData, $where);
            if ($updatePay){
              $cardDetails = array(
                'uc_cardno'=> $resp['data']['card']['cardBIN']." xxxx xxxx ".$resp['data']['card']['last4digits'],
                'uc_expiry'=> $resp['data']['card']['expirymonth']."/".$resp['data']['card']['expiryyear'],
                'uc_vendor'=> $resp['data']['card']['type'],
                'uc_lifetoken'=> $resp['data']['card']['life_time_token'],
                'uc_userid'=> $s_userid
             );

              $recordCard = $this->db->insert('user_cards', $cardDetails);
              $metaname = "Donation";
                     
            }
            if($recordCard){
              $data = array(
                "feedback"=>[
                  "b"=>"Thank you so much, for your Kind donation.",
                  "f"=>"We are so excited and really grateful for this show of love and support"
                ],
                "nextUrl"=>"create_new_cv");   
                  $_SESSION['home_message'] = $data;  
                 $this->session->mark_as_temp('home_message', 40); 
            redirect(site_url());
            }
                //Give Value and return to Success page
          } 
          else {
               $data = array(
                "feedback"=>[
                  "b"=>"Unfortunately your donation was not successful, reason is  $paymentStatus",
                  "f"=>"Do try again, or contact support for additional help",
                ],
                "nextUrl"=>"create_new_cv");   
          echo json_encode($data);
          }//
          }//if trnrecord 
          else{
        redirect(site_url('dashboard?status=no_trxref'));
        } 
    }//if trnx
  }

  function submit_contact(){
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->library('email');
    $msg = $this->input->post('contactmsg', true);
    $email = $this->input->post('email', true);
    $name = $this->input->post('name', true);
    $subject = $this->input->post('subject', true);
    // $this->form_validation->set_error_delimiters('', '');
    $this->form_validation->set_rules('contactmsg', 'Message', 'trim|required|min_length[10]');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
    $this->form_validation->set_rules('name', 'Name', 'trim|required');
    $this->form_validation->set_rules('subject', 'subject', 'trim|required');
    if ($this->form_validation->run() === FALSE){
      echo validation_errors();
    } else {
      $body = "Subject". $subject."<br> <br>". $msg."<br> <br>From: ". $name ;
      $this->email->from($email, $name);
      $this->email->to($email);
      $this->email->subject("Contat Form From CV Bot with Subject ".$subject);
      $this->email->message($body);
      $this->email->set_mailtype('html');
      $this->email->send();

      echo "true";
    }
  }




	public function reset() {
		$this->load->helper('form');
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('message', 'Reset Code', 'required|min_length[8]|callback_check_fp_code_exists');
		if ($this->form_validation->run()) {
       $data = array(
                "feedback"=>[
                      "a"=>"Alright ".$this->session->userdata['uname'].", please what is the password you would like to use?",
                ],
                "nextUrl"=>"set_new_password");   
               echo json_encode($data);
			
		} else {
      $data = array(
                "feedback"=>[
                  // "a"=>validation_errors(),
                  "b"=>"The Reset Code you entered is not valid. Please check and try again?"
                ],
                "nextUrl"=>"reset");   
          echo json_encode($data); 

    }
		}

		public function change_password() {
		$this->header('Reset Password');
		$data['site_name'] = $this->site_name;
		// $this->form_validation->set_rules('code', 'Reset code', 'required|min_length[8]|callback_check_code_exists');
 
		$this->form_validation->set_rules('password2', 'Repeat Password', 'required|matches[password1]');
		if ($this->form_validation->run()) {
			if ($this->core_model->change_password()) {
		$this->session->set_flashdata("msg","Password Changed successfully."); 
			}else {
				$this->session->set_flashdata("msg","Error in Changing password."); 
			}
		}
			$this->load->view('profile', $data);
			$this->footer();
		}

  public function set_new_password() {
      $this->load->helper('form');
      $this->load->library('form_validation');
      $chat_msg = $this->input->post('message', true);
      $this->form_validation->set_error_delimiters('', '');
      $this->form_validation->set_rules('message', 'Password', 'trim|required|min_length[6]|max_length[32]');
      if ($this->form_validation->run() === FALSE){
        $data = array(
          "feedback"=>[
            "a"=> validation_errors(),
            "b"=>"Please try another combination; even I can easily guess this password in a few attempts - and am not the brightest of us yet"
          ],
          "nextUrl"=>"set_new_password");   
         echo json_encode($data);

      } else {
        if (!isset($_SESSION['demail'])){
          
          $data = array(
            "feedback"=>[
              "a"=>"I am ashamed to say this, but honesty is the best policy right? Well something went wrong and I can't seem to know exactly what it is.",
              "b"=>"Mind trying again? Please give me your email once again"
            ],
            "nextUrl"=>"signin");   
           echo json_encode($data);

            }
            else
            {
              $email = $_SESSION['demail'];
              $name = $_SESSION['uname'];
              if ($this->Main_model->pswd_set($email, $chat_msg)) {
                $this->session->set_userdata('loggedin', TRUE);
                $cvs = $this->Main_model->list_cvs();
                if($cvs->num_rows()>0){
                 
                  $data = array("feedback"=> [
                    "s"=>"GREAT, now I am sure you are ".$_SESSION['uname'], 
                    "b" => "Give me a minute let me pull up your CVs", 
                    "c" => "I found ".$cvs->num_rows()." CVs in your account",
                    "a" => "Reply with <b>'preview'</b> (without quotes) if you want to preview and download a CV",
                    "e" => "Reply with 'New' (without quotes) if you want to create a new CV"],
                    "nextUrl"=>"create_new_cv"
                  );
                }
                else{
                  $data = array("feedback"=>[
                    "s"=>"Welcome back ".$_SESSION['uname'], 
                    "b" => "Just a Minute let me pull up your CVs", 
                    "c" => "I found no CVs in your account",
                    "e" => "Reply with 'New' (without quotes) if you would like to create a new CV"],
                 "nextUrl"=>"create_new_cv"
                  );
                }
                echo  json_encode($data);
              }  else{
                $data = array(
                "feedback"=>[
                      "a"=>"NOT AGAIN: something went wrong again ".$name.", Pleas can you try typing your password one more time?",
                ],
                "nextUrl"=>"set_new_password");   
               echo json_encode($data);
              } 
            } 
    }//if no errorsform validation
  }//end functon

	public function resend() {
		$data['site_name'] = $this->site_name;

		if ($this->Main_model->reverify()) {
		$this->session->set_flashdata("msg","Verification Email Resent successfully. <br> Please Allow Upto 30 Minutes before you can request for it to be sent again"); 
			}else {
				$this->session->set_flashdata("error","Error in Resending Verification Email."); 
			}
			   	$this->load->view('header');

			$this->load->view('verify', $data);
		
	   	$this->load->view('footer');
	}

  public function send_reset_password_mail() {
    $email = $this->input->post('email', true);
    $this->load->helper('string');
    $code = random_string('alnum', 8);
    $data = array('code' => $code, 'user_email' => $email, 'status' =>'0');
    $this->db->insert('reset_codes', $data);
    $this->load->library('email');
    // $this->email->initialize($config);


    $to = $email;
    $nameto = '';
    $subject = 'Password Recovery for Legit Shop';

    // Get full html:
    $body = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml" 
      xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office">
      <head>
        <!--[if gte mso 9]><xml>
        <o:OfficeDocumentSettings>
          <o:AllowPNG/>
          <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
        </xml><![endif]-->
        <!-- fix outlook zooming on 120 DPI windows devices -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
        <meta name="format-detection" content="date=no"> <!-- disable auto date linking in iOS 7-9 -->
        <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS 7-9 -->
        <title>Password Recovery For .$this->site_name. </title>
        
        <style type="text/css">
      body {
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
      }
      table {
        border-spacing: 0;
      }
      table td {
        border-collapse: collapse;
      }
      .ExternalClass {
        width: 100%;
      }
      .ExternalClass,
      .ExternalClass p,
      .ExternalClass span,
      .ExternalClass font,
      .ExternalClass td,
      .ExternalClass div {
        line-height: 100%;
      }
      .ReadMsgBody {
        width: 100%;
        background-color: #ebebeb;
      }
      table {
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
      }
      img {
        -ms-interpolation-mode: bicubic;
      }
      .yshortcuts a {
        border-bottom: none !important;
      }
      @media screen and (max-width: 599px) {
        .force-row,
        .container {
          width: 100% !important;
          max-width: 100% !important;
        }
      }
      @media screen and (max-width: 400px) {
        .container-padding {
          padding-left: 12px !important;
          padding-right: 12px !important;
        }
      }
      .ios-footer a {
        color: #aaaaaa !important;
        text-decoration: underline;
      }
      a[href^="x-apple-data-detectors:"],
      a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
      }
      </style>
      </head>

      <body style="margin:0; padding:0;" bgcolor="#F0F0F0" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

      <!-- 100% background wrapper (grey background) -->
      <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0">
        <tr>
          <td align="center" valign="top" bgcolor="#F0F0F0" style="background-color: #F0F0F0;">

            <br>

            <!-- 600px container (white background) -->
            <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px">
              <tr>
                <td class="container-padding header" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:24px;font-weight:bold;padding-bottom:12px;color:#DF4726;padding-left:24px;padding-right:24px">
                  Password Recovery For .$this->site_name.
                </td>
              </tr>
              <tr>
                <td class="container-padding content" align="left" style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#ffffff">
                  <br>

      <div class="title" style="font-family:Helvetica, Arial, sans-serif;font-size:18px;font-weight:600;color:#374550; text-transform: capitalize">We heard you lost your password</div>
      <br>

      <div class="body-text" style="font-family:Helvetica, Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#333333">
        We heard you lost your password to .$this->site_name. We understand that this may not be entirely your fault and we are with you all the way .
        <br><br>

        To begin with, please <a href="https://legitshop.com.ng/reset"> click here </a> and use the code: '.$code.' to reset your password or visit  https://legitshop.com.ng/reset in your browser.
        <br><br>
        After you have succeeded in resetting your password, you will be able to login and all things should be back to normal from thence. 
          <br><br>
        Warmest Regards,<br><br>
        Timchosen and the Legit Shop
      </div>

                </td>
              </tr>
              <tr>
                <td class="container-padding footer-text" align="left" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:4px;padding-right:4px">
                  <br><br>
                  © Legit Shop 2019.
                  <br><br>

                  You are receiving this email because you opted in on our website. Update your <a href="#" style="color:#aaaaaa">email preferences</a> or <a href="#" style="color:#aaaaaa">unsubscribe</a>.
                  <br><br>

                  
                  <br><br>

                </td>
              </tr>
            </table>
      <!--/600px container -->


          </td>
        </tr>
      </table>
      <!--/100% background wrapper-->

      </body>
    </html>';

    $this->email->from('noreply@legitshop.com.ng', 'Legit Shop Team');
    $this->email->to($email);
    // $this->email->cc('another@another-example.com');
    // $this->email->bcc('them@their-example.com');

    $this->email->subject($subject);
    $this->email->message($body);

    $this->email->send();
    return $this->email->print_debugger();
      
  }
  public function logout() {

    $data = array('email', 'loggedin');
    $this->session->unset_userdata($data);

    redirect(base_url('login'));
  }
}

?>