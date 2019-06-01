
   <div class="user-content">
      <div class="user-panel">
        <div class="row">
          <div class="col-md-6">
            <div class="tile-item tile-primary">
                <div class="tile-bubbles"></div>
                <h6 class="tile-title">AVAILABLE BALANCE</h6>
                <h1 class="tile-info">NGN 0.00</h1>
            </div>
          </div><!-- .col -->
          <div class="col-md-6">
              <div class="tile-item tile-light">
                  <div class="tile-bubbles"></div>
                  <h6 class="tile-title">YOUR LEGIT SHOP CODE</h6>
                  <ul class="tile-info-list">
                      <li><span><?php echo $duser->u_key?></span></li>
                  </ul>
              </div>
          </div><!-- .col -->
        </div><!-- .row -->
                        <div class="info-card info-card-bordered">
                            <div class="row align-items-center">
                                <div class="col-sm-3">
                                    <div class="info-card-image">
                                        <img src="static-content/user/images/refernearn.png" alt="vector">
                                    </div>
                                    <div class="gaps-2x d-md-none"></div>
                                </div>
                                <div class="col-sm-9">
                                    <h4 style="text-transform: uppercase;">Earn from Refferals </h4>
                                    <p>Do you buy from a seller or vendor that is not registered with <?php echo $site_name ?>?</p>
                                    <p>You can earn N500 for every vendor your invite and gets verified</p>
                                    <p>Send this link to them to register today. <a href="https://<?php echo base_url()?>register?ref=<?php echo $duser->u_email?>"><?php echo base_url()?>register?ref=<?php echo $duser->u_email?></a>.</p>
                                </div>
                            </div>
                        </div><!-- .info-card -->
                        <div class="token-card">
                            <div class="token-info">
                                <span class="token-smartag">GET DISCOUNT</span>
                                <h2 class="token-bonus">30% <span> Early Birds Discount</span></h2>
                                <ul class="token-timeline">
                                    <li><span>START DATE</span>01 May 2019</li>
                                    <li><span>END DATE</span>05 June 2019</li>
                                </ul>
                            </div>
                            <div class="token-countdown">
                                <span class="token-countdown-title">THE DISCOUNT ENDS IN</span>
                                <div class="token-countdown-clock" data-date="2019/06/05"></div>
                            </div>
                        </div><!-- .token-card -->
                    </div><!-- .user-panel -->
                </div><!-- .user-content -->
            </div><!-- .d-flex -->
        </div><!-- .container -->
    </div>
    <!-- UserWraper End -->
    
