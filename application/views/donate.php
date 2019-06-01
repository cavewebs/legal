<section class="probootstrap-xs-hero probootstrap-hero-colored">
      <div class="container">
        <div class="row">
          <div class="col-md-8 text-left probootstrap-hero-text probootstrap-animate" data-animate-effect="fadeIn">
            <p> I created CV Bot as a hobby project, the donation will help me cover the cost of server hosting and data. If you want to pay for access instead, you can disregard the donation and <a href="#" onclick="upgrade()"> pay for a subscription here instead </a>.  </p>
          </div>
        </div>
      </div>
    </section>
    <section class="probootstrap-section probootstrap-bg-white">
      <div class="container">
        <div class="row">
          <div class="col-sm-5 col-xs-12 probootstrap-animate" data-animate-effect="fadeIn">
            <article class="card">
              <form class="probootstrap-form" method="POST">
                <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
                <div class="form-group">
                  <label>Enter amount to donate (in &#163; GBP)</label>
                  <input id="bplan" type="integer" onchange="calcBplan()" max="100" min="1" placeholder="e.g 10.00" required class="form-control">
                   
                </div>
                <div class="form-group col-sm-12">
                  <button type="button" class="btn btn-primary" onClick="donateNow()">Donate Now</button>
                </div>
              </form>
                  <script>
                    const API_publicKey = "FLWPUBK-079cde3786eb9a192226829e36aae9dd-X";
                    function calcBplan(){
                    var plan = $("#bplan").val();                   
                      var metavalue = "Donation";
                     var  metaname = "Donation";
                     var planAmt = (plan * 469);
                    
                    return [planAmt, metaname, metavalue, plan];
                }

                      function donateNow() {
                       <?php  if ($user =='donor'){
                          $userId = '0';
                          $user_name = 'Donor';
                          $user_email = 'donor@cvbot.ml';
                        } else {
                          $userId = $user->user_id;
                          $user_name = $user->user_name;
                          $user_email = $user->user_email;

                        }
                        ?>
                          var plan = calcBplan()[0];
                          var tref = "<?=$trx_ref?>";
                          var user = <?=$userId?>;
                          var ddata = "";
                          // alert("plan: "+plan+" User: "+user+" tref: "+tref);
                          $.post("start_donation", {amt: plan , trx: tref, userid: user},
                              function(data)
                              {          
                                // alert(data);
                              if(data =='true'){ 
                                  var x = getpaidSetup({
                                  PBFPubKey: API_publicKey,
                                  customer_email: "<?=$user_email?>",
                                  $custname: "<?=$user_name?>", 
                                  amount: calcBplan()[0],
                                  customer_phone: "234099940409",
                                  currency: "NGN",
                                  payment_method: "both",
                                  txref: "<?=$trx_ref?>",
                                  payment_plan: calcBplan()[3],
                                  meta: [{
                                      metaname: calcBplan()[1],
                                      metavalue: calcBplan()[2]
                                  }],
                                  onclose: function() {},
                                  callback: function(response) {
                                      var txref = response.tx.txRef; // collect flwRef returned and pass to a           server page to complete status check.
                                      console.log("This is the response returned after a charge", response);
                                      if (
                                          response.tx.chargeResponseCode == "00" ||
                                          response.tx.chargeResponseCode == "0"
                                      ) {
                                          // redirect to a success page
                                        var txref = response.tx.txRef;
                                        var fwref = response.tx.flwRef;
                                        var planAmnt = calcBplan();
                                        window.location = "payment_done?txref="+txref;
                                      } else {
                                          // redirect to a failure page.
                                          // window.location = 'profile';
                                          alert("We could not charge your account")
                                      }
                                      x.close(); // use this to close the modal immediately after payment.
                                          }
                                      });
                                   } 
                                else{
                               $("#errorzz").html(data);  
                                alert(JSON.stringify(data));
                              }           
                         }); 
                      }
                  </script>
            </article> <!-- card.// -->

          </div> 
          </div>
        </div>
      </div>
    </section>
  