<?php
    session_start();  
    ?>

    

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ning Nong Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            background-color:rgb(255, 255, 255); 
            color: #333;
            scroll-behavior: smooth;
        }

        .hero {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            background: #001f3f;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .hero-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.4;
            z-index: 0;
        }

        .hero-content {
            z-index: 2;
            position: relative;
        }

        .hero-content h1 {
            font-family: 'Lora', serif;
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-top: 0.5rem;
        }

        .btn-primary {
            background: #ff851b;
            border: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-primary:hover {
            background: #ff6300;
            transform: scale(1.05);
        }

        #about {
            background-color:rgb(255, 255, 255); 
            border-radius: 8px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: -5rem;
        }

        #products {
            padding: 5rem 0;
            background: #001f3f;
            color: white
        }

        #products .carousel-item img {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        #products .carousel-item img:hover {
            transform: scale(1.05);
        }

        footer {
            background: #001f3f;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        footer a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            font-size: 1rem;
            display: inline-block;
        }

        footer a:hover {
            color: #f0e68c;
            transition: color 0.3s ease;
        }

        footer i {
            margin-right: 10px;
        }

        .footer p {
            font-size: 0.9rem;
        }

        .navbar {
            background: #001f3f;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: white;
            font-weight: 500;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #f0e68c;
            transform: translateY(-3px);
        }

        .modal-content {
            border-radius: 15px;
        }

        .modal-header {
            background: #001f3f;
            color: white;
        }

        .form-check-label {
            font-size: 0.9rem;
        }
        #productCarousel {
    max-width: 1200px; 
    margin: auto;
    color: white;
}

#productCarousel .carousel-item img {
    width: 100%;
    height: 600px; 
    object-fit: cover;
}
.stats-section {
            background-color:rgb(255, 255, 255); 
            padding: 50px 0;
        }
        .stat-item {
            text-align: center;
            color:rgb(0, 0, 0); 
        }
        .stat-icon {
            font-size: 40px;
            margin-bottom: 10px;
            color: #2f6b41;
        }
        .large {
            font-size: 1.2em;
            font-weight: bold;
        }
        .text-box {
    padding: 30px;
    border-radius: 5px;
    position: relative;
    text-align: justify;
}

.tag {
    background: #ff7f00;
    color: white;
    font-size: 14px;
    padding: 4px 8px;
    position: absolute;
    top: -10px;
    left: -10px;
    border-radius: 3px;
}

.title {
    font-weight: bold;
    color: #002f3f;
}

.btn-success {
    background-color: #4CAF50;
    border: none;
    padding: 12px 24px;
    font-weight: bold;
    border-radius: 5px;
}

.rounded-image {
    width: 100%;
    border-radius: 15px;
}
        
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Ning Nong Indonesia</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="products.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Products</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="product-original.php">Original Flavor</a></li>
                            <li><a class="dropdown-item" href="product-coffee.php">Coffee Flavor</a></li>
                            <li><a class="dropdown-item" href="product-chocolate.php">Chocolate Flavor</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="https://linktr.ee/KueNingNong?utm_source=linktree_profile_share&ltsid=967a707b-8c1b-4016-a0b7-ff773bfe9c91">Contact Us</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="index id.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Language🌐</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="index.php">🇬🇧 EN</a></li>
                            <li><a class="dropdown-item" href="index id.php">🇮🇩 ID</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <section class="hero">
        <video autoplay muted loop playsinline class="hero-video">
            <source src="videos/Indo.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="container hero-content">
            <h1>One Bite For Every Moment!</h1>
            <p>Kue Kembang Goyang – 100% Gluten Free – Premium Quality.</p>
            <a href="products.php" class="btn btn-primary mt-3">Order Now</a>
        </div>
    </section>

    <!-- Language Toggle -->
  <div class="text-center mb-5">
    <a href="?lang=en" class="btn btn-outline-primary">English</a>
    <a href="?lang=id" class="btn btn-outline-primary">Bahasa Indonesia</a>
  </div>

    <div class="container my-5">
    <div class="row align-items-center">
       
        <div class="col-md-6">
            <div class="text-box">
                <h1 class="title">Our Vision & Mission!</h1>
                <p> Advancing and empowering women in the family and national economy, as well as reviving traditional culinary heritage to be recognized and loved by the younger generation.</p> <br>
                <ul class="mt-3">
            <li>Continue to innovate and improve product quality to bring traditional food that remains relevant to today's tastes.</li> <br>
            <li>Providing products that prioritize customer satisfaction, by maintaining the authenticity of the taste while introducing jadoel snacks to young people.</li> <br>
            <li>Making a positive impact on women empowerment through sustainable economic opportunities in the food industry.</li>
        </ul>
            </div>
        </div>


        <div class="col-md-6">
        <img src="Image/kembang.jpg" alt="Kembang Goyang traditional Indonesian snack">
        </div>
    </div>
</div>     


    <section id="products">
        <div class="container">
            <h2 class="text-center">Our Products</h2>
            <div id="productCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="Image/product2.jpg" class="d-block w-100" alt="Original Flavor">
                        <div class="carousel-caption">
                            <h5>Original Flavor</h5>
                            <p>Classic and delicious!</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="Image/product1.jpg" class="d-block w-100" alt="Coffee Flavor">
                        <div class="carousel-caption">
                            <h5>Coffee Flavor</h5>
                            <p>A hint of coffee for the perfect crunch.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="Image/product3.jpg" class="d-block w-100" alt="Chocolate Flavor">
                        <div class="carousel-caption">
                            <h5>Chocolate Flavor</h5>
                            <p>Rich and indulgent chocolate taste.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                 <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </div>
        </div>


    <footer class="footer">
        <p>&copy; 2025 Ning Nong Indonesia. All Rights Reserved.</p>
        <div>
            <a href="https://www.instagram.com/ningnongindonesia?igsh=MW56eGl3YmlzNzdhZw==" target="_blank"><i class="fab fa-instagram"></i>Instagram</a>
            <a href="https://www.tiktok.com/@kembang.goyang.ningnong?_t=8rsfO053Sr9&_r=1" target="_blank"><i class="fab fa-tiktok"></i> TikTok</a>
            <a href="https://api.whatsapp.com/send?phone=6282299891278" target="_blank"><i class="fab fa-whatsapp"></i>Whatsapp</a>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
 
</body>

</html>
