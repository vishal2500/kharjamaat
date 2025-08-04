<style>
    .carousel-back {
        height: 256px;
        width: 100%;
        margin: 0 auto;
        padding: 0;
        background-position: top center;
        background-repeat: no-repeat;
        background-size: cover;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        background-image: url('<?php echo base_url('assets/home_banner.jpg') ?>');
    }

    .carousel-logo {
        border-radius: 100px;
        height: 200px;
        opacity: 1;
        margin-top: 30px;
    }

    .home-content .title-base {
        text-align: left;
        position: relative;
        flex-direction: column-reverse;
        display: flex;
        margin-top: 0;
    }
    /* .round {
        position: absolute;
        right: 0;
        top: 0;
    }
    .round-btn {
        background-color: #f3b007;
        font-weight: 200;
        radius: 100px;
        border: none;   
    } */
    .home-content .title-base h2 {
        line-height: 35px;
        margin: 0 0 10px 0;
    }

    .home-content .title-base hr {
        border: none;
        background-color: #f3b007;
        width: 100%;
        height: 5px;
        margin: 0 auto 20px 0;
        left: 0;
        opacity: 1;
    }

    .home-content p.welcome-text {
        font-size: 14px;
        line-height: 23px;
        letter-spacing: 0.5px;
        color: #f39f07;
    }

    .boxed.golden {
        background-color: #f3b007;
    }

    .container img {
        border: 4px solid white;
    }
.round {
    position: fixed; /* Use fixed to position relative to the viewport */
    bottom: 20px;   /* Distance from the bottom of the page */
    right: 20px;    /* Distance from the right of the page */
    z-index: 1000;  /* Ensure it stays above other content */
}

