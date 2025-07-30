<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .icon {
        font-size: 40pt;
        margin: 10px 0;
        color: #ffffff;
    }

    .title {
        color: white;
        font-size: 0.8rem;
    }

    .heading {
        color: #ad7e05;
        font-family: 'Amita', cursive;
    }

    .card {
        height: 165px;
        transition: transform 0.5s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
        transform: scale(1.1)
    }

    .row a {
        text-decoration: none;
        color: inherit;
    }
</style>
<div class="container margintopcontainer">
    <h1 class="text-center heading pt-5 mb-4">Welcome to Anjuman-e-Saifee Khar Jamaat</h1>
    <hr>
    <div class="continer d-flex justify-content-center">
        <div class="row mt-4">
            <!--<a href="<?php echo base_url('accounts/Umoor') ?>" class="col-6 col-md-3 col-xxl-2 py-2">-->
            <!--    <div class="card text-center">-->
            <!--        <div class="card-body"-->
            <!--            style="display: flex; flex-direction: column; justify-content: center; height: 100%;">-->
            <!--            <div class="title" style=" margin: 0;">12 Umoor & Event Raza</div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</a>-->
            <a class="col-6 col-md-3 col-xxl-2 py-2" href="<?php echo base_url('accounts/Umoor') ?>">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="title">12 Umoor & Event Raza</div>
                        <i class="fa-solid icon fa-clipboard-list"></i>
                    </div>
                </div>
            </a>
            <a class="col-6 col-md-3 col-xxl-2 py-2" href="<?php echo base_url('accounts/MyRazaRequest') ?>">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="title">My Applications</div>
                        <i class="fa-solid icon fa-hands-holding"></i>
                    </div>
                </div>
            </a>
            <a href="<?php echo base_url('accounts/miqaat') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="title">Event RSVP</div>
                        <i class="fa-solid icon fa-calendar-days"></i>
                    </div>
                </div>
            </a>
            <div class="col-6 col-md-3 col-xxl-2 py-2 ">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="title">FMB Menu & Feedback</div>
                        <i class="fa-solid icon fa-comments"></i>
                    </div>
                </div>
            </div>
            <a href="<?php echo base_url('accounts/profile') ?>" class="col-6 col-md-3 col-xxl-2 py-2 ">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="title">My Profile</div>
                        <i class="fa-solid icon fa-clipboard-user"></i>
                    </div>
                </div>
            </a>
            <?php
            if ($user_name == $hof_data) {
                echo '<a href="' . base_url('accounts/appointment') . '" class="col-6 col-md-3 col-xxl-2 py-2 ">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="title">Amil Saheb\'s Appointment</div>
                        <i class="fa-solid icon fa-calendar-days"></i>
                    </div>
                </div>
            </a>';
            }
            ?>

            <!--<div class="col-6 col-md-3 col-xxl-2 py-2 ">-->
            <!--    <div class="card text-center">-->
            <!--        <div class="card-body">-->
            <!--            <div class="title">Finance</div>-->
            <!--            <i class="fa-solid icon fa-money-check-alt"></i>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
        </div>
    </div>
</div>
<script>
    const colors = ["rgb(142, 68, 173)",
        "rgb(243, 156, 18)",
        "rgb(135, 0, 0)",
        "rgb(211, 84, 0)",
        "rgb(0, 106, 63)",
        "rgb(192, 57, 43)",
        "rgb(39, 174, 96)",
        "rgb(41, 128, 185)",
        "rgb(142, 68, 173)",
        "rgb(243, 156, 18)",
        "rgb(135, 0, 0)",
        "rgb(211, 84, 0)",
        "rgb(0, 106, 63)",
        "rgb(192, 57, 43)",
        "rgb(39, 174, 96)",
        "rgb(41, 128, 185)",
        "rgb(142, 68, 173)",
        "rgb(243, 156, 18)",
        "rgb(135, 0, 0)",
        "rgb(211, 84, 0)",
        "rgb(0, 106, 63)",
        "rgb(192, 57, 43)",
        "rgb(39, 174, 96)",
        "rgb(41, 128, 185)",]
    $(document).ready(function () {
        $(".card").each(function (i, el) {
            this.style.backgroundColor = colors[i];
        });
    })
</script>