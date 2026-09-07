<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - UniSIRAJ Attendance System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .landing-container {
            max-width: 1200px;
            width: 100%;
        }
        
        .header-section {
            text-align: center;
            margin-bottom: 50px;
            color: white;
        }
        
        .logo-container {
            background: white;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .logo-container i {
            font-size: 50px;
            color: #667eea;
        }
        
        .header-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .header-section p {
            font-size: 1.2rem;
            font-weight: 300;
            opacity: 0.95;
        }
        
        .role-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .role-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        
        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--card-color) 0%, var(--card-color-light) 100%);
        }
        
        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .role-card.admin {
            --card-color: #e74c3c;
            --card-color-light: #c0392b;
        }
        
        .role-card.lecturer {
            --card-color: #3498db;
            --card-color-light: #2980b9;
        }
        
        .role-card.student {
            --card-color: #2ecc71;
            --card-color-light: #27ae60;
        }
        
        .role-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: white;
        }
        
        .role-card.admin .role-icon {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }
        
        .role-card.lecturer .role-icon {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }
        
        .role-card.student .role-icon {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        }
        
        .role-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .role-card p {
            color: #7f8c8d;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .role-card .btn {
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            transition: all 0.3s ease;
        }
        
        .role-card.admin .btn {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }
        
        .role-card.lecturer .btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
        }
        
        .role-card.student .btn {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: white;
        }
        
        .role-card .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .footer {
            text-align: center;
            margin-top: 50px;
            color: white;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .features {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .feature-item {
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
        }
        
        .feature-item i {
            font-size: 20px;
        }
        
        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 2rem;
            }
            
            .header-section p {
                font-size: 1rem;
            }
            
            .role-cards {
                grid-template-columns: 1fr;
            }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
    </style>
</head>
<body>
    <div class="landing-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="logo-container pulse-animation">
                <i class="bi bi-qr-code-scan"></i>
            </div>
            <h1>UniSIRAJ Attendance System</h1>
            <p>Automated Student Attendance with QR Code Technology</p>
            
            <!-- Features -->
            <div class="features">
                <div class="feature-item">
                    <i class="bi bi-shield-check"></i>
                    <span>Secure</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-lightning-charge"></i>
                    <span>Fast</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-phone"></i>
                    <span>Mobile Friendly</span>
                </div>
                <div class="feature-item">
                    <i class="bi bi-clock-history"></i>
                    <span>Real-time</span>
                </div>
            </div>
        </div>
        
        <!-- Role Selection Cards -->
        <div class="role-cards">
            <!-- Admin Card -->
            <a href="<?= url('login?role=admin') ?>" class="role-card admin">
                <div class="role-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h3>Administrator</h3>
                <p>Manage students, lecturers, courses, and system settings. Access full system reports and analytics.</p>
                <button class="btn">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Admin Login
                </button>
            </a>
            
            <!-- Lecturer Card -->
            <a href="<?= url('login?role=lecturer') ?>" class="role-card lecturer">
                <div class="role-icon">
                    <i class="bi bi-person-workspace"></i>
                </div>
                <h3>Lecturer</h3>
                <p>Create attendance sessions, generate QR codes, and monitor student attendance in real-time.</p>
                <button class="btn">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Lecturer Login
                </button>
            </a>
            
            <!-- Student Card -->
            <a href="<?= url('login?role=student') ?>" class="role-card student">
                <div class="role-icon">
                    <i class="bi bi-person-badge"></i>
                </div>
                <h3>Student</h3>
                <p>Scan QR codes to mark attendance, view your attendance history and statistics for all courses.</p>
                <button class="btn">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Student Login
                </button>
            </a>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><i class="bi bi-mortarboard me-2"></i>Universiti Islam Antarabangsa Tuanku Syed Sirajuddin (UniSIRAJ)</p>
            <p style="font-size: 0.85rem; margin-top: 10px;">Final Year Project 2025/2026 - Ahmed Mohammed Alsadig Mohammed</p>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
