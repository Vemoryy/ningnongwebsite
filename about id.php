<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Ning Nong Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .navbar {
            background-color: #001f3f; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 600;
    font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: white;
            font-weight: 500;
            font-size: 1rem;
        }

        .navbar-nav .nav-link:hover {
            color: #f0e68c;
            transform: translateY(-3px);
            transition: color 0.3s ease, transform 0.3s ease;
        }


        .hero {
            background: linear-gradient(135deg, #003366, #1a4976); 
            padding: 120px 0;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero h1 {
            font-family: 'Lora', serif;
            font-size: 5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .hero p {
            font-size: 1.2rem;
            margin-top: 0.5rem;
            font-weight: 400;
        }


        .about-content {
            padding: 4rem 2rem;
            background-color: white;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            margin: -50px auto 4rem;
            max-width: 900px;
        }

        .about-content h2 {
            font-family: 'Lora', serif;
            font-size: 2.5rem;
            color:rgb(0, 0, 0); 
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
        }

        .about-content p {
            font-size: 1rem;
            color: black;
            line-height: 1.6;
            text-align: justify;
        }

        .about-content .mission {
            background-color: #f4f7fc;
            padding: 3rem 2rem;
            border-radius: 10px;
            margin-top: 3rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        }

        .about-content .mission h3 {
            font-size: 2rem;
            color: #003366;
            margin-bottom: 1rem;
        }

        .about-content .mission p {
            font-size: 1rem;
            color: #555;
            line-height: 1.8;
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

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.8rem;
            }

            .about-content h2 {
                font-size: 2rem;
            }

            .about-content p {
                font-size: 0.95rem;
            }

            .about-content .mission h3 {
                font-size: 1.5rem;
            }
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
                        <a class="nav-link dropdown-toggle" href="about id.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Bahasa🌐</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="about.php">🇬🇧 EN</a></li>
                            <li><a class="dropdown-item" href="about id.php">🇮🇩 ID</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<section class="hero">
        <div class="container">
            <h1>Ning Nong Indonesia</h1>
            <p>Makanan Tradisional Dan Legendaris Khas Betawi</p>
        </div>
    </section>

    <section class="about-content">
        <div class="container">
            <h2>Filosofi Di Balik Ning Nong</h2>
            <p>Nama Ning Nong tercipta dari perpaduan dua kata yang sederhana, mudah diingat, dan penuh makna bagi kami.</p>
            <ul>
                <li><p>"Ning" berasal dari nama pendiri perusahaan, Ibu Ningsih, sebagai bentuk penghormatan terhadap dedikasi dan semangat beliau dalam membangun usaha ini.</p></li>
            <li><p>"Nong" adalah panggilan sayang untuk anak perempuan di daerah Banten, mencerminkan kehangatan, kedekatan, dan rasa cinta yang ingin kami hadirkan dalam setiap produk.</p></li>
            </ul>
            <p>Melalui brand Ning Nong, kami berharap kembang goyang tidak hanya menjadi camilan tradisional yang lezat, tetapi juga membawa kebahagiaan dan kehangatan bagi setiap orang yang mencicipinya. Harapan kami adalah turut membangun rasa cinta terhadap produk lokal, khususnya kue kembang goyang khas Tangerang, agar terus lestari dan dikenal lebih luas.</p>
        </div>
    </section>

    <section class="about-content mission">
        <div class="container">
            <h2>Profil Perusahaan Ning Nong</h2>
            <p><b>Kue Kembang Goyang Ning Nong</b> didirikan pada <b>5 Januari 2020</b>. Kami percaya bahwa cita rasa tradisional harus bisa dinikmati oleh semua orang, kapan saja dan di mana saja. Spesialisasi kami adalah Kembang Goyang berkualitas premium, dibuat dengan penuh perhatian agar tidak hanya lezat, tetapi juga aman dan inklusif untuk semua.</p>
            <p>We use only the finest ingredients and a meticulous production process to ensure that each bite delivers a soft, savory delight. Our kembang goyang is carefully crafted to be:</p>
            <ul>
                <li><b>Kualitas Premium</b> – Menggunakan bahan pilihan untuk rasa yang lebih istimewa.</li>
                <li><b>Aman untuk Semua</b> – Dirancang dengan hati agar aman dikonsumsi oleh ibu hamil serta anak-anak dengan autisme atau disabilitas.</li>
                <li><b>Camilan untuk Berbagi</b> – Cocok untuk segala acara, mulai dari kumpul keluarga, perayaan spesial, hingga momen santai bersama orang-orang tercinta.</li>
            </ul>
            <p>Terinspirasi dari kekayaan kuliner Tangerang, kami berkomitmen untuk menjaga warisan kue tradisional ini tetap hidup, sambil memastikan kualitas dan keamanannya sesuai dengan standar masa kini. Harapan kami, Kembang Goyang Ning Nong menjadi camilan yang bisa dinikmati dengan tenang oleh semua kalangan.</p>
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
