<?php

use Core\Validator;
use Core\ValidationException;
use Core\Session;
use Models\User;
    
class AuthController
{
    private $db;
    private $session;
    private $validator;
    private $user;

    public function __construct(mysqli $db, Session $session, Validator $validator, User $user)
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

        require_once __DIR__ . '/../../views/auth/signup.view.php';
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

        require_once __DIR__ . '/../../views/auth/setup.view.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'];
                $role = $_POST['role'];

                // Debug logging
                error_log("Login attempt - Email: $email, Role: $role");

                if (!$email || !$password || !$role) {
                    return ['error' => 'All fields are required'];
                }

                $stmt = $this->db->prepare("SELECT id, password, role FROM users WHERE email = ? AND role = ?");
                $stmt->bind_param("ss", $email, $role);
                $stmt->execute();

                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();

                // Debug logging
                error_log("User data: " . print_r($user, true));

                if (!$user || !password_verify($password, $user['password'])) {
                    return ['error' => 'Invalid email, password, or role.'];
                }

                $this->session->set('user_id', $user['id']);
                $this->session->set('role', $user['role']);
                $this->session->set('email', $email);

                // Debug logging
                error_log("Session after login: " . print_r($_SESSION, true));

                if ($user['role'] === 'admin') {
                    header('Location: /admin/dashboard');
                } else {
                    header('Location: /dashboard');
                }
                exit();
            } catch (Exception $e) {
                error_log("Login error: " . $e->getMessage());
                return ['error' => $e->getMessage()];
            }
        }

        require_once __DIR__ . '/../../views/auth/login.view.php';
    }

    public function logout()
    {
        $this->session->destroy();
        header('Location: /login');
        exit();
    }
}
