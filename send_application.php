<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Collect all form data
    $applicantData = [
        'fullName' => htmlspecialchars($_POST['fullName'] ?? ''),
        'email' => filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL),
        'phone' => htmlspecialchars($_POST['phone'] ?? ''),
        'position' => htmlspecialchars($_POST['position'] ?? ''),
        'agent' => htmlspecialchars($_POST['agent'] ?? ''),
        'agentLocation' => htmlspecialchars($_POST['agentLocation'] ?? ''),
        'timeslot' => htmlspecialchars($_POST['timeslot'] ?? '')
    ];

    // 2. Validate required fields
    $requiredFields = ['fullName', 'email', 'phone', 'position', 'agent', 'timeslot'];
    $missingFields = [];
    
    foreach ($requiredFields as $field) {
        if (empty($applicantData[$field])) {
            $missingFields[] = $field;
        }
    }

    if (!empty($missingFields)) {
        header("Location: error.html?missing=" . implode(',', $missingFields));
        exit();
    }

    // 3. Configure email settings
    $toEmployer = "your_recruiting@company.com"; // Replace with your HR email
    $toApplicant = $applicantData['email'];
    $subjectEmployer = "New Application: {$applicantData['position']}";
    $subjectApplicant = "Interview Confirmation for {$applicantData['position']}";

    // 4. Create employer email content
    $employerEmail = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .header { color: #2563eb; font-size: 24px; margin-bottom: 20px; }
            .detail { margin-bottom: 10px; }
            .label { font-weight: bold; color: #4b5563; }
        </style>
    </head>
    <body>
        <div class='header'>New Job Application Received</div>
        
        <div class='detail'><span class='label'>Position:</span> {$applicantData['position']}</div>
        <div class='detail'><span class='label'>Applicant:</span> {$applicantData['fullName']}</div>
        <div class='detail'><span class='label'>Email:</span> {$applicantData['email']}</div>
        <div class='detail'><span class='label'>Phone:</span> {$applicantData['phone']}</div>
        <div class='detail'><span class='label'>Interviewer:</span> {$applicantData['agent']}</div>
        <div class='detail'><span class='label'>Location:</span> {$applicantData['agentLocation']}</div>
        <div class='detail'><span class='label'>Scheduled Time:</span> {$applicantData['timeslot']}</div>
        
        <div style='margin-top: 30px;'>
            <p>This interview was scheduled through the online application portal.</p>
        </div>
    </body>
    </html>
    ";

    // 5. Create applicant confirmation email
    $applicantEmail = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .header { color: #2563eb; font-size: 24px; margin-bottom: 20px; }
            .detail { margin-bottom: 10px; }
            .label { font-weight: bold; color: #4b5563; }
        </style>
    </head>
    <body>
        <div class='header'>Interview Confirmation</div>
        
        <p>Dear {$applicantData['fullName']},</p>
        
        <div class='detail'><span class='label'>Position:</span> {$applicantData['position']}</div>
        <div class='detail'><span class='label'>Interviewer:</span> {$applicantData['agent']}</div>
        <div class='detail'><span class='label'>Location:</span> {$applicantData['agentLocation']}</div>
        <div class='detail'><span class='label'>Time:</span> {$applicantData['timeslot']}</div>
        
        <div style='margin-top: 20px;'>
            <p>Please arrive 10 minutes early for your interview.</p>
            <p>Bring copies of your resume and any relevant work samples.</p>
        </div>
        
        <div style='margin-top: 30px;'>
            <p>Best regards,<br>Human Resources Team</p>
        </div>
    </body>
    </html>
    ";

    // 6. Set email headers
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: careers@yourcompany.com',
        'Reply-To: ' . $applicantData['email']
    ];

    // 7. Send emails
    $employerSent = mail(
        $toEmployer,
        $subjectEmployer,
        $employerEmail,
        implode("\r\n", $headers)
    );

    $applicantSent = mail(
        $toApplicant,
        $subjectApplicant,
        $applicantEmail,
        implode("\r\n", array_merge($headers, ['From: no-reply@yourcompany.com']))
    );

    // 8. Handle results
    if ($employerSent && $applicantSent) {
        // Log successful submission (for debugging)
        file_put_contents('submissions.log', 
            date('Y-m-d H:i:s') . " - {$applicantData['email']}\n", 
            FILE_APPEND
        );
        header("Location: thank-you.html");
    } else {
        // Log email errors
        error_log("Email failed to send for: {$applicantData['email']}");
        header("Location: error.html?code=email_failed");
    }
    exit();
} else {
    // Not a POST request - redirect to home
    header("Location: index.html");
    exit();
}
?>