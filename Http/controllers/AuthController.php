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
                // Verify content type
                if (empty($_SERVER['CONTENT_TYPE']) || stripos($_SERVER['CONTENT_TYPE'], 'application/json') === false) {
                    throw new Exception('Invalid content type');
                }

                // Get JSON data from request body
                $input = json_decode(file_get_contents('php://input'), true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('Invalid JSON data');
                }

                $data = [
                    'email' => $input['email'],
                    'mobile_number' => $input['mobile_number'],
                    'password' => $input['password'],
                    'confirm_password' => $input['confirm_password'],
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'gender' => $input['gender'],
                    'birthdate' => $input['birthdate'],
                    'terms_accepted' => $input['terms_accepted'] ? 1 : 0,
                    'role' => 'customer',
                ];

                // Log the processed data
                error_log('Processed registration data: ' . print_r($data, true));

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
                    throw new ValidationException(['confirm_password' => ['Passwords do not match']]);
                }

                // Create user with complete data
                $userId = $this->user->create($data);
                
                // Set session data
                $this->session->set('user_id', $userId);
                $this->session->set('role', 'customer');

                // Return JSON response
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Registration successful',
                    'redirect' => '/login'
                ]);
                exit();
            } catch (ValidationException $e) {
                error_log('Validation Error: ' . print_r($e->getErrors(), true));
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'errors' => $e->getErrors()
                ]);
                exit();
            } catch (Exception $e) {
                error_log('Signup Error: ' . $e->getMessage());
                error_log('Trace: ' . $e->getTraceAsString());
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'error' => 'An error occurred during signup. Please try again.'
                ]);
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
                    throw new Exception('Invalid credentials');
                }

                if (!password_verify($password, $user['password'])) {
                    throw new Exception('Invalid credentials');
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
