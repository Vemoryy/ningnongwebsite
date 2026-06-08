<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ningnong Kembang Goyang – Original</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            display: flex;
            flex-direction: column;
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

        .btn-primary { 
            background: #362e3d; 
            border: none; 
            transition: background-color 0.3s, transform 0.3s ease; 
            padding: 10px 20px;
        }

        .btn-primary:hover { 
            background: #6a5acd; 
            transform: translateY(-2px);
        }

        .container {
            flex: 1;
        }

        .product-section { 
            margin-top: 30px; 
            background-color: #fff; 
            border-radius: 10px; 
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); 
            padding: 40px; 
        }

        .product-section img { 
            border-radius: 10px; 
        }

        .product-description { 
            text-align: left; 
        }

        .product-description h3, h4, h5 {
            font-family: 'Poppins', sans-serif;
        }

        .product-description p {
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .footer {
            background: #001f3f;
            color: white;
            padding: 20px 0;
            text-align: center;
            margin-top: auto;
        }

        .footer a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            font-size: 1rem;
            display: inline-block;
        }

        .footer a:hover {
            color: #f0e68c;
            transition: color 0.3s ease;
        }

        .footer i {
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
                        <a class="nav-link dropdown-toggle" href="product-original id.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Bahasa🌐</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="product-original.php">🇬🇧 EN</a></li>
                            <li><a class="dropdown-item" href="product-original id.php">🇮🇩 ID</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="container my-5 product-section">
        <div class="row">
            <div class="col-md-6">
                <img src="Image/product2.jpg" alt="Original Flavor" class="img-fluid rounded">
            </div>
            <div class="col-md-6 product-description">
                <h2><b>Ningnong Kembang Goyang – Original</b></h2>
                <h3>Deskripsi</h3>
                <p>Kembang Goyang klasik yang selalu Anda sukai, dengan tekstur yang renyah dan rasa yang tak lekang oleh waktu. Sempurna untuk segala acara!</p>
                <h4>Harga: Rp. 30,000</h4>
                <h5>Berat Bersih: 65g</h5>
                <a href="http://shopee.co.id/kembanggoyangningnong" class="btn btn-primary">Pesen Sekarang</a>
            </div>
        </div>
    </section>

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