.round-btn {
    background-color: #f3b007;
    font-weight: bold;
    border-radius: 100px;
    border: none;
    width: 40px;
    height: 40px;
    font-size: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center; /* Center the '?' inside the button */
}   
    /* Modal Overlay */
    .modal {
        display: none;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100vh; /* Use viewport height to constrain modal to screen */
        background-color: rgba(0,0,0,0.5);
        overflow: hidden; /* Prevent scrollbar by disabling overflow */
    }

    /* Modal Box */
    .modal-content {
        background-color: #fff3cd;
        margin: 10% auto;
        padding: 25px;
        border-radius: 12px;
        width: 90%;
        max-width: 410px;
        text-align: center;
        position: relative;
        box-shadow: 0 0 20px rgba(0,0,0,0.3);
        max-height: 82vh; /* Limit modal content height to 80% of viewport */
        overflow-y: auto; /* Allow scrolling within modal if content overflows */
    }

    /* Modal Title */
    .modal-content h3 {
        margin-bottom: 20px;
        font-size: 20px;
        font-weight: 600;
    }

    /* Button Group */
    .button-group {
        display: flex;
        flex-wrap: wrap; /* Allow buttons to wrap if needed */
        justify-content: center;
        gap: 10px;
    }

    /* Buttons */
    .btn {
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        justify-content: center;
    }
    .btn span {
        font-size: 18px;
    }

    /* Button Colors */
    .btn.blue { background-color: #007bff; }
    .btn.yellow { background-color: #ffc107; color: #000; }
    .btn.green { background-color: #28a745; }
    .btn.red { background-color: #dc3545; }

    /* Close Button (top-right X) */
    .close-btn {
        position: absolute;
        right: 12px;
        top: 10px;
        font-size: 22px;
        font-weight: bold;
        color: #333;
        cursor: pointer;
    }
</style>


<div class="margintopcontainer">

    <div class="carousel-back">
        <div class="container">
            <img src="https://www.seattlejamaat.org/static/assets/images/Maula.tus.67235089a22e.jpg"
                class="carousel-logo" />
        </div>
    </div>
    <?php
    // echo"<pre>";print_r($_SESSION);exit;
    ?>
    <div class="container content mt-5">
        <div class="home-content">
            <div class="row">
                <div class="col-12">
                    <div class="title-base text-left">
                        <hr />
                        <h2>Anjuman-e-Saifee Khar</h2>
                        <p>Welcome to</p>
                    </div>
                    <p class="welcome-text">
                        Welcome to Anjuman-e-Saifee Jamaat, Khar.
                    </p>
                    <p>
                        We are the members of Dawoodi Bohra Community - Fatimid Shia
                        followers of His Holiness Syedna Aali Qadr Muffaddal Saifuddin
                        (TUS) residing in the Khar.
                    </p>
                    <p>
                        The rich history of the Dawoodi Bohra community can be traced
                        back to the 11th century during the Fatimid Caliphate. Islam,
                        with its strict interpretation, is emphasized, particularly
                        focusing on education and observance. Customs like specific
                        prayers and rituals are passed down through generations. The
                        religious life of the community is centered around the highest
                        spiritual leader, the Dai al-Mutlaq. Strong community bonds,
                        communal gatherings, and celebrations are known features. Active
                        involvement in business and trade networks characterizes the
                        community, and cultural practices are interwoven with religious
                        beliefs.
                    </p>
                    <p>
                        We are a non-profit organization administering and managing the
                        affairs of the Dawoodi Bohra community in Khar, Mumbai, Maharashtra, India.
                    </p>
                </div>
                <div class="col-12 mt-5">
                    <div class="title-base title-left">
                        <hr />
                        <h2>Information about Dawoodi Bohras</h2>
                        <div class="round">
                            <button class="round-btn" onclick="openPopup()">?</button>
                        </div>
                    </div>
                    <div class="list-items">
                        <div class="list-item">
                            <b>The Dawoodi Bohras</b> - comprehensive website about&nbsp;
                            <a href="http://thedawoodibohras.com/" target="_blank">
                                history and culture of the Dawoodi Bohra community
                            </a>
                        </div>
                        <!--<div class="list-item">-->
                        <!--    <b>Mumineen.org</b> - accurate and authentic content-->
                        <!--    pertaining to&nbsp;-->
                        <!--    <a href="http://mumineen.org/" target="_blank">-->
                        <!--        Dawoodi Bohra Muslims-->
                        <!--    </a>-->
                        <!--</div>-->
                        <!--<div class="list-item">-->
                        <!--    <b>Bohra.net</b> – Chronicle of&nbsp;-->
                        <!--    <a href="http://bohra.net/" target="_blank">-->
                        <!--        Syedna Mufaddal Saifuddin's historic visits to the North-->
                        <!--        American Jamiat-->
                        <!--    </a>-->
                        <!--</div>-->
                        <!--<div class="list-item">-->
                        <!--    <b>SBMAA</b> - The Saifee Burhani Medical Association of&nbsp;-->
                        <!--    <a href="http://sbmedical.org/" target="_blank">-->
                        <!--        Dawoodi Bohra physicians&nbsp;-->
                        <!--    </a>-->
                        <!--    in America and Canada-->
                        <!--</div>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="home-content mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="title-base title-left">
                        <hr />
                        <h2>Contact us now</h2>
                        <p>Get in touch</p>
                    </div>
                    <div class="col-12 boxed golden p-3 mb-5">
                        <form method="post" class="contact-form form-box">
                            <div class="row gy-3">
                                <div class="col-md-4 mb-3 mb-sm-3 mb-md-0">
                                    <input type="text" name="name" class="form-control" placeholder="name"
                                        maxLength="255" required id="id_name" />
                                </div>
                                <div class="col-md-4 mb-3 mb-sm-3 mb-md-0">
                                    <input type="text" name="email" class="form-control" placeholder="email"
                                        maxLength="255" required id="id_email" />
                                </div>
                                <div class="col-md-4 mb-3 mb-sm-3 mb-md-0">
                                    <input type="text" name="phone" class="form-control" placeholder="phone"
                                        maxLength="255" required id="id_phone" />
                                </div>
                            </div>
                            <hr class="space xs" />
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea name="message" cols="40" rows="3" class="form-control"
                                        placeholder="Write your message/feedback" required id="id_message"></textarea>
                                    <hr class="space s" />
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-envelope mr-2"></i>Send message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Popup -->
    <div id="infoModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <h3>👋 What would you like to do today?</h3>
            <div class="button-group">
                <a href="<?= base_url('accounts/Umoor') ?>" class="btn btn-primary m-2">📅 Request Raza</a>
                <a href="<?= base_url('accounts/thaali') ?>" class="btn btn-secondary m-2">🥣 Thaali Signup</a>
                <a href="<?= base_url('accounts/fmb') ?>" class="btn btn-warning m-2">🍽️ FMB / Sabeel Tracking</a>
                <a href="<?= base_url('accounts/Rsvpnew') ?>" class="btn btn-success m-2">🔔 RSVP</a>
                <?php    
                    if (empty($_SESSION['user'])) {
                        ?>
                    <a href="<?= base_url('/accounts') ?>" style="padding-right: 24px;" class="btn btn-info m-2">👤 Login</a>

                    <?php    
                    }
                ?>
                <button class="btn btn-danger m-2" style="padding-right: 24px;" onclick="closePopup()"><span>❌</span> Close</button>
            </div>
        </div>
    </div>
</div>

<script>
setTimeout(function() {
    document.getElementById('infoModal').style.display = 'block';
}, 6000); // 6 seconds
function openPopup() {
    document.getElementById('infoModal').style.display = 'block';
}

function closePopup() {
    document.getElementById('infoModal').style.display = 'none';
}

// Optional: Close modal if user clicks outside the modal content
window.onclick = function(event) {
    const modal = document.getElementById('infoModal');
    if (event.target === modal) {
        modal.style.display = "none";
    }
}
</script>
