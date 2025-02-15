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
                // Get JSON data from request body
                $input = json_decode(file_get_contents('php://input'), true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('Invalid JSON data received');
                }

                // Log the received data for debugging
                error_log('Received registration data: ' . print_r($input, true));
                
                $data = [
                    'email' => $input['email'] ?? null,
                    'mobile_number' => $input['mobile_number'] ?? null,
                    'password' => $input['password'] ?? null,
                    'confirm_password' => $input['confirm_password'] ?? null,
                    'terms_accepted' => isset($input['terms_accepted']) ? 1 : 0,
                    'role' => 'customer'
                ];

                // Log the processed data
                error_log('Processed registration data: ' . print_r($data, true));

                $rules = [
                    'email' => ['required' => true, 'email' => true],
                    'mobile_number' => ['required' => true, 'pattern' => '/^09[0-9]{9}$/'],
                    'password' => ['required' => true, 'min' => 8],
                    'confirm_password' => ['required' => true],
                    'terms_accepted' => ['required' => true, 'value' => true]
                ];

                // Validate the data
                $this->validator->validate($data, $rules);

                if ($data['password'] !== $data['confirm_password']) {
                    throw new ValidationException(['confirm_password' => ['Passwords do not match']]);
                }

                // Check if email already exists
                $stmt = $this->db->prepare("SELECT user_id FROM users WHERE email = ?");
                $stmt->bind_param("s", $data['email']);
                $stmt->execute();
                if ($stmt->get_result()->num_rows > 0) {
                    throw new ValidationException(['email' => ['Email already registered']]);
                }
                $stmt->close();

                // Create the user account
                $userId = $this->user->create($data);
                
                // Store the user ID in session
                $this->session->set('user_id', $userId);
                
                // Return success response
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Registration successful'
                ]);
                exit();
            } catch (ValidationException $e) {
                error_log('Validation error: ' . print_r($e->getErrors(), true));
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'errors' => $e->getErrors()
                ]);
                exit();
            } catch (Exception $e) {
                error_log('Registration error: ' . $e->getMessage());
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit();
            }
        }

        // If not POST request, display the signup form
        require_once __DIR__ . '/../../views/auth/signup.view.php';
    }

    public function setupProfile()
    {
        $userId = $this->session->get('user_id');

        if (!$userId) {
            header('Location: /register');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $input = json_decode(file_get_contents('php://input'), true);

                $profileData = [
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'gender' => strtolower($input['gender']),
                    'birthdate' => $input['birthdate'],
                ];

                $rules = [
                    'first_name' => ['required' => true],
                    'last_name' => ['required' => true],
                    'gender' => ['required' => true, 'in' => ['male', 'female', 'other']],
                    'birthdate' => ['required' => true, 'date' => true]
                ];

                $this->validator->validate($profileData, $rules);

                // Create user profile
                $this->userProfiles->create($userId, $profileData);

                // Set role in session to customer
                $this->session->set('role', 'customer');

                header('Content-Type: application/json');
                exit();
            } catch (ValidationException $e) {
                header('Content-Type: application/json');
                exit();
            } catch (Exception $e) {
                header('Content-Type: application/json');
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

                error_log("Login attempt: Email={$email}, Role={$role}"); // Debugging

                $stmt = $this->db->prepare("SELECT user_id, password, role, email FROM users WHERE email = ? AND role = ?");
                if (!$stmt) {
                    error_log("Database error: " . $this->db->error);
                    return ['error' => 'Database error occurred'];
                }

                $stmt->bind_param("ss", $email, $role);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();

                if (!$user) {
                    error_log("Invalid credentials: User not found"); // Debugging
                    return ['error' => 'Invalid credentials'];
                }

                if (!password_verify($password, $user['password'])) {
                    error_log("Invalid credentials: Password incorrect"); // Debugging
                    return ['error' => 'Invalid credentials'];
                }

                // Set session variables
                $this->session->set('user_id', $user['user_id']);
                $this->session->set('role', $user['role']);
                $this->session->set('email', $user['email']);
                $this->session->set('authenticated', true);

                error_log("Session after setting: " . print_r($_SESSION, true)); // Debugging

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: /dashboard');
                    exit();
                } else if ($user['role'] === 'customer') {
                    header('Location: /shop');
                    exit();
                }
            } catch (Exception $e) {
                error_log("Login error: " . $e->getMessage());
                return ['error' => 'An error occurred during login'];
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
