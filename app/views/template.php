<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <meta name="description" content="Chartist.html">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link id="appbundle" rel="stylesheet" media="screen, print" href="style/css/app.bundle.css">
        <link id="myskin" rel="stylesheet" media="screen, print" href="style/css/skins/skin-master.css">
        <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="style/css/vendors.bundle.css">
        <link rel="stylesheet" media="screen, print" href="style/css/statistics/chartist/chartist.css">
        <link rel="stylesheet" media="screen, print" href="style/css/miscellaneous/lightgallery/lightgallery.bundle.css">
        <link rel="stylesheet" media="screen, print" href="style/css/fa-solid.css">
        <link rel="stylesheet" media="screen, print" href="style/css/fa-brands.css">
        <link rel="stylesheet" media="screen, print" href="style/css/fa-regular.css">
    </head>

    <?php if($_SERVER["REQUEST_URI"] !== "/page_login"): ?>
        <?php if($_SERVER["REQUEST_URI"] !== "/page_register"): ?>
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-primary-gradient">
                <a class="navbar-brand d-flex align-items-center fw-500" href="/users"><img alt="logo" class="d-inline-block align-top mr-2" src="style/img/logo.png"> Учебный проект</a> <button aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarColor02" data-toggle="collapse" type="button"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarColor02">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="/users">Главная <span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/page_login">Войти</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/logOut">Выйти</a>
                        </li>
                    </ul>
                </div>
            </nav>
        <?php endif; ?>
    <?php endif; ?>

    <?=$this->section('content'); ?>

    <script src="style/js/vendors.bundle.js"></script>
    <script src="style/js/app.bundle.js"></script>
    <script src="style/js/miscellaneous/lightgallery/lightgallery.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $('input[type=radio][name=contactview]').change(function() {
                if (this.value == 'grid') {
                    $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-g');
                    $('#js-contacts .col-xl-12').removeClassPrefix('col-xl-').addClass('col-xl-4');
                    $('#js-contacts .js-expand-btn').addClass('d-none');
                    $('#js-contacts .card-body + .card-body').addClass('show');

                } else if (this.value == 'table') {
                    $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-1');
                    $('#js-contacts .col-xl-4').removeClassPrefix('col-xl-').addClass('col-xl-12');
                    $('#js-contacts .js-expand-btn').removeClass('d-none');
                    $('#js-contacts .card-body + .card-body').removeClass('show');
                }
            });
            initApp.listFilter($('#js-contacts'), $('#js-filter-contacts'));
        });
    </script>
    <script>
        if(window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>   
</html>
