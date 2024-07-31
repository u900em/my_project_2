<?php $this->layout('template'); ?>
<head>
    <link rel="apple-touch-icon" sizes="180x180" href="style/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="style/img/favicon/favicon-32x32.png">
    <link rel="mask-icon" href="style/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="stylesheet" media="screen, print" href="style/css/page-login-alt.css">
</head>
<body>
    <div class="blankpage-form-field">
        <div class="page-logo m-0 w-100 align-items-center justify-content-center rounded border-bottom-left-radius-0 border-bottom-right-radius-0 px-4">
            <a href="javascript:void(0)" class="page-logo-link press-scale-down d-flex align-items-center">
                <img src="style/img/logo.png" alt="SmartAdmin WebApp" aria-roledescription="logo">
                <span class="page-logo-text mr-1">Учебный проект</span>
                <i class="fal fa-angle-down d-inline-block ml-1 fs-lg color-primary-300"></i>
            </a>
        </div>
        <div class="card p-4 border-top-left-radius-0 border-top-right-radius-0">
            <?php if(!empty($_SESSION['user'])): ?>
                <div class="alert alert-success">
                    <?php
                        echo $_SESSION['user'];
                        unset($_SESSION['user']);
                    ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($error)): ?>
                <div class="alert alert-danger">
                    <?php
                        echo $error;
                        unset($error);
                    ?>
                </div>
            <?php endif; ?>
            <form action="/page_login" method="POST">
                <div class="form-group">
                    <label class="form-label" for="username">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email" value="">
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Пароль</label>
                    <input type="password" name="password" class="form-control" placeholder="Password" >
                </div>
                <button type="submit" class="btn btn-default float-right">Войти</button>
            </form>
        </div>
        <div class="blankpage-footer text-center">
            Нет аккаунта? <a href="/page_register"><strong>Зарегистрироваться</strong>
        </div>
    </div>
    <video poster="style/img/backgrounds/clouds.png" id="bgvid" playsinline autoplay muted loop>
        <source src="style/media/video/cc.webm" type="video/webm">
        <source src="style/media/video/cc.mp4" type="video/mp4">
    </video>
</body>
