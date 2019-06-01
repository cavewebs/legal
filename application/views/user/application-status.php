 <div class="user-content">
                    <div class="user-panel">
                        <h2 class="user-panel-title">Seller Verification Status</h2>
                        <p> </p>
                        <div class="gaps-2x"></div>
                        <?php if($duser->vendor_status=='verified'){ ?>
                        <div class="status status-thank">
                            <div class="status-icon">
                                <em class="ti ti-files"></em>
                                <div class="status-icon-sm">
                                    <em class="ti ti-alarm-clock"></em>
                                </div>
                            </div>
                            <span class="status-text">Thank you! You have completed the process for your Identity verification.</span>
                            <p>We are still waiting for your guarantors verification. Once our team verifies your guarantors, you will be added to our trusted sellers database and notified by email. As a trusted seller, you have access to restricted services such as Loans and overdrafts, recommendation and features, delivery and logistic services, Cash On delivery among others.</p>
                        </div><!-- .status -->
                        <?php }?>

                        <?php if($duser->vendor_status=='processing'){ ?>
                        <div class="status status-process">
                            <div class="status-icon">
                                <em class="ti ti-files"></em>
                                <div class="status-icon-sm">
                                    <em class="ti ti-alarm-clock"></em>
                                </div>
                            </div>
                            <span class="status-text">Your Application is being processed for Varification.</span>
                            <p>We are still working on your identity verification. Once our team verifies your indentity, you will be added to our verified sellers database and notified by email.</p>
                        </div><!-- .status -->
                        <?php }?>                        

                        <?php if($duser->vendor_status=='canceled'){ ?>
                        <div class="status status-canceled">
                            <div class="status-icon">
                                <em class="ti ti-files"></em>
                                <div class="status-icon-sm">
                                    <em class="ti ti-close"></em>
                                </div>
                            </div>
                            <span class="status-text">Your application was rejected .</span>
                            <p>In our verification process, we found some information to be incorrect or missing. An email was sent explaining why your application was rejected. It will be great if you resubmit the form. If you face problems in submission please contact us by DM or email verification@legitshop.com.ng.</p>
                            <a href="seller-application" class="btn btn-primary">Resubmit</a>
                        </div><!-- .status -->
                        <?php }?>                        

                        <?php if($duser->vendor_status=='pending'){ ?>
                        <div class="status status-warnning">
                            <div class="status-icon">
                                <em class="ti ti-files"></em>
                                <div class="status-icon-sm">
                                    <em class="ti ti-alert"></em>
                                </div>
                            </div>
                            <span class="status-text">Application is Incomplete.</span>
                            <p>To complete the application process, you are required to pay the verification fee specified in your invoice. A copy of the invoice was sent to your email. If you did not see it in your inbox, check your junk/spam folders.</p>
                            Alternatively you can <a href="invoice" target="blank" class=""><b><strong>click here</strong></b> </a>to view and print the invoice
                        </div><!-- .status -->
                        <?php }?>
                        
                        <?php if($duser->vendor_status=='trusted'){ ?>
                        <div class="status status-verified">
                            <div class="status-icon">
                                <em class="ti ti-files"></em>
                                <div class="status-icon-sm">
                                    <em class="ti ti-check"></em>
                                </div>
                            </div>
                            <span class="status-text">Your Identity is fully Verified.</span>
                            <p>You have completed all the required verifications successfully <br class="d-none d-md-block">You are now fully trusted seller. We wish you more sales and the the best in your endeavours.</p>
                            <div class="gaps-2x"></div>
                        </div><!-- .status -->
                        <?php }?>

                    </div><!-- .user-kyc -->
                </div><!-- .user-content -->
            </div><!-- .d-flex -->
        </div><!-- .container -->
    </div>
    <!-- UserWraper End -->