 <div class="user-content">
                    <div class="user-panel">
                        <h2 class="user-panel-title">Earn with Referral</h2>
                        <h5>Do you buy from a seller or vendor that is not registered with Legit Shop?</h5>
                        <p><strong>You can earn N500 for every vendor your invite and gets verified. </strong></p>
                        <p>Share your referral link with them to register today.</p>
                        <h6>Your unique referral link</h6>
                        <div class="refferal-info">
                            <span class="refferal-copy-feedback copy-feedback"></span>
                            <em class="fas fa-link"></em>
                            <input type="text" class="refferal-address" value="<?php echo base_url()?>register?ref=<?php echo $duser->u_email?>" disabled>
                            <button class="refferal-copy copy-clipboard" data-clipboard-text="<?php echo base_url()?>register?ref=<?php echo $duser->u_email?>"><em class="ti ti-files"></em></button>
                        </div><!-- .refferal-info --> <!-- @updated on v1.0.1 -->
                        <div class="gaps-2x"></div>
                        <ul class="share-links">
                            <li>Share with : </li>
                            <li><a href="#"><em class="fas fa-at"></em></a></li>
                            <li><a href="#"><em class="fab fa-twitter"></em></a></li>
                            <li><a href="#"><em class="fab fa-facebook-f"></em></a></li>
                            <li><a href="#"><em class="fab fa-google"></em></a></li>
                            <li><a href="#"><em class="fab fa-linkedin-in"></em></a></li>
                            <li><a href="#"><em class="fab fa-whatsapp"></em></a></li>
                            <li><a href="#"><em class="fab fa-viber"></em></a></li>
                            <li><a href="#"><em class="fab fa-vk"></em></a></li>
                        </ul><!-- .share-links -->
                        <div class="gaps-1x"></div>
                        <h4>Refferal Statistics</h4>
                        <div class="refferal-statistics">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="refferal-statistics-item">
                                        <h6>Total Count</h6>
                                        <span><?php echo $tot?></span>
                                    </div>
                                </div><!-- .col -->
                                <div class="col-md-4">
                                    <div class="refferal-statistics-item">
                                        <h6>Verified</h6>
                                        <span>0</span>
                                    </div>
                                </div><!-- .col -->
                                <div class="col-md-4">
                                    <div class="refferal-statistics-item">
                                        <h6>Referral Earnings</h6>
                                        <span>N0.00</span>
                                    </div>
                                </div><!-- .col -->
                            </div><!-- .row -->
                        </div><!-- .refferal-statistics -->
                        <h4>Refferal Lists</h4>
                        <table class="data-table refferal-table">
                            <thead>
                                <tr>
                                    <th class="refferal-name"><span>Referee</span></th>
                                    <th class="refferal-tokens"><span>Vendor Status</span></th>
                                    <th class="refferal-bonus"><span>Verification</span></th>
                                    <th class="refferal-date"><span>Date</span></th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php 
                              if($refs){
                              foreach ($refs as $ref) {?>
                                
                                <tr>
                                    <td class="refferal-name"><?php echo $ref->u_name?></td>
                                    <td class="refferal-tokens"><?php if($ref->is_vendor==1) {echo 'Vendor';} else {echo 'User';}?></td>
                                    <td class="refferal-bonus"><?php echo $ref->vendor_status?></td>
                                    <td class="refferal-date"><?php echo date('M d, Y', strtotime($ref->u_date_created)) ?></td>
                                </tr>
                                <?php } }?>
                            </tbody>
                        </table>
                        
                    </div><!-- .user-panel -->
                </div><!-- .user-content -->
            </div><!-- .d-flex -->
        </div><!-- .container -->
    </div>
    <!-- UserWraper End -->