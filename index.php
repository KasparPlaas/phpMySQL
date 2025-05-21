<?php include 'header.php'; ?>

<!-- Custom inline styles to replace external CSS -->
<style>
  /* Text shadows for better contrast on hero section */
  .text-shadow {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
  }

  .text-shadow-sm {
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
  }

  /* Hover effects */
  .hover-scale {
    transition: transform 0.3s ease;
  }

  .hover-scale:hover {
    transform: scale(1.05);
  }

  .hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .hover-lift:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
  }

  .hover-zoom {
    transition: transform 0.5s ease;
  }

  .hover-zoom:hover {
    transform: scale(1.1);
  }

  .hover-fade-in {
    transition: opacity 0.3s ease;
  }

  .hover-fade-in:hover {
    opacity: 1 !important;
  }

  /* Animation classes for scroll effects */
  .fade-in-up {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }

  .fade-in-left {
    opacity: 0;
    transform: translateX(-30px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }

  .fade-in-right {
    opacity: 0;
    transform: translateX(30px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }

  .fade-in-up.visible,
  .fade-in-left.visible,
  .fade-in-right.visible {
    opacity: 1;
    transform: translate(0);
  }

  /* Icon circle */
  .icon-circle {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
  }

  .icon-circle:hover {
    background-color: var(--bs-primary) !important;
    color: white !important;
  }

  /* Gallery styles */
  .gallery-overlay {
    transition: background-color 0.3s ease;
  }

  .gallery-item:hover .gallery-overlay {
    background-color: rgba(0, 0, 0, 0.5) !important;
  }

  .gallery-item:hover .hover-fade-in {
    opacity: 1 !important;
  }

  /* Testimonial card styles */
  .testimonials-slider .swiper-slide {
    height: auto;
  }

  /* Custom pagination and navigation for Swiper */
  .swiper-pagination-bullet {
    width: 12px;
    height: 12px;
    background: var(--bs-primary);
    opacity: 0.3;
  }

  .swiper-pagination-bullet-active {
    opacity: 1;
  }

  .swiper-button-next,
  .swiper-button-prev {
    color: var(--bs-primary);
    background-color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .swiper-button-next:after,
  .swiper-button-prev:after {
    font-size: 18px;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .display-2 {
      font-size: 2.1rem;
      line-height: 1.1;
    }
    .display-4 {
      font-size: 1.5rem;
    }
    .icon-circle {
      width: 50px;
      height: 50px;
    }
    .hero-banner {
      min-height: 260px !important;
      padding-top: 2.5rem !important;
      padding-bottom: 2.5rem !important;
    }
    .hero-banner h1,
    .hero-banner p {
      text-align: center !important;
    }
    .hero-banner .btn {
      width: 100%;
      padding-left: 0.5rem !important;
      padding-right: 0.5rem !important;
    }
    .hero-banner .d-flex {
      flex-direction: column !important;
      gap: 0.75rem !important;
    }
  }
  @media (max-width: 400px) {
    .display-2 {
      font-size: 1.3rem;
    }
    .hero-banner {
      min-height: 180px !important;
      padding-top: 1.5rem !important;
      padding-bottom: 1.5rem !important;
    }
  }
</style>

<main>
<!-- Improved Hero Banner: Centered Text on High-Quality Background Image -->
<div class="position-relative w-100 hero-banner" style="min-height: 420px; padding-top: 3rem; padding-bottom: 3rem;">
  <img 
    src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1920&q=80" 
    alt="Spa banner" 
    class="w-100 h-100 position-absolute top-0 start-0 object-fit-cover" 
    style="min-height: 100%; object-fit: cover; z-index:1;"
    loading="eager"
    decoding="async"
    fetchpriority="high"
  >
  <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(30,30,40,0.55); z-index:2;"></div>
  <div class="container h-100 d-flex flex-column justify-content-center align-items-center position-relative" style="z-index:3; min-height: inherit;">
    <span class="badge bg-light text-primary fs-5 mb-3 rounded-pill px-4 py-2 shadow">UUS! Eksklusiivne pakkumine</span>
    <h1 class="display-2 fw-bold text-white mb-3 text-center text-shadow">Tule ja lõõgastu meie spaahotellis</h1>
    <p class="lead text-white text-shadow-sm mb-4 text-center" style="max-width: 600px;">
      Broneeri oma unustamatu spaapuhkus juba täna ja naudi kevadkampaania erihindu!
    </p>
    <div class="d-flex flex-column flex-sm-row gap-3 w-100 justify-content-center align-items-center">
      <a href="#packages" class="btn btn-primary btn-lg px-5 py-3 rounded-pill hover-scale shadow">Vaata pakette</a>
      <a href="broneerima.php" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill hover-scale shadow">Broneeri kohe</a>
    </div>
  </div>
</div>

<!-- Add spacing between hero and promo banner on mobile -->
<div class="d-block d-md-none" style="height: 1.5rem;"></div>

<!-- Promotional Banner with Picsum Background -->
<div class="position-relative overflow-hidden" style="background-image: url('https://picsum.photos/id/327/1920/200'); background-size: cover; background-position: center;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(var(--bs-primary-rgb), 0.7);"></div>
    <div class="container position-relative py-3">
      <div class="d-flex justify-content-center align-items-center flex-wrap gap-2 text-white text-center">
        <span class="fw-bold fs-5"><i class="bi bi-stars me-1"></i>KEVADKAMPAANIA</span>
        <span class="d-none d-sm-inline mx-2 text-white">|</span>
        <span>Broneeri juuni lõpuni ja saad -20% kõikidelt pakettidelt</span>
        <a href="packages.php" class="btn btn-light btn-sm rounded-pill ms-2 px-3 hover-scale text-primary fw-bold">Vaata pakkumisi</a>
      </div>
    </div>
  </div>



  <!-- Features Section with Animation -->
  <section class="py-5 bg-body-tertiary">
    <div class="container py-5">
      <div class="row g-5 align-items-center">
        <div class="col-lg-5 fade-in-left">
          <span class="badge bg-primary text-white fs-6 mb-3 rounded-pill px-4 py-2">Tere tulemast</span>
          <h2 class="display-4 fw-bold mb-4">Avasta Spaahotelli</h2>
          <p class="lead text-body-secondary mb-5">Elamused, mis jätavad igaveseks mulje. Pakume eksklusiivseid pakette romantikale, puhkusele, spaale ja sünnipäevadeks.</p>
          <div class="d-flex flex-column flex-sm-row gap-3">
            <a href="#packages" class="btn btn-primary px-4 py-3 rounded-pill hover-scale">Vaata pakette</a>
            <a href="contact.php" class="btn btn-outline-secondary px-4 py-3 rounded-pill hover-scale">Kontakt</a>
          </div>
        </div>
        <div class="col-lg-7 fade-in-right">
          <div class="row g-4">
            <div class="col-md-6">
              <div class="card border-0 shadow h-100 bg-body rounded-4 hover-lift">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center mb-3">
                    <div class="icon-circle bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                      <i class="bi bi-check2-circle fs-1"></i>
                    </div>
                    <h3 class="h4 mb-0 fw-bold">Professionaalsed terapeudid</h3>
                  </div>
                  <p class="text-body-secondary mb-0">Kogenud spetsialistid, kes aitavad teil lõõgastuda</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card border-0 shadow h-100 bg-body rounded-4 hover-lift">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center mb-3">
                    <div class="icon-circle bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                      <i class="bi bi-flower1 fs-1"></i>
                    </div>
                    <h3 class="h4 mb-0 fw-bold">Orgaanilised tooted</h3>
                  </div>
                  <p class="text-body-secondary mb-0">Looduslikud ja ohutud koostisosad</p>
                </div>
              </div>
            </div>
            <div class="col-12 mt-4">
              <div class="ratio ratio-16x9 rounded-5 overflow-hidden shadow">
                <img 
                  src="https://picsum.photos/800/600" 
                  class="object-fit-cover" 
                  alt="Spa interjöör" 
                  loading="lazy"
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section with Animated Counters -->
  <section class="py-5 bg-light">
    <div class="container py-5">
      <div class="row g-4 justify-content-center text-center">
        <div class="col-6 col-md-3">
          <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift">
            <div class="text-primary mb-3">
              <i class="bi bi-people-fill display-4"></i>
            </div>
            <h3 class="counter-value display-5 fw-bold">5000</h3>
            <p class="text-body-secondary mb-0">Rahulolevat klienti</p>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift">
            <div class="text-primary mb-3">
              <i class="bi bi-award-fill display-4"></i>
            </div>
            <h3 class="counter-value display-5 fw-bold">15</h3>
            <p class="text-body-secondary mb-0">Aastane kogemus</p>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift">
            <div class="text-primary mb-3">
              <i class="bi bi-gem display-4"></i>
            </div>
            <h3 class="counter-value display-5 fw-bold">25</h3>
            <p class="text-body-secondary mb-0">Proceduurid</p>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card border-0 shadow-sm rounded-4 p-4 h-100 hover-lift">
            <div class="text-primary mb-3">
              <i class="bi bi-star-fill display-4"></i>
            </div>
            <h3 class="counter-value display-5 fw-bold">4.9</h3>
            <p class="text-body-secondary mb-0">Keskmine hinnang</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Packages Grid with Hover Effects -->
  <section id="packages" class="py-5">
    <div class="container py-5">
      <div class="text-center mb-5 fade-in-up">
        <span class="badge bg-primary text-white fs-6 rounded-pill px-4 py-2 mb-3">Meie teenused</span>
        <h2 class="display-4 fw-bold mb-3">Populaarsemad paketid</h2>
        <p class="lead text-body-secondary mx-auto" style="max-width: 600px">Valige endale sobivaim pakett unikaalseks kogemuseks</p>
      </div>

      <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
        <?php
        $offers = [
          'romantika' => [
            'title' => 'Romantiline puhkus', 
            'price' => rand(120, 180), 
            'icon' => 'bi-heart-fill',
            'description' => 'Täiuslik valik kahele, lõõgastav massaaž ja privaatne mullivann'
          ],
          'puhkus' => [
            'title' => 'Lõõgastuspuhkus', 
            'price' => rand(90, 150), 
            'icon' => 'bi-emoji-smile-fill',
            'description' => 'Unustage igapäevamured meie spetsiaalselt lõõgastavas keskkonnas'
          ],
          'spaa' => [
            'title' => 'Premium Spa', 
            'price' => rand(150, 220), 
            'icon' => 'bi-water',
            'description' => 'Eksklusiivsed protseduurid ja privaatne spa-kogemus'
          ],
          'synnipaev' => [
            'title' => 'Sünnipäevapakk', 
            'price' => rand(200, 300), 
            'icon' => 'bi-gift-fill',
            'description' => 'Tähistage oma erilist päeva meie juures eriliste hoolitsustega'
          ],
        ];
        
        foreach ($offers as $key => $offer): ?>
          <div class="col fade-in-up">
            <div class="card h-100 border-0 shadow bg-body rounded-4 hover-lift overflow-hidden">
              <div class="card-img-top position-relative overflow-hidden">
                <img 
                  src="https://picsum.photos/800/600?random=<?= rand(1,100) ?>" 
                  class="object-fit-cover w-100 hover-zoom" 
                  style="height: 200px" 
                  alt="<?= htmlspecialchars($offer['title']) ?>"
                  loading="lazy"
                >
                <div class="position-absolute top-0 start-0 m-3">
                  <div class="badge bg-primary text-white p-2 rounded-pill">
                    <i class="bi <?= $offer['icon'] ?> me-1"></i> TOP pakett
                  </div>
                </div>
              </div>
              <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h3 class="h4 mb-0 fw-bold"><?= htmlspecialchars($offer['title']) ?></h3>
                  <div class="price-tag bg-primary text-white px-3 py-2 rounded-pill fw-bold">
                    <?= $offer['price'] ?>€
                  </div>
                </div>
                <p class="text-body-secondary mb-4"><?= htmlspecialchars($offer['description']) ?></p>
                <div class="d-grid">
                  <a href="book.php?package=<?= urlencode($key) ?>" class="btn btn-primary btn-lg rounded-pill hover-scale">
                    <i class="bi <?= $offer['icon'] ?> me-2"></i>Broneeri kohe
                  </a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Testimonials Section with Modern Cards -->
  <section class="py-5 bg-body-tertiary">
    <div class="container py-5">
      <div class="text-center mb-5 fade-in-up">
        <span class="badge bg-primary text-white fs-6 rounded-pill px-4 py-2 mb-3">Kliendid räägivad</span>
        <h2 class="display-4 fw-bold mb-3">Klientide arvamused</h2>
        <p class="lead text-body-secondary mx-auto" style="max-width: 600px">Meie rahulolevate klientide kogemused</p>
      </div>

      <div class="testimonials-slider swiper">
        <div class="swiper-wrapper pb-5">
          <?php
          $testimonials = [
            [
              'name' => 'Anna K', 
              'location' => 'Tartu', 
              'text' => 'Parim spaakogemus, mis mul kunagi olnud on! Töötajad olid väga abivalmid ja ruumid imeilusad. Soovitan soojalt kõigile, kes otsivad kvaliteetset puhkust.', 
              'image' => 'https://picsum.photos/seed/anna/200/200', 
              'stars' => 5,
              'date' => '12. märts 2025'
            ],
            [
              'name' => 'Marko T', 
              'location' => 'Tallinn', 
              'text' => 'Täiuslik romantiline puhkuspäev abikaasaga. Nautisime väga privaatset mullivanni ja lõõgastavaid massaaže. Kindlasti tuleme tagasi!', 
              'image' => 'https://picsum.photos/seed/marko/200/200', 
              'stars' => 4.5,
              'date' => '5. aprill 2025'
            ],
            [
              'name' => 'Katri L', 
              'location' => 'Pärnu', 
              'text' => 'Tähistasime siin oma 10. pulma-aastapäeva - täiuslik koht eriliseks päevaks! Personal oli äärmiselt tähelepanelik ja teenindus suurepärane.', 
              'image' => 'https://picsum.photos/seed/katri/200/200', 
              'stars' => 5,
              'date' => '28. veebruar 2025'
            ],
            [
              'name' => 'Peeter M', 
              'location' => 'Võru', 
              'text' => 'Otsisin kohta, kus saaks tööstressist puhata ja leidsin selle spaa. Mõnus rahulik keskkond ja professionaalne teenindus aitasid mul täielikult lõõgastuda.', 
              'image' => 'https://picsum.photos/seed/peeter/200/200', 
              'stars' => 5,
              'date' => '17. april 2025'
            ],
          ];

          foreach ($testimonials as $t):
            $fullStars = floor($t['stars']);
            $halfStar = ($t['stars'] - $fullStars) >= 0.5;
          ?>
            <div class="swiper-slide pt-4">
              <div class="card border-0 shadow h-100 bg-body rounded-4 p-1">
                <div class="card-body p-4">
                  <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                      <img 
                        src="<?= $t['image'] ?>" 
                        alt="<?= $t['name'] ?>" 
                        class="rounded-circle me-3 object-fit-cover border border-3 border-primary" 
                        width="70" 
                        height="70"
                        loading="lazy"
                      >
                      <div>
                        <h3 class="h5 mb-0 fw-bold"><?= htmlspecialchars($t['name']) ?></h3>
                        <small class="text-body-secondary">
                          <i class="bi bi-geo-alt-fill me-1"></i><?= htmlspecialchars($t['location']) ?>
                        </small>
                      </div>
                    </div>
                    <small class="text-body-secondary"><?= $t['date'] ?></small>
                  </div>
                  <div class="mb-3 text-warning">
                    <?= str_repeat('<i class="bi bi-star-fill fs-5"></i>', $fullStars) ?>
                    <?= $halfStar ? '<i class="bi bi-star-half fs-5"></i>' : '' ?>
                    <?= str_repeat('<i class="bi bi-star fs-5"></i>', 5 - $fullStars - ($halfStar ? 1 : 0)) ?>
                  </div>
                  <blockquote class="mb-0">
                    <p class="fst-italic text-body mb-0">"<?= htmlspecialchars($t['text']) ?>"</p>
                  </blockquote>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- Gallery Section -->
  <section class="py-5">
    <div class="container py-5">
      <div class="text-center mb-5 fade-in-up">
        <span class="badge bg-primary text-white fs-6 rounded-pill px-4 py-2 mb-3">Galerii</span>
        <h2 class="display-4 fw-bold mb-3">Vaata meie spaad</h2>
        <p class="lead text-body-secondary mx-auto" style="max-width: 600px">Nautige eelnevalt meie spa-keskkonna ilu ja atmosfääri</p>
      </div>

      <div class="row g-3 gallery">
        <?php for($i = 1; $i <= 6; $i++): ?>
          <div class="col-6 col-md-4 fade-in-up">
            <a href="https://picsum.photos/1200/800?random=<?= $i ?>" class="gallery-item rounded-4 overflow-hidden d-block position-relative shadow hover-lift">
              <img 
                src="https://picsum.photos/600/400?random=<?= $i ?>" 
                alt="Spa galerii pilt <?= $i ?>" 
                class="img-fluid w-100 h-100 object-fit-cover hover-zoom" 
                loading="lazy"
              >
              <div class="gallery-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-25">
                <i class="bi bi-zoom-in text-white fs-1 opacity-0 hover-fade-in"></i>
              </div>
            </a>
          </div>
        <?php endfor; ?>
      </div>
    </div>
  </section>

 <!-- Newsletter and CTA Section - Modern Light Theme -->
<section class="py-5 bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <!-- Content card with shadow -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
          <div class="row g-0">
            <!-- Image column (only visible on md and larger) -->
            <div class="col-md-5 d-none d-md-block">
              <div class="h-100">
                <img 
                  src="https://picsum.photos/600/800?random=11" 
                  alt="Relaxing spa atmosphere" 
                  class="w-100 h-100 object-fit-cover"
                  loading="lazy"
                >
              </div>
            </div>
            
            <!-- Content column -->
            <div class="col-md-7">
              <div class="card-body p-4 p-lg-5">
                <div class="text-center text-md-start mb-4">
                  <span class="badge bg-primary bg-opacity-10 text-primary fs-6 rounded-pill px-3 py-2 mb-3">Liitu meiega</span>
                  <h2 class="display-5 fw-bold mb-3">Hellita ennast <span class="text-primary">luksusega</span></h2>
                  <p class="lead text-body-secondary mb-4">
                    Avasta meie eksklusiivsed spa pakkumised ja erihinnad. Oleme siin, et luua teile täiuslik puhkuseelamus.
                  </p>
                </div>
                
                <!-- Newsletter form -->
                <div class="mb-4">
                  <form class="row g-3">
                    <div class="col-12">
                      <div class="form-floating mb-3">
                        <input type="email" class="form-control form-control-lg" id="emailInput" placeholder="name@example.com" required>
                        <label for="emailInput" class="text-body-secondary">
                          <i class="bi bi-envelope me-2 text-primary"></i>Teie e-posti aadress
                        </label>
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg py-3 rounded-3">
                          <i class="bi bi-bell me-2"></i>Liitu uudiskirjaga
                        </button>
                      </div>
                      <div class="form-text text-center mt-2">
                        <i class="bi bi-shield-check me-1 text-primary"></i>Me austame teie privaatsust ja ei jaga teie andmeid.
                      </div>
                    </div>
                  </form>
                </div>
                
                <!-- Contact and booking options -->
                <div class="row g-3">
                  <div class="col-sm-6">
                    <a href="tel:+37255667788" class="d-flex align-items-center p-3 rounded-3 bg-light text-decoration-none">
                      <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-telephone-fill fs-4"></i>
                      </div>
                      <div>
                        <div class="text-body-secondary small">Helista meile</div>
                        <div class="fw-medium">+372 5566 7788</div>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-6">
                    <a href="mailto:info@spaa.ee" class="d-flex align-items-center p-3 rounded-3 bg-light text-decoration-none">
                      <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi bi-envelope-fill fs-4"></i>
                      </div>
                      <div>
                        <div class="text-body-secondary small">Kirjuta meile</div>
                        <div class="fw-medium">info@spaa.ee</div>
                      </div>
                    </a>
                  </div>
                  <div class="col-12 mt-4">
                    <a href="book.php" class="btn btn-outline-primary btn-lg w-100 py-3 rounded-3">
                      <i class="bi bi-calendar-check me-2"></i>Broneeri koht nüüd
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

</main>

<!-- Custom Scripts for Animation and Interactivity -->
<script>
// Initialize all features when document is ready
document.addEventListener('DOMContentLoaded', function() {
  /**
   * Testimonials slider initialization
   * Uses Swiper library for smooth, responsive carousel
   */
  if (typeof Swiper !== 'undefined') {
    new Swiper('.testimonials-slider', {
      slidesPerView: 1,
      spaceBetween: 30,
      grabCursor: true,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
        dynamicBullets: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        768: {
          slidesPerView: 2,
        },
        1200: {
          slidesPerView: 3,
        }
      }
    });
  }
  
  /**
   * Counter animation with IntersectionObserver
   * Animates number counting when element comes into view
   */
  const counterElements = document.querySelectorAll('.counter-value');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const targetValue = parseFloat(entry.target.textContent);
        let currentValue = 0;
        const duration = 2000; // ms
        const steps = 50;
        const stepValue = targetValue / steps;
        const stepTime = duration / steps;
        const isDecimal = String(targetValue).includes('.');
        
        const counter = setInterval(() => {
          currentValue += stepValue;
          if (currentValue >= targetValue) {
            entry.target.textContent = isDecimal ? targetValue.toFixed(1) : targetValue;
            clearInterval(counter);
          } else {
            entry.target.textContent = isDecimal ? 
              currentValue.toFixed(1) : 
              Math.round(currentValue);
          }
        }, stepTime);
        
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });
  
  counterElements.forEach(el => {
    observer.observe(el);
  });
  
  /**
   * Gallery image viewer
   * Opens gallery images with lightbox effect or in new tab as fallback
   */
  const galleryLinks = document.querySelectorAll('.gallery-item');
  galleryLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      
      // Try to detect if a lightbox library is available
      if (typeof GLightbox !== 'undefined') {
        GLightbox({elements: [{ href: this.href, type: 'image' }]}).open();
      } else if (typeof lightGallery !== 'undefined') {
        lightGallery(document.querySelector('.gallery'), {
          dynamic: true,
          dynamicEl: [{ src: this.href, thumb: this.href }]
        });
      } else {
        // Fallback - open image in new tab
        window.open(this.href, '_blank');
      }
    });
  });
  
  /**
   * Parallax effect for hero section
   * Creates depth effect on scroll
   */
  const parallaxContainer = document.querySelector('.parallax-container');
  if (parallaxContainer) {
    window.addEventListener('scroll', function() {
      const scrollPosition = window.pageYOffset;
      const translateY = Math.min(scrollPosition * 0.4, 150); // Limit maximum movement
      parallaxContainer.style.transform = `translateY(${translateY}px)`;
    });
  }
  
  /**
   * Scroll animations with IntersectionObserver
   * Fades in elements as they enter the viewport
   */
  const fadeElements = document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right');
  const scrollObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        // Add small delay between elements for cascade effect
        setTimeout(() => {
          entry.target.classList.add('visible');
        }, 150 * Array.from(fadeElements).indexOf(entry.target) % 5);
        
        scrollObserver.unobserve(entry.target);
      }
    });
  }, { 
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px' 
  });
  
  fadeElements.forEach(el => {
    scrollObserver.observe(el);
  });

  /**
   * Add smooth scrolling to anchor links
   */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const targetId = this.getAttribute('href');
      if (targetId !== '#') {
        e.preventDefault();
        document.querySelector(targetId).scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
});
</script>

<?php include 'footer.php'; ?>