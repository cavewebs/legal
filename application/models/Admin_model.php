<?php
defined('BASEPATH') or die();

class Admin_model extends CI_Model {

      public function __construct() {
        parent::__construct();
        $this->load->database();
      }

      public function login() {
        $email = $this->input->post('email', true);
        $password = $this->input->post('password', true);
        //hash password
       // $hash = hash('ripemd128', $password);

        $query = $this->db->get_where('admin', array('email' => $email, 'password' => $password))->num_rows();

        if($query > 0) {
          return true;
        } else {
          return false;
        }
      }

      public function update_announcement() {
        $annoucement = $this->input->post('announcement', true);
        return $this->db->query("UPDATE annoucement SET message='$annoucement' WHERE id='1'");
      }

      public function edit($id, $pass) {
        $name = strtoupper($this->input->post('name', true));
        $phone_number = $this->input->post('number', true);
        $email = $this->input->post('email', true);
        $trnxpass = strtoupper($this->input->post('trnxpass', true));
        if(empty($this->input->post('password', true))) {
          $password = $pass;
        } else {
        $password = hash('ripemd128', $this->input->post('password', true));
        }
       // $bundle = $this->input->post('bundle', true);


        $data = array('
        name' => $name,
        'number' => $phone_number,
        'email' => $email,
        'trnxpass' => $trnxpass,
        'password' => $password
       // 'bundle' => $bundle
      );
        return $this->db->query("UPDATE users SET name='$name', email='$email', trnx_pass='$trnxpass', password='$password' WHERE id='$id'");
      }
      
public function send_support_reply(){
    $message = $this->input->post('message', true);
    $email = $this->session->userdata('email');
    $r_id= $this->input->post('replyid', true);
     $user_email= $this->input->post('user_email', true);
    //$date = time();
    $error = array();
    $file = $_FILES['proof']['name'];
    if(!empty($file)) {

    $validextensions = array("jpeg", "jpg", "png"); //Extensions which are allowed
      $ext = explode('.', basename($file)); //explode file name from dot(.)
        $file_extension = end($ext); //store extensions in the variable
        $target_dir = './proofs/';
        $target_file = $target_dir . basename(str_replace(' ', '-', $file));      
        if(!in_array($file_extension, $validextensions)) {
            $error[] = 'File extension is invalid';
        }
        if(count($error) == 0) {
          if(!move_uploaded_file($_FILES['proof']['tmp_name'], $target_file)) { die("File not uploaded"); }
          }else {foreach ($error as $errors) { echo '<div class="alert alert-danger">'.$errors.'</div>';}
        }
      }//if (!empty($file));

    $data = array('sender_email' => $email, 'reciever_email'=>$user_email, 'reply' =>$message, 'reply_id'=>$r_id, 'evidence'=>$file);
     $query = $this->db->insert('support_replies', $data);
      if($query) {return true;} else {return false;}
  }


  public function get_all_support_history() {
     $this->db->order_by('id','DESC');
      return $this->db->get('support')->result_array();

    } 
public function get_support_reply($id) {
    //$query = $this->db->query("UPDATE support_replies SET read_status='1' WHERE reply_id='$id'");
    //$result = $query->result();
    return $this->db->get_where('support_replies', array('reply_id' => $id))->result_array();
  
  } 
}
?>
