<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = htmlspecialchars($_POST['first_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $position = htmlspecialchars($_POST['position']);
    $resume = $_FILES['resume'];

    if ($resume['size'] > 5000000) {
        header("Location: apply.html?status=file_too_large");
        exit();
    }

    $fileType = strtolower(pathinfo($resume['name'], PATHINFO_EXTENSION));
    if ($fileType != 'pdf') {
        header("Location: apply.html?status=invalid_file");
        exit();
    }

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $resumeName = uniqid() . '.pdf';
    $resumePath = $uploadDir . $resumeName;
    
    if (move_uploaded_file($resume['tmp_name'], $resumePath)) {
        $toEmployer = "perisetlathanuj@gmail.com";
        $subjectEmployer = "Job Application: $firstName $lastName for $position";
        $boundary = md5(time());
        
        $headers = "From: $email\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
        
        $message = "--$boundary\r\n";
        $message .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
        $message .= "Name: $firstName $lastName\n";
        $message .= "Email: $email\n";
        $message .= "Phone: $phone\n";
        $message .= "Position: $position\n\n";
        
        $fileContent = file_get_contents($resumePath);
        $encodedContent = chunk_split(base64_encode($fileContent));
        
        $message .= "--$boundary\r\n";
        $message .= "Content-Type: application/pdf; name=\"$resumeName\"\r\n";
        $message .= "Content-Disposition: attachment; filename=\"$resumeName\"\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $message .= $encodedContent . "\r\n";
        $message .= "--$boundary--";

        $emailSentToEmployer = mail($toEmployer, $subjectEmployer, $message, $headers);

        $subjectApplicant = "Application Received - $position";
        $messageApplicant = "Dear $firstName,\n\nThank you for applying for $position.\n\nWe'll contact you soon.\n\nBest regards,\nAgentConnect";
        $headersApplicant = "From: no-reply@yourcompany.com\r\n";
        $emailSentToApplicant = mail($email, $subjectApplicant, $messageApplicant, $headersApplicant);

        header("Location: apply.html?status=" . ($emailSentToEmployer && $emailSentToApplicant ? "success" : "error"));
        exit();
    } else {
        header("Location: apply.html?status=upload_error");
        exit();
    }
}
?>