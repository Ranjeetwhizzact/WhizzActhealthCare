<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prescription</title>
    <style>
        body {
            font-family: "Helvetica Neue", Arial, sans-serif;
            line-height: 1.7;
            color: #2c3e50;
            background: #f3f4fd;
            margin: 0;
            padding: 0;
        }

        .page {
            max-width: 850px;
            margin: 0 auto;
            background: #fff;
            padding: 20px 25px 100px; /* extra bottom space for footer */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 50px;
            padding-bottom: 25px;
            border-bottom: 5px solid #3419AB;
        }
        .header img {
            max-width: 90px;
            margin-bottom: 12px;
        }
        .hospital-name {
            font-size: 30px;
            font-weight: bold;
            color: #3419AB;
        }
        .hospital-sub {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }
        /* SECTIONS */
        .section {
            background: #fff;
            border-radius: 7px;
            padding: 25px;
            margin-bottom: 35px;
            border: 1px solid #e4e4f5;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #3419AB;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #eaeaea;
        }

        /* TABLES */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #3419AB;
            color: #fff;
            font-weight: bold;
            text-align: left;
            padding: 6px 14px;
            font-size: 14px;
        }
        td {
            padding: 12px 14px;
            font-size: 14px;
            border-bottom: 1px solid #eee;
        }
        tr:nth-child(even) td {
            background: #f9f9ff;
        }

        /* SIGNATURE */
        .signature-block {
            margin-top: 60px;
            text-align: right;
            font-size: 15px;
            color: #2c3e50;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #333;
            width: 280px;
            float: right;
            text-align: center;
            padding-top: 8px;
            font-weight: bold;
        }

        /* FIXED FOOTER */
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0;
            right: 0;
            height: 70px;
            text-align: center;
            font-size: 13px;
            color: #555;
            border-top: 1px dashed #aaa;
            padding-top: 8px;
            background: #fff;
        }
    </style>
</head>
<body>

    <div class="page">
        <!-- Header -->
        <div class="header">
            <img src="{{ public_path('images/logo.png') }}" alt="Hospital Logo">
            <div class="hospital-name">Whizzact Hospital</div>
            <div class="hospital-sub">123 Main Street, Mumbai · +91-9876543210 · info@hospital.com</div>
        </div>

        <!-- Patient Info -->
        <div class="section">
            <div class="section-title">Patient Information</div>
            <table>
                <tr>
                    <th>Name</th>
                    <td>{{ $prescription->patient_name }}</td>  
                    <th>Gender</th>
                    <td>{{ $prescription->patient_gender }}</td>
                </tr>
                <tr>
                    <th>DOB</th>
                    <td colspan="3">{{ $prescription->patient_dob }}</td>
                </tr>
                <tr>
                    <th>Age</th>
                    <td colspan="3">{{ $prescription->patient_age }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td colspan="3">{{ $prescription->patient_phone }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td colspan="3">{{ $prescription->patient_email }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td colspan="3">{{ $prescription->patient_address }}</td>
                </tr>
            </table>
        </div>

        <!-- Physician Info -->
        <div class="section">
            <div class="section-title">Physician</div>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                </tr>
                <tr>
                    <td>{{ $prescription->physician_name }}</td>
                    <td>{{ $prescription->physician_phone }}</td>
                    <td >{{ $prescription->physician_email }}</td>
                </tr>
            </table>
        </div>

        <!-- Prescription Info -->
        <div class="section">
            <div class="section-title">Prescription Details</div>
            <table>
                <tr>
                    <th>Appointment ID</th>
                    <th>Prescription No</th>
                    <th>Date</th>
                    <th>Allergies</th>
                </tr>
                <tr>
                    <td>{{ $prescription->appointment_id }}</td>
                    <td>{{ $prescription->prescription_no }}</td>
                    <td>{{ $prescription->prescription_date }}</td>
                    <td>{{ $prescription->allergies }}</td>
                </tr>
            </table>

            <div style="color:#3419AB; font-weight:bold; margin-top:10px;">Notable Condition</div>
            <p style="font-size: 14px">{{ $prescription->notable_condition }}</p>
        </div>

        <!-- Medications -->
        <div class="section">
            <div class="section-title">Medications</div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Medication Name</th>
                        <th>Purpose</th>
                        <th>Dosage</th>
                        <th>Route</th>
                        <th>Frequency</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prescription->medications as $index => $med)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $med['medication_name'] }}</td>
                        <td>{{ $med['purpose'] }}</td>
                        <td>{{ $med['dosage'] }}</td>
                        <td>{{ $med['route'] }}</td>
                        <td>{{ $med['frequency'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Signature -->
        <div class="signature-block">
            <div class="signature-line">Doctor’s Signature</div>
        </div>
    </div>

    <!-- Fixed Footer -->
    <div class="footer">
        Generated by Whizzact Hospital System · Confidential Medical Document  
        Page <span class="page-number"></span> of <span class="total-pages"></span>
    </div>

</body>
</html>
