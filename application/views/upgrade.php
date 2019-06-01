<section class="probootstrap-xs-hero probootstrap-hero-colored">
      <div class="container">
        <div class="row">
          <div class="col-md-8 text-left probootstrap-hero-text probootstrap-animate" data-animate-effect="fadeIn">
            <p> Remove restrictions by subscribing to a plan below  </p>
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
                  <label>Please Choose Your Billing Plan</label>
                  <select id="bplan" onchange="calcBplan()" required class="form-control">
                    <option>Please Select Plan</option>
                    <!-- <option value="1"> Standard Trial @N10.99 </option> -->
                    <option value="2"> Basic @ &#163;1.00 </option>
                    <option value="3"> Standard  &#163;5.00 </option>
                    <option value="4180"> Premium Monthly @ &#163;5.00 </option>
                    <option value="4181"> Premium Yearly @ &#163;50.00</option>
                  </select>
                </div>
                <div class="form-group col-sm-12">
                  <button type="button" class="btn btn-primary" onClick="payWithRave()">Pay Now</button>
                </div>
              </form>
                  <script>
                    const API_publicKey = "FLWPUBK-079cde3786eb9a192226829e36aae9dd-X";

                    function calcBplan(){
                    var plan = $("#bplan").val();
                    var planAmt = 0;
                   
                    if (plan==='2'){
                      planAmt = 450.00;
                      metaname = "Forever";
                      metavalue = "Basic";
                    }
                    if (plan==='3'){
                      planAmt = 2400.00;
                      metavalue = "Forever";
                      metaname = "Standard";
                    }
                    if (plan==='4180'){
                      metavalue = "30 Days";
                      metaname = "Premium Monthly";
                      planAmt = 2400.00;
                    }
                    if (plan==='4181'){
                      metavalue = "365 Days";
                      metaname = "Premium Yearly";
                      planAmt = 24500.99;
                    }
                     if (plan==='1'){
                      metavalue = "Forever";
                      metaname = "Test";
                      planAmt = 10.99;
                    }
                    return [planAmt, metaname, metavalue, plan];
                }

                      function payWithRave() {
                          var plan = calcBplan()[0];
                          var tref = "<?=$trx_ref?>";
                          var user = <?=$user->user_id?>;
                          var ddata = "";
                          // alert("plan: "+plan+" User: "+user+" tref: "+tref);
                          $.post("start_payment", {amt: plan , trx: tref, userid: user},
                              function(data)
                              {          
                                // alert(data);
                              if(data =='true'){ 
                                  var x = getpaidSetup({
                                  PBFPubKey: API_publicKey,
                                  customer_email: "<?=$user->user_email?>",
                                  $custname: "<?=$user->user_name?>", 
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
  