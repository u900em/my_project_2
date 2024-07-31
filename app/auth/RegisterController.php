<?php
    namespace App\auth;

    use League\Plates\Engine;
    use Delight\Auth\Auth;
    use App\dbService;

    class RegisterController {
        private $auth, $templates, $db;

        public function __construct(dbService $db, Auth $auth, Engine $templates) {
            $this->auth = $auth;
            $this->templates = $templates;
            $this->db = $db;
        }

        public function page_register() {
            if(!empty($_POST)) {
                $info_users = $this->db->getAll('info_users', 'email');
                $users = $this->db->getAll('users', 'email');

                if(in_array($_POST['email'], $this->convertationArray($info_users)) || in_array($_POST['email'], $this->convertationArray($users))) {
                    $_SESSION['danger'] = 'Email address already exists.';
                    header("Location: /page_register");
                    exit;
                } else {
                    try {
                        $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
                            $_SESSION['verification'] = ['token' => $token, 'selector' => $selector];    
                        });

                        $this->db->insert('info_users', [
                            'email' => $_POST['email'],
                            'name' => $_POST['username'],
                            'job_title' => '',
                            'tel' => '',
                            'address' => '',
                            'status' => 'online',
                            'avatar' => '',
                            'vk' => '',
                            'telega' => '',
                            'insta' => '',
                            'id_users' => $userId
                        ]);
                        header("Location: /verification_mail");
                        exit;
                    }
                    catch (\Delight\Auth\InvalidEmailException $e) {
                        $_SESSION['danger'] = 'Invalid email address';
                    }
                    catch (\Delight\Auth\InvalidPasswordException $e) {
                        $_SESSION['danger'] = 'Invalid password';
                    }
                    catch (\Delight\Auth\UserAlreadyExistsException $e) {
                        $_SESSION['danger'] = 'User already exists';
                    }
                }
            } else {
                echo $this->templates->render('page_register');
            }
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