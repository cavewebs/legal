<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Contact_form extends My_Controller {

	public function __construct() {
    parent::__construct();
    $this->load->model('Main_model');
  }

  public function contact_form() {
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->helper('string');
    $this->load->library('email');
    $this->load->library('user_agent');  
    $email = $this->input->post('contact-email', true);
    $name = $this->input->post('contact-name', true);
    $message = $this->input->post('contact-message', true);
    $subject = $this->input->post('contact-subject', true);
    $honeypot = $this->input->post('contact-username', true);
    $site_email = $this->site_email;
    $site_name = $this->site_name;
    $msg_success = "We have <strong>successfully</strong> received your message. We'll get back to you soon.";


    $this->form_validation->set_rules('contact-message', 'Message', 'required');
    $this->form_validation->set_rules('contact-subject', 'Subject', 'required');
    $this->form_validation->set_rules('contact-name', 'Name', 'required');
    $this->form_validation->set_rules('contact-email', 'Email', 'valid_email|required');
	  if ($this->form_validation->run() ){
      
      if ($honeypot == '') { 
        $bodymsg = '';
           ### Include Form Fields into Body Message
        $bodymsg .= isset($name) ? "Contact Name: $name<br><br>" : '';
        $bodymsg .= isset($subject) ? "Contact Subject: $subject<br><br>" : '';
        $bodymsg .= isset($email) ? "Contact Email: $email<br><br>" : '';
        $bodymsg .= isset($message) ? "Message: $message<br><br>" : '';
        $bodymsg .= $_SERVER['HTTP_REFERER'] ? '<br>---<br><br>This email was sent from : ' . $_SERVER['HTTP_REFERER'] : ''; 

        $ua = $this->agent->platform();
        $data = array('c_email' => $email, 'c_name'=> $name, 'c_ua' => $ua,'c_subject'=>$subject, 'c_msg'=>$bodymsg);
        $q = $this->db->insert('contact_form', $data);
          if($q) {
            $this->email->from($site_email, 'Contact Form from'.$site_name);
            $this->email->to($site_email);
            $this->email->cc($email);
            // $this->email->bcc('them@their-example.com');
            $this->email->subject('Contact email from '.$site_name.' - '. $subject);
            $this->email->message($bodymsg);
            $this->email->set_mailtype('html');
            // if($this->email->send()){
                $response = array ('result' => "success", 'message' => $msg_success);
              // } else {
                // $response = array ('result' => "error", 'message' => $this->email->print_debugger());
              // }
              echo json_encode($response);    
          } 

          else {
            echo json_encode(array ('result' => "error", 'message' => " <strong>Submission Error</strong>.! Form could not be sumbitted.!"));
          } 
      }

        else {
          echo json_encode(array ('result' => "error", 'message' => "Bot <strong>Detected</strong>.! Clean yourself Botster.!"));
        }
        
        } else {
          echo json_encode(array ('result' => "error", 'message' => validation_errors()." Please <strong>Fill up</strong> all required fields and try again."));
        }
  } 


  public function register() {
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->helper('string');
    $this->load->library('email');
    $this->load->library('user_agent');  
    $referrer = $this->input->post('referrer', true);
    $email = $this->input->post('email', true);
    $name = $this->input->post('name', true);
    $pswd = $this->input->post('upswd', true);
    $cpswd = $this->input->post('upswd', true);
    $honeypot = $this->input->post('username', true);
    $site_email = $this->site_email;
    $site_name = $this->site_name;
    $msg_success = "Your registeration was <strong>successful</strong> You will be redirected shortly.";


    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('upswd', 'required');
    $this->form_validation->set_rules('ucpswd', 'Name', 'required');
    $this->form_validation->set_rules('referrer', 'Referrer', 'valid_email');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.u_email]', array(
                'is_unique'     => 'This %s is already registered, please login instead.'
        ));


    if ($this->form_validation->run() ){
      if ($pswd === $cpswd) {
        $ref =NULL;
        if($referrer !=''){
         $rf = $this->db->get_where('users', array('u_email'=>$referrer))->row();
         $ref = $rf->u_sn;
        }
        $ukey = random_string('alnum', 4).substr(time(), 4);
        $ua = $this->agent->platform();
        $data = array('u_email' => $email, 'u_name'=> $name, 'u_ua' => $ua,'u_pswd'=>hash('ripemd128', $pswd), 'u_key'=>$ukey, 'referrer'=>$ref);
        $q = $this->db->insert('users', $data);


          if($q) {            
            $response = array ('result' => "success", 'message' =>$msg_success );
            $session_data = array('email' => $email, 'loggedin' => TRUE, 'name' => $name);
            $this->session->set_userdata($session_data);
            // redirect(site_url('dashboard'));
            echo json_encode($response);

          } else {
            $response = array ('result' => "error", 'message' => 'Unfortunately we could not add you to our database, please try again');
            echo json_encode($response);    

          }
          }      

        else {
          echo json_encode(array ('result' => "error", 'message' => " <strong>Passwords do not Match</strong>!"));
        }
        
        } else {
          echo json_encode(array ('result' => "error", 'message' => validation_errors()));
        }
  } 


  public function login() {
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->form_validation->set_rules('upswd', 'Password', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

    if ($this->form_validation->run() ){
        if($this->Main_model->login()) {
          echo "success";         
        } else {
          echo '<strong>Wrong Login details</strong>, please try again';
        }
     
    } else { echo validation_errors();}
  }


  public function seller_search() {
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->form_validation->set_rules('searchBy', 'Search By', 'required');
    $this->form_validation->set_rules('searchText', 'Search Text', 'required');

    if ($this->form_validation->run() ){
        $seller = $this->Main_model->search_seller();
        if($seller=="no_result") {
          echo json_encode(array ('result' => "error", 'data' => '<strong>The Seller Code entered was not found. Check and try again</strong>'));
        }
        if($seller=="empty_code") {
          echo json_encode(array ('result' => "error", 'data' => '<strong>You did not enter any seller code</strong>, please try again</strong>'));
        }
   else {
          echo json_encode(array ('result' => "success", 'data' => $seller));
        }
     
    } else { echo validation_errors();}
  }

  public function kyc_submit(){
    $cac_cert = '';
    // $id_doc = '';
    // $selfie_id = '';
    $this->load->library('email');
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('', '<br/>');
    $this->form_validation->set_rules('first-name', 'First Name', 'required');
    $this->form_validation->set_rules('middle-name', 'Middle Name', 'required');
    $this->form_validation->set_rules('last-name', 'Last Name', 'required');
    $this->form_validation->set_rules('phone-number', 'Contact Phone Number', 'required');
    $this->form_validation->set_rules('date-of-birth', 'Date of Birth', 'required');
    $this->form_validation->set_rules('nationality', 'Nationality', 'required');
    $this->form_validation->set_rules('address-line-1', 'Business Address', 'required');
    $this->form_validation->set_rules('lga', 'Local Government of Origin', 'required');
    $this->form_validation->set_rules('state', 'State of Origin', 'required');
    $this->form_validation->set_rules('biz-address', 'Busines Address', 'required');
    $this->form_validation->set_rules('biz-number', 'Business Phone Number', 'required');
    $this->form_validation->set_rules('cac-reg', 'Are you registered with CAC', 'required');
    $this->form_validation->set_rules('biz-lga', 'Local Government of Business', 'required');
    $this->form_validation->set_rules('biz-state', 'State of Business', 'required');
    $this->form_validation->set_rules('biz-name', 'Business Name', 'required');
    $this->form_validation->set_rules('id-type', 'Type of ID', 'required');

    if (empty($_FILES['id-doc']['name'])){
      $this->form_validation->set_rules('id-doc', 'Picture of means of ID', 'required');
    }
    if (empty($_FILES['selfie-id']['name'])){
      $this->form_validation->set_rules('selfie-id', 'Selfie With ID ', 'required');
    }

    $this->form_validation->set_rules('account-name', 'Acount Name', 'required');
    $this->form_validation->set_rules('account-number', 'Account Number', 'required|exact_length[10]');
    $this->form_validation->set_rules('bank-name', 'Bank Name', 'required');
    $this->form_validation->set_rules('account-bvn', 'BVN Number', 'exact_length[11]');
    if($this->form_validation->run()){

      $config['upload_path']          = './static-content/user/uploads/';
      $config['allowed_types']        = 'jpg|png|jpeg';
      $config['max_size']             = 6240;
      $config['max_width']            = 0;
      $config['max_height']           = 0;

      $this->load->library('upload', $config);
      $this->upload->display_errors('', '<br />');

      if (!empty($_FILES['cac-cert']['name'])){
        if (!$this->upload->do_upload('cac-cert'))
        {
          $error = array('error' => $this->upload->display_errors());
          echo json_encode($error);
        }
        else
        {
          $data = array('upload_data' => $this->upload->data());
          $cac_cert = $this->upload->data('file_name');
          // echo "uploaded ".$cac_cert;

        }
      }//upload if CAC cert is selected
      
      //upload ID card
      if ( !$this->upload->do_upload('id-doc'))
      {
        $error = array('error' => $this->upload->display_errors());
        echo json_encode($error);
      }
      else
      {
        $id_doc = $this->upload->data('file_name');
        // echo "uploaded ".$id_doc;
      }

      //Upload Selfie with ID
      if (!$this->upload->do_upload('selfie-id'))
      {
        $error = array('error' => $this->upload->display_errors());
        echo json_encode($error);
      }
      else
      {
        $selfie_id = $this->upload->data('file_name');
        // echo "uploaded ".$selfie_id;
      }
      //start populating dbs
      //personal details
      $kp_uid  = $this->Main_model->user_details()->u_sn;

      $kp_fn  = $this->input->post('first-name', true);

      $kp_ln  = $this->input->post('last-name', true);

      $kp_mn  = $this->input->post('middle-name', true);

      $kp_tel  = $this->input->post('phone-number', true);

      $kp_dob  = date('Y-m-d', strtotime($this->input->post('date-of-birth', true)));

      $kp_address  = $this->input->post('address-line-1', true);

      $kp_address2  = $this->input->post('address-line-2', true);

      $kp_state  = $this->input->post('state', true);

      $kp_lga = $this->input->post('lga', true);
      $kp_nationality = $this->input->post('nationality', true);

      $kpdata = array('kp_uid' => $kp_uid, 'kp_fn' => $kp_fn, 'kp_ln' => $kp_ln, 'kp_mn' => $kp_mn, 'kp_tel' => $kp_tel, 'kp_dob' => $kp_dob, 'kp_address' => $kp_address, 'kp_address2' => $kp_address2, 'kp_state' => $kp_state, 'kp_lga' =>$kp_lga, 'kp_nationality' =>$kp_nationality);
     $kpqry = $this->db->insert('kyc_personal',$kpdata);

      //business details
      $kb_uid= $this->Main_model->user_details()->u_sn; 
      $kb_tel= $this->input->post('biz-number', true); 
      $kb_biz_name= $this->input->post('biz-name', true); 
      $kb_ig= $this->input->post('ig-name', true); 
      $kb_fb= $this->input->post('fb-name', true); 
      $kb_twitter= $this->input->post('twitter-name', true); 
      $kb_whatsapp= $this->input->post('whatsapp-no', true); 
      $kb_reg= $this->input->post('cac-reg', true); 
      $kb_cac_no= $this->input->post('cac-no', true); 
      $kb_address= $this->input->post('biz-address', true); 
      $kb_state= $this->input->post('biz-state', true); 
      $kb_lga= $this->input->post('biz-lga', true);
      $kbdata = array('kb_uid' => $kb_uid, 'kb_tel' => $kb_tel, 'kb_ig' => $kb_ig, 'kb_fb' => $kb_fb, 'kb_twitter' => $kb_twitter, 'kb_whatsapp' => $kb_whatsapp, 'kb_reg' => $kb_reg, 'kb_cac_no' => $kb_cac_no, 'kb_address' => $kb_address, 'kb_state' => $kb_state, 'kb_lga' => $kb_lga, 'kb_biz_name'=>$kb_biz_name);
     $kbqry = $this->db->insert('kyc_business', $kbdata);

      //Documents
      $kd_uid = $this->Main_model->user_details()->u_sn;
      $kd_id_type = $this->input->post('id-type');
      $kd_id_url = $id_doc;
      $kd_id_selfie = $selfie_id;
      $kd_id_cac = $cac_cert;
      $kddata = array('kd_uid' => $kd_uid, 'kd_id_type' => $kd_id_type, 'kd_id_url' => $kd_id_url, 'kd_id_selfie' => $kd_id_selfie, 'kd_id_cac' => $kd_id_cac);
     $kdqry = $this->db->insert('kyc_documents', $kddata);

      //bank details
      $kyb_uid = $this->Main_model->user_details()->u_sn;
      $kyb_bn = $this->input->post('bank-name', true); 
      $kyb_an = $this->input->post('account-name', true); 
      $kyb_a_no  = $this->input->post('account-number', true); 
      $kyb_bvn = $this->input->post('bvn-number', true); 
      $bndata = array('kyb_uid' => $kyb_uid, 'kyb_bvn' => $kyb_bvn, 'kyb_bn' => $kyb_bn, 'kyb_a_no' => $kyb_a_no, 'kyb_an' => $kyb_an);
      $bdqry = $this->db->insert('kyc_bank', $bndata);

      if($kpqry && $kbqry && $bdqry && $kdqry){
        $this->db->update('users', array('is_vendor'=>1, 'vendor_status'=>'pending', 'vendor_comment'=>'Awaiting Verification Payment'), array('u_sn'=>$kyb_uid));
            $trx_ref = "LSNG-".$kyb_uid."-".time();

          $this->db->insert('kyc_payments', array('s_userid'=>$kyb_uid, 's_ref'=>$trx_ref, 's_amt'=>3500, 's_payment_type'=>'Bank'));
          
          $user = $this->Main_model->user_details();
          $uid = $user->u_sn;
          $invoice = $this->db->get_where('kyc_payments', array('s_userid'=>$uid))->row();
          $body = '<!doctype html>
            <html>
              <head>
                  <meta charset="utf-8">
                  <title>Invoice for '.$user->u_name.'</title>
                  
                  <style>
                  .invoice-box {
                      max-width: 800px;
                      margin: auto;
                      padding: 30px;
                      border: 1px solid #eee;
                      box-shadow: 0 0 10px rgba(0, 0, 0, .15);
                      font-size: 16px;
                      line-height: 24px;
                      font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                      color: #555;
                  }
                  
                  .invoice-box table {
                      width: 100%;
                      line-height: inherit;
                      text-align: left;
                  }
                  
                  .invoice-box table td {
                      padding: 5px;
                      vertical-align: top;
                  }
                  
                  .invoice-box table tr td:nth-child(2) {
                      text-align: right;
                  }
                  
                  .invoice-box table tr.top table td {
                      padding-bottom: 20px;
                  }
                  
                  .invoice-box table tr.top table td.title {
                      font-size: 45px;
                      line-height: 45px;
                      color: #333;
                  }
                  
                  .invoice-box table tr.information table td {
                      padding-bottom: 40px;
                  }
                  
                  .invoice-box table tr.heading td {
                      background: #eee;
                      border-bottom: 1px solid #ddd;
                      font-weight: bold;
                  }
                  
                  .invoice-box table tr.details td {
                      padding-bottom: 20px;
                  }
                  
                  .invoice-box table tr.item td{
                      border-bottom: 1px solid #eee;
                  }
                  
                  .invoice-box table tr.item.last td {
                      border-bottom: none;
                  }
                  
                  .invoice-box table tr.total td:nth-child(2) {
                      border-top: 2px solid #eee;
                      font-weight: bold;
                  }
                  
                  @media only screen and (max-width: 600px) {
                      .invoice-box table tr.top table td {
                          width: 100%;
                          display: block;
                          text-align: center;
                      }
                      
                      .invoice-box table tr.information table td {
                          width: 100%;
                          display: block;
                          text-align: center;
                      }
                  }
                  
                  /** RTL **/
                  .rtl {
                      direction: rtl;
                      font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                  }
                  
                  .rtl table {
                      text-align: right;
                  }
                  
                  .rtl table tr td:nth-child(2) {
                      text-align: left;
                  }
                  </style>
              </head>

              <body>
                  <div class="invoice-box">
                      <table cellpadding="0" cellspacing="0">
                          <tr class="top">
                              <td colspan="2">
                                  <table>
                                      <tr>
                                          <td class="title">
                                              <img src="https://legitshop.com.ng/static-content/images/logo-s2-white2x.png" style="width:100%; max-width:300px;">
                                          </td>
                                          
                                          <td>
                                              Invoice #: 00191'. $invoice->s_id.'<br>
                                              Created: '.date('M d, Y', strtotime($invoice->s_created)).'<br>
                                              Due: '.date('M d, Y', strtotime($invoice->s_created)).'
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>
                          
                          <tr class="information">
                              <td colspan="2">
                                  <table>
                                      <tr>
                                          <td>
                                              Make Payment to,<br>
                                              Sterling Bank PLC, <br>
                                              Shopily Ecommerce Ventures Ltd<br>
                                              0069748667 - Current Account
                                          </td>
                                          
                                          <td>
                                              '. $user->u_name .'<br>
                                              Transaction Ref: '.$invoice->s_ref.'<br>
                                              '.$user->u_email.'
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>
                          
                          <tr class="heading">
                              <td>
                                  Payment Method
                              </td>
                              
                              <td> Amount 
                              </td>
                          </tr>
                          
                          <tr class="details">
                              <td>
                                  Bank
                              </td>
                              
                              <td>
                                  N3,500.00
                              </td>
                          </tr>
                          
                          <tr class="heading">
                              <td>
                                  Item
                              </td>
                              
                              <td>
                                  Price
                              </td>
                          </tr>
                          
                          <tr class="item">
                              <td>
                                  Seller Verification Fee
                              </td>
                              
                              <td>
                                  N5,000.00
                              </td>
                          </tr>
                          
                          <tr class="item">
                              <td>
                                  Discount (30%)
                              </td>
                              
                              <td>
                                  - N1,500.00
                              </td>
                          </tr>
                          
                          
                          
                          <tr class="total">
                              <td></td>
                              
                              <td>
                                 Total: N3,500.00
                              </td>
                          </tr>
                          <tr>
                            <td><small>After payment send email or DM with transaction reference and receipt to @legitshopng on Instagram or payments@legistshop.com.ng</small>
                            </td>
                          </tr>
                      </table>
                  </div>
              </body>
            </html>';
          $subject = 'Your Invoice for Seller Verification on Legit Shop';

          $this->email->from('payments@legitshop.com.ng', 'LegitShop Seller Verification');

          $this->email->to($user->u_email);
          $this->email->subject($subject);
          $this->email->message($body);
          $this->email->set_mailtype('html');
          $this->email->send();
          // echo $this->email->print_debugger();
          echo "success";
      }

    } else {
      echo validation_errors();
    }
  }
 
}
