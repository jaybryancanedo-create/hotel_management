<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background:
                linear-gradient(rgba(0, 15, 40, 0.35), rgba(0, 15, 40, 0.35)),
                url('https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1920')
                center/cover no-repeat;
        }

        main {
            width: 100%;
            max-width: 430px;
            padding: 45px 40px;
            border-top: 6px solid #00BCD4;
            border-radius: 20px;
            background: white;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        h1 {
            margin-bottom: 35px;
            color: #0D2B50;
            font-size: 38px;
            font-weight: 700;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        input {
            width: 100%;
            padding: 16px;
            border: 2px solid #D5E3F5;
            border-radius: 12px;
            outline: none;
            background: #F3F7FC;
            font-size: 15px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #0875D1;
            box-shadow: 0 0 8px rgba(8, 117, 209, 0.2);
        }

        button {
            padding: 16px;
            border: none;
            border-radius: 12px;
            background: #0865D9;
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #034FAE;
            transform: translateY(-2px);
        }

        .form-footer {
            margin-top: 28px;
            color: #58677D;
            font-size: 14px;
            text-align: center;
        }

        a {
            color: #0865D9;
            font-weight: 600;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            main {
                padding: 35px 25px;
            }

            h1 {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <main>
        {{ $slot }}
    </main>
</body>
</html>
