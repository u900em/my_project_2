<?php
    namespace App\auth;

    use League\Plates\Engine;
    use Delight\Auth\Auth;

    class LoginController {
        private $auth, $templates;

        public function __construct(Auth $auth, Engine $templates) {
            $this->auth = $auth;
            $this->templates = $templates;
        }

        public function page_login() {
            if(!empty($_POST)) {
                try {
                    $this->auth->login($_POST['email'], $_POST['password']);
                    header("Location: /users");
                    exit;
                }
                catch (\Delight\Auth\InvalidEmailException $e) {
                    $err = 'Wrong email address';
                }
                catch (\Delight\Auth\InvalidPasswordException $e) {
                    $err = 'Wrong password';
                }
                catch (\Delight\Auth\EmailNotVerifiedException $e) {
                    $err = 'Email not verified';
                }
               
                echo $this->templates->render('page_login', ['error' => $_SESSION['error'] = $err]);
            } else {
                echo $this->templates->render('page_login');
            }
        }
    }
?>