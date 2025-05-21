<?php include 'header.php'; ?>

<main>
  <section class="py-5 bg-body-tertiary">
    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-7">
          <div class="text-center mb-5">
            <span class="badge bg-primary text-white fs-6 rounded-pill px-4 py-2 mb-3">Kontakt</span>
            <h1 class="display-4 fw-bold mb-3">Võta meiega ühendust</h1>
            <p class="lead text-body-secondary mx-auto" style="max-width: 500px">
              Küsimuste, broneeringute või koostöösoovide korral täida allolev vorm või kasuta kontaktandmeid. Vastame tavaliselt samal tööpäeval!
            </p>
          </div>
          <div class="card border-0 shadow rounded-4 p-4 mb-4">
            <form>
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="name" class="form-label">Nimi</label>
                  <input type="text" class="form-control" id="name" placeholder="Teie nimi" required>
                </div>
                <div class="col-md-6">
                  <label for="email" class="form-label">E-post</label>
                  <input type="email" class="form-control" id="email" placeholder="teie@email.ee" required>
                </div>
                <div class="col-12">
                  <label for="subject" class="form-label">Teema</label>
                  <input type="text" class="form-control" id="subject" placeholder="Broneering, küsimus vms" required>
                </div>
                <div class="col-12">
                  <label for="message" class="form-label">Sõnum</label>
                  <textarea class="form-control" id="message" rows="5" placeholder="Kirjuta oma küsimus või soov..." required></textarea>
                </div>
              </div>
              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                  <i class="bi bi-send me-2"></i>Saada sõnum
                </button>
              </div>
            </form>
          </div>
          <div class="row g-4 mb-4">
            <div class="col-md-6">
              <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex align-items-center mb-2">
                  <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;">
                    <i class="bi bi-telephone-fill fs-4"></i>
                  </div>
                  <div>
                    <div class="fw-bold">Telefon</div>
                    <a href="tel:+37255667788" class="text-body-secondary text-decoration-none">+372 5566 7788</a>
                  </div>
                </div>
                <div class="mt-3">
                  <div class="fw-bold mb-1">Avatud</div>
                  <div class="text-body-secondary small">E-R 09:00–20:00<br>L-P 10:00–18:00</div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <div class="d-flex align-items-center mb-2">
                  <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;">
                    <i class="bi bi-envelope-fill fs-4"></i>
                  </div>
                  <div>
                    <div class="fw-bold">E-post</div>
                    <a href="mailto:info@spaa.ee" class="text-body-secondary text-decoration-none">info@spaa.ee</a>
                  </div>
                </div>
                <div class="mt-3">
                  <div class="fw-bold mb-1">Aadress</div>
                  <div class="text-body-secondary small">Spaahotell, Puhkuse tee 1, Tallinn</div>
                </div>
              </div>
            </div>
          </div>
          <div class="card border-0 shadow rounded-4 p-4 mb-4">
            <div class="row g-3 align-items-center">
              <div class="col-12 col-md-6 mb-3 mb-md-0">
                <iframe
                  src="https://www.openstreetmap.org/export/embed.html?bbox=24.7536%2C59.4369%2C24.7636%2C59.4469&amp;layer=mapnik"
                  style="width:100%;height:200px;border-radius:12px;border:1px solid #eee;"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"
                  title="Spaahotell kaart"
                ></iframe>
              </div>
              <div class="col-12 col-md-6">
                <div class="fw-bold mb-2">Kuidas meid leida?</div>
                <div class="text-body-secondary small mb-2">
                  Tasuta parkimine hotelli ees.<br>
                  Ühistransport: Buss nr 5, peatus "Puhkuse tee".
                </div>
                <div class="text-body-secondary small">
                  <i class="bi bi-info-circle me-1"></i> Vajad abi? Helista või kirjuta meile!
                </div>
              </div>
            </div>
          </div>
          <div class="text-center mt-5">
            <a href="book.php" class="btn btn-outline-primary btn-lg rounded-pill px-5 py-3">
              <i class="bi bi-calendar-check me-2"></i>Broneeri aeg
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include 'footer.php'; ?>
