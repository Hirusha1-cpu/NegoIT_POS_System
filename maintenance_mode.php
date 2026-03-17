<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Construction</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Reset some default styles */
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        /* Container for the page */
        .construction-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            text-align: center;
            padding: 20px;
        }

        /* Message box styling */
        .message-box {
            max-width: 500px;
            background-color: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 30px;
            text-align: center;
        }

        /* Heading */
        .message-box h1 {
            font-size: 2rem;
            color: #ff9800;
            margin-bottom: 20px;
        }

        /* Paragraph */
        .message-box p {
            font-size: 1rem;
            color: #666;
            line-height: 1.5;
            margin-bottom: 30px;
        }

        /* Icon styling */
        .construction-icon {
            font-size: 4rem;
            color: #ff9800;
            animation: bounce 1.5s infinite;
        }

        /* Bounce animation */
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        /* Responsive design */
        @media screen and (max-width: 768px) {
            .message-box {
                padding: 20px;
            }

            .message-box h1 {
                font-size: 1.5rem;
            }

            .message-box p {
                font-size: 0.9rem;
            }

            .construction-icon {
                font-size: 3rem;
            }
        }

    </style>
</head>
<body>
    <div class="construction-container">
        <div class="message-box">
            <h1>We're Under Construction</h1>
            <p>Our website is currently undergoing scheduled maintenance. <br>We’ll be back soon. Thank you for your patience!</p>
            <div class="construction-icon">
                🚧
            </div>
        </div>
    </div>
</body>
</html>