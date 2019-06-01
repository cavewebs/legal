<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use \GuzzleHttp\Client;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Dashboard extends My_Controller {

	public function __construct() {
    parent::__construct();
    $this->load->model('Main_model');
        $this->is_restricted();
    // $client = new \GuzzleHttp\Client();
  }

 
	public function profile() {
    $user = $this->db->get_where('users', array('u_email'=>$this->session->email))->row();
    $data['user'] = $user;
    // $data['cardDetails'] = $this->db->get_where('user_cards', array('uc_userid'=>$user->user_id))->result();
		// $data['subscriptions'] = $this->db->get_where('subscriptions', array('s_userid'=>$user->user_id))->row();

		$this->header2($this->site_name." | User Profile");
		$this->load->view('user/dashboard', $data);
		$this->load->view('user/footer');
	}

	public function kyc() {
    $user = $this->db->get_where('users', array('u_email'=>$this->session->email))->row();
    $data['user'] = $user;
    // $data['cardDetails'] = $this->db->get_where('user_cards', array('uc_userid'=>$user->user_id))->result();
		// $data['subscriptions'] = $this->db->get_where('subscriptions', array('s_userid'=>$user->user_id))->row();

		$this->header2($this->site_name." | User Profile");
		$this->load->view('user/kyc', $data);
		$this->load->view('user/footer');
	}

	public function referrals() {
    $ref = $this->db->get_where('users', array('referrer'=>$this->Main_model->user_details()->u_sn));
    $refTot = $ref->num_rows();
    $refs = $ref->result();
    $data['refs'] = $refs;
    $data['tot'] = $refTot;
    // $data['cardDetails'] = $this->db->get_where('user_cards', array('uc_userid'=>$user->user_id))->result();
		// $data['subscriptions'] = $this->db->get_where('subscriptions', array('s_userid'=>$user->user_id))->row();

		$this->header2($this->site_name." | Referrals");
		$this->load->view('user/referrals', $data);
		$this->load->view('user/footer');
	}

	public function seller_application() {
    $user = $this->db->get_where('users', array('u_email'=>$this->session->email))->row();
    $data['user'] = $user;
		$this->header2($this->site_name." | User Profile");
		$this->load->view('user/kyc-application', $data);
		$this->load->view('user/footer');
	}

	public function kyc_status() {
		$this->header2($this->site_name." | Seller Application Status");
		$this->load->view('user/application-status');
		$this->load->view('user/footer');
	}

	public function apply_loan() {
		$this->header2($this->site_name." | Loan  Application");
		$this->load->view('user/loan-application');
		$this->load->view('user/footer');
	}

	public function start_payment() {
		$this->load->helper('form');
		$amt = $this->input->post('amt', true);
		$trx = $this->input->post('trx', true);
		$userid = $this->input->post('userid', true);
		if($this->db->insert('subscriptions', array('s_userid'=>$userid, 's_ref'=>$trx, 's_amt'=>$amt))){
			echo "true";
		}
	}



	
	public function payment() {
		$user= $this->db->get_where('users', array('user_email'=>$this->session->demail))->row();
		$data['user'] = $user;		
		$data['trx_ref'] = "QCV-".$user->user_id."-".time();
		$this->header($this->site_name." | Upgrade your Account ");
		$this->load->view('upgrade', $data);
		$this->load->view('footer');
		
	}
 

	public function verify() {
		//$this->header('Forgot Password');
		$data['site_name'] = $this->site_name;
		$this->form_validation->set_rules('code', 'Verification code', 'required|min_length[8]');
		//$this->form_validation->set_rules('password', 'Password', 'trim|required');
		if ($this->form_validation->run()) {
			if ($this->Main_model->verify_code()) {
		$this->session->set_flashdata("msg","Account Verified  successfully."); 
		redirect(site_url('dashboard'));
			}else {
				$this->session->set_flashdata("error","Could not verify your Account; Did you enter the correct Code?"); 
			}
		}
			   	$this->header($this->site_name.' | Verify your accout');

			$this->load->view('verify', $data);
		
	   	$this->load->view('footer');

		}
		

	public function payment_success(){
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
			            // $s_amt = $resp['data']['status'];
			            // $s_expires  = strtotime($interval, $startdate);
			            // $s_plan = $amount;
			            // $s_date = $startdate;
			            // $s_status = $paymentStatus;
			            // $s_flwref = $paymentRef;
			            $startdate1 = strtotime($startdate);
			          $updateData = array(
			            's_expires'=> date("Y-m-d h:i:s", (strtotime("+".$interval, $startdate1))),
			            's_date'=> $startdate,
                  		's_plan'=>$paymentPlan,
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
                  $metaname = "FREE";
                     if ($paymentPlan=='2'){
                      $metaname = "Basic";
                    }
                    if ($paymentPlan=='2'){
                      $metaname = "Standard";
                    }
                    if ($paymentPlan=='4180'){
                      $metaname = "Premium Monthly";
                    }
                    if ($paymentPlan=='4181'){
                      $metaname = "Premium Yearly";
                    }
                     if ($paymentPlan=='1'){
                      $metaname = "Test";
                    }
            $where2 = 'user_id = "'.$trnrecord->s_userid.'"';
            $updatePlan = $this->db->update('users', array('user_plan'=>$metaname), $where2);
						}
						if($recordCard){
							$data = array(
				            "feedback"=>[
				              "b"=>"Thank you so much, ".$_SESSION['uname'].", You have successfully subscibed for the $metaname plan",
				              "f"=>"You have unlocked access to a whole new possibilities"
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
				              "b"=>"Unfortunately $this->session->userdata['uname'], Your payment was not successful, reason is  $paymentStatus",
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



}