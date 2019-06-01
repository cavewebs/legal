<!doctype html>
<html lang="en">
  <head style="height: 100%">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="With CVBot, you can create professional resumes or CVs online for free, in minutes. Simple fun chat that will generate beautiful and professional resumes/CVs!" />
    <meta name="keywords" content="CVBot to create CV/Resume, CVBot, CV Bot, CV Robot, CV Droid, cv maker, resume maker,  Chat and create your Resume/CV, curriculum vitae maker, cv generator, cv creator, resume creator, resume generator, make cv online, make resume online, cv builder, resume builder, cv automatic, automatically create resume,free online curriculum vitae maker, free cv maker, free resume make, free resume builder, pdf resume, pdf cv, create pdf resume online, Free cv, Easy resume, pdf resume generator, CV Bot, CV Bot" />

    <!-- Bootstrap CSS -->
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url()?>static-content/imgs/cvdroid.png" />
    <link rel="stylesheet" href="<?php echo base_url()?>static-content/css/bootstrap.min.css" >
    <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.min.css'>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>static-content/css/main.css">

    <title>CV Bot | Chat and create your Resume/CV!</title>
  </head>
  <body>
    <div class="jumbotron m-0 p-0 bg-transparent">
        <div class="row m-0 p-0 position-relative">
          <div class="col-12 p-0 m-0 position-absolute" style="right: 0px;">
            <div class="card border-0 rounded" id="frame">

              <div class="card-header p-1 bg-light border border-top-0 border-left-0 border-right-0" style="color: rgba(96, 125, 139,1.0); width: 100%; height: 50px; position: fixed; top:0; z-index: 10">

                <img class="rounded float-left" style="width: 50px; height: 50px;" src="<?php echo base_url()?>static-content/imgs/cvdroid.png" />
                
                <h6 class="float-left" style="margin: 3px; margin-left: 10px;">
                  <a href="<?php echo base_url()?>" > CV Bot</a> <br>
                  <small> Lets's Chat and I will Make you a CV</small>
                </h6>
                <h6 class="float-right" style="top:8px; position: absolute; float: right; right:55px">
                    <a href="#" onclick="about()" class="b-menu"> About</a> 
                    <a href="#" onclick="pricing()" class="b-menu"> Pricing</a> 
                    <a href="#" onclick="contact()" class="b-menu"> Contact</a> 
                    <!-- <i class="fa fa-send text-primary" title="Quick CV Bot" aria-hidden="true"></i>              -->
                </h6>

                <div class="dropdown show">

                  <a id="dropdownMenuLink" data-toggle="dropdown" class="btn btn-sm float-right text-primary" role="button"><h5><i class="fa fa-reorder" title="More Menu" aria-hidden="true"></i>&nbsp;</h5>
                  </a>

                  <div class="dropdown-menu dropdown-menu-right border p-0" aria-labelledby="dropdownMenuLink">
                  
                    <a class="dropdown-item p-2 text-primary" href="#" onclick="profile()"> 
                      <i class="fa fa-user m-1" aria-hidden="true"></i> Profile 
                    </a>
                    <hr class="my-1">
                    <a class="dropdown-item p-2 text-primary" href="#" onclick="logout()"> <i class="fa fa-sign-out m-1" aria-hidden="true"></i> Logout 
                    </a>

                  </div>
                </div>
                
              </div>
                
                <div class="row">
                  <div class="col-md-5">
                      <div class="card messages bg-sohbet border-0 m-0 p-0">
                        <div id="sohbet" class="card border-0 m-0 p-0 position-relative bg-transparent" style="overflow-y: auto; height: 85vh">
                          <?php if($this->session->home_message){ 
                             $nextUrl =$this->session->home_message['nextUrl'];
                            foreach ($this->session->home_message['feedback'] as $feedback) { ?>
                           <div class="sent p-2 m-0 position-relative" data-is="Sophia">
                        
                            <p class="float-left sohbet2"> <?php echo $feedback ?> </p>
                            
                          </div>
                          <?php  } 
                            }
                         
                          if(!$this->session->loggedin){ 
                            $nextUrl = "signin"; ?>

                          <div class="sent p-2 m-0 position-relative" data-is="Sophia">
                        
                            <p class="float-left sohbet2"> Welcome to CV Bot, I'm Sophie; I am here to help you create your awesome CV/Resume! </p>
                            
                          </div>

                          <div class="sent p-2 m-0 position-relative" data-is="Sophia">
                          
                            <p class="float-left sohbet2"> I will ask you a few questions, just reply me correctly and your CV/Resume will be ready as soon as we are done. Please avoid using short-hand as I will not edit or correct anything you send. </p>
                            
                          </div>


                          <div class="sent p-2 m-0 position-relative" data-is="Sophia">
                          
                            <p class="float-left sohbet2"> Please enter your email address let me check if I have your records.</p>
                            
                          </div>
                        <?php  } else {
                        $nextUrl ="create_new_cv" ?>
                      
                        
                        
                        <div class="sent p-2 m-0 position-relative" data-is="Sophia - <?php echo date('h:iA')?>">
                          <p class="float-left sohbet2">Welcome back <?=$_SESSION['uname']?>, give me a minute let me pull up your CVs </p>
                          
                        </div>
                        <?php if($cvs->num_rows()>0){ ?>

                        <div class="sent p-2 m-0 position-relative" data-is="Sophia - <?php echo date('h:iA')?>">
                        
                          <p class="float-left sohbet2"> 👍 </p>
                          
                        </div>
                        <div class="sent p-2 m-0 position-relative" data-is="Sophia - <?php echo date('h:iA')?>">
                        
                          <p class="float-left sohbet2"> I found <?=$cvs->num_rows()?>  CVs in your account </p>
                          
                        </div>


                        <div class="sent p-2 m-0 position-relative" data-is="Sophia - <?php echo date('h:iA')?>">
                        
                          <p class="float-left sohbet2"> Reply with 'Edit' (without quotes) if you want to edit a CV.</p>
                          
                        </div>

                        <div class="sent p-2 m-0 position-relative" data-is="Sophia - <?php echo date('h:iA')?>">
                        
                          <p class="float-left sohbet2 "> Reply with 'New' (without quotes) if you want to create a new CV</p>
                          
                        </div>

                         <div class="sent p-2 m-0 position-relative" data-is="Sophia - <?php echo date('h:iA')?>">
                        
                          <p class="float-left sohbet2 "> If you want to preview/download your existing CVs reply with 'preview' </p>
                          
                        </div>
                      <?php } else { ?>

                        <div class="sent p-2 m-0 position-relative" data-is="Sophia  - <?php echo date('h:iA')?>">
                        
                          <p class="float-left sohbet2"> 🙆  </p>
                          
                        </div>
                        <div class="sent p-2 m-0 position-relative" data-is="Sophia  - <?php echo date('h:iA')?>">
                        
                          <p class="float-left sohbet2"> I did not find any CV in your account </p>
                          
                        </div>

                        <div class="sent p-2 m-0 position-relative" data-is="Sophia - <?php echo date('h:iA')?>">
                        
                          <p class="float-left sohbet2"> Reply with 'New' (without quotes) if you want to create a new CV</p>
                        
                        </div>
                        <?php } ?>

                      <?php }
                      ?>
                      </div>

                      <div class="w-100 card-footer p-0 bg-light border border-bottom-0 border-left-0 border-right-0">
                      
                        <form class="m-0 p-0" action="" method="POST" autocomplete="off" id="msgForm">
                  
                          <div class="row m-0 p-0">
                          <div class="col-9 m-0 p-1">
                          
                            <input id="message" class="mw-100 border rounded form-control" type="text" name="message" title="Type a message..." placeholder="Type a message..." >
                            <input type="hidden" value="<?php echo $nextUrl ?>" id="nextUrl">
                            
                          </div>
                          <div class="col-3 m-0 p-1">
                          
                            <button class="submit btn btn-outline-secondary rounded border w-100" title="Submit!" style="padding-right: 16px;"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                                
                          </div>
                          </div>
                      
                      </form>
                        
                      </div>

                    </div>
                  </div>
                  <div class="col-md-7" style="padding-top: 0px" >
                    <iframe id="prevFrame" src="intro" width="100%" height="100%" frameborder="0"></iframe>
                  </div>

                </div>
            </div>
          
          </div>
        </div>
    </div>

    <script src="<?php echo base_url()?>static-content/js/jquery.min.js"></script>
    <script src="<?php echo base_url()?>static-content/js/popper.min.js" ></script>
    <script src="<?php echo base_url()?>static-content/js/bootstrap.min.js" ></script>
    <script src="<?php echo base_url()?>static-content/js/puymodals.js"></script>   
    <script src="<?php echo base_url()?>static-content/js/timModals.js"></script>   
      
    <script type="text/javascript">
    function newMessage() {
      message = $("#message").val();
      nextUrl = $("#nextUrl").val();
      if (nextUrl=='login_now'){
        var display_msg = "Password Hidden ******";
      } else {
        var display_msg = message;
      }
      wait = "<img src='<?php base_url()?>static-content/imgs/loader.gif'>";
      if($.trim(message) == '') {
        return false;
      }

      $('<div class="replies p-2 m-0 position-relative" data-is="You - '+getChatTime()+'"><p class="float-right">' + display_msg + ' </p></div>').appendTo($('#sohbet'));
      $('<div id="ticker" class="p-2 m-0" id="ticker" data-is="You - '+getChatTime()+'"><p class="float-left">   Sophia is typing '+wait+' </p></div>').appendTo($('#sohbet'));
        $(".messages").animate({ scrollTop: $("#sohbet")[0].scrollHeight }, "slow"); 
      $('#message').val(null);
        // $("#ticker").show();
      // $("#sohbet").animate({ scrollTop: $(document).height() }, "fast");
      // $("#msgForm").on('submit',(function(e) {

       $.post(nextUrl,
            {message: message})

            .done(function(data){
            // console.log(data);
                  // alert(data);
                  var dat = JSON.parse(data)
                  // alert(dat.nextUrl);
                  var feedback = dat.feedback;           
            for (var i in feedback) {
                 $('<div class="sent p-2 m-0 position-relative" data-is="Sophia - '+getChatTime()+'"><p class="float-left sohbet2">' + feedback[i] + ' </p></div>').appendTo($('#sohbet'));
            };
             // if(dat.preview_link !==null){
             //      var did1 = "preview_cv/"+dat.preview_link;
             //      $('#prevWindow').attr("href", did1);  
             //     }
              $('#nextUrl').val(dat.nextUrl);
              $("#sohbet").animate({ scrollTop: $("#sohbet")[0].scrollHeight }, "slow"); 
              $("#ticker").remove();
          })

          .fail(function(data){
              $("#ticker").remove();
                  // alert(data);
                 $('<div class="sent p-2 m-0 position-relative" data-is="Sophia - '+getChatTime()+'"><p class="float-left sohbet2"> I am having some issues connecting to my server, please ensure you have an active connection </p></div>').appendTo($('#sohbet'));
              $("#sohbet").animate({ scrollTop: $("#sohbet")[0].scrollHeight }, "slow"); 
                });

    };

    $('.submit').click(function() {
          // $("#loader").show();
      newMessage();
      return false;
    });

    $(window).on('keydown', function(e) {
      if (e.which == 13) {
            // $("#loader").show();
        newMessage();
        return false;
      }
    });
    function getChatTime(){
    var date = new Date();
      var hours = date.getHours();
      var minutes = date.getMinutes();
      var ampm = hours >= 12 ? 'PM' :'AM';
      hours = hours % 12;
      hours = hours ? hours : 12; // the hour '0' should be '12'
      minutes = minutes < 10 ? '0'+minutes : minutes;
      var strTime = hours + ':' + minutes + ' ' + ampm;
      return strTime;
    }

    $('.frmit').click(function() {
      var did = $(this).attr("href"); // Get current url
      alert(did);

    $('#prevWindow').attr("src", did);  
      // document.getElementById("prevWindow").src = did;

    });
    function pricing(){
      timModal({title:'Pricing',loadPage:'pricing'});
    }  
    function about(){
      timModal({title:'About CV Bot',loadPage:'about'});
    }  
    function contact(){
      timModal({title:'Contact Me',loadPage:'contact'});
    }   
    function donate(){
      timModal({title:'Donate to CV Bot',loadPage:'donate'});
    }
     function upgrade(){
      timModal({title:'Upgrade Your Account',loadPage:'upgrade'});
    }

    function profile(){
      timModal({title:'Your Profile On CV Bot',loadPage:'dashboard'});
    }
    function logout(){
      message = $("#message").val("logout");
      nextUrl = $("#nextUrl").val("logout");
      newMessage();
    }

</script>
<!-- <div id='loader' style='top: 30%; width: 100%; min-height: 200px; z-index: 1000000'> Please wait
  <img src='<?php base_url()?>static-content/imgs/loader.gif' width='132px' height='32px'>
</div> -->

  </body>

</html>