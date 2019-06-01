<!DOCTYPE html>
<html lang="zxx" class="js">
<head>
  <meta charset="utf-8">
  <meta name="author" content="Softnio">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="ICO Crypto is a modern and elegant landing page, created for ICO Agencies and digital crypto currency investment website.">
  <!-- Fav Icon  -->
  <link rel="shortcut icon" href="images/favicon.png">
  <!-- Site Title  -->
  <title>Admin</title>
  <!-- Vendor Bundle CSS -->
  <link rel="stylesheet" href="<?php echo base_url()?>static-content/user/css/vendor.bundle.css?ver=101">
  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="<?php echo base_url()?>static-content/user/css/style.css?ver=101">
  
</head>

<body class="user-ath">
   
   <div class="user-ath-page">
       <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8  text-center">
                    <div class="user-ath-logo">
                        <a href="#">
                            <img src="<?php echo base_url()?>static-content/user/images/logo.png"  srcset="images/logo.png 2x" alt="icon">
                        </a>
                    </div>
                    <div class="user-ath-box">
                        <h4>Login to Your Account</h4>
                        <form action="admin/index" method="post" class="user-ath-form">

                            <?php echo validation_errors('<div class="alert alert-danger">','</div>'); ?>
        <?php if(!empty($this->session->flashdata('error'))): ?>
        <div class="alert alert-danger"><div class="note note-lg note-no-icon note-danger">
                                <p>Your email and password is invalid.</p>
                             <?php echo $this->session->flashdata('error'); ?> </div></div>
        <?php endif; ?>



                            <div class="input-item">
                                <input type="text" placeholder="Your Email" name="email" class="input-bordered">
                            </div>
                            <div class="input-item">
                                <input type="password" placeholder="Password" name="password" class="input-bordered">
                            </div>
                            <div class="gaps"></div>
                            <div class="d-flex justify-content-between align-items-center">
                                <button class="btn btn-primary">Log in</button>
                                <a href="recovery.html" class="simple-link">Forgot password?</a>
                            </div>
                        </form>
                        <div class="or-saparator"><span>or</span></div>
                        <span class="small-heading">Log in with:</span>
                        
                        <ul class="btn-grp guttar-30px">
                            <li><a href="#" class="btn btn-sm btn-icon btn-facebook"><em class="fab fa-facebook-f"></em>Facebook</a></li>
                            <li><a href="#" class="btn btn-sm btn-icon btn-google"><em class="fab fa-google"></em>Google</a></li>
                        </ul>
                    </div>
                    <div class="gaps-2x"></div>
                    <div class="form-note">
                        Not a member? <a href="signup.html">Sign up now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
  <!-- JavaScript (include all script here) -->
  <script src="<?php echo base_url()?>static-content/user/js/jquery.bundle.js?ver=101"></script>
  <script src="<?php echo base_url()?>static-content/user/js/script.js?ver=101"></script>
</body>
</html>
