 <div class="user-content">
                    <div class="user-panel">
                        <h2 class="user-panel-title">Loan Application</h2>
                        <p> </p>
                        <div class="gaps-2x"></div>                    

                        <div class="status status-canceled">
                            <div class="status-icon">
                                <em class="ti ti-files"></em>
                                <div class="status-icon-sm">
                                    <em class="ti ti-close"></em>
                                </div>
                            </div>
                            <span class="status-text">You do not qualify for a loan</span>
                            <p>Only fully verified sellers can access the collateral free loan. The loan is intended to be used to expand the business by paying for stock, raw materials or new machinery</p>
                            <?php if($duser->is_vendor==0){?>
                            <a href="seller-application" class="btn btn-primary">Apply for verification</a>
                            <?php } else { ?>
                            <a href="application-status" class="btn btn-primary">Check Application Status</a>

                            <?php }?>
                        </div><!-- .status -->
                        
                    </div><!-- .user-kyc -->
                </div><!-- .user-content -->
            </div><!-- .d-flex -->
        </div><!-- .container -->
    </div>
    <!-- UserWraper End -->