<?php
    namespace App\auth;

    use League\Plates\Engine;
    use Delight\Auth\Auth;

    class VerificationEmailController {
        private $auth, $templates;

        public function __construct(Auth $auth, Engine $templates) {
            $this->auth = $auth;
            $this->templates = $templates;
        }

        public function verification_mail() {
            $sess = 'selector=' . $_SESSION['verification']['selector'] . '&token=' .$_SESSION['verification']['token'];
            echo $this->templates->render('verification_mail', ['sess' => $sess]);
        }

        public function verification_mail2() {
            try {
                $this->auth->confirmEmail($_GET['selector'], $_GET['token']);
                $_SESSION['user'] = 'User created and verificationed. Sign in.';
                header("Location: /page_login");
                exit;
            }
            catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
                die('Invalid token');
            }
            catch (\Delight\Auth\TokenExpiredException $e) {
                die('Token expired');
            }
            catch (\Delight\Auth\UserAlreadyExistsException $e) {
                die('Email address already exists');
            }
        }
    }
?>