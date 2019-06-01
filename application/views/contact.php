    <section class="probootstrap-xs-hero probootstrap-hero-colored">
      <div class="container">
        <div class="row">
          <div class="col-md-8 text-left probootstrap-hero-text probootstrap-animate" data-animate-effect="fadeIn">
            <p>  Is there anything you would like to say? I'd love to talk with you </p>
          </div>
        </div>
      </div>
    </section>
    <section class="probootstrap-section probootstrap-bg-white">
      <div class="container">
        <div class="row">
          <div id="Form_update"></div>
          <div class="col-md-5 probootstrap-animate" id="formEl" data-animate-effect="fadeIn">
            <div id="formErr"></div>
            <h2>Drop me a line</h2>
            <form action="#" method="post" id="confrm" class="probootstrap-form">
              <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name">
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email">
              </div>
              <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject">
              </div>
              <div class="form-group">
                <label for="contactmsg">Message</label>
                <textarea cols="20" rows="10" class="form-control" id="contactmsg" name="contactmsg"></textarea>
              </div>
              <div class="form-group">
                <button class="btn btn-primary btn-lg" id="submit" name="submit" onclick="submitContact()" >Submit Form </button>
              </div>
            </form>
            <div id="formEl1"></div>
          </div>
          <div class="col-md-6 col-md-push-1 probootstrap-animate" data-animate-effect="fadeIn">
            <h2>Get in touch</h2>
            <p>If you want to talk to someone through a rather unconventional means, you can reach Tim via:</p>
            
            <h4>Twitter</h4>
            <ul class="probootstrap-contact-info">
              <li><i class="icon-twitter"></i> <span><a href="https://Twitter.com/timchosen" target="_blank">@timchosen</a></span></li>
            </ul>
            
            <h4>LinkedIn</h4>
            <ul class="probootstrap-contact-info">
              <li><i class="icon-linkedin"></i> <span><a href="https://LinkedIn.com/in/timchosen" target="_blank">@timchosen</a></span></li>
            </ul>
          </div>
        </div>
      </div>
    </section>
  <script type="text/javascript">
    function submitContact(){
      $("#confrm").submit(function(event){
      name = $("#name").val();
      email = $("#email").val();
      subject = $("#subject").val();
      contactmsg = $("#contactmsg").val();
      $.post('submit_contact',
        {contactmsg: contactmsg, name: name, email: email, subject: subject})

        .done(function(data){
          if(data=='true'){
          $('#formEl').hide();
          $('#Form_update').html('<div class="col-md-12" ><p class="alert alert-success"> Thanks '+name+', I have recieved your message and will be in touch soon</p></div>');
        }
          else {
            $('#formErr').html('<div class="col-md-12 alert alert-danger" >'+data+'</div>'); 

            $('#formEl1').html('<div class="col-md-12 alert alert-danger" ><p>There are errors on the form</p></div>');        

          }
        
        })

      .fail(function(data){
            $('<div class="col-md-12 alert alert-warning" ><p>There are errors with the form</p></div>').appendTo($('#formEl'));        

          
      });
            return false;

    });

    }
  </script>