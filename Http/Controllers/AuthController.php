<?php

namespace Modules\AdminManagement\Http\Controllers;

use Modules\AdminManagement\Models\User;
use Modules\AdminManagement\Models\PersonalAccessToken;
use Modules\Shared\Models\Setting;

class AuthController
{
    /**
     * Render the Login view.
     */
    public function showLoginForm($viewFactory)
    {
        $appSettings = Setting::getAllKeyValues();
        $error = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);

        return $viewFactory->make('auth.login', [
            'appSettings' => $appSettings,
            'error' => $error,
        ]);
    }

    /**
     * Handle Login submission (Web and API).
     */
    public function login(array $data, bool $isApi = false)
    {
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            $msg = 'Email and password are required.';
            if ($isApi) {
                http_response_code(422);
                return ['status' => 'error', 'message' => $msg];
            }
            $_SESSION['auth_error'] = $msg;
            header('Location: /login');
            exit;
        }

        $user = User::where('email', $email)->first();

        if (!$user || !$user->verifyPassword($password)) {
            $msg = 'Invalid email or password.';
            if ($isApi) {
                http_response_code(401);
                return ['status' => 'error', 'message' => $msg];
            }
            $_SESSION['auth_error'] = $msg;
            header('Location: /login');
            exit;
        }

        if (!$user->isActive()) {
            $msg = 'Your account has been deactivated. Please contact administrator.';
            if ($isApi) {
                http_response_code(403);
                return ['status' => 'error', 'message' => $msg];
            }
            $_SESSION['auth_error'] = $msg;
            header('Location: /login');
            exit;
        }

        // Generate Sanctum Personal Access Token
        $tokenObj = $user->createToken('web_session');
        $plainToken = $tokenObj->plainTextToken;

        // Set Web Session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['auth_token'] = $plainToken;

        // Handle Remember Me (7-day cookie)
        if (!empty($data['remember'])) {
            setcookie('guestpost_auth', $plainToken, time() + (86400 * 7), '/', '', false, true);
        }

        if ($isApi) {
            return [
                'status' => 'success',
                'message' => 'Authentication successful.',
                'token' => $plainToken,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'avatar' => $user->avatar,
                ],
            ];
        }

        header('Location: /');
        exit;
    }

    /**
     * Handle Logout.
     */
    public function logout(bool $isApi = false)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Revoke token if active
        if (!empty($_SESSION['auth_token'])) {
            $tokenInstance = PersonalAccessToken::findToken($_SESSION['auth_token']);
            if ($tokenInstance) {
                $tokenInstance->delete();
            }
        }

        // Clear session & remember cookie
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        setcookie('guestpost_auth', '', time() - 3600, '/');

        if ($isApi) {
            return [
                'status' => 'success',
                'message' => 'Successfully logged out.',
            ];
        }

        header('Location: /login');
        exit;
    }

    /**
     * Return Authenticated User Info.
     */
    public function me($user)
    {
        if (!$user) {
            http_response_code(401);
            return ['status' => 'error', 'message' => 'Unauthenticated'];
        }

        return [
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'avatar' => $user->avatar,
                'status' => $user->status,
                'tokens_count' => $user->tokens()->count(),
            ],
        ];
    }
}
