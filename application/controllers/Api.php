<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {

        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");

        header('Access-Control-Allow-Credentials: true');

        header('Access-Control-Max-Age: 86400');    // cache for 1 day

    }

    // Access-Control headers are received during OPTIONS requests

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))

            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");        

       if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))

            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);

    }

require(APPPATH.'/libraries/REST_Controller.php');
require APPPATH . 'libraries/Format.php';
// require APPPATH . '/helpers/MY_url_helper.php';
use Restserver\Libraries\REST_Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Embed\Embed;

// require('application/libraries/REST_Controller.php');

    class Api extends REST_Controller
{
    

    public function __construct() {
    parent::__construct();
    $this->load->model('dashboard_model');
    $this->load->model('api_core_model');
    $this->load->model('api_model');
    $this->load->helper('url');

    $this->load->database();
    $this->methods['competitions_by_country_get']['limit'] = 30; 
    // 500 requests per hour per user/key
    $this->methods['countries_get']['limit'] = 30; 
    $this->methods['competitions_by_id_get']['limit'] = 30; 
    $this->methods['all_competitions_get']['limit'] = 30; 
    $this->methods['countries_get']['limit'] = 300; 
    // $this->methods['countries_get']['limit'] = 30; 
    // $this->methods['countries_get']['limit'] = 30; 
    // $this->methods['countries_get']['limit'] = 30; 
    // $this->methods['countries_get']['limit'] = 30; 
    // $this->methods['countries_get']['limit'] = 30; 
    // $this->methods['countries_get']['limit'] = 30; 
    // $this->methods['countries_get']['limit'] = 30; 

    }

//List all Countries 
    public function countries_get() {
    	$query = $this->db->query("SELECT * FROM countries ORDER BY c_name ASC")->result();
        if($query){
            $countries[] = [
            "status"=>TRUE,
            "message"=>"Retrieved list of countries"
            ];

    		foreach ($query as $row)
    		{
                $numofcomp = $this->db->get_where('competitions', array('com_c_id'=>$row->c_id))->num_rows();
    			$data[] = [
    		        "country"=>[
                        "id" => $row->c_id,
                        "name" => $row->c_name
                        ],
                    "noOfAvailableCompetitions"=>$numofcomp
    		    ];
    		}
            $countries[] =[
                "data"=>$data
            ];
        }
         else{
            $countries[] = [
                "status"=>FALSE,
                "message"=>"Could not get list of countries"
            ];
        }
    		$this->response($countries, 200);

    }




// List All Competitions
    public function all_competitions_get() {
    	$filters=array();
    	$limita= NULL;
        $filter='';
    	if($this->get('limit')){
    		$limita = $this->get('limit');
    		$filter = "LIMIT ".$limita;
            $filters[]=["Limit"=>$limita];

    	}
        if($this->get('format')){
            $format = $this->get('format');
            $filters[]=["Format"=>$format];

        }

    	$query = $this->db->query("SELECT * FROM competitions ".$filter);
        if($query->result()){

        	$data[] = [
            	"totalCompetitions" => $this->db->get('competitions')->num_rows(),
            	"Filters"=>$filters
            ];
    		foreach ($query->result() as $row)
    		{
    			//
            $totalMatches = $this->db->get_where('matches', array('m_com_id' => $row->com_id))->num_rows();

            $competitionCountry = $this->db->get_where('countries', array('c_id' => $row->com_c_id))->row();
            $currentSeason = $this->db->query('SELECT cs_id, MAX(cs_name) as cs_name FROM competition_seasons WHERE cs_com_id="'.$row->com_id.'"')->row();
            // $curSeason = $this->db->select_max('cs_name', 'currentSeason')->get_where('competition_seasons', array('cs_com_id' => $row->com_id))->row()->currentSeason;

            $lStart = $this->db->select_min('m_date', 'startDay')->get_where('matches', array('m_com_id' => $row->com_id, 'm_season'=>$currentSeason->cs_name))->row()->startDay;
            if ($lStart){
               $lStart = date("d-m-Y", $lStart); 
            }

            $lEnd = $this->db->select_max('m_date', 'endDay')->get_where('matches', array('m_com_id' => $row->com_id, 'm_season'=>$currentSeason->cs_name))->row()->endDay;
            if ($lEnd !=NULL){
               $lEnd = date("d-m-Y", $lEnd); 
            }

    			$data[] = [
    				"competition" =>[
                        "id" => $row->com_id,
                        "name" => $row->com_name,
                        "currentSeason"=>$currentSeason->cs_name,
                        "totalMatches" => $totalMatches,
                        "seasonStarts" => $lStart,
                        "SeasonEnds" => $lEnd,
        		        "country"=> [
                            "id" => $competitionCountry->c_id,
        		            "name" => $competitionCountry->c_name
                            ]
    		        ]
    		    ];
    		}

            $countries=[
                "status"=>TRUE,
                "message"=>"Retrieved list of Competitions",
                "data"=>$data
            ];
        }
		else{
            $countries = [
                "status"=>FALSE,
                "message"=>"Could not get list of competitions"
            ];
        }
    $this->response($countries, 200);
		

    }


//GEt competitions by Id
 public function competitions_by_id_get() 
{
 	$cm_id = $this->uri->segment(3);
    $cs_id = explode(",", $cm_id);
    if(!empty($cs_id)){
        foreach ($cs_id as $com_id) {

        	$row = $this->db->query("SELECT * FROM competitions WHERE com_id = '".$com_id."'")->row();
            if($row != NULL){
            $competitionCountry = $this->db->get_where('countries', array('c_id' => $row->com_c_id))->row();
             // $sqlg ="SELECT  * FROM competition_seasons GROUP BY cs_name 
             //          MAX(cs_name)  
             //        FROM competition_seasons 
             //        GROUP BY cs_name
             //        WHERE g.active = 1 
             //          AND g.department_id = 1 
             //          AND g.manage_work_orders = 1 
             //          AND g.group_name != 'root' 
             //          AND g.group_name != 'superuser' 
             //        GROUP BY 
             //          g.group_name, 
             //          g.group_id 
             //        HAVING COUNT(d.user_id) > 0 
             //        ORDER BY g.group_name" 
            // $currentSeason = $this->db->query('SELECT * FROM  competition_seasons WHERE cs_com_id="'.$row->com_id.'" ORDER BY cs_name DESC LIMIT 1')->row()->cs_name;


            $currentSeason = $this->db->query('SELECT MAX(cs_name) as cs_name FROM competition_seasons WHERE cs_com_id="'.$row->com_id.'"')->row()->cs_name;

            $result = $this->db->get_where('matches', array('m_com_id' => $row->com_id, 'm_season'=>$currentSeason));

             $lStart = $this->db->select_min('m_date', 'startDay')->get_where('matches', array('m_com_id' => $row->com_id, 'm_season'=>$currentSeason))->row()->startDay;
            if ($lStart){
               $lStart = date("d-m-Y", $lStart); 
            }

            $lEnd = $this->db->select_max('m_date', 'endDay')->get_where('matches', array('m_com_id' => $row->com_id, 'm_season'=>$currentSeason))->row()->endDay;
            if ($lEnd !=NULL){
                // if($lEnd instanceof DateTime){
                 // expression is true if $myVar is a PHP DateTime Object
               $lEnd = date("d-m-Y", $lEnd); 
             // }
            }
            $availableSeasons = $this->db->get_where('competition_seasons', array('cs_com_id'=>$row->com_id))->num_rows();

            $getSeason = $this->db->get_where('competition_seasons', array('cs_com_id'=>$row->com_id,'cs_name !='=>$currentSeason));
            if($getSeason->num_rows()>0){
                foreach ($getSeason->result() as $srow) {
                    $winna = $this->db->get_where('competition_standings', array('cx_season'=>$srow->cs_name, 'cx_com_id'=>$srow->cs_com_id, 'cx_position'=>"1"))->row();
                    if($winna){
                        $winner = $winna->cx_t_id;
                        $seasonWinner = $this->api_model->team_detail($winner)->t_name;
                    } else{$winner=NULL;$seasonWinner=NULL;}
                    $topScorer = $this->db->query(' SELECT * FROM competition_topscorers WHERE ct_season="'.$srow->cs_name.'" AND ct_com_id="'.$srow->cs_com_id.'" LIMIT 1')->row();
                    if($topScorer){
                        $topScorer = $topScorer->ct_p_id;
                        $seasonTopScorer = $this->api_model->player_detail($topScorer)->p_name;

                    }else{
                        $seasonTopScorer = NULL;
                    }

                    $sizStart = $this->db->select_min('m_date', 'startDay')->get_where('matches', array('m_com_id' => $row->com_id, 'm_season'=>$srow->cs_name))->row()->startDay;
                    if ($sizStart){
                       $sizStartd = date("d-m-Y", $sizStart); 
                    }

                    $sizEnd = $this->db->select_max('m_date', 'endDay')->get_where('matches', array('m_com_id' => $row->com_id, 'm_season'=>$srow->cs_name))->row()->endDay;
                    if ($sizEnd !=NULL){
                        // if($lEnd instanceof DateTime){
                         // expression is true if $myVar is a PHP DateTime Object
                       $sizEndd = date("d-m-Y", $sizEnd); 
                     // }
                    }

                    $season[]=[
                        "name"=>$srow->cs_name,
                        "Started"=>$sizStartd,
                        "Ended"=>$sizEndd,
                        "winner"=> [
                            "id"=>$winner,
                            "name"=>$seasonWinner
                        ],
                        "topScorer"=>[
                            "id"=>$topScorer,
                            "name"=>$seasonTopScorer
                        ]
                    ];
                }
            }else{
                $season=NULL;
            }


    			$data[] = [
    				"competition" =>[
                        "id" => $row->com_id,
        		        "name" => $row->com_name,
                        "availableSeasons"=>$availableSeasons,
                        "country" =>[
                            "id"=> $competitionCountry->c_id,
                            "name" => $competitionCountry->c_name
                            ],
                        "currentSeason"=> [
                            "name"=>$currentSeason,
            				"totalMatches" => $result->num_rows(),
                            "seasonStarts" => $lStart,
                            "SeasonEnds" => $lEnd
                        ],
                        "previousSeasons"=>$season
                        ]	    
    		    ];

            }//If (row)
            else {
                $data[] = [

                    "status" => FALSE,
                    "message" => "No record found for country with ID: ".$com_id,
                ];

            }
        }//foreach cs as 

    }
        else {
            $countries = [

                "status" => FALSE,
                "message" => "No cometition ID suplied with request: ",
            ];

            }
		
        $countries=[
            "status"=>TRUE,
            "message"=>"Retrieved list of Competitions by the competition ID",
            "data"=>$data
        ];
        $this->response($countries, 200);

}

