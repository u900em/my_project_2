<?php
    namespace App;
    
    use League\Plates\Engine;
    use Delight\Auth\Auth;
    use App\dbService;

    class HomeController {
        private $templates, $auth, $db;

        public function __construct(dbService $db, Engine $templates, Auth $auth) {
            $this->templates = $templates;
            $this->auth = $auth;
            $this->db = $db;
        }

        public function users() {
            if($this->auth->isLoggedIn()) {
                $users = $this->db->getAll('info_users');
                $role = $this->auth->getRoles();
                $email = $this->auth->getEmail();
                echo $this->templates->render('users', ['users' => $users, 'role' => $role, 'email' => $email]);
            } else {
                header("Location: /page_login");
                exit;
            }
        }

        public function edit() {
            if($this->auth->isLoggedIn()) {
                if(!empty($_POST)) {
                    $this->db->update('info_users', [
                        'name' => $_POST['username'],
                        'job_title' => $_POST['job_title'],
                        'tel' => $_POST['tel'],
                        'address' => $_POST['address'],
                    ], $_GET['id']);
                    $_SESSION['success'] = 'Профиль успешно отредактирован.';
                    header("Location: /users");
                    exit;
                } else {   
                    $user = $this->db->getOne('info_users', $_GET['id']);
                    echo $this->templates->render('edit', ['user' => $user]);
                }
            } else {
                header("Location: /page_login");
                exit;
            }
        }

        public function security() {
            if($this->auth->isLoggedIn()) {
                $user = $this->db->getOne('users', $_GET['id']);
                $emails = $this->db->getAll('info_users', 'email');
                $admin = $this->auth->hasRole(\Delight\Auth\Role::ADMIN);
                //* User changing password and email address. *//
                if(!$admin && !empty($_POST)) {
                    if($_POST['newEmail'] !== $user['email']) {
                        try {
                            $this->auth->changeEmail($_POST['newEmail'], function ($selector, $token) {
                                $this->auth->confirmEmail($selector, $token);
                            });
                            $this->db->update('info_users', [
                                'email' => $_POST['newEmail'],
                            ], $_GET['id']);
                            $_SESSION['success'] = 'Email address is changed';
                        }
                        catch (\Delight\Auth\InvalidEmailException $e) {
                            $_SESSION['danger'] = 'Invalid email address';
                        }
                        catch (\Delight\Auth\UserAlreadyExistsException $e) {
                            $_SESSION['danger'] = 'Email address already exists';
                        }
                        catch (\Delight\Auth\EmailNotVerifiedException $e) {
                            $_SESSION['danger'] = 'Account not verified';
                        }
                        catch (\Delight\Auth\NotLoggedInException $e) {
                            $_SESSION['danger'] = 'Not logged in';
                        }
                    }
                    if($_POST['oldPassword'] !== '') {
                        try {
                            if($_POST['newPassword'] === $_POST['newPasswordConfirm']) {
                                $this->auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);
                                $_SESSION['success'] = 'Password of user with ID ' . $_GET['id'] . ' has been changed';
                            } else {
                                $_SESSION['danger'] = 'Passwords is not matches';
                            }
                        }
                        catch (\Delight\Auth\NotLoggedInException $e) {
                            $_SESSION['danger'] = 'Not logged in';
                        }
                        catch (\Delight\Auth\InvalidPasswordException $e) {
                            $_SESSION['danger'] = 'Invalid password(s)';
                        }
                    }
                }
                //* Admin changing password and email address. *//
                if($admin) {
                    if(!empty($_POST)) {
                        if(filter_var($_POST['newEmail'], FILTER_VALIDATE_EMAIL)) {
                            $result = [];
                            array_walk_recursive($emails, function($item) use (&$result) {
                                $result[] = $item;
                            });
                            if($_POST['newEmail'] == $user['email']) {
                                true;
                            } else {
                                if(in_array($_POST['newEmail'], $result)) {
                                    $_SESSION['danger'] = 'Email address already exists';
                                } else {
                                    $this->db->update('users', [
                                        'email' => $_POST['newEmail']
                                    ], $_GET['id']);

                                    $this->db->update('info_users', [
                                        'email' => $_POST['newEmail']
                                    ], $_GET['id']);
                                    $_SESSION['success'] = 'Email address is changed';
                                }
                            }
                        }
                        if(!empty($_POST['newPassword'])) {
                            try {
                                $this->auth->admin()->changePasswordForUserById($_GET['id'], $_POST['newPassword']);
                                $_SESSION['success'] = 'Password of user with ID ' . $_GET['id'] . ' has been changed.';
                            }
                            catch (\Delight\Auth\UnknownIdException $e) {
                                $_SESSION['danger'] = 'Unknown ID';
                            }
                            catch (\Delight\Auth\InvalidPasswordException $e) {
                                $_SESSION['danger'] = 'Invalid password';
                            }
                        }
                    }
                }
                echo $this->templates->render('security', ['user' => $user, 'admin' => $admin]);
            } else {
                header("Location: /page_login");
                exit;
            }
        }

        public function status() {
            if($this->auth->isLoggedIn()) {
                if(!empty($_POST)) {
                    $this->db->update('info_users', [
                        'status' => $_POST['status']
                    ], $_GET['id']);
                    $_SESSION['success'] = 'Status is changed.';
                }
                $user = $this->db->getOne('info_users', $_GET['id']);
                $statuses = [
                    'success' => 'Онлайн',
                    'offline' => 'Отошел',
                    'warning' => 'Не беспокоить',
                    'danger' => 'Не в сети'
                ];
                echo $this->templates->render('status', ['statuses' => $statuses, 'user' => $user]);
            } else {
                header("Location: /page_login");
                exit;
            }
        }

        public function media() {
            if($this->auth->isLoggedIn()) {
                $user = $this->db->getOne('info_users', $_GET['id']);
                if(!empty($_FILES['image_field'])) {
                    $handle = new \Verot\Upload\Upload($_FILES['image_field']);
    
                    if($handle->uploaded) {
                        $file_name = uniqid();
                        $handle->file_new_name_body = $file_name;
                        $handle->image_convert = 'png';
                        $handle->image_resize = true;
                        $handle->image_x = 296;
                        $handle->image_y = 296;
                        $handle->process('../style/img/demo/avatars');
                        if($handle->processed) {               
                            unlink('../style/img/demo/avatars/'.$user['avatar']);
                            $this->db->update('info_users', [
                                'avatar' => $handle->file_new_name_body = $file_name.'.png',
                            ], $_GET['id']);
                            header("Location: /media?id=".$_GET['id']);
                            $_SESSION['success'] = 'Avatar is changed.';
                            $handle->clean();
                            exit;
                        } else {
                            echo 'error: ' . $handle->error;
                        }
                    }
                }
                echo $this->templates->render('media', ['user' => $user]);
            } else {
                header("Location: /page_login");
                exit;
            }
        }

        public function delete_user() {
            if($this->auth->isLoggedIn()) {
                $this->db->delete('info_users', $_GET['id']);
                $this->db->delete('users', $_GET['id']);
                $_SESSION['success'] = 'User with ID ' . $_GET['id'] . ' has been delete.';
                header("Location: /users");
                exit;
            } else {
                header("Location: /page_login");
                exit;
            }
        }

        public function create_user() {     
            if($this->auth->hasRole(\Delight\Auth\Role::ADMIN)) {

                if(!empty($_POST)) {
                    $info_users = $this->db->getAll('info_users', 'email');
                    $users = $this->db->getAll('users', 'email');
    
                    if(in_array($_POST['email'], $this->convertationArray($info_users)) || in_array($_POST['email'], $this->convertationArray($users))) {
                        $_SESSION['danger'] = 'Email address already exists.';
                    } else {
                        try {
                            $userId = $this->auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);

                            if(!empty($_FILES['image_field'])) {
                                $handle = new \Verot\Upload\Upload($_FILES['image_field']);
                                if($handle->uploaded) {
                                    $file_name = uniqid();
                                    $handle->file_new_name_body = $file_name;
                                    $handle->image_convert = 'png';
                                    $handle->image_resize = true;
                                    $handle->image_x = 296;
                                    $handle->image_y = 296;
                                    $handle->process('../style/img/demo/avatars');
                                    if($handle->processed) {
                                        $image = $handle->file_new_name_body = $file_name.'.png';
                                        $handle->clean();
                                    } else {
                                        $_SESSION['danger'] =  $handle->error;
                                    }
                                }
                            }
                            if(!empty($image)) {
                                true;
                            } else {
                                $image = '';
                            }

                            $this->db->insert('info_users', [
                                'name' => $_POST['username'],
                                'job_title' => $_POST['job_title'],
                                'tel' => $_POST['tel'],
                                'address' => $_POST['address'],
                                'email' => $_POST['email'],
                                'status' => $_POST['status'],
                                'avatar' => $image,
                                'vk' => $_POST['vk'],
                                'telega' => $_POST['telega'],
                                'insta' => $_POST['insta'],
                                'id_users' => $userId
                            ]);
                        }

                        catch (\Delight\Auth\InvalidEmailException $e) {
                            $_SESSION['danger'] = 'Invalid email address.';
                        }
                        catch (\Delight\Auth\InvalidPasswordException $e) {
                            $_SESSION['danger'] = 'Invalid password.';
                        }
                        catch (\Delight\Auth\UserAlreadyExistsException $e) {
                            $_SESSION['danger'] = 'User already exists.';
                        }

                        $_SESSION['success'] = 'New user with id '.$userId.' is created.';
                        header("Location: /create_user");
                        exit;
                    }
                }

                $statuses = [
                    'success' => 'Онлайн',
                    'offline' => 'Отошел',
                    'warning' => 'Не беспокоить',
                    'danger' => 'Не в сети'
                ];

                echo $this->templates->render('create_user', ['statuses' => $statuses]);
            } else {
                header("Location: /page_login");
                exit;
            }
        }

        public function logOut() {
            $this->auth->logOut();
            header("Location: /page_login");
        }

        private function convertationArray($arr) {
            $result = [];
            array_walk_recursive($arr, function($item) use (&$result) {
                $result[] = $item;
            });
            return $result;
        }
    }
?>