<?php $this->layout('template'); ?>
<body>
    <main id="js-page-content" role="main" class="page-content mt-3">
        <div class="subheader">
            <h1 class="subheader-title">
                <i class='subheader-icon fal fa-lock'></i> Безопасность
            </h1>
        </div>
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['danger'])): ?>
            <div class="alert alert-danger">
                <?php
                    echo $_SESSION['danger'];
                    unset($_SESSION['danger']);
                ?>
            </div>
        <?php endif; ?>
        <form action="/security?id=<?php echo $_GET['id']; ?>" method="POST">
            <div class="row">
                <div class="col-xl-6">
                    <div id="panel-1" class="panel">
                        <div class="panel-container">
                            <div class="panel-hdr">
                                <h2>Обновление эл. адреса и пароля</h2>
                            </div>
                            <div class="panel-content">

                                <!-- email -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Email</label>
                                    <input type="text" name="newEmail" id="simpleinput" class="form-control" value="<?php echo $user['email']; ?>">
                                </div>

                                <?php if(!$admin): ?>
                                    <!-- old password -->
                                    <div class="form-group">
                                        <label class="form-label" for="simpleinput">Пароль пользователя</label>
                                        <input type="password" name="oldPassword" id="simpleinput" class="form-control">
                                    </div>
                                <?php endif; ?>

                                <!-- new password -->
                                <div class="form-group">
                                    <label class="form-label" for="simpleinput">Новый пароль</label>
                                    <input type="password" name="newPassword" id="simpleinput" class="form-control">
                                </div>

                                <?php if(!$admin): ?>
                                    <!-- password confirmation-->
                                    <div class="form-group">
                                        <label class="form-label" for="simpleinput">Подтверждение пароля</label>
                                        <input type="password" name="newPasswordConfirm" id="simpleinput" class="form-control">
                                    </div>
                                <?php endif; ?>

                                <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                    <button class="btn btn-warning">Изменить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>
</body>