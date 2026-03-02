<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $studentId = htmlspecialchars($_POST["studentId"]);
    $major = htmlspecialchars($_POST["major"]);
    $gpa = htmlspecialchars($_POST["gpa"]);

    echo "<!DOCTYPE html>\n";
    echo "<html lang=\"en\">\n";
    echo "<head>\n";
    echo "    <meta charset=\"UTF-8\">\n";
    echo "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
    echo "    <title>Submission Details</title>\n";
    echo "    <style>\n";
    echo "        body {\n";
    echo "            font-family: Arial, sans-serif;\n";
    echo "            background-color: #1a1a1a;\n";
    echo "            color: #f0f0f0;\n";
    echo "            display: flex;\n";
    echo "            justify-content: center;\n";
    echo "            align-items: center;\n";
    echo "            min-height: 100vh;\n";
    echo "            margin: 0;\n";
    echo "        }\n";
    echo "        .container {\n";
    echo "            background-color: #2a2a2a;\n";
    echo "            padding: 30px;\n";
    echo "            border-radius: 8px;\n";
    echo "            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);\n";
    echo "            width: 100%;\n";
    echo "            max-width: 400px;\n";
    echo "        }\n";
    echo "        h2 {\n";
    echo "            color: #4CAF50;\n";
    echo "            text-align: center;\n";
    echo "            margin-bottom: 20px;\n";
    echo "        }\n";
    echo "        p {\n";
    echo "            margin-bottom: 10px;\n";
    echo "        }\n";
    echo "        strong {\n";
    echo "            color: #4CAF50;\n";
    echo "        }\n";
    echo "    </style>\n";
    echo "</head>\n";
    echo "<body>\n";
    echo "    <div class=\"container\">\n";
    echo "        <h2>Submission Details</h2>\n";
    echo "        <p><strong>Name:</strong> " . $name . "</p>\n";
    echo "        <p><strong>Email:</strong> " . $email . "</p>\n";
    echo "        <p><strong>Student ID:</strong> " . $studentId . "</p>\n";
    echo "        <p><strong>Major:</strong> " . ($major ? $major : "N/A") . "</p>\n";
    echo "        <p><strong>GPA:</strong> " . ($gpa ? $gpa : "N/A") . "</p>\n";
    echo "    </div>\n";
    echo "</body>\n";
    echo "</html>\n";
} else {
    echo "<p>No direct access to this page.</p>";
}

?>