    //get all competitions from a country:

    function country_competitions_get()
    {
        $c_id = $this->uri->segment(2);
        $result = $this->db->get_where('competitions', array('com_c_id' => $c_id))->result();
         
        if(!$result)
        {
           $this->response(false, 200);
        }
         
        else
        {
           foreach ($result as $row)
			{
				$competitions[] = [

			        "competition_id" => $row->com_id,
			        "competition_name" => $row->com_name
			    ];
			}
			$this->response($competitions, 200);
	        }
	        // $this->response($c_id, 200);
         
    }


//GEt competitions by Country
    public function competitions_by_country_get() {
    $cm_id = $this->uri->segment(3);
    $cs_id = explode(",", $cm_id);
    if(!empty($cs_id)){
            foreach ($cs_id as $com_id) {

                    $rowsz = $this->db->query("SELECT * FROM competitions WHERE com_c_id = '".$com_id."'");
                    $rows = $rowsz->result();
                    if($rows != NULL){
                        foreach($rows as $row){

                        $competitionCountry = $this->db->get_where('countries', array('c_id' => $row->com_c_id))->row();

                        $result = $this->db->get_where('matches', array('m_com_id' => $row->com_id));
                        


                        $lStart = $this->db->select_min('m_date', 'startDay')->get_where('matches', array('m_com_id' => $row->com_id))->row()->startDay;
                        if ($lStart){
                            // if($lStart instanceof DateTime){
                             // expression is true if $myVar is a PHP DateTime Object
                           $lStart = date("d-m-Y", $lStart); 
                        }
                            

                        $lEnd = $this->db->select_max('m_date', 'endDay')->get_where('matches', array('m_com_id' => $row->com_id))->row()->endDay;
                        if ($lEnd !=NULL){
                            // if($lEnd instanceof DateTime){
                             // expression is true if $myVar is a PHP DateTime Object
                           $lEnd = date("d-m-Y", $lEnd); 
                         // }
                        }


                            $data[] = [
                                // "totalCompetitions" => $rowsz->num_rows(),                
                                "competition"=> [
                                "id" => $row->com_id,
                                "name" => $row->com_name,
                                "totalMatches" => $result->num_rows(),
                                "seasonStarts" => $lStart,
                                "SeasonEnds" => $lEnd,
                                "country"=>[
                                    "id"=> $competitionCountry->c_id,
                                    "name" => $competitionCountry->c_name]                
                                ]       
                            ];
                        }

                        }//If (row)
                        else {
                            $countries[] = [

                                "status" => "Failed",
                                "message" => "No record found for country with ID: ".$com_id,
                            ];

                        }
                } //for each cs_id as com
                $countries[]=[
                    "status"=>TRUE,
                    "message"=>"Retrieved list of competitions by the country ID",
                    "data"=>$data
                ];
            } else {
                $countries[] = [

                    "status" => FALSE,
                    "message" => "No country ID suplied with request: ",
                ];

            }
            $this->response($countries, 200);
    }

//matches
    function all_matches_get(){
        $filter='';
        $limita= NULL;
        if($this->get('limit')){
            $limita = $this->get('limit');
            $filter = "LIMIT ".$limita;

        }

        $query = $this->db->query("SELECT * FROM matches ".$filter)->result();
        if($query){
            $matches[] = [
            "status"=>TRUE,
            "message"=>"Retrieved list of matches"
            ];

            $data[] = [
                "totalMatches" => $this->db->get_where('matches', array('m_status'=>"79"))->num_rows(),
                "filters"=>[
                    "limit" => $limita
                    ]
            ];
                foreach ($query as $row){
                    //

                    $q1 = $this->db->get_where('competitions', array('com_id'=>$row->m_com_id))->row();

                    $q2 = $this->db->get_where('countries', array('c_id'=>$q1->com_c_id))->row();
                    

                    $matchTime = $row->m_time;

                    $matchDay = $row->m_date;

                    if ($matchDay !=NULL){
                       $matchDay = date("d-m-Y", $matchDay); 
                    }

                        $data[] = [

                            "match"=>[
                                "id" => $row->m_id,
                                "time" => $matchTime,
                                "date" => $matchDay,
                                "competition"=>[
                                    "id" => $row->m_com_id,
                                    "name" => $q1->com_name,
                                    "country"=>[
                                        "id"=> $q2->c_id,
                                        "name" => $q2->c_name
                                    ]
                                ]
                            ]                  
                        ];
                }  //each query as row    
                $matches[]=[
                    "data"=>$data
                ];
        } 

        else{
            $matches[] = [
                "status"=>FALSE,
                "message"=>"Could not get retrieve list of macthes"
            ];
        }
                $this->response($matches, 200);
    }


