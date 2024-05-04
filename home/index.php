<?php
include 'db_connection.php';

// Fetch site settings to get the image path
$query = "SELECT * FROM sitesettings WHERE id = 1";
$stmt = $pdo->prepare($query);
$stmt->execute();
$site_settings = $stmt->fetch(PDO::FETCH_ASSOC);

$site_title = $site_settings['site_title'];
$header_image = $site_settings['header_image'];
$site_logo = $site_settings['site_logo'];

$image_path = "home/assets/"; // Base path for images
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pacifico|Quicksand:400,600&display=swap">
  <link rel="stylesheet" href="styles.css" />

  <title><?php echo $site_title; ?></title>
</head>
<header class="header">
  <nav>
    <div class="nav__header">
      <div class="nav__logo">

        <a href="#"><?php echo $site_logo; ?><?php echo $site_title; ?></a>
      </div>
      <div class="nav__menu__btn" id="menu-btn">
        <span><i class="ri-menu-line"></i></span>
      </div>
    </div>
    <ul class="nav__links" id="nav-links">
      <li class="link"><a href="#home">Home</a></li>
      <li class="link"><a href="#class">Classes</a></li>
      <li class="link"><a href="#price">Pricing</a></li>
      <li class="link"><a href="#about">About</a></li>
      <li class="link"><a href="#">Contact</a></li>

    </ul>
  </nav>
  <div class="section__container header__container" id="home">
    <div class="header__image">
      <img src="assets/header.png" alt="header" />
    </div>
    <div class="header__content">
      <h4>Build Your Body &</h4>
      <h1 class="section__header">Shape Yourself!</h1>
      <p>
        Unleash your potential and embark on a journey towards a stronger,
        fitter, and more confident you. Sign up for 'Make Your Body Shape'
        now and witness the incredible transformation your body is capable
        of!
      </p>
      <div class="header__btn">
        <button class="btn">Join Today</button>
      </div>
    </div>
  </div>
</header>

<section class="section__container class__container" id="class">
  <h2 class="section__header">Our Classes</h2>
  <p class="section__description">
    Discover a diverse range of exhilarating classes at our gym designed to
    cater to all fitness levels and interests. Whether you're a seasoned
    athlete or just starting your fitness journey, our classes offer
    something for everyone.
  </p>
  <div class="class__grid">
    <div class="class__card">
      <img src="assets/dot-bg.png" alt="bg" class="class__bg" />
      <img src="assets/class-1.jpg" alt="class" />
      <div class="class__content">
        <h4>Strength Training</h4>
        <p>Resistance Training</p>
      </div>
    </div>
    <div class="class__card">
      <img src="assets/dot-bg.png" alt="bg" class="class__bg" />
      <img src="assets/class-2.jpg" alt="class" />
      <div class="class__content">
        <h4>Flexibility & Mobility</h4>
        <p>Yoga & Pilates</p>
      </div>
    </div>
    <div class="class__card">
      <img src="assets/dot-bg.png" alt="bg" class="class__bg" />
      <img src="assets/class-3.jpg" alt="class" />
      <div class="class__content">
        <h4>HIIT</h4>
        <p>Circuit Training</p>
      </div>
    </div>
  </div>
</section>

<section class="section__container price__container" id="price">
  <h2 class="section__header">Our Pricing</h2>
  <p class="section__description">
    Our pricing plan comes with a membership.
  </p>

  <div class="price__card">
    <div class="price__content">
      <h4>Paid Plan</h4>
      <img src="assets/price-3.png" alt="price" />
      <p>
        With this flexible membership, you'll have access to our
        state-of-the-art gym facilities, expert trainers, and a vibrant
        fitness community
      </p>
      <hr />
      <h4>Key Features</h4>
      <p>ELITE Gyms & Classes</p>
      <p>PRO Gyms</p>
      <p>Smart workout plan</p>
      <p>At home workouts</p>
      <p>Personal Training</p>
    </div>
    <button class="btn">Join Now</button>
  </div>
  </div>
</section>

