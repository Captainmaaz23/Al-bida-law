<!-- resources/views/emails/contact_submitted.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Contact Submission</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 30px;">

    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 25px 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);">

        <h2 style="color: #0d6efd; border-bottom: 1px solid #dee2e6; padding-bottom: 10px;">New Contact Submission</h2>

        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="padding: 8px 0;"><strong>Full Name:</strong></td>
                <td style="padding: 8px 0;">{{ $contact->fullname }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0;"><strong>Email:</strong></td>
                <td style="padding: 8px 0;">{{ $contact->email }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0;"><strong>Phone:</strong></td>
                <td style="padding: 8px 0;">{{ $contact->phone }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0;"><strong>Date:</strong></td>
                <td style="padding: 8px 0;">{{ $contact->date }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; vertical-align: top;"><strong>Message:</strong></td>
                <td style="padding: 8px 0;">{{ $contact->message }}</td>
            </tr>
        </table>

        <p style="margin-top: 30px; font-size: 13px; color: #6c757d;">
            This is an automated message from your contact form. Please do not reply directly to this email.
        </p>
    </div>

</body>
</html>
