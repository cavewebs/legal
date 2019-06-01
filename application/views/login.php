        <main class="nk-pages nk-pages-centered bg-theme">
            <div class="ath-container">
                <div class="ath-header text-center">
                </div>
                <div class="ath-body">
                    <h5 class="ath-heading title">Sign in <small class="tc-default">with your <?php echo $site_name;?> Account</small></h5>
                    
                    <form class="" action="#">
                            <div class="form-results"></div>
                        <div class="field-item">
                            <div class="field-wrap">
                                <input type="email" class="input-bordered" id="email" name="email" placeholder="Your Email">
                            </div>
                        </div>
                        <div class="field-item">
                            <div class="field-wrap">
                                <input type="password" class="input-bordered" id="upswd" name="upswd" placeholder="Password">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pdb-r">
                            <div class="field-item pb-0">
                                <input type="hidden" name="username" value="">
                                <input class="input-checkbox" id="remember-me-2" type="checkbox">
                                <label for="remember-me-2">Remember Me</label>
                            </div>
                            <div class="forget-link fz-6">
                                <a href="reset-password">Forgot password?</a>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block btn-md" id="signin">Sign In</button>
                    </form>
                <div class="ath-note text-center tc-light">
                    Don’t have an account? <a href="register"> <strong>Sign up here</strong></a>
                </div>
            </div>
        </main>

        