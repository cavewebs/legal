<title><?php echo $title ?></title>
    <?php 
    foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
                   <div class="user-content">
                      <div class="user-panel">
                        <div class="row">
                          <div class="col-md-12">
                          <?php echo $output; ?>
                        </div>
                        </div><!-- .row -->
                       
          
                    </div><!-- .user-panel -->
                </div><!-- .user-content -->
            </div><!-- .d-flex -->
        </div><!-- .container -->
    </div>
    <!-- UserWraper End -->