<section class="section__container client__container" id="client">
  <h2 class="section__header">What People Says About Us?</h2>
  <p class="section__description">
    These testimonials serve as a testament to our commitment to helping
    individuals achieve their fitness goals, and fostering a supportive
    environment for everyone who walks through our doors.
  </p>
  <!-- Slider main container -->
  <div class="swiper">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">
      <!-- Slides -->
      <div class="swiper-slide">
        <div class="client__card">
          <img src="assets/client-1.jpg" alt="client" />
          <div class="client__ratings">
            <span><i class="ri-star-fill"></i></span>
            <span><i class="ri-star-fill"></i></span>
            <span><i class="ri-star-fill"></i></span>
            <span><i class="ri-star-fill"></i></span>
            <span><i class="ri-star-line"></i></span>
          </div>
          <p>
            The trainers here customized a plan that balanced my work-life
            demands, and I've seen remarkable progress in my fitness
            journey. It's not just a gym; it's my sanctuary for self-care.
          </p>
          <h4>Jane Smith</h4>
          <h5>Marketing Manager</h5>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="client__card">
          <img src="assets/client-2.jpg" alt="client" />
          <div class="client__ratings">
            <span><i class="ri-star-fill"></i></span>
            <span><i class="ri-star-fill"></i></span>
            <span><i class="ri-star-fill"></i></span>
            <span><i class="ri-star-fill"></i></span>
            <span><i class="ri-star-half-fill"></i></span>
          </div>
          <p>
            The trainers' expertise and the gym's commitment to cleanliness
            during these times have made it a safe haven for me to maintain
            my health and de-stress.
          </p>
          <h4>Emily Carter</h4>
          <h5>Registered Nurse</h5>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="client__card">
          <img src="assets/client-3.jpg" alt="client" />
          <div class="client__ratings">
            <span><i class="ri-star-fill"></i></span>
            <span><i class="ri-star-fill"></i></span>
            <span><i class="ri-star-fill"></i></span>
            <span><i class="ri-star-half-fill"></i></span>
            <span><i class="ri-star-line"></i></span>
          </div>
          <p>
            The variety of classes and the supportive community have kept me
            motivated. I've shed pounds, gained confidence, and found a new
            level of energy to inspire my students.
          </p>
          <h4>John Davis</h4>
          <h5>Teacher</h5>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container">
  <form action="subcontact.php" method="post">
    <h1 class="title">Talk to Us</h1>
    <div class="form-group">
      <label for="formName">Name</label>
      <input type="text" id="formName" name="name" class="form-control" placeholder="Name" required>
    </div>
    <div class="form-group">
      <label for="formEmail">E-mail</label>
      <input type="email" id="formEmail" name="email" class="form-control" placeholder="E-mail" required>
    </div>
    <div class="form-group">
      <label for="formSubject">Subject</label>
      <input type="text" id="formSubject" name="subject" class="form-control" placeholder="Subject" required>
    </div>
    <div class="form-group message">
      <label for="formMessage">Message</label>
      <textarea id="formMessage" name="message" class="form-control" rows="7" placeholder="Message" required></textarea>
    </div>
    <div class="text-center">
      <button type="submit" class="btn btn-primary">Send Message</button>
    </div>
  </form>

  <footer class="footer">
    <div class="section__container footer__container">
      <div class="footer__col">
        <div class="footer__logo">
          <a href="#">FitNepal</a>
          Find your healthy, and your happy.
        </div>
      </div><br><br>

      <div class="footer__col">
        <h4>About Us</h4>

        <div class="footer__links">
          <a href="#">Blogs</a>
          <a href="#">Security</a>
          <a href="#">Careers</a>
        </div>

      </div>

      <div class="footer__col">
        <h4>Support</h4>

        <div class="footer__links">
          <a href="#">Contact Us</a>
          <a href="#">Privacy Policy</a>
          <a href="#">Terms & Conditions</a>
        </div>
      </div>
    </div>

    <div class="footer__socials">
      <a href="#"><i class="ri-facebook-fill"></i></a>
      <a href="#"><i class="ri-instagram-line"></i></a>
      <a href="#"><i class="ri-twitter-fill"></i></a>
    </div>
    <div class="footer__bar">
      Copyright © 2024 fitnepal . All rights reserved.
    </div>
  </footer>

  <script src="https://unpkg.com/scrollreveal"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
  <script src="main.js"></script>
  </body>

</html>