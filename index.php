<?php
    session_start();
    require_once("./config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="author" content="url shortener">
    <meta name="description" content="Quick and easy URL shortening service to make your links short, clean, and easy to share.">
    <meta name="keywords" content="URL shortener, link shortening, custom URL, free URL shortener">
    <meta property="og:title" content="<?php echo SITE_NAME; ?>" />
    <meta property="og:description" content="Shorten your URLs with ease. Use our tool to create clean, shareable links." />
    <meta property="og:image" content="assets/images/og-image.jpg" />
    <meta property="og:url" content="https://www.example.com" />
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <style>
        /* General body styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        /* Main container for centering content */
        .container {
            width: 90%;
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }

        p {
            font-size: 1em;
            color: #555;
            margin-bottom: 20px;
        }

        .form-container {
            display: flex;
            flex-direction: column;
        }

        /* Input fields */
        .input, .input_custom {
            width: 97%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            background-color: #f8f9fa;
        }

        .input_custom:disabled {
            background-color: #f0f0f0;
        }

        /* Submit button */
        .submit {
            width: 100%;
            padding: 14px;
            background-color: #28a745;
            color: white;
            font-size: 1.1em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit:hover {
            background-color: #218838;
        }

        /* Success message */
        .success {
            display: inline-block;
            padding: 15px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            border-radius: 25px;
            position: relative;
            margin: 10px 0;
            width:95%;
        }
        
        .container a {
            color:white;
        }
        

        /* Copy button */
        .copy-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background-color: #fff;
            color: #28a745;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 20px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .copy-btn:hover {
            background-color: #f0f0f0;
        }

        /* Error messages */
        .alert {
            padding: 15px;
            background-color: #dc3545;
            color: white;
            font-size: 16px;
            border-radius: 25px;
            text-align: center;
            margin: 10px 0;
        }

        /* Toggle switch styles */
        .onoffswitch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .onoffswitch-checkbox {
            display: none;
        }

        .onoffswitch-label {
            position: absolute;
            top: 0;
            left: 0;
            width: 50px;
            height: 24px;
            background-color: #ddd;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .onoffswitch-label:after {
            content: "";
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            background-color: white;
            border-radius: 50%;
            transition: transform 0.3s;
        }

        .onoffswitch-checkbox:checked + .onoffswitch-label {
            background-color: #28a745;
        }

        .onoffswitch-checkbox:checked + .onoffswitch-label:after {
            transform: translateX(26px);
        }

       
    </style>
</head>
<body>

<div class="container">
    <h1><?php echo SITE_NAME; ?></h1>
    <p>Shorten your URLs with ease! Simply paste your URL and get a shorter version. You can even choose a custom alias for your link.</p>

    <?php
      if (isset($_SESSION['success'])) {
            $sorturl = $_SESSION['success'];
            echo "<p class='success'>" . $sorturl . 
                 "<button class='copy-btn' onclick='copyToClipboard()'>Copy</button></p>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<p class='alert'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        if (isset($_GET['error']) && $_GET['error'] == 'db') {
            echo "<p class='alert'>Error in connecting to database!</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'inurl') {
            echo "<p class='alert'>Not a valid URL!</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 'dnp') {
            echo "<p class='alert'>Ok! So I got to know you love playing! But don't play here!!!</p>";
        }
    ?>

    <div class="form-container">
        <form method="POST" action="functions/shorten.php">
            <input type="url" id="input" name="url" class="input" placeholder="Enter a URL here" required>
            <div class="custom-option">
                <input type="text" id="custom" name="custom" class="input_custom" placeholder="Enable custom text" disabled>
                <div class="onoffswitch">
                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" onclick="toggle()">
                    <label class="onoffswitch-label" for="myonoffswitch"></label>
                </div>
            </div>
            <input type="submit" value="Go" class="submit">
        </form>
    </div>

    <script>
      function toggle () {
        if (document.getElementById('myonoffswitch').checked) {
          document.getElementById('custom').placeholder = 'Enter your custom text'
          document.getElementById('custom').disabled = false
          document.getElementById('custom').focus()
        }
        else {
          document.getElementById('custom').value = ''
          document.getElementById('custom').placeholder = 'Enable custom text'
          document.getElementById('custom').disabled = true
          document.getElementById('custom').blur()
          document.getElementById('input').focus()
        }
      }

      function copyToClipboard() {
          var text = document.querySelector('.success').textContent.replace('Copy', '').trim();
          navigator.clipboard.writeText(text).then(function() {
              alert("URL copied to clipboard!");
          }, function(err) {
              alert("Error copying text: " + err);
          });
      }
    </script>
</div>

</body>
</html>
