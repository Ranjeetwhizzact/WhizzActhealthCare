<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Availability Submitted</title>
</head>
<body style="background-color: #f9fafb; font-family: Arial, sans-serif; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

        <h2 style="font-size: 18px; font-weight: 600; color: #111827;">Availability Submitted</h2>

        <p style="font-size: 14px; color: #374151;">Dear Admin,</p>

        <p style="font-size: 14px; color: #374151;">
            The customer <strong>{{ $data['customer_name'] ?? 'Rohit' }}</strong> has submitted their availability details:
        </p>

        <ul style="font-size: 14px; color: #374151; line-height: 1.6;">
            <li><strong>Proposal Number:</strong> {{ $data['proposal_number'] }}</li>
            <li><strong>Date:</strong> {{ $data['avalibledate'] }}</li>
            <li><strong>Start Time:</strong> {{ $data['start_time'] }}</li>
            <li><strong>End Time:</strong> {{ $data['end_time'] }}</li>
        </ul>

        <p style="font-size: 14px; color: #374151; margin-top: 16px;">
            Please arrange the necessary actions based on the availability provided.
        </p>

        <p style="font-size: 14px; color: #374151;">Best Regards,</p>
        <p style="font-size: 14px; font-weight: bold; color: #111827;">Honest Health Care</p>
    </div>
</body>
</html>
