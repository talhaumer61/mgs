<?php

class User extends Controller {

    public function index()
    {
        if (Auth::isLogin()){
          return redirect("dashboard", [
            'type' => "error",
            'message' => "You are already logged In to the application"
          ]);
        }
        $this->view("auth/login");
    }

    public function login()
    {
        $body = Request::body();

        $user = UserModel::where('username', $body['username'])->first();

        if (!$user)
            throw new AppError("user", "Username or password is incorrect.");

        $options = [
            'cost' => 12,
        ];

        $clientPassword = $body['password'];

        if (!password_verify (  $clientPassword , $user->password ))
            throw new AppError("user", "Username or password is incorrect.");

        // User is Authenticated Login him in.
        Auth::login($user);

        return redirect("dashboard", [
            'type' => 'success',
            'message' => "You are now Logged In"
        ]);
    }

    public function logout()
    {
        $this->authMiddleware();

        Auth::logout();
        return redirect('user', [
            'type' => 'success',
            'message' => "Logged out successfully"
        ]);
    }
}