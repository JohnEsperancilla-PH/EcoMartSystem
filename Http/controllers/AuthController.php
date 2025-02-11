<?php
class AuthController
{
    private $db;
    private $session;
    private $validator;
    private $user;

    public function __construct(PDO $db, Session $session, Validator $validator, User $user)
    {
        $this->db = $db;
        $this->session = $session;
        $this->validator = $validator;
        $this->user = $user;
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'email' => $_POST['email'],
                    'mobile_number' => $_POST['mobile_number'],
                    'password' => $_POST['password'],
                    'confirm_password' => $_POST['confirm_password'],
                    'terms_accepted' => isset($_POST['terms_accepted']) ? 1 : 0,
                    'role' => 'customer',
                ];

                $rules = [
                    'email' => ['required' => true, 'email' => true],
                    'mobile_number' => ['required' => true, 'pattern' => '/^09[0-9]{9}$/'],
                    'password' => ['required' => true, 'min' => 8],
                    'confirm_password' => ['required' => true],
                    'terms_accepted' => ['required' => true, 'value' => true]
                ];

                $this->validator->validate($data, $rules);

                if ($data['password'] !== $data['confirm_password']) {
                    throw new ValidationException(['confirm_password' => ['Passwords do not match']]);
                }

                $userId = $this->user->create($data);
                $this->session->set('user_id', $userId);
                $this->session->set('role', 'customer');

                header('Location: /setup-profile');
                exit();
            } catch (ValidationException $e) {
                return ['errors' => $e->getErrors()];
            } catch (Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }

        require_once __DIR__ . '/../views/auth/signup.view.php';
    }

    public function setupProfile()
    {
        if (!$this->session->get('user_id')) {
            header('Location: /login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'gender' => $_POST['gender'],
                    'birthdate' => $_POST['birthdate']
                ];

                $rules = [
                    'first_name' => ['required' => true],
                    'last_name' => ['required' => true],
                    'gender' => ['required' => true, 'in' => ['male', 'female', 'other']],
                    'birthdate' => ['required' => true, 'date' => true]
                ];

                $this->validator->validate($data, $rules);

                $this->user->updateProfile($this->session->get('user_id'), $data);

                header('Location: /dashboard');
                exit();
            } catch (ValidationException $e) {
                return ['errors' => $e->getErrors()];
            } catch (Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }

        require_once __DIR__ . '/../views/auth/setup.view.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'];

                $stmt = $this->db->prepare('
                    SELECT id, password, role,
                        (SELECT COUNT(*) FROM UserProfiles WHERE user_id = users.id) AS profile_completed
                    FROM users
                    WHERE email = ?
                ');
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    $this->session->set('user_id', $user['id']);
                    $this->session->set('role', $user['role']);
                    
                    if ($user['role'] === 'admin') {
                        header('Location: /admin/dashboard');
                        exit();
                    } else if ($user['profile_completed'] === 0) {
                        header('Location: /setup-profile');
                        exit();
                    } else {
                        header('Location: /dashboard');
                    }
                    exit();
                }

                return ['error' => 'Invalid credentials'];
            } catch (Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }

        require_once __DIR__ . '/../views/auth/login.view.php';
    }

    public function logout()
    {
        $this->session->destroy();
        header('Location: /login');
        exit();
    }
}
