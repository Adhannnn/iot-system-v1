<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Montserrat:wght@400;700&display=swap"
        rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @keyframes gradientAnimation {
            0% {background-position: 0% 50%}
            50% {background-position: 100% 50%}
            100% {background-position: 0% 50%}
        }

        .gradient-animation {
            background: linear-gradient(-45deg, #4158D0, #e560dc, #ffb374, #ef5f5f, #d840b6);
            background-size: 400% 400%;
            animation: gradientAnimation 10s ease infinite;
            z-index: -1;
        }

        html,
        body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Montserrat', sans-serif;
        }

        .font-jetbrains {
            font-family: 'JetBrains Mono', monospace;
        }

        .font-roboto {
            font-family: "Roboto", sans-serif;
        }
    </style>
</head>

<body class="h-screen w-screen overflow-hidden m-0 p-0">
    <!-- Background dan kontainer utama -->
    <div class="flex justify-center items-center h-screen w-screen gradient-animation">
        <!-- Box login -->
        <div
            class="w-full max-w-md sm:max-w-lg md:max-w-xl bg-white p-6 sm:p-8 rounded-xl shadow-xl shadow-black/30 z-10">
            <p class="roboto-font text-center font-bold text-xl sm:text-2xl">Welcome To Sensor Monitoring System!</p>
            <p class="text-center mt-2 text-sm italic text-gray-500">
                Please input your <b>Username</b> and your <b>Password</b>
            </p>

            <form class="flex flex-col items-center mt-6 space-y-6 w-full" method="POST"
                action="{{ route('login') }}">
                @csrf
                <!-- Name Input -->
                <div class="relative w-full">
                    <input type="text" id="username" name="username" placeholder="Username"
                        class="peer w-full px-4 py-3 text-gray-900 bg-transparent border-2 border-gray-400 rounded-xl 
              focus:outline-none focus:border-blue-500 transition-all placeholder-gray-400 focus:placeholder-transparent" />
                    <label for="name"
                        class="absolute left-4 text-sm text-gray-500 bg-white px-1 
              transition-all duration-300 ease-in-out opacity-0 scale-95 -top-2.5 
              peer-focus:opacity-100 peer-focus:scale-100 peer-focus:top-[-10px] peer-focus:px-1
              peer-placeholder-shown:opacity-0 peer-placeholder-shown:scale-95 peer-placeholder-shown:top-3.5 peer-placeholder-shown:px-0">
                        Username
                    </label>
                    @if ($errors->has('username'))
                        <script>
                            Swal.fire({
                                icon: "error",
                                title: "Login Failed",
                                text: "Wrong Username or Password",
                                confirmButtonColor: "#3085d6"
                            })
                        </script>
                    @endif
                </div>

                <!-- password Input -->
                <div class="relative w-full">
                    <input type="password" id="password" name="password" placeholder="Password"
                        class="peer w-full px-4 py-3 text-gray-900 bg-transparent border-2 border-gray-400 rounded-xl 
              focus:outline-none focus:border-blue-500 transition-all placeholder-gray-400 focus:placeholder-transparent" />
                    <label for="nim"
                        class="absolute left-4 text-sm text-gray-500 bg-white px-1 
              transition-all duration-300 ease-in-out opacity-0 scale-95 -top-2.5 
              peer-focus:opacity-100 peer-focus:scale-100 peer-focus:top-[-10px] peer-focus:px-1
              peer-placeholder-shown:opacity-0 peer-placeholder-shown:scale-95 peer-placeholder-shown:top-3.5 peer-placeholder-shown:px-0">
                        Password
                    </label>
                    @if ($errors->has('password'))
                        <script>
                            Swal.fire({
                                icon: "error",
                                title: "Login Failed",
                                text: "Wrong Username or Password",
                                confirmButtonColor: "#3085d6"
                            })
                        </script>
                    @endif
                </div>

                <!-- Tombol login -->
                <button type="submit"
                    class="w-full px-6 py-3 text-white bg-blue-500 font-bold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>

</html>
