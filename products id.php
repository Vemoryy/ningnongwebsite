<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Ning Nong Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .content {
            flex: 1;
        }
        

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
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
        

        #products {
            padding: 2rem 0;
        }

        .product-card {
            border: 2px solid #001f3f;
            border-radius: 8px;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            text-align: center;
            transition: transform 0.3s;
            text-decoration: none;
            height: 350px;
            color: black;
        }


        .product-card:hover {
            transform: scale(1.05);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }

        .product-card h5 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 1rem;
        }

        .product-card p {
            font-size: 0.9rem;
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
                    <li class="nav-item"><a class="nav-link" href="index id.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="about id.php">Tentang Kami</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="products id.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Produk</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="product-original id.php">Rasa Original</a></li>
                            <li><a class="dropdown-item" href="product-coffee id.php">Rasa Kopi</a></li>
                            <li><a class="dropdown-item" href="product-chocolate id.php">Rasa Cokelat</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="https://linktr.ee/KueNingNong?utm_source=linktree_profile_share&ltsid=967a707b-8c1b-4016-a0b7-ff773bfe9c91">Hubungi Kami</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="products id.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Bahasa🌐</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="products.php">🇬🇧 EN</a></li>
                            <li><a class="dropdown-item" href="products id.php">🇮🇩 ID</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container content">
        <h2 class="text-center mt-4">Produk Kami</h2>
        <div class="row mt-4">
            <div class="col-md-4 col-sm-6 mb-4">
                <a href="product-original id.php" class="product-card d-block">
                    <img src="Image/product2.jpg" alt="Original Flavor">
                    <h5>Rasa Original</h5>
                    <p>Kembang Goyang yang renyah dan klasik dengan rasa yang cocok setiap saat.</p>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <a href="product-coffee id.php" class="product-card d-block">
                    <img src="Image/product3.jpg" alt="Coffee Flavor">
                    <h5>Rasa Kopi</h5>
                    <p>Camilan renyah dengan aroma dan rasa kopi yang nikmat.</p>
                </a>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <a href="product-chocolate id.php" class="product-card d-block">
                    <img src="Image/product1.jpg" alt="Chocolate Flavor">
                    <h5>Rasa Cokelat</h5>
                    <p>Kembang Goyang yang renyah dengan rasa cokelat yang lembut.</p>
                </a>
            </div>
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
