<?php include 'header.php'; ?>

<main>

  <!-- Hero Section -->
  <section class="vh-100 d-flex align-items-center bg-dark">
    <div class="container">
      <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden">
        <img 
          src="https://picsum.photos/id/1056/1920/1080" 
          alt="Rahulik spaamaastik" 
          class="w-100 h-100 object-fit-cover" 
          loading="lazy"
        >
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-75"></div>
      </div>
      <div class="position-relative text-center text-white py-5">
        <div class="col-lg-8 mx-auto">
          <h1 class="display-2 fw-bold mb-4">Leia oma sisemine rahu</h1>
          <div class="lead fs-3 mb-5 text-white-75">Luxury spa kogemus Eesti parimas kuurordis</div>
          <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
            <a href="book.php" class="btn btn-primary btn-lg px-5 py-3 fw-bold rounded-pill">
              <i class="bi bi-calendar-plus me-2"></i>Broneeri koht
            </a>
            <a href="packages.php" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold rounded-pill">
              <i class="bi bi-gift me-2"></i>Paketid
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="py-5 bg-body-tertiary">
    <div class="container py-5">
      <div class="row g-5 align-items-center">
        <div class="col-lg-5">
          <span class="badge bg-primary bg-opacity-10 text-primary-emphasis fs-6 mb-3 rounded-pill px-4 py-2">Tere tulemast</span>
          <h2 class="display-4 fw-bold mb-4">Avasta Spaahotelli</h2>
          <p class="lead text-body-secondary mb-5">Elamused, mis jätavad igaveseks mulje. Pakume eksklusiivseid pakette romantikale, puhkusele, spaale ja sünnipäevadeks.</p>
          
          <div class="d-flex flex-column flex-sm-row gap-3">
            <a href="#packages" class="btn btn-primary px-4 py-3 rounded-pill">Vaata pakette</a>
            <a href="contact.php" class="btn btn-outline-primary px-4 py-3 rounded-pill">Kontakt</a>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="row g-4">
            <div class="col-md-6">
              <div class="card border shadow-sm h-100 bg-body">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-check2-circle text-primary fs-1 me-3"></i>
                    <h3 class="h4 mb-0 fw-bold">Professionaalsed terapeudid</h3>
                  </div>
                  <p class="text-body-secondary mb-0">Kogenud spetsialistid, kes aitavad teil lõõgastuda</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card border shadow-sm h-100 bg-body">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-flower1 text-primary fs-1 me-3"></i>
                    <h3 class="h4 mb-0 fw-bold">Orgaanilised tooted</h3>
                  </div>
                  <p class="text-body-secondary mb-0">Looduslikud ja ohutud koostisosad</p>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm">
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

  <!-- Packages Grid -->
  <section id="packages" class="py-5">
    <div class="container py-5">
      <div class="text-center mb-5">
        <span class="badge bg-primary bg-opacity-10 text-primary-emphasis fs-6 rounded-pill px-4 py-2 mb-3">Meie teenused</span>
        <h2 class="display-4 fw-bold mb-3">Populaarsemad paketid</h2>
        <p class="lead text-body-secondary mx-auto" style="max-width: 600px">Valige endale sobivaim pakett unikaalseks kogemuseks</p>
      </div>

      <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
        <?php
        $offers = [
          'romantika' => ['title' => 'Romantiline puhkus', 'price' => rand(120, 180), 'icon' => 'bi-heart'],
          'puhkus' => ['title' => 'Lõõgastuspuhkus', 'price' => rand(90, 150), 'icon' => 'bi-emoji-smile'],
          'spaa' => ['title' => 'Premium Spa', 'price' => rand(150, 220), 'icon' => 'bi-spa'],
          'synnipaev' => ['title' => 'Sünnipäevapakk', 'price' => rand(200, 300), 'icon' => 'bi-gift'],
        ];
        
        foreach ($offers as $key => $offer): ?>
          <div class="col">
            <div class="card h-100 border shadow-sm bg-body">
              <div class="card-img-top position-relative overflow-hidden">
                <img 
                  src="https://picsum.photos/800/600?random=<?= rand(1,100) ?>" 
                  class="object-fit-cover" 
                  style="height: 250px" 
                  alt="<?= htmlspecialchars($offer['title']) ?>"
                >
                <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-dark bg-opacity-75 text-white">
                  <div class="d-flex justify-content-between align-items-center">
                    <h3 class="h5 mb-0"><?= htmlspecialchars($offer['title']) ?></h3>
                    <span class="badge bg-primary rounded-pill px-3"><?= $offer['price'] ?>€</span>
                  </div>
                </div>
              </div>
              <div class="card-body p-4">
                <div class="d-grid">
                  <a href="book.php?package=<?= urlencode($key) ?>" class="btn btn-outline-primary btn-lg rounded-pill">
                    <i class="bi <?= $offer['icon'] ?> me-2"></i>Vaata lähemalt
                  </a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Testimonials Carousel -->
  <section class="py-5 bg-body-tertiary">
    <div class="container py-5">
      <div class="text-center mb-5">
        <span class="badge bg-primary bg-opacity-10 text-primary-emphasis fs-6 rounded-pill px-4 py-2 mb-3">Kliendid räägivad</span>
        <h2 class="display-4 fw-bold mb-3">Klientide arvamused</h2>
        <p class="lead text-body-secondary mx-auto" style="max-width: 600px">Meie rahulolevate klientide kogemused</p>
      </div>

      <div class="swiper testimonial-slider">
        <div class="swiper-wrapper">
          <?php
          $testimonials = [
            ['name' => 'Anna K', 'location' => 'Tartu', 'text' => 'Parim spaakogemus, mis mul kunagi olnud on! Töötajad olid väga abivalmid ja ruumid imeilusad.', 'image' => 'https://picsum.photos/seed/anna/200/200', 'stars' => 5],
            ['name' => 'Marko T', 'location' => 'Tallinn', 'text' => 'Täiuslik romantiline puhkuspäev abikaasaga. Soovitame soojalt!', 'image' => 'https://picsum.photos/seed/marko/200/200', 'stars' => 4.5],
            ['name' => 'Katri L', 'location' => 'Pärnu', 'text' => 'Tähistasime siin oma 10. pulma-aastapäeva - täiuslik koht eriliseks päevaks!', 'image' => 'https://picsum.photos/seed/katri/200/200', 'stars' => 5],
          ];

          foreach ($testimonials as $t):
            $fullStars = floor($t['stars']);
            $halfStar = ($t['stars'] - $fullStars) >= 0.5;
          ?>
            <div class="swiper-slide">
              <div class="card border shadow-sm h-100 bg-body">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center mb-4">
                    <img 
                      src="<?= $t['image'] ?>" 
                      alt="<?= $t['name'] ?>" 
                      class="rounded-circle me-3 object-fit-cover" 
                      width="60" 
                      height="60"
                    >
                    <div>
                      <h3 class="h5 mb-0 fw-bold"><?= htmlspecialchars($t['name']) ?></h3>
                      <small class="text-body-secondary"><?= htmlspecialchars($t['location']) ?></small>
                    </div>
                  </div>
                  <div class="mb-3 text-warning">
                    <?= str_repeat('<i class="bi bi-star-fill fs-5"></i>', $fullStars) ?>
                    <?= $halfStar ? '<i class="bi bi-star-half fs-5"></i>' : '' ?>
                  </div>
                  <blockquote class="mb-0">
                    <p class="fst-italic text-body">"<?= htmlspecialchars($t['text']) ?>"</p>
                  </blockquote>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="swiper-pagination mt-4"></div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-5 bg-primary bg-gradient text-white">
    <div class="container py-5">
      <div class="text-center">
        <h2 class="display-4 fw-bold mb-4">Kas olete valmis lõõgastuma?</h2>
        <p class="lead text-white-75 mb-5 mx-auto" style="max-width: 600px">Broneerige oma spaapäev juba täna ja naudige erilist rahu</p>
        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
          <a href="book.php" class="btn btn-light btn-lg px-5 py-3 fw-bold rounded-pill">
            <i class="bi bi-calendar-plus me-2"></i>Broneeri koht
          </a>
          <a href="tel:+37255667788" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold rounded-pill">
            <i class="bi bi-telephone me-2"></i>+372 5566 7888
          </a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php include 'footer.php'; ?>