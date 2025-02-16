<?php

use Core\Validator;
use Core\ValidationException;
use Core\Session;
use Models\User;
use Models\UserProfiles;

class AuthController
{
    private $db;
    private $session;
    private $validator;
    private $user;
    private $userProfiles;

    public function __construct(mysqli $db, Session $session, Validator $validator, User $user)
    {
        $this->db = $db;
        $this->session = $session;
        $this->validator = $validator;
        $this->user = $user;
        $this->userProfiles = new UserProfiles($db);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'email' => $_POST['email'] ?? '',
                    'mobile_number' => $_POST['mobile_number'] ?? '',
                    'password' => $_POST['password'] ?? '',
                    'confirm_password' => $_POST['confirm_password'] ?? '',
                    'first_name' => $_POST['first_name'] ?? '',
                    'last_name' => $_POST['last_name'] ?? '',
                    'gender' => $_POST['gender'] ?? '',
                    'birthdate' => $_POST['birthdate'] ?? '',
                    'terms_accepted' => isset($_POST['terms_accepted']) ? 1 : 0,
                    'role' => 'customer',
                ];

                // Check if email already exists
                $stmt = $this->db->prepare("SELECT email FROM users WHERE email = ?");
                $stmt->bind_param("s", $data['email']);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $this->session->set('email_error', 'Email address is already registered');
                    header('Location: /register');
                    exit();
                }
                $stmt->close();

                // Check if mobile number already exists
                $stmt = $this->db->prepare("SELECT mobile_number FROM users WHERE mobile_number = ?");
                $stmt->bind_param("s", $data['mobile_number']);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $this->session->set('mobile_error', 'Mobile number is already registered');
                    header('Location: /register');
                    exit();
                }
                $stmt->close();

                $rules = [
                    'email' => ['required' => true, 'email' => true],
                    'mobile_number' => ['required' => true, 'pattern' => '/^09[0-9]{9}$/'],
                    'password' => ['required' => true, 'min' => 8],
                    'confirm_password' => ['required' => true],
                    'first_name' => ['required' => true],
                    'last_name' => ['required' => true],
                    'gender' => ['required' => true, 'in' => ['Male', 'Female', 'Other']],
                    'birthdate' => ['required' => true, 'date' => true],
                    'terms_accepted' => ['required' => true, 'value' => true]
                ];

                // Validate the data
                $this->validator->validate($data, $rules);

                if ($data['password'] !== $data['confirm_password']) {
                    $this->session->set('password_error', 'Passwords do not match');
                    header('Location: /register');
                    exit();
                }

                // Validate password strength
                if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $data['password'])) {
                    $this->session->set('password_error', 'Password must contain at least 8 characters, including uppercase, lowercase, and numbers');
                    header('Location: /register');
                    exit();
                }

                // Create user with complete data
                $userId = $this->user->create($data);

                $this->session->set('success', 'Registration successful! Please login to continue.');
                header('Location: /login');
                exit();
            } catch (ValidationException $e) {
                foreach ($e->getErrors() as $field => $errors) {
                    $this->session->set($field . '_error', $errors[0]);
                }
                header('Location: /register');
                exit();
            } catch (Exception $e) {
                $this->session->set('error', 'An error occurred during registration. Please try again.');
                header('Location: /register');
                exit();
            }
        }

        // If not POST request, display the signup form
        require_once __DIR__ . '/../../views/auth/signup.view.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'] ?? '';
                $role = $_POST['role'] ?? '';

                if (!$email) {
                    $this->session->set('email_error', 'Please enter a valid email address');
                    header('Location: /login');
                    exit();
                }

                $stmt = $this->db->prepare("SELECT user_id, password, role, email FROM users WHERE email = ? AND role = ?");
                if (!$stmt) {
                    throw new Exception('Database error occurred');
                }

                $stmt->bind_param("ss", $email, $role);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();

                if (!$user) {
                    $this->session->set('login_error', 'Invalid email or role');
                    header('Location: /login');
                    exit();
                }

                if (!password_verify($password, $user['password'])) {
                    $this->session->set('password_error', 'Invalid password');
                    header('Location: /login');
                    exit();
                }

                // Set session variables
                $this->session->set('user_id', $user['user_id']);
                $this->session->set('user_role', $user['role']);
                $this->session->set('authenticated', true);

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: /dashboard');
                    exit();
                } else {
                    header('Location: /shop');
                    exit();
                }
            } catch (Exception $e) {
                $this->session->set('error', $e->getMessage());
                header('Location: /login');
                exit();
            }
        }

        require_once DIR . '/views/auth/login.view.php';
    }

    public function logout()
    {
        $this->session->destroy();
        header('Location: /login');
        exit();
    }
}
