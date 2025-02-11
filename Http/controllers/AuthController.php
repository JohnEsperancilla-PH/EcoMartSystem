<?php
class AuthController
{
    private $db;
    private $session;
    private $validator;

    public function __construct(PDO $db, Session $session, Validator $validator)
    {
        $this->db = $db;
        $this->session = $session;
        $this->validator = $validator;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'];

                $stmt = $this->db->prepare('SELECT id, password FROM users WHERE email = ?');
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    $this->session->set('user_id', $user['id']);
                    header('Location: /dashboard');
                    exit();
                }

                return ['error' => 'Invalid credentials'];
            } catch (Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }

        require_once __DIR__ . '/../views/auth/login.view.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'email' => $_POST['email'],
                    'mobile_number' => $_POST['mobile_number'],
                    'password' => $_POST['password'],
                    'confirm_password' => $_POST['confirm_password']
                ];

                $rules = [
                    'email' => ['required' => true, 'email' => true],
                    'mobile_number' => ['required' => true, 'pattern' => '/^09[0-9]{9}$/'],
                    'password' => ['required' => true],
                    'confirm_password' => ['required' => true]
                ];

                $this->validator->validate($data, $rules);

                if ($data['password'] !== $data['confirm_password']) {
                    throw new ValidationException(['confirm_password' => ['Passwords do not match']]);
                }

                $stmt = $this->db->prepare('INSERT INTO users (email, mobile_number, password) VALUES (?, ?, ?)');
                $stmt->execute([
                    $data['email'],
                    $data['mobile_number'],
                    password_hash($data['password'], PASSWORD_DEFAULT)
                ]);

                $this->session->set('user_id', $this->db->lastInsertId());
                header('Location: /dashboard');
                exit();
            } catch (ValidationException $e) {
                return ['errors' => $e->getErrors()];
            } catch (Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }

        require_once __DIR__ . '/../views/auth/signup.view.php';
    }

    public function logout()
    {
        $this->session->destroy();
        header('Location: /login');
        exit();
    }
}
