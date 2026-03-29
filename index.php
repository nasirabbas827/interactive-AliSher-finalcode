<?php
include 'config.php';

// Fetch Real Dynamic Stats
$total_ideas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM business_ideas"))['count'] ?? 0;
$total_opps = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM opportunities WHERE status = 'open'"))['count'] ?? 0;
$total_mentors = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='agent'"))['count'] ?? 0;
$total_resources = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM business_resources"))['count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Portal - Empowering Tomorrow's Entrepreneurs</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --dark: #0f172a;
            --accent: #a5b4fc;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Hero */
        .hero {
            min-height: 100vh;
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(79, 70, 229, 0.6)), 
                        url('https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
            display: flex;
            align-items: center;
            position: relative;
        }

        .hero-content {
            z-index: 2;
        }

        /* Stats */
        .stats-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 60px -10px rgba(0, 0, 0, 0.15);
            padding: 40px 30px;
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }

        .stat-number {
            font-size: 2.8rem;
            font-weight: 800;
            background: linear-gradient(90deg, var(--primary), #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Section Styling */
        .section-padding {
            padding: 120px 0;
        }

        .section-title {
            font-size: 2.8rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .feature-card {
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            background: white;
        }
        
        .feature-card:hover {
            transform: translateY(-20px);
            box-shadow: 0 40px 80px -20px rgba(99, 102, 241, 0.25);
            border-color: var(--primary);
        }

        .icon-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 25px;
        }

        /* Trust Bar */
        .trust-bar {
            background: #f1f5f9;
            padding: 25px 0;
        }

        /* CTA Sections */
        .cta-gradient {
            background: linear-gradient(135deg, #0f172a, #1e2937);
        }

        /* Footer */
        footer {
            background: #0f172a;
        }
    </style>
</head>
<body>

<?php include 'top_nav.php'; ?>

<!-- 1. Hero Section -->
<section class="hero text-white">
    <div class="container hero-content">
        <div class="row justify-content-center text-center">
            <div class="col-lg-10 col-xl-8">
                <span class="badge bg-white text-dark px-4 py-3 rounded-pill mb-4 fs-6 fw-semibold">Built for Aspiring Entrepreneurs</span>
                <h1 class="display-3 fw-bold mb-4 lh-sm">
                    Turn Your <span class="text-warning">Idea</span> Into a <span class="text-indigo">Thriving Business</span>
                </h1>
                <p class="lead fs-4 opacity-90 mb-5">
                    The ultimate platform connecting ambitious entrepreneurs with expert mentors, 
                    proven business ideas, funding opportunities, and world-class resources.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="register.php" class="btn btn-primary btn-lg px-5 py-4 rounded-pill shadow-lg fs-5 fw-semibold">
                        Start Your Journey — It's Free
                    </a>
                    <a href="#features" class="btn btn-outline-light btn-lg px-5 py-4 rounded-pill fs-5 fw-semibold">
                        Explore Platform
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 2. Dynamic Stats -->
<div class="container stats-container">
    <div class="stats-card card border-0">
        <div class="row text-center g-5">
            <div class="col-6 col-md-3">
                <h2 class="stat-number"><?= number_format($total_ideas) ?>+</h2>
                <p class="text-muted fw-medium mb-0">Curated Business Ideas</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="stat-number"><?= number_format($total_opps) ?>+</h2>
                <p class="text-muted fw-medium mb-0">Open Opportunities</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="stat-number"><?= number_format($total_mentors) ?>+</h2>
                <p class="text-muted fw-medium mb-0">Verified Mentors</p>
            </div>
            <div class="col-6 col-md-3">
                <h2 class="stat-number"><?= number_format($total_resources) ?>+</h2>
                <p class="text-muted fw-medium mb-0">Resources & Templates</p>
            </div>
        </div>
    </div>
</div>

<!-- 3. Problem Section -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase">The Challenge</h6>
            <h2 class="section-title">Most Entrepreneurs Fail Because They Lack The Right Support</h2>
        </div>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-5">
                    <i class="fas fa-lightbulb fa-3x text-warning mb-4"></i>
                    <h5>Idea Without Direction</h5>
                    <p class="text-muted">Great ideas die due to lack of proper planning and execution strategy.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-5">
                    <i class="fas fa-users fa-3x text-primary mb-4"></i>
                    <h5>No Access to Mentors</h5>
                    <p class="text-muted">Finding experienced guidance is expensive and time-consuming.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-5">
                    <i class="fas fa-file-invoice-dollar fa-3x text-success mb-4"></i>
                    <h5>Limited Resources</h5>
                    <p class="text-muted">Most startups struggle to access quality templates, research, and funding leads.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. Features Section -->
<section class="section-padding bg-light" id="features">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase">Powerful Features</h6>
            <h2 class="section-title">Everything You Need to Succeed</h2>
            <p class="lead text-muted col-lg-8 mx-auto">A complete ecosystem designed to take you from idea to successful business.</p>
        </div>
        
        <div class="row g-4">
            <?php
            $features = [
                ['icon' => 'lightbulb', 'color' => 'bg-warning text-white', 'title' => 'Business Ideas', 'desc' => 'Hundreds of validated ideas across 20+ industries with market analysis.'],
                ['icon' => 'user-tie', 'color' => 'bg-primary text-white', 'title' => 'Expert Mentorship', 'desc' => 'Connect with verified entrepreneurs and industry experts for 1-on-1 guidance.'],
                ['icon' => 'file-alt', 'color' => 'bg-success text-white', 'title' => 'Business Templates', 'desc' => 'Professional startup plans, pitch decks, financial models & legal documents.'],
                ['icon' => 'handshake', 'color' => 'bg-info text-white', 'title' => 'Opportunities', 'desc' => 'Investment, partnership, franchise, and co-founder matching opportunities.'],
                ['icon' => 'comments', 'color' => 'bg-secondary text-white', 'title' => 'Community Forum', 'desc' => 'Engage with fellow entrepreneurs, ask questions, and share experiences.'],
                ['icon' => 'play-circle', 'color' => 'bg-danger text-white', 'title' => 'Learning Hub', 'desc' => 'Structured courses, webinars, and tutorials from successful founders.']
            ];
            foreach($features as $f):
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card p-5 text-center h-100">
                    <div class="icon-circle <?= $f['color'] ?>">
                        <i class="fas fa-<?= $f['icon'] ?>"></i>
                    </div>
                    <h4 class="fw-bold mb-3"><?= $f['title'] ?></h4>
                    <p class="text-muted"><?= $f['desc'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 5. How It Works -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold text-uppercase">3 Simple Steps</h6>
            <h2 class="section-title">How Business Portal Works</h2>
        </div>
        <div class="row g-5 text-center">
            <div class="col-md-4">
                <div class="bg-light rounded-4 p-5">
                    <div class="display-1 fw-bold text-primary mb-3">01</div>
                    <h4>Join the Community</h4>
                    <p class="text-muted">Create your free account and complete your entrepreneur profile.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light rounded-4 p-5">
                    <div class="display-1 fw-bold text-primary mb-3">02</div>
                    <h4>Explore & Connect</h4>
                    <p class="text-muted">Browse ideas, resources, opportunities, and connect with mentors.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bg-light rounded-4 p-5">
                    <div class="display-1 fw-bold text-primary mb-3">03</div>
                    <h4>Launch & Grow</h4>
                    <p class="text-muted">Use tools, get feedback, and turn your vision into reality.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. For Mentors / Agents -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title">Are You an Experienced Entrepreneur or Mentor?</h2>
                <p class="lead text-muted">Share your knowledge, upload resources, and help the next generation while growing your network.</p>
                <ul class="list-unstyled mt-4">
                    <li class="mb-3"><i class="fas fa-check text-success me-3"></i> Get approved as a verified mentor</li>
                    <li class="mb-3"><i class="fas fa-check text-success me-3"></i> Upload business ideas and templates</li>
                    <li class="mb-3"><i class="fas fa-check text-success me-3"></i> Earn through premium mentorship</li>
                </ul>
                <a href="register.php" class="btn btn-outline-primary btn-lg px-5 rounded-pill">Become a Mentor →</a>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=800&q=80" class="img-fluid rounded-4 shadow" alt="Mentor">
            </div>
        </div>
    </div>
</section>

<!-- 7. Testimonials -->
<section class="section-padding bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-primary fw-bold">Success Stories</h6>
            <h2 class="section-title">What Our Community Says</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-5 border rounded-4 h-100">
                    <p class="fst-italic">"Thanks to Business Portal, I found my co-founder and secured my first investment within 3 months!"</p>
                    <div class="mt-4">
                        <strong>Ayesha Khan</strong><br>
                        <small class="text-muted">Founder, EduTech Startup</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-5 border rounded-4 h-100">
                    <p class="fst-italic">"The templates and mentor guidance helped me launch my e-commerce business profitably."</p>
                    <div class="mt-4">
                        <strong>Ahmed Raza</strong><br>
                        <small class="text-muted">Founder, TrendMart</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-5 border rounded-4 h-100">
                    <p class="fst-italic">"As a mentor, I've helped 12 startups and built meaningful connections."</p>
                    <div class="mt-4">
                        <strong>Mr. Bilal Hassan</strong><br>
                        <small class="text-muted">Serial Entrepreneur & Mentor</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 8. Trusted By / Trust Bar -->
<section class="trust-bar text-center">
    <div class="container">
        <p class="text-muted mb-3">Trusted by aspiring entrepreneurs from leading institutions</p>
        <div class="d-flex flex-wrap justify-content-center gap-5 opacity-75">
            <h5 class="fw-bold">VU • LUMS • IBA • FAST • NUST</h5>
        </div>
    </div>
</section>

<!-- 9. Final CTA -->
<section class="cta-gradient text-white py-5">
    <div class="container text-center py-5">
        <h2 class="display-5 fw-bold mb-4">Ready to Build Your Dream Business?</h2>
        <p class="lead mb-5 col-lg-8 mx-auto">Join thousands of entrepreneurs who are turning their ideas into reality with Business Portal.</p>
        <a href="register.php" class="btn btn-light btn-lg px-5 py-4 rounded-pill fw-bold fs-5 shadow">
            Create Your Free Account Now
        </a>
    </div>
</section>

<!-- 10. Footer -->
<footer class="text-white py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-md-5">
                <h5 class="fw-bold fs-3 mb-3">
                    <i class="fas fa-rocket text-primary me-2"></i>Business Portal
                </h5>
                <p class="text-white-50">Empowering the next generation of entrepreneurs with knowledge, connections, and opportunities.</p>
            </div>
            
            <div class="col-md-3">
                <h6 class="fw-semibold mb-3">Platform</h6>
                <div class="d-flex flex-column gap-2 small">
                    <a href="login.php" class="text-white-50 text-decoration-none">Business Ideas</a>
                    <a href="login.php" class="text-white-50 text-decoration-none">Find Mentors</a>
                    <a href="login.php" class="text-white-50 text-decoration-none">Opportunities</a>
                    <a href="login.php" class="text-white-50 text-decoration-none">Resources</a>
                </div>
            </div>
            
            <div class="col-md-4 text-md-end">
                <h6 class="fw-semibold mb-3">Get In Touch</h6>
                <div class="d-flex justify-content-md-end gap-3 mb-4">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle"><i class="fab fa-facebook-f"></i></a>
                </div>
                <p class="text-white-50 small">
                    © <?= date('Y') ?> Business Portal. All Rights Reserved.<br>
                </p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>