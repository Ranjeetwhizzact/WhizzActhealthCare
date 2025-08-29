<!DOCTYPE html>
<html>
<head>
    <title>Customer Availability</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333333; line-height: 1.6; margin: 0; padding: 20px; background-color: #f9f9f9;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
        <div style="text-align: center;">
            <img src="{{url('/assests/img/honestlogo.png')}}" alt="WhizzCare" style="max-width: 180px; margin-bottom: 20px;">
        </div>

        <p style="margin-top: 0;">Dear Admin,</p>

        <p>The customer <strong>{{ $data['name'] }}</strong> has submitted their availability details:</p>

        <ul style="padding-left: 20px; margin-top: 10px; margin-bottom: 20px;">
            <li><strong>Date:</strong> {{ $data['date'] }}</li>
            <li><strong>Time:</strong> {{ $data['time'] }}</li>
            <li><strong>Meeting Link:</strong> <a href="{{ $data['meetinglink'] }}" target="_blank" rel="noopener noreferrer" style="color: #007BFF; text-decoration: none;">Join Meeting</a></li>
        </ul>

        <p>Please arrange accordingly.</p>

        <p>Best Regards,<br>
        WhizzCare Team</p>

        <div style="margin-top: 40px; font-size: 14px; color: #777777; text-align: center; border-top: 1px solid #eeeeee; padding-top: 20px;">
            <p style="margin: 0;">WhizzCare Pvt. Ltd.<br>
            Mumbai, Maharashtra - 400068<br>
            Email: <a href="mailto:honesthealthcare3@gmail.com" style="color: #007BFF; text-decoration: none;">honesthealthcare3@gmail.com</a><br>
            Website: <a href="honesthealthcare3@gmail.com" target="_blank" style="color: #007BFF; text-decoration: none;">honesthealthcare3@gmail.com</a></p>
        </div>
    </div>
</body>
</html>
