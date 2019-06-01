<?php
defined('BASEPATH') or die('Direct access is not allowed');

class My_Controller extends CI_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->library('session');
    $this->load->helper('url');
    $this->load->database();
    $this->site_name = 'Legit Shop';
    $this->site_email = 'info@legitshop.com.ng';
	  $this->user_email = $this->session->email;
    $this->load->library('user_agent');
  }

  public function is_restricted() {
     if ($this->session->loggedin) {
      return true;
    } else {
            $this->session->set_userdata('page_url',  current_url());
      redirect(site_url('login'));
    }
  }

  public function is_authed() {
    if ($this->session->loggedin) {
       redirect(site_url('dashboard'));
    } else {
     return true;
    }
  }

  public function admin_restricted(){
   if ($this->session->admin_logged) {
      return true;
    } else {
            $this->session->set_userdata('page_url',  current_url());
      redirect(site_url('admin/index'));
    }
  }


  public function header($title) {
    $data['site_name'] = $this->site_name;
        $data['langi'] = $this->agent->languages();

    $data['title'] = $title;
    return $this->load->view('header', $data);
  }

  public function header2($title) {
    $duser = $this->db->get_where('users', array('u_email'=>$this->user_email))->row();
    $data['duser'] = $duser;
    $data['site_name'] = $this->site_name;
    $data['title'] = $title;
    return $this->load->view('user/header', $data);
  }

  public function footer($data = NULL) {
    $data['site_name'] = $this->site_name;
    return $this->load->view('footer', $data);
  }

  public function admin_header($title) {
    $data['site_name'] = $this->site_name;
    $data['title'] = $title;
    $duser = $this->db->get_where('admin', array('email'=>$this->session->admin_email))->row();
    $data['duser'] = $duser;
    // $data['sup_msg'] = $this->db->get_where('support_replies', array('reciever_email' =>$this->user, 'read_status' => '0'))->num_rows();

    return $this->load->view('admin/header', $data);
  }

  public function admin_footer($data = NULL) {
      $data['site_name'] = $this->site_name;
      return $this->load->view('admin/footer', $data);
    }
} 

?>
