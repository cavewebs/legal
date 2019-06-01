<?php
defined('BASEPATH') or die('');
date_default_timezone_set('Africa/Lagos');

use Embed\Embed;

class Main_model extends CI_Model {
  public function __construct(){
    parent::__construct();
     $this->load->helper('date');
     $this->load->library('session');
       $this->load->database();
  }


  public function search_seller() {
    $code = $this->input->post('code', true);
    // $by = $this->input->post('by', true);
    // $text = $this->input->post('text', true);
    if(isset($code)&&($code !='')){
      $query = $this->db->get_where('users', array('u_key' => $code))->row();
      if($query){
        $userid = $query->u_sn;
       
        $q1 = $this->db->get_where('kyc_personal', array('kp_uid'=>$userid))->row();
        $q2 = $this->db->get_where('kyc_business', array('kb_uid'=>$userid))->row();
        $q3 = $this->db->get_where('kyc_documents', array('kd_uid'=>$userid))->row();
        $q4 = $this->db->get_where('kyc_payments', array('s_userid'=>$userid))->row();

        $data['kyc_personal'] = $q1;
        $data['kyc_documents'] = $q3;
        $data['kyc_business'] = $q2;
        $data['kyc_payments'] = $q4;
        return $data;
      } else{
        return "no_result";
      }

    }else{ return "empty_code";}

    
    // if((isset($by)&&($by !='')) &&(isset($text) && $text!='')){

    // }

  }
  public function login() {
    $this->load->library('user_agent');  
    $email = $this->input->post('email', true);
    $password = hash('ripemd128', $this->input->post('upswd', true));

    $query = $this->db->get_where('users', array('u_email' => $email, 'u_pswd' => $password, 'is_blocked' => 'false'));

    $result = $query->result();

      if($result) {
        $ip = $this->input->ip_address();
        $ua = $this->agent->platform();
            $data = array('last_login' => date('Y-m-d h:m:s'), 'last_login_ip'=> $ip, 'last_login_device' => $ua);
            $where = array("u_email"=>$email);
            $loginUpdate = $this->db->update('users', $data, $where);
             $session_data = array('email' => $email, 'loggedin' => TRUE);
            $this->session->set_userdata($session_data);

        return  true;
      } 
      else { return false;}
    }

    function user_details(){
      
      return $this->db->get_where('users', array('u_email'=>$this->user_email))->row();
    }

}