    //Get Single Id matches
    function match_by_filters_get(){
        $ms_id = $this->uri->segment(4);
        if(isset($_GET['competitionId'])){
          $compId = $_GET['competitionID'];  
        }
        $ms_id = $this->uri->segment(4);
        if(isset($_GET['dateFrom'])){
          $dfrom = $_GET['dateFrom'];  
        }
        $ms_id = $this->uri->segment(4);
        if(isset($_GET['dateTo'])){
          $dTo = $_GET['dateTo'];  
        }
        $ms_id = $this->uri->segment(4);
        if(isset($_GET['status'])){
          $mstatus = $_GET['status'];  
        }
 

        $totalC =0;
        $filter='';
        $limita= NULL;
        if($this->get('limit')){
            $limita = $this->get('limit');
            $filter = "LIMIT ".$limita;
        }
            $mss_id = explode(",", $ms_id);
            if(!empty($mss_id)){
             $matches[] = [
            "status"=>TRUE,
            "message"=>"Retrieved match details"
            ];

            $data[] = [
                "filters"=>[
                    "limit" => $limita
                    ]
            ];
            foreach ($mss_id as $m_id) {
                $row = $this->db->query("SELECT * FROM matches WHERE m_id = '".$m_id."' ".$filter)->row();
                //check if the match ID is valid
                if($row){

                    $q1 = $this->db->get_where('competitions', array('com_id'=>$row->m_com_id))->row();


                    $q2 = $this->db->get_where('countries', array('c_id'=>$q1->com_c_id))->row();
                    $homeTeamname = $this->db->get_where('teams', array('t_id'=>$row->m_hometeam))->row();
                    $awayTeamname = $this->db->get_where('teams', array('t_id'=>$row->m_awayteam))->row();
                    $hteamCountry = $this->db->get_where('countries', array('c_id'=>$homeTeamname->t_c_id))->row();
                    $ateamCountry = $this->db->get_where('countries', array('c_id'=>$awayTeamname->t_c_id))->row();



                    $matchTime = $row->m_time;

                    $matchDay = $row->m_date;

                    if ($matchDay !=NULL){
                       $matchDay = date("d-m-Y", $matchDay); 
                    }

                        $data[] = [
                            // "totalMatches" => $totalC,

                            "match"=>[
                                "id" => $row->m_id,
                                "time" => $matchTime,
                                "date" => $matchDay,
                                "round" => $row->m_round,
                                "status" => $row->m_live_status,
                                "competition" => [
                                    "id" => $row->m_com_id,
                                    "name" => $q1->com_name,
                                    "country"=>[
                                        "id"=> $q2->c_id,
                                        "name" => $q2->c_name
                                    ],
                                    "teams"=>[
                                        "home" => [
                                            "id" => $row->m_hometeam,
                                            "name"=> $homeTeamname->t_name,
                                            "country"=>[
                                                "id"=>$hteamCountry->c_id,
                                                "name"=>$hteamCountry->c_name
                                            ]
                                        ],
                                        "away" => [
                                            "id" => $row->m_awayteam,
                                            "name"=> $awayTeamname->t_name,
                                            "country"=>[
                                                "id"=>$ateamCountry->c_id,
                                                "name"=>$ateamCountry->c_name
                                            ]
                                        ],
                                    ],
                                    "score"=>[
                                        "winner"=>"",
                                        "concludedIn"=>"REGULAR_TIME",
                                        "extraTime"=>NULL,
                                        "penaltyShootout"=>NULL,
                                        "homeTeam"=>[
                                            "firstHalfScore" => $row->m_hscore_fh,
                                            "secondHalfScore" => $row->m_hscore_sh,
                                            "fulltimeScore" => $row->m_hscore_ft,
                                            "firstExtraTimeScore" => $row->m_hscore_et1,
                                            "secondExtraTimeScore" => $row->m_hscore_et2,
                                            "penaltyScore" => $row->m_hscore_pk,
                                        ],
                                        "awayTeam"=>[
                                            "firstHalfScore" => $row->m_ascore_fh,
                                            "secondHalfScore" => $row->m_ascore_sh,
                                            "fulltimeScore" => $row->m_ascoreft,
                                            "firstExtraTimeScore" => $row->m_ascore_et1,
                                            "secondExtraTimescore" => $row->m_ascore_et2,
                                            "penaltyScore" => $row->m_ascore_pk
                                        ]
                                    ]
                                ]
                           
                            ]
                        ];
                    }// if(row) i.e match record was found
                    else{
                        $data[] = [
                        "status"=>FALSE,
                        "message"=>"No record found for match id:".$m_id
                        ];
                    }

                }// if ms_id is not empty
                $matches[]=[
                    "data"=>$data
                ];
            }// 
        else{
             $this->response("error: Competition Id is missing", 202);
            
        } 
                                $this->response($matches, 200);    
    }



//Get Single Id matches
    function match_by_id_get(){
        $ms_id = $this->uri->segment(4);
        $totalC =0;
        $filter='';
        $limita= NULL;
        $aTeam_lineup=[];
        $aTeam_bench=[];
        $aTeam_coach=[];
        $hTeam_lineup=[];
        $hTeam_bench=[];
        $hTeam_coach=[];
        $match_bookings=[];
        $match_goals=[];
        $match_substitutions=[];
        if($this->get('limit')){
            $limita = $this->get('limit');
            $filter = "LIMIT ".$limita;
        }
            $mss_id = explode(",", $ms_id);
            if(!empty($mss_id)){
             $matches[] = [
            "status"=>TRUE,
            "message"=>"Retrieved match details"
            ];

            $data[] = [
                "filters"=>[
                    "limit" => $limita
                    ]
            ];
            foreach ($mss_id as $m_id) {
                $row = $this->db->query("SELECT * FROM matches WHERE m_id = '".$m_id."' ".$filter)->row();
                //check if the match ID is valid
                if($row){

                    $q1 = $this->db->get_where('competitions', array('com_id'=>$row->m_com_id))->row();


                    $q2 = $this->db->get_where('countries', array('c_id'=>$q1->com_c_id))->row();

                    $homeTeamname = $this->db->get_where('teams', array('t_id'=>$row->m_hometeam))->row();

                    $awayTeamname = $this->db->get_where('teams', array('t_id'=>$row->m_awayteam))->row();

                    $hteamCountry = $this->db->get_where('countries', array('c_id'=>$homeTeamname->t_c_id))->row();

                    $ateamCountry = $this->db->get_where('countries', array('c_id'=>$awayTeamname->t_c_id))->row();
                    

                    // TEAM LINEUP
                    $at_lineUp = $this->db->get_where('match_lineup', array('ml_id'=>$m_id, 'ml_t_id'=>$row->m_awayteam))->result();
                    foreach ($at_lineUp as $match_lineup) {
                        $match_lineup_playerDetails = $this->db->query('SELECT * FROM players WHERE p_id = "'.$match_lineup->ml_p_id.'"')->row();
                        $aTeam_lineup=[
                            "id"=>$match_lineup->ml_p_id,
                            "name"=>$match_lineup_playerDetails->p_name,
                            "role"=>$match_lineup_playerDetails->p_role,
                            "nationality"=>$match_lineup_playerDetails->p_nationality,
                            "playerAvatar"=>$match_lineup_playerDetails->p_pic_url
                        ];
                    }

                    $ht_lineUp = $this->db->get_where('match_lineup', array('ml_id'=>$m_id, 'ml_t_id'=>$row->m_hometeam))->result();
                    foreach ($ht_lineUp as $match_lineup) {
                        $match_lineup_playerDetails = $this->db->query('SELECT * FROM players WHERE p_id = "'.$match_lineup->ml_p_id.'"')->row();
                        $hTeam_lineup=[
                            "id"=>$match_lineup->ml_p_id,
                            "name"=>$match_lineup_playerDetails->p_name,
                            "role"=>$match_lineup_playerDetails->p_role,
                            "nationality"=>$match_lineup_playerDetails->p_nationality,
                            "playerAvatar"=>$match_lineup_playerDetails->p_pic_url
                        ];
                    }


                    //TEAM BENCH

                    $at_bench = $this->db->get_where('match_bench', array('mb_id'=>$m_id, 'mb_t_id'=>$row->m_awayteam))->result();
                    foreach ($at_bench as $bench_list) {
                        $bench_list_playerDeatils = $this->db->query('SELECT * FROM players WHERE p_id = "'.$bench_list->mb_p_id.'"')->row();
                        $aTeam_bench=[
                            "id"=>$bench_list_playerDeatils->ml_p_id,
                            "name"=>$bench_list_playerDeatils->p_name,
                            "role"=>$bench_list_playerDeatils->p_role,
                            "nationality"=>$bench_list_playerDeatils->p_nationality,
                            "playerAvatar"=>$bench_list_playerDeatils->p_pic_url
                        ];
                    }


                    $ht_bench = $this->db->get_where('match_bench', array('mb_id'=>$m_id, 'mb_t_id'=>$row->m_hometeam))->result();
                    foreach ($ht_bench as $bench_list) {
                        $bench_list_playerDeatils = $this->db->query('SELECT * FROM players WHERE p_id = "'.$bench_list->mb_p_id.'"')->row();
                        $hTeam_bench=[
                            "id"=>$bench_list_playerDeatils->ml_p_id,
                            "name"=>$bench_list_playerDeatils->p_name,
                            "role"=>$bench_list_playerDeatils->p_role,
                            "nationality"=>$bench_list_playerDeatils->p_nationality,
                            "playerAvatar"=>$bench_list_playerDeatils->p_pic_url
                        ];
                    }

                    //TEAM COACHES
                    $ht_coachq = $this->db->get_where('coaches',array('cc_id'=>$row->m_hteam_coach))->row();
                    if($ht_coachq){
                        $hTeam_coach[]=[
                            "id"=>$ht_coachq->cc_id,
                            "name"=>$ht_coachq->cc_name
                        ];
                    }

                    $at_coachq = $this->db->get_where('coaches',array('cc_id'=>$row->m_ateam_coach))->row();
                    if($at_coachq){
                        $aTeam_coach[]=[
                            "id"=>$at_coachq->cc_id,
                            "name"=>$ht_coachq->cc_name
                        ];
                    }

                    
                    //TEAM BOOKINGS
                    $ma_booking = $this->db->get_where('match_bookings', array('mk_m_id'=>$row->m_id))->result();
                    foreach ($ma_booking as $match_booking) {
                        $match_bookingPlayerDetails = $this->db->query('SELECT * FROM players WHERE p_id = "'.$match_booking->mk_p_id.'"')->row()->p_name;
                        $match_bookingTeamDetail = $this->db->get_where('teams', array('team_id'=>$match_booking->mb_t_id))->row()->t_name;
                        $match_bookings[]=[
                            "minute"=>$match_booking->mb_time,
                            "player"=>[
                                "id"=>$match_booking->mk_p_id,
                                "name"=>$match_bookingPlayerDetails->p_name,
                            ],
                            "team"=>[                            
                                "id"=>$match_booking->mb_t_id,
                                "name"=>$match_bookingTeamDetail->t_name,
                            ]
                        ];
                    }


                    //TEAM SUBSITUTIONS
                    $match_subsq = $this->db->get_where('match_subs', array('ms_m_id'=>$row->m_id))->result();
                    foreach ($match_subsq as $match_subs) {
                        $match_subPlayerDetails = $this->db->get_where('players', array('p_id' =>$match_subs->ms_p_id))->row();
                        $match_subTeamDetail = $this->db->get_where('teams', array('team_id'=>$match_subs->ms_t_id))->row();
                        $match_substitutions[]=[
                            "minute"=>$match_subs->ms_time,
                            "player"=>[
                                "id"=>$match_subs->ms_p_id,
                                "name"=>$match_subPlayerDetails->p_name,
                            ],
                            "team"=>[                            
                                "id"=>$match_subs->ms_t_id,
                                "name"=>$match_subTeamDetail->t_name,
                            ]
                        ];
                    }

                    //MATCH GOALS
                    $match_goalsq = $this->db->get_where('match_goals', array('mg_m_id'=>$row->m_id))->result();
                    foreach ($match_goalsq as $match_goals) {
                        $match_goalPlayerDetails = $this->api_model->player_detail($match_goals->mg_p_id);
                        $match_goalTeamDetail = $this->db->api_model($match_goals->mg_t_id) ;
                        $match_goals[]=[
                            "minute"=>$match_goals->mg_time,
                            "player"=>[
                                "id"=>$match_goals->mg_p_id,
                                "name"=>$match_goalPlayerDetails->p_name,
                            ],
                            "team"=>[                            
                                "id"=>$match_goals->mg_t_id,
                                "name"=>$match_goalTeamDetail->t_name,
                            ]
                        ];
                    }

                    if($row->m_penalties=='1'){
                        $row_penalties = TRUE;
                    } else {                        
                        $row_penalties = FALSE;
                    }
                    if($row->m_extra_time=='1'){
                        $row_extratime = TRUE;
                    } else {                        
                        $row_extratime = FALSE;
                    }

                    $matchTime = $row->m_time;

                    $matchDay = $row->m_date;

                    if ($matchDay !=NULL){
                       $matchDay = date("d-m-Y", $matchDay); 
                    }

                        $data[] = [
                            // "totalMatches" => $totalC,

                            "match"=>[
                                "id" => $row->m_id,
                                "time" => $matchTime,
                                "day" => $matchDay,
                                "round" => $row->m_round,
                                "status" => $row->m_live_status,
                                "competition" => [
                                    "id" => $row->m_com_id,
                                    "name" => $q1->com_name,
                                    "country"=>[
                                        "id"=> $q2->c_id,
                                        "name" => $q2->c_name
                                    ],
                                ],
                                "teams"=>[
                                    "home" => [
                                        "id" => $row->m_hometeam,
                                        "name"=> $homeTeamname->t_name,
                                        "country"=>[
                                            "id"=>$hteamCountry->c_id,
                                            "name"=>$hteamCountry->c_name
                                        ],
                                        "lineup"=>$hTeam_lineup,
                                        "bench"=>$hTeam_bench
                                    ],
                                    "away" => [
                                        "id" => $row->m_awayteam,
                                        "name"=> $awayTeamname->t_name,
                                        "country"=>[
                                            "id"=>$ateamCountry->c_id,
                                            "name"=>$ateamCountry->c_name
                                        ],
                                        "lineup"=>$aTeam_lineup,
                                        "bench"=>$aTeam_bench
                                    ],
                                ],
                                "result"=>[
                                    "winner"=>"",
                                    "concludedIn"=>"REGULAR_TIME",
                                    "extraTime"=>$row_extratime,
                                    "penaltyShootout"=>$row_penalties,
                                    "homeTeam"=>[
                                        "firstHalfScore" => $row->m_hscore_fh,
                                        "secondHalfScore" => $row->m_hscore_sh,
                                        "fulltimeScore" => $row->m_hscore_ft,
                                        "firstExtraTimeScore" => $row->m_hscore_et1,
                                        "secondExtraTimeScore" => $row->m_hscore_et2,
                                        "penaltyScore" => $row->m_hscore_pk,
                                    ],
                                    "awayTeam"=>[
                                        "firstHalfScore" => $row->m_ascore_fh,
                                        "secondHalfScore" => $row->m_ascore_sh,
                                        "fulltimeScore" => $row->m_ascoreft,
                                        "firstExtraTimeScore" => $row->m_ascore_et1,
                                        "secondExtraTimescore" => $row->m_ascore_et2,
                                        "penaltyScore" => $row->m_ascore_pk
                                    ]
                                ],
                                "goals"=>$match_goals,

                                "substitutions"=>$match_substitutions,

                                "bookings"=>$match_bookings, 

                            ]
                        ];
                    }// if(row) i.e match record was found
                    else{
                        $data[] = [
                        "status"=>FALSE,
                        "message"=>"No record found for match id:".$m_id
                        ];
                    }

                }// if ms_id is not empty
                $matches[]=[
                    "data"=>$data
                ];
            }// 
        else{
             $this->response("error: competition ID is missing", 202);
            
        } 
                                $this->response($matches, 200);    
    }
//Get Single Team  byb ID matches
    function competition_teams_get(){
        $comp_id = $this->uri->segment(2);
        $totalC =0;
        $filters=[];
        $currentSeason = $this->db->query('SELECT MAX(cs_name) FROM competition_seasons WHERE cs_com_id="'.$comp_id.'"')->row()->cs_name;
        $filter='AND m_season ="'.$currentSeason.'" '
         if(isset($_GET['competitionId'])){
          $compId = $_GET['competitionID'];  
        }
        if(isset($_GET['season'])){
          $season = $_GET['season']; 
          $filter ='AND m_season ="'.$season.'" ';
        }

        $limita= NULL;
        if($this->get('limit')){
            $limita = $this->get('limit');
            $filter = $filter." LIMIT ".$limita;
        }
            if($comp_id){
                $con_id = $this->db->query('SELECT * FROM competitions WHERE com_id="'.$comp_id.'"')->row();
                $compQ = $this->db->query('SELECT * FROM matches WHERE com_id="'.$comp_id.'"')->row();
                $rowa = $this->db->query("SELECT * FROM teams WHERE t_c_id = '".$con_id."' ".$filter);
                $row = $rowa->result();
                $totResult = $rowa->num_rows();
                $data[]=[
                    "totalRecords"=>$totResult
                ];
                //check if the match ID is valid
                if($row){
                    foreach($row as $row1){

                    $q1 = $this->db->get_where('competitions', array('com_id'=>$row1->m_com_id))->row();
                        $data[] = [
                            "competition"=>[
                            "id"=>$row1->,
                            "area"=>{
                              "id"=>2267,
                              "name"=>"World"
                            },
                            "name"=>"FIFA World Cup",
                            "code"=>"WC",
                            "plan"=>"TIER_ONE",
                            "lastUpdated"=>"2018-08-23T12:16:17Z"
                          },
                          "season"=>{
                            "id"=>1,
                            "startDate"=>"2018-06-14",
                            "endDate"=>"2018-07-15",
                            "currentMatchday"=>3,
                            "winner"=>{
                              "id"=>773,
                              "name"=>"France",
                              "shortName"=>"France",
                              "tla"=>"FRA",
                              "crestUrl"=>"https://upload.wikimedia.org/wikipedia/en/c/c3/Flag_of_France.svg"
                            }
                          },

                        "teams" => [
                            "id" => $row1->m_com_id,
                            "name" => $q1->com_name,
                            "country"=>[
                                "id"=> $q2->c_id,
                                "name" => $q2->c_name
                   
                    ]
                ];
                    }//end of foreach row
                    }// if(row) i.e match record was found
                    else{
                        $data[] = [
                        "status"=>FALSE,
                        "message"=>"No record found for competition id:".$m_id. " and filters "
                        ];
                    }

                }// if ms_id is not empty
                $matches[]=[
                    "data"=>$data
                ];
            }// 
        else{
             $this->response("error: Competition Id is missing", 202);
            
        } 
                                $this->response($matches, 200);    
    }
    
//Get Single Id matches
    function match_by_competitions_get(){
        $ms_id = $this->uri->segment(4);
        $totalC =0;
        $filter='';
        $filters=[];
         if(isset($_GET['competitionId'])){
          $compId = $_GET['competitionID'];  
        }
        if(isset($_GET['dateFrom'])){
          $dfrom = $_GET['dateFrom']; 
          $filter = $filter.'AND m_date >="'.strtotime($dfrom).'" ';
          $filters[] =[
                    "dateFrom"=> $dfrom
                    ];

        }
        if(isset($_GET['dateTo'])){
          $dTo = $_GET['dateTo'];  
          $filter = $filter.'AND m_date <="'.((strtotime($dTo))+86400).'" ';
                $filters[]=[
                    "dateTo" => $dTo
                    ];
        }
        if(isset($_GET['status'])){
          $mstatus = $_GET['status'];
          $filter = $filter.'AND m_live_status = "'.$mstatus.'" ';
                $filters[]=[
                    "status" => $mstatus
                    ];
        }
        if(isset($_GET['round'])){
          $round = $_GET['round'];
          $filter = $filter.'AND m_round ="'.$round.'" ';
                $filters[]=[
                    "Round" => $round
                    ];  
        }

        $limita= NULL;
        if($this->get('limit')){
            $limita = $this->get('limit');
            $filter = $filter." LIMIT ".$limita;
            $filters[]=[
                    "limit" => $limita
                    ];
        }
            $mss_id = explode(",", $ms_id);
            if(!empty($mss_id)){
             $matches[] = [
            "status"=>TRUE,
            "message"=>"Retrieved match details"
            ];
            $data[]=[
                "filters"=>$filters
            ];

            foreach ($mss_id as $m_id) {
                $rowa = $this->db->query("SELECT * FROM matches WHERE m_com_id = '".$m_id."' ".$filter);
                $row = $rowa->result();
                $totResult = $rowa->num_rows();
                $data[]=[
                    "totalRecords"=>$totResult
                ];
                //check if the match ID is valid
                if($row){
                    foreach($row as $row1){

                    $q1 = $this->db->get_where('competitions', array('com_id'=>$row1->m_com_id))->row();


                    $q2 = $this->db->get_where('countries', array('c_id'=>$q1->com_c_id))->row();
                    $homeTeamname = $this->db->get_where('teams', array('t_id'=>$row1->m_hometeam))->row();
                    $awayTeamname = $this->db->get_where('teams', array('t_id'=>$row1->m_awayteam))->row();
                    $hteamCountry = $this->db->get_where('countries', array('c_id'=>$homeTeamname->t_c_id))->row();
                    $ateamCountry = $this->db->get_where('countries', array('c_id'=>$awayTeamname->t_c_id))->row();
                    if($row1->m_penalties=='1'){
                        $row_penalties = TRUE;
                    } else {                        
                        $row_penalties = FALSE;
                    }
                    if($row1->m_extra_time=='1'){
                        $row_extratime = TRUE;
                    } else {                        
                        $row_extratime = FALSE;
                    }

                    $matchTime = $row1->m_time;

                    $matchDay = $row1->m_date;

                    if ($matchDay !=NULL){
                       $matchDay = date("d-m-Y", $matchDay); 
                    }

                        $data[] = [
                            // "totalMatches" => $totalC,

                            "match"=>[
                                "id" => $row1->m_id,
                                "time" => $matchTime,
                                "date" => $matchDay,
                                "round" => $row1->m_round,
                                "status" => $row1->m_live_status,
                                "competition" => [
                                    "id" => $row1->m_com_id,
                                    "name" => $q1->com_name,
                                    "country"=>[
                                        "id"=> $q2->c_id,
                                        "name" => $q2->c_name
                                    ],
                                    "teams"=>[
                                        "home" => [
                                            "id" => $row1->m_hometeam,
                                            "name"=> $homeTeamname->t_name,
                                            "country"=>[
                                                "id"=>$hteamCountry->c_id,
                                                "name"=>$hteamCountry->c_name
                                            ]
                                        ],
                                        "away" => [
                                            "id" => $row1->m_awayteam,
                                            "name"=> $awayTeamname->t_name,
                                            "country"=>[
                                                "id"=>$ateamCountry->c_id,
                                                "name"=>$ateamCountry->c_name
                                            ]
                                        ],
                                    ],
                                    "result"=>[
                                        "winner"=>"",
                                        "concludedIn"=>"REGULAR_TIME",
                                        "extraTime"=>$row_extratime,
                                        "penaltyShootout"=>$row_penalties,
                                        "homeTeam"=>[
                                            "firstHalfScore" => $row1->m_hscore_fh,
                                            "secondHalfScore" => $row1->m_hscore_sh,
                                            "fulltimeScore" => $row1->m_hscore_ft,
                                            "firstExtraTimeScore" => $row1->m_hscore_et1,
                                            "secondExtraTimeScore" => $row1->m_hscore_et2,
                                            "penaltyScore" => $row1->m_hscore_pk,
                                        ],
                                        "awayTeam"=>[
                                            "firstHalfScore" => $row1->m_ascore_fh,
                                            "secondHalfScore" => $row1->m_ascore_sh,
                                            "fulltimeScore" => $row1->m_ascoreft,
                                            "firstExtraTimeScore" => $row1->m_ascore_et1,
                                            "secondExtraTimescore" => $row1->m_ascore_et2,
                                            "penaltyScore" => $row1->m_ascore_pk
                                        ]
                                    ]
                                ]
                           
                            ]
                        ];
                    }//end of foreach row
                    }// if(row) i.e match record was found
                    else{
                        $data[] = [
                        "status"=>FALSE,
                        "message"=>"No record found for competition id:".$m_id. " and filters "
                        ];
                    }

                }// if ms_id is not empty
                $matches[]=[
                    "data"=>$data
                ];
            }// 
        else{
             $this->response("error: Competition Id is missing", 202);
            
        } 
                                $this->response($matches, 200);    
    }

function countri_competitions_get()
    {
        $c_id = $this->get('country_id');
        $com_id = $this->get('com_id');
        if (isset($com_id)){
        	$result = $this->db->get_where('competitions', array('com_id' => $com_id))->result();
         
        if(!$result)
        {
           $this->response(false, 200);
        }
         
        else
        {
           foreach ($result as $row)
			{
				$competitions[] = [

			        "competition_id" => $row->com_id,
			        "competitionGroupId" => $row->com_c_id,
			        "competitionGroupName" => $row->com_id,
			        "competition_name" => $row->com_name
			    ];
			}
			$this->response($competitions, 200);
	        }

        }
        if(isset($c_id)){

        $result = $this->db->get_where('competitions', array('com_c_id' => $c_id))->result();
         
        if(!$result)
        {
           $this->response(false, 200);
        }
         
        else
        {
           foreach ($result as $row)
			{
				$competitions[] = [

			        "competition_id" => $row->com_id,
			        "competition_name" => $row->com_name
			    ];
			}
			$this->response($competitions, 200);
	        }
	        // $this->response($c_id, 200);
         }
    }



    function checkemail_get()
    {
        $email = $this->get('email');
        $result = $this->db->get_where('users', array('email' => $email))->row();
         
        if(!$result)
        {
            $this->response(false, 200);
        }
         
        else
        {
            $this->response(true, 200);
        }
         
    }

function verify_get()
    {
        $email = $this->get('email');
        $code = $this->get('code');
        $result = $this->db->get_where('users', array('email' => $email, 'verify' => $code))->row();
         
        if($result)

        {
           $query = $this->db->query("UPDATE users SET verify='8' WHERE verify='$code'");
           if ($query){
             $this->response(true, 200);

           }
        }
         
        else
        {
            $this->response(false, 200);
        }
         
    }

function login_post()
    {
        $email = $this->post('email');
        $password = hash('ripemd128', $this->post('password'));
        $result = $this->api_core_model->login($email, $password);
         
        if($result === FALSE)
        {
            $this->response(array('status' => 'failed'));
        }
         
        else
        {
            $this->response('success');
        }
         
    }

    
 }