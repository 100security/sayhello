<!--
Project: SayHello
GitHub: https://github.com/100security/sayhello
Based on Saycheese
https://github.com/hangetzzu/saycheese
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SayHello</title>
    <link rel="icon" type="image/png" href="../images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Global Styles */
        body {
            background-color: #f8f9fa;
            padding-top: 70px; /* Space for fixed navbar */
            padding-bottom: 50px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .content-container {
            flex: 1;
        }
        
        /* Navbar Styles */
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background-color: #343a40;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: #fff;
        }
        
        .navbar-brand i {
            color: #ff9900;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
        }
        
        .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            color: #fff !important;
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #ff9900;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 80%;
            left: 10%;
        }
        
        .nav-link.active::after {
            width: 80%;
            left: 10%;
        }
        
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* About Modal Styles */
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .modal-header {
            background-color: #343a40;
            color: white;
            border-bottom: none;
            padding: 1.5rem;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .modal-footer {
            border-top: none;
            padding: 1.5rem;
            background-color: #f8f9fa;
        }
        
        .profile-image {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin: 0 auto 1.5rem;
            display: block;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            color: white;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .social-link:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .github {
            background-color: #333;
        }
        
        .linkedin {
            background-color: #0077b5;
        }
        
        .instagram {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        }
        
        .about-text {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .about-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .about-title {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }
        
        .about-description {
            color: #495057;
            line-height: 1.6;
        }
        
        /* Footer Styles */
        .footer {
            background-color: #343a40;
            color: white;
            padding: 15px 0;
            text-align: center;
            margin-top: auto;
        }
        
        .footer a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: #ff9900;
        }
    </style>
    
    <?php if (isset($additionalStyles)) echo $additionalStyles; ?>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="panel.php">
                😀 SayHello
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'panel.php' ? 'active' : ''; ?>" href="panel.php">
                            <i class="fas fa-home me-1"></i> Panel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'captures.php' ? 'active' : ''; ?>" href="captures.php">
                            <i class="fas fa-images me-1"></i> Captures
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'ip_log_viewer.php' ? 'active' : ''; ?>" href="ip_log_viewer.php">
                            <i class="fas fa-network-wired me-1"></i> IP Log Viewer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'geolocation.php' ? 'active' : ''; ?>" href="geolocation.php">
                            <i class="fas fa-street-view me-1"></i> Geolocation
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#aboutModal">
                            <i class="fas fa-info-circle me-1"></i> About
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- About Modal -->
    <div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="aboutModalLabel">About</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="https://www.100security.com.br/images/marcoshenrique.png" alt="Marcos Henrique" class="profile-image">
                    
                    <div class="about-text">
                        <div class="about-name">Marcos Henrique</div>
                        <div class="about-title">CyberSecurity & Artificial Intelligence</div>
                        </p>
                    </div>
                    
                    <div class="social-links">
                        <a href="https://www.github.com/100security/sayhello" target="_blank" class="social-link github" title="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/user-marcoshenrique" target="_blank" class="social-link linkedin" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="https://www.instagram.com/100security" target="_blank" class="social-link instagram" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
		    </div>
                        <div class="footer"><a href="https://www.100security.com.br" target="_blank">www.100security.com.br</a></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content-container">
        <!-- Content will be inserted here -->
