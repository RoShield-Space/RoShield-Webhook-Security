<?php
$main = realpath(__DIR__ . '/../');
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.wxer.tech/assets/qr-code-styling/lib/qr-code-styling.js"></script>
</head>

<style>
    .footer {
        background: linear-gradient(15deg, #0a0a0a, #1a1a1a);
        color: #e0e0e0;
        padding: 40px 20px 20px;
        font-family: 'Segoe UI', system-ui, sans-serif;
        position: relative;
    }

    .footer:before {
        content: '';
        position: absolute;
        top: -5px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(
            90deg, 
            #4fa4e9 0%, 
            #3953b2 50%, 
            #4fa4e9 100%
        );
        background-size: 200% auto;
        box-shadow: 0 0 15px rgba(79, 164, 233, 0.3);
        animation: gradientFlow 3s linear infinite;
    }

    @keyframes gradientFlow {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    .footer-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .footer-section {
        padding: 15px;
        position: relative;
    }

    .logo-section {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .footer-logo-img {
        width: 140px;
        margin-bottom: 15px;
        filter: drop-shadow(0 2px 5px rgba(79, 164, 233, 0.2));
        transition: transform 0.3s ease;
    }

    .footer-logo-img:hover {
        transform: scale(1.05);
    }

    .logo-section p {
        font-size: 14px;
        color: #b0b0b0;
        line-height: 1.6;
        max-width: 280px;
        position: relative;
        padding-left: 20px;
    }

    .logo-section p:before {
        content: '';
        position: absolute;
        left: 0;
        top: 5px;
        height: 60%;
        width: 3px;
        background: linear-gradient(#4fa4e9, #2e5bff);
    }

    h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #f0f0f0;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
    }

    h3:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 2px;
        background: linear-gradient(90deg, #2e7df3, transparent);
    }

    .footer ul {
        display: flex;
        flex-direction: column;
        gap: 12px;
        list-style-type: none;
    }

    .footer ul li {
        position: relative;
        transition: transform 0.3s ease;
    }

    .footer ul li:hover {
        transform: translateX(5px);
    }

    .footer ul li a {
        color: #b0b0b0;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .footer ul li a:hover {
        color: #4fa4e9;
        text-shadow: 0 0 10px rgba(79, 164, 233, 0.3);
    }

    .footer ul li a i {
        width: 20px;
        font-size: 14px;
    }

    .social-icons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .social-icons a {
        width: 40px;
        height: 40px;
        background: rgba(79, 164, 233, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .social-icons a:hover {
        background: #4fa4e9;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(79, 164, 233, 0.3);
    }

    .qr-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    #qrCode {
        width: 120px;
        height: 120px;
        background: #fff;
        padding: 10px;
        border-radius: 12px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    #qrCode:hover {
        transform: scale(1.05);
    }

    .footer-bottom {
        margin-top: 40px;
        padding-top: 20px;
        text-align: center;
        font-size: 12px;
        color: #808080;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .footer-bottom a {
        color: #4fa4e9;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .footer-bottom a:hover {
        color: #fff;
        text-shadow: 0 0 10px rgba(79, 164, 233, 0.5);
    }

    @media (max-width: 768px) {
        .footer {
            padding: 30px 15px 15px;
        }

        .footer-container {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .footer-section {
            padding: 10px;
            text-align: center;
        }

        .logo-section {
            align-items: center !important;
        }

        .footer-logo-img {
            width: 120px !important;
        }

        .social-icons {
            justify-content: center;
            flex-wrap: wrap;
        }

        .footer-bottom {
            font-size: 10px !important;
            margin-top: 25px !important;
        }

        h3 {
            font-size: 1rem !important;
            margin-bottom: 15px !important;
        }

        .footer ul li a {
            font-size: 13px !important;
        }
    }

    @media (max-width: 480px) {
        .footer {
            padding: 25px 10px 10px;
        }

        .footer-container {
            gap: 20px;
        }

        .social-icons a {
            width: 35px !important;
            height: 35px !important;
        }

        .footer-bottom p {
            line-height: 1.4;
        }
    }
</style>
<body>
    <div class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>Quick Navigation</h3>
                <ul>
                    <li><a href="https://www.roshield.net/docs/"><i class="fas fa-chevron-right"></i>Documentation</a></li>
                    <li><a href="https://www.roshield.net/blog/"><i class="fas fa-chevron-right"></i>Blog & Updates</a></li>
                    <li><a href="https://www.roshield.net/support/"><i class="fas fa-chevron-right"></i>Support Center</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Legal</h3>
                <ul>
                    <li><a href="https://www.roshield.net/docs/termsofservice/"><i class="fas fa-balance-scale"></i>Terms of Service</a></li>
                    <li><a href="https://www.roshield.net/docs/privacypolicy/"><i class="fas fa-shield-alt"></i>Privacy Policy</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Connect With Us</h3>
                <div class="social-icons">
                    <a href="https://www.roshield.net/discord" target="_blank"><i class="fab fa-discord"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>©2025 RoShield. All rights reserved. 
        </div>
    </div>
</body>
</html>