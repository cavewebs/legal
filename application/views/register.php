       <?php
       $ref=''; 
       if(isset($_GET['ref'])){
        $ref = $_GET['ref'];
       } ?>
        <main class="nk-pages tc-light">
          
            <div class="container">   
            	  <main class="nk-pages nk-pages-centered bg-theme">
            <div class="ath-container">
                <div class="ath-header text-center">
                </div>
                <div class="ath-body">
                    <h5 class="ath-heading title">Sign Up <small class="tc-default">Create New <?php echo $site_name;?> Account</small></h5>
                    <form class="nk-form-submit" action="form/register" method="post">
                    <div class="form-results"></div>

                        <div class="field-item">
                            <div class="field-wrap">
                                <input type="text" class="input-bordered" placeholder="Your Name" name="name" required="">
                            </div>
                        </div>
                        <div class="field-item">
                            <div class="field-wrap">
                                <input type="email" class="input-bordered" placeholder="Your Email" name="email" required="">
                            </div>
                        </div>
                        <div class="field-item">
                            <div class="field-wrap">
                                Who Referred you
                                <input type="email" class="input-bordered" placeholder="Referrer Email" value="<?php echo $ref?>" name="referrer" >
                            </div>
                        </div>
                        <div class="field-item">
                            <div class="field-wrap">
                                <input type="password" class="input-bordered" placeholder="Password" name="upswd" required="">
                            </div>
                        </div>
                        <div class="field-item">
                            <div class="field-wrap">
                                <input type="password" class="input-bordered" placeholder="Repeat Password" name="ucpswd" required="">
                            </div>
                        </div>
                        <div class="field-item">
                          <div class="field-wrap">
                          	<input type="hidden" name="username">
                            <label for="agree-term-2"><small>By clicking Signup, you agree to <?php echo $site_name?>'s <a href="#">Privacy Policy</a> &amp; <a href="#">Terms</a></small></label>
                         </div>
                        </div>
                        <button class="btn btn-primary btn-block btn-md">Sign Up</button>
	                        
                    </form>
                    
                <div class="ath-note text-center tc-light">
                    Already have an account? <a href="login"> <strong>Sign in here</strong></a>
                </div>
            </div>
        </main>

            </div>
        </main>