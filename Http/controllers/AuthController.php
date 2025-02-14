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
                // Get JSON data from request body
                $input = json_decode(file_get_contents('php://input'), true);
                
                $data = [
                    'email' => $input['email'],
                    'mobile_number' => $input['mobile_number'],
                    'password' => $input['password'],
                    'confirm_password' => $input['confirm_password'],
                    'terms_accepted' => $input['terms_accepted'] ? 1 : 0,
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

                // Store data in session for setup step
                $this->session->set('signup_data', $data);
                
                // Return success response
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit();
            } catch (ValidationException $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'errors' => $e->getErrors()]);
                exit();
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                exit();
            }
        }

        require_once DIR . '/views/auth/signup.view.php';
    }

    public function setupProfile()
    {
        // Get signup data from session
        $signupData = $this->session->get('signup_data');
        if (!$signupData) {
            header('Location: /signup');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Get JSON data from request body
                $input = json_decode(file_get_contents('php://input'), true);
                
                $profileData = [
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'gender' => $input['gender'],
                    'birthdate' => $input['birthdate']
                ];

                // Combine signup and profile data
                $completeData = array_merge($signupData, $profileData);

                $rules = [
                    'first_name' => ['required' => true],
                    'last_name' => ['required' => true],
                    'gender' => ['required' => true, 'in' => ['male', 'female', 'other']],
                    'birthdate' => ['required' => true, 'date' => true]
                ];

                $this->validator->validate($profileData, $rules);

                // Create user with complete data
                $userId = $this->user->create($completeData);
                
                // Set session data
                $this->session->set('user_id', $userId);
                $this->session->set('role', 'customer');
                $this->session->remove('signup_data');

                // Return success response
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit();
            } catch (ValidationException $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'errors' => $e->getErrors()]);
                exit();
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                exit();
            }
        }

        require_once DIR . '/views/auth/setup.view.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $password = $_POST['password'] ?? '';
                $role = $_POST['role'] ?? '';

                $stmt = $this->db->prepare("SELECT user_id, password, role, email FROM users WHERE email = ? AND role = ?");
                if (!$stmt) {
                    return ['error' => 'Database error occurred'];
                }

                $stmt->bind_param("ss", $email, $role);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();

                if (!$user) {
                    return ['error' => 'Invalid credentials'];
                }

                if (!password_verify($password, $user['password'])) {
                    return ['error' => 'Invalid credentials'];
                }

                // Set session variables
                $this->session->set('user_id', $user['user_id']);
                $this->session->set('role', $user['role']);
                $this->session->set('email', $user['email']);
                $this->session->set('authenticated', true);

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: /dashboard');
                    exit();
                } else {
                    header('Location: /dashboard');
                    exit();
                }
            } catch (Exception $e) {
                return ['error' => 'An error occurred during login'];
            }
        }

        // Show login form for GET requests
        require_once DIR . '/views/auth/login.view.php';
    }

    public function logout()
    {
        $this->session->destroy();
        header('Location: /login');
        exit();
    }
}
