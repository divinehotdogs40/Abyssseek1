<?php include_once "header.php"; ?>

<style>
.button {
 cursor: pointer;
 --c: #0ea5e9;
 padding: 12px 28px;
 margin: 1em auto; 
 position: relative; 
 min-width: 12em;
 background: transparent;
 font-size: 12px;
 font-weight: bold;
 color: #ffffff;
 text-align: center;
 text-transform: uppercase;
 font-family: sans-serif;
 letter-spacing: 0.1em;
 border: 2px solid #45acab;
 border-radius: 8px;
 overflow: hidden;
 z-index: 1;
 transition: 0.5s;
}

.button span {
  position: absolute;
  width: 25%;
  height: 100%;
  background-color: #45acab !important;
  transform: translateY(150%);
  border-radius: 50%;
  left: calc((var(--n) - 1) * 25%);
  transition: 0.5s;
  transition-delay: calc((var(--n) - 1) * 0.1s);
  z-index: -1;
}

.button:hover {
  color: black;
}

.button:hover span {
  transform: translateY(0) scale(2);
}

.button span:nth-child(1) {
  --n: 1;
}

.button span:nth-child(2) {
  --n: 2;
}

.button span:nth-child(3) {
  --n: 3;
}

.button span:nth-child(4) {
  --n: 4;
}

.in{
  font-size: 23px;
}

.display-4{
  border-bottom: 1px solid #45acab;
}


.button2 {
  padding: 1.5em 5em;
  font-size: 16px;
  text-transform: uppercase;
  letter-spacing: 2.5px;
  font-weight: 500;
  color: #000;
  background-color: #45acab;
  border: none;
  border-radius: 45px;
  box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease 0s;
  cursor: pointer;
  outline: none;
}

.button2:hover {
  background-color: #41C9E2;
  box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
  color: #fff;
  transform: translateY(-7px);
}

.button2:active {
  transform: translateY(-1px);
}

</style>


<div class="container-fluid" style="margin-top:50px">
    <div class="row">
        <div class="col-sm-3 text-justify text-center">
            <h1 class="display-4">About AbyssSeek</h1> <br>
            <p class="in"><strong>Abyssseek.com</strong> serves as a platform where developers build,
launch, and share tools for web crawling, scraping, social
media finding, and lookup in user-friendly formats for Armed
Forces of the Philippines (AFP) personnel and civilian
employees.</p>
            <div class="text-center" style="padding-top:115px">
                <button class="button" role="button" ><a href="about.php" style="text-decoration:none; color: #ffffff">Read More.... <span></span><span></span><span></span><span></span></button></a>
            </div>
        </div>
        <div class="col-sm-6 text-center">
        <div class="logo-container">
            <img src="images/logo2.png" class="img-fluid" alt="Logo">
</div>
    </div>
        <div class="col-sm-3 text-align: left text-left">
            <h1 class="display-4">Getting Started: New Account Setup</h1> 
              <br><strong>Step 1: Sign Up for an Account</strong>
              <br>Access the form to request an account from the admin. <br> <br> 
              <strong>Step 2: Account Approval</strong> Email Confirmation: After submitting the form, wait for an email from the
admin confirming the approval of your account. This email will typically
include further instructions or credentials needed to access your new
account.</p><br>
<strong>Step 3: Access Your Account</strong>
              <br>Sign In: Once your account has been approved and you have received your
credentials, you can sign in to start using your account. <br> <br>
            <div class="text-center">
                <button class="button" role="button"><a href="guide.php" style="text-decoration:none; color: #ffffff">Read More.... <span></span><span></span><span></span><span></span></button></a>
            </div>
        </div>
    </div>
</div>



<?php include_once "footer.php"; ?>
