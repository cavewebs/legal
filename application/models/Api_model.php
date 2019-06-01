<?php

class Api_model extends CI_Model {
		public function __construct() {
			parent::__construct();
			$this->load->database();

		}

		public function get_details($email) {
			return $this->db->get_where('users', array('user_email' => $email))->row();
		}


		public function player_detail($p_id) {
			return $this->db->get_where('players', array('p_id' => $p_id))->row();
		}

		public function coach_detail($p_id) {
			return $this->db->get_where('coaches', array('cc_id' => $p_id))->row();
		}

		public function team_detail($t_id) {
			return $this->db->get_where('teams', array('t_id' => $t_id))->row();
		}

		public function country_detail($p_id) {
			return $this->db->get_where('countries', array('c_id' => $p_id))->row();
		}


		public function competition_detail($p_id) {
			return $this->db->get_where('competitions', array('com_id' => $p_id))->row();
		}
}