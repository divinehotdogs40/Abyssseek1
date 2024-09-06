<?php include_once "header2.php"; ?>

<style>
    .back-button {
        position: absolute;
        top: 50px;
        left: 10px;
        background-color: #37E8FC;
        color: #fff;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        border-radius: 5px;
    }

    .box {
        background-color: transparent;
        height: 15vw; /* Use viewport width (vw) for responsive height */
        border: 1px solid #ccc;
        padding: 2vw; /* Use viewport width (vw) for responsive padding */
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column; 
        justify-content: flex-end;
        overflow: hidden;
        position: relative;
        text-align: center;
        margin-bottom: 40px;
    }

    .box img {
        max-width: 100%;
        max-height: 100%;
        position: absolute;
        top: 30%;
        left: 50%;
        transform: translate(-50%, -50%);
        margin: auto;
    }

    .box strong {
        font-weight: bold; 
    }

    .custom-box {
        --border: 5px;    /* the border width */
        --slant: 0.7em;   /* control the slanted corners */
        --color: #37E8FC; /* the color */
        
        font-size: 25px;
        padding: 0.4em 1.2em;
        border: none;
        cursor: pointer;
        font-weight: bold;
        color: var(--color);
        background: 
            linear-gradient(to bottom left,var(--color)  50%,#0000 50.1%) top right,
            linear-gradient(to top   right,var(--color)  50%,#0000 50.1%) bottom left;
        background-size: calc(var(--slant) + 1.3*var(--border)) calc(var(--slant) + 1.3*var(--border));
        background-repeat: no-repeat;
        box-shadow:
            0 0 0 200px inset var(--s,#0000),
            0 0 0 var(--border) inset var(--color);
        clip-path: 
            polygon(0 0, calc(100% - var(--slant)) 0, 100% var(--slant),
                    100% 100%, var(--slant) 100%,0 calc(100% - var(--slant))
                    );
        transition: color var(--t,0.3s), background-size 0.3s;
    }
    .custom-box:hover {
        background-size: 100% 100%;
        color: #fff;
        --t: 0.2s 0.1s;
    }

    .first-word {
        font-family: 'YourCustomFont', fantasy; 
        font-size: 150%;
    }
</style>

<div class="container-fluid" style="margin-top: 50px;">
    <a href="index_in.php" class="back-button">Back</a>
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8 mx-auto" style="padding: 0;">
            <div class="custom-box">
                <span class="first-word"><h1 class="display-4 text-center">Contact Us!</h1></span>
                <br><br>
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box">
                                <img src="images/office.gif" alt="Image 1">
                                <span class="first-word"> Head Office<br></span>
                                Armed Forces of the Philippines
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box">
                                <img src="images/phone.gif" alt="Image 2">
                                <span class="first-word"> Phone Number<br></span>
                                Mobile Number:09063072611<br>
                                Telephone Number:106-23-93
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box">
                                <img src="images/fax.gif" alt="Image 3">
                                <span class="first-word">Fax<br></span>
                                1-243-765-0098
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box">
                                <img src="images/mail.gif" alt="Image 4">
                                <span class="first-word">Email<br></span>
                                afp@email.com
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>
</div>

<div class="container-fluid" style="margin-top: 50px;">
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
            <div class="custom-box text-center">
                <span class="first-word">How can we help you!</span>
                <br>
                <span class="first-word"><h2>Abyssseek Team</h2></span>
                <span class="first-word"><h2>divinehotdog@gmail.com</h2></span>
            </div>
        </div>
    </div>
</div>

<script>
    // Get all elements with the class 'first-word-bold'
    var elements = document.querySelectorAll('.first-word-bold');

    // Loop through each element and make the first word bold
    elements.forEach(function(element) {
        var words = element.textContent.split(' '); // Split the text into words
        words[0] = '<strong>' + words[0] + '</strong>'; // Wrap the first word in <strong> tags
        element.innerHTML = words.join(' '); // Join the words back together
    });
</script>

<?php include_once "footer.php"; ?>
