<?php
session_start();

require_once 'connect.php';
require_once 'user.php';

$database = new Database();
$db = $database->connect();

$user = new user($db);

// SIGN UP
if (isset($_POST['register_btn'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user->register($name, $email, $password)) {
        header('Location: dashboard.php');
    } else {
        echo "FAILED";
    }
}

//SIGN IN 
if (isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($user->login($email, $password)) {
        header('Location: dashboard.php');
    } else {
        echo "FAILED";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Wallet - Login</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://kit.fontawesome.com/559afa4763.js" crossorigin="anonymous"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-glass-border: rgba(255, 255, 255, 0.1);
        }
        
        /* Floating Animation */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Custom glow for focused inputs */
        .input-glow:focus {
            box-shadow: 0 0 15px rgba(34, 211, 238, 0.3); /* Cyan glow */
        }
    </style>
</head>

<body class="bg-[#0f172a] flex items-center justify-center min-h-screen relative overflow-hidden text-white font-sans">

    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute top-0 left-10 w-96 h-96 bg-cyan-500 rounded-full mix-blend-screen filter blur-[100px] opacity-30 animate-blob"></div>
        <div class="absolute top-10 right-10 w-96 h-96 bg-violet-600 rounded-full mix-blend-screen filter blur-[100px] opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-fuchsia-600 rounded-full mix-blend-screen filter blur-[100px] opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative w-full max-w-md p-px bg-white/5 border border-white/10 rounded-2xl shadow-2xl backdrop-blur-xl z-10 overflow-hidden min-h-[580px]">

        <div class="absolute inset-0 bg-black/40 rounded-2xl"></div>

        <div id="login-section" class="relative w-full h-full p-8 transition-all duration-500 ease-in-out transform translate-x-0 opacity-100">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-blue-600 rounded-lg flex items-center justify-center shadow-lg shadow-cyan-500/30">
                    <i class="fa-solid fa-wallet text-xl"></i>
                </div>
            </div>

            <h2 class="text-3xl font-bold text-center mb-1 text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400">Welcome Back</h2>
            <p class="text-gray-400 text-center mb-8 text-sm">Access your Smart Wallet</p>

            <form action="index.php" method="POST" class="flex flex-col gap-5">
                <div class="relative group">
                    <i class="fa-solid fa-envelope absolute left-4 top-3.5 text-gray-400 group-focus-within:text-cyan-400 transition-colors"></i>
                    <input type="email" name="email" placeholder="Email Address" required
                        class="input-glow w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:border-cyan-400 transition-all duration-300">
                </div>

                <div class="relative group">
                    <i class="fa-solid fa-lock absolute left-4 top-3.5 text-gray-400 group-focus-within:text-cyan-400 transition-colors"></i>
                    <input type="password" name="password" placeholder="Password" required
                        class="input-glow w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:border-cyan-400 transition-all duration-300">
                </div>

                <button type="submit" name="login_btn" class="mt-2 w-full bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold py-3 rounded-xl shadow-[0_0_20px_rgba(6,182,212,0.3)] hover:shadow-[0_0_25px_rgba(6,182,212,0.5)] transform transition-all hover:scale-[1.02] active:scale-95">
                    Sign In
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-gray-400 text-sm">Don't have an account?</p>
                <button onclick="toggleForms('register')" class="text-cyan-400 hover:text-cyan-300 font-bold text-sm mt-1 cursor-pointer transition-colors">
                    Create New Account
                </button>
            </div>
        </div>

        <div id="register-section" class="absolute top-0 left-0 w-full h-full p-8 transition-all duration-500 ease-in-out transform translate-x-full opacity-0 pointer-events-none">
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-fuchsia-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg shadow-fuchsia-500/30">
                    <i class="fa-solid fa-user-plus text-xl"></i>
                </div>
            </div>

            <h2 class="text-3xl font-bold text-center mb-1 text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-purple-400">Join Us</h2>
            <p class="text-gray-400 text-center mb-6 text-sm">Create your free account</p>

            <form action="index.php" method="POST" class="flex flex-col gap-4">
                <div class="relative group">
                    <i class="fa-solid fa-user absolute left-4 top-3.5 text-gray-400 group-focus-within:text-fuchsia-400 transition-colors"></i>
                    <input type="text" name="name" placeholder="Full Name" required
                        class="input-glow w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:border-fuchsia-400 transition-all duration-300">
                </div>

                <div class="relative group">
                    <i class="fa-solid fa-envelope absolute left-4 top-3.5 text-gray-400 group-focus-within:text-fuchsia-400 transition-colors"></i>
                    <input type="email" name="email" placeholder="Email Address" required
                        class="input-glow w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:border-fuchsia-400 transition-all duration-300">
                </div>

                <div class="relative group">
                    <i class="fa-solid fa-lock absolute left-4 top-3.5 text-gray-400 group-focus-within:text-fuchsia-400 transition-colors"></i>
                    <input type="password" name="password" placeholder="Password" required
                        class="input-glow w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 pl-12 pr-4 text-white placeholder-gray-500 focus:outline-none focus:border-fuchsia-400 transition-all duration-300">
                </div>

                <button type="submit" name="register_btn" class="mt-4 w-full bg-gradient-to-r from-fuchsia-500 to-purple-600 hover:from-fuchsia-400 hover:to-purple-500 text-white font-bold py-3 rounded-xl shadow-[0_0_20px_rgba(192,38,211,0.3)] hover:shadow-[0_0_25px_rgba(192,38,211,0.5)] transform transition-all hover:scale-[1.02] active:scale-95">
                    Sign Up
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-400 text-sm">Already have an account?</p>
                <button onclick="toggleForms('login')" class="text-fuchsia-400 hover:text-fuchsia-300 font-bold text-sm mt-1 cursor-pointer transition-colors">
                    Sign In Here
                </button>
            </div>
        </div>

    </div>

    <script>
        function toggleForms(target) {
            const loginSection = document.getElementById('login-section');
            const registerSection = document.getElementById('register-section');

            if (target === 'register') {
                // Login Out
                loginSection.classList.remove('translate-x-0', 'opacity-100');
                loginSection.classList.add('-translate-x-full', 'opacity-0', 'pointer-events-none');

                // Register In
                registerSection.classList.remove('translate-x-full', 'opacity-0', 'pointer-events-none');
                registerSection.classList.add('translate-x-0', 'opacity-100');
            } else {
                // Register Out
                registerSection.classList.remove('translate-x-0', 'opacity-100');
                registerSection.classList.add('translate-x-full', 'opacity-0', 'pointer-events-none');

                // Login In
                loginSection.classList.remove('-translate-x-full', 'opacity-0', 'pointer-events-none');
                loginSection.classList.add('translate-x-0', 'opacity-100');
            }
        }
    </script>
</body>

</html>