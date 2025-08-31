<?php
ini_set('session.cookie_secure', '1'); 
ini_set('session.cookie_httponly', '1'); 
session_start();
include '../db.php';
include '../isLogin.php';

$email = $_SESSION['email'];
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>• RoShield | Webhook Security</title>
    <link rel="icon" href="https://cdn.roshield.net/logos/RSmainLogo2.png">
    <meta property="og:image" content="https://cdn.roshield.net/RoShieldMini1.png">
    <meta name="description" content="RoShield - Professional security tools and solutions">
    <meta property="og:description" content="RoShield - Comprehensive security and management tools">
    <meta property="og:title" content="RoShield - Webhook Security">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://app.roshield.net/webhook-security">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/notyf@3.10.0/notyf.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3.10.0/notyf.min.js"></script>
    <style>
        :root {
            --primary-color: #3a86ff;
            --primary-hover: #2667cc;
            --background-dark: #0f0f13;
            --background-light: #16161e;
            --card-bg: rgba(22, 22, 30, 0.85);
            --card-hover: rgba(26, 26, 36, 0.95);
            --card-border: rgba(255, 255, 255, 0.07);
            --text-primary: #f0f0f0;
            --text-secondary: #a0a0a0;
            --shadow-color: rgba(0, 0, 0, 0.3);
            --accent-color: #4e9af1;
            --success-color: #4BB543;
            --card-radius: 14px;
            --transition-speed: 0.25s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', 'Roboto', sans-serif;
        }

        body {
            background-color: var(--background-dark);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.5;
        }

        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: var(--background-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loader {
            width: 40px;
            height: 40px;
            position: relative;
        }

        .loader:before, .loader:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: var(--primary-color);
            opacity: 0.6;
            animation: pulse 2s ease-in-out infinite;
        }

        .loader:after {
            animation-delay: -1s;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(0);
                opacity: 0.6;
            }
            50% {
                transform: scale(1);
                opacity: 0;
            }
        }

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 20px;
        }

        header {
            background-color: rgba(15, 15, 19, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--card-border);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .brand img {
            height: 42px;
            transition: transform 0.3s ease;
        }

        .brand-text {
            font-size: 22px;
            font-weight: 600;
            background: linear-gradient(45deg, var(--primary-color), #4f6df5);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .back-link {
            display: flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(45deg, var(--primary-color), #4f6df5);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(58, 134, 255, 0.3);
        }

        main {
            padding: 40px 0;
            min-height: calc(100vh - 80px);
            position: relative;
            overflow-x: hidden;
        }

        .section-title {
            width: 100%;
            text-align: center;
            margin-bottom: 40px;
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary-color), #4f6df5);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .webhook-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 30px;
        }

        .z4v-mascot {
            position: fixed;
            bottom: -70px;
            left: -20px;
            width: 200px;
            height: auto;
            transition: bottom 0.3s ease-in-out;
            z-index: 99;
            pointer-events: none;
            transform: translateZ(0);
        }

        .z4v-mascot-link {
            position: fixed;
            bottom: -70px;
            left: 0;
            width: 160px;
            height: 100px;
            z-index: 100;
            cursor: pointer;
        }

        .z4v-mascot-link:hover + .z4v-mascot {
            bottom: -50px;
        }

        .webhook-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--card-radius);
            padding: 25px;
            transition: var(--transition-speed);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .webhook-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            background: var(--card-hover);
        }

        .webhook-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .webhook-icon {
            font-size: 24px;
            color: var(--primary-color);
        }

        .webhook-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .webhook-description {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 20px;
        }

        .webhook-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--card-border);
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .stat-value {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .coming-soon-badge {
            background: linear-gradient(45deg, var(--primary-color), #4f6df5);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-top: 10px;
        }

        .language-notice {
            background: linear-gradient(90deg, var(--primary-color), #4f6df5);
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .webhook-container {
                grid-template-columns: 1fr;
            }

            .section-title {
                font-size: 24px;
                margin-bottom: 30px;
            }

            .webhook-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div id="loading-screen">
        <div class="loader"></div>
    </div>

    <div class="language-notice">
        This page currently supports English language only
    </div>

    <header>
        <div class="container">
            <div class="header-content">
                <div class="brand">
                    <img src="https://cdn.roshield.net/logos/RSmainLogo.png" alt="Logo">
                    <div class="brand-text">Webhook Security</div>
                </div>
                <div class="header-actions">
                    <button class="back-link" onclick="window.location.href='https://app.roshield.net'">
                        <i class="fas fa-home"></i>
                        <span>Back</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                <i class="fas fa-wind" style="font-size: 40px; margin: 15px 0; opacity: 0.7; color: var(--text-secondary);"></i>
                <p style="font-size: 18px; font-weight: 500; color: var(--text-secondary);">Coming Soon with Z4V</p>
            </div>
        </div>
        <a href="https://z4v.eu" class="z4v-mascot-link"></a>
        <img src="https://z4v.eu/assets/z4v_mascot2.png" alt="Z4V Mascot" class="z4v-mascot">
    </main>

    <script>
        const loadingScreen = document.getElementById('loading-screen');
        
        window.addEventListener('load', () => {
            loadingScreen.style.opacity = '0';
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 500);
        });
    </script>
</body>
</html>