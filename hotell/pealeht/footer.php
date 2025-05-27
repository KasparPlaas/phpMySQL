<?php
// Hotelli jaluse komponent
// Kasutatakse kõigil lehtedel
$praegune_aasta = date('Y');
$hotelli_nimi = "Eesti Hotell"; // Match header
$hotelli_aadress = "Hotellitee 12, Tallinn";
$hotelli_email = "info@eestihotell.ee";
$hotelli_telefon = "+372 5555 5555";
?>

<!-- Jalus algus -->
<footer class="border-top py-5 bg-white">
    <div class="container">
        <div class="row g-4">
            <!-- Kontaktandmed -->
            <div class="col-lg-4 col-md-6">
                <a class="navbar-brand d-flex align-items-center mb-3 text-decoration-none" href="../index.php">
                    <i class="bi bi-building fs-3 text-primary me-2"></i>
                    <span class="h5 fw-bold mb-0 text-dark"><?php echo $hotelli_nimi; ?></span>
                </a>
                
                <div class="mb-3">
                    <p class="mb-2 text-muted">
                        <i class="bi bi-geo-alt-fill me-2 text-primary"></i> 
                        <?php echo $hotelli_aadress; ?>
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-envelope-fill me-2 text-primary"></i> 
                        <a href="mailto:<?php echo $hotelli_email; ?>" class="text-muted text-decoration-none">
                            <?php echo $hotelli_email; ?>
                        </a>
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-telephone-fill me-2 text-primary"></i> 
                        <a href="tel:<?php echo str_replace(' ', '', $hotelli_telefon); ?>" class="text-muted text-decoration-none">
                            <?php echo $hotelli_telefon; ?>
                        </a>
                    </p>
                </div>
                
                <!-- Sotsiaalmeedia lingid -->
                <div class="d-flex gap-3">
                    <a href="#" class="text-muted" title="Facebook">
                        <i class="bi bi-facebook fs-5"></i>
                    </a>
                    <a href="#" class="text-muted" title="Instagram">
                        <i class="bi bi-instagram fs-5"></i>
                    </a>
                    <a href="#" class="text-muted" title="Twitter">
                        <i class="bi bi-twitter fs-5"></i>
                    </a>
                    <a href="#" class="text-muted" title="LinkedIn">
                        <i class="bi bi-linkedin fs-5"></i>
                    </a>
                </div>
            </div>

            <!-- Kiirlingid -->
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-3 text-dark">Lingid</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="../pealeht/index.php" class="text-muted text-decoration-none">Avaleht</a>
                    </li>
                    <li class="mb-2">
                        <a href="#toad" class="text-muted text-decoration-none">Toad</a>
                    </li>
                    <li class="mb-2">
                        <a href="#teenused" class="text-muted text-decoration-none">Teenused</a>
                    </li>
                    <li class="mb-2">
                        <a href="#kontakt" class="text-muted text-decoration-none">Kontakt</a>
                    </li>
                    <li class="mb-2">
                        <a href="../meist.php" class="text-muted text-decoration-none">Meist</a>
                    </li>
                </ul>
            </div>

            <!-- Uudiskiri -->
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-3 text-dark">Uudiskiri</h5>
                <p class="small text-muted mb-3">
                    Liitu meie uudiskirjaga, et saada teada eripakkumistest ja uudistest.
                </p>
                
                <form id="uudiskiri-vorm" class="mb-3" novalidate>
                    <div class="input-group">
                        <input 
                            type="email" 
                            id="uudiskiri-email" 
                            class="form-control border-end-0" 
                            placeholder="Sinu e-posti aadress" 
                            required
                        >
                        <button class="btn btn-outline-primary border-start-0" type="submit">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                    <div id="email-viga" class="text-danger small mt-1 d-none">
                        Palun sisesta kehtiv e-posti aadress
                    </div>
                </form>
                
                <div class="form-check">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        id="noustun-tingimustega" 
                        required
                    >
                    <label class="form-check-label small text-muted" for="noustun-tingimustega">
                        Nõustun 
                        <a href="../privaatsus.php" class="text-primary">privaatsustingimustega</a>
                    </label>
                </div>
            </div>

            <!-- Kaart -->
            <div class="col-lg-3 col-md-6">
                <h5 class="fw-bold mb-3 text-dark">Asukoht</h5>
                <div class="ratio ratio-16x9 rounded border overflow-hidden">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d20382.22319616376!2d24.7531885!3d59.4372297!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x469293672b5b9f0f%3A0xf827c9f8c6a1dff0!2sTallinn%2C%20Estonia!5e0!3m2!1sen!2sus!4v1620000000000!5m2!1sen!2sus" 
                        class="w-100 h-100 border-0" 
                        loading="lazy" 
                        allowfullscreen
                        title="Eesti Hotelli asukoht kaardil">
                    </iframe>
                </div>
            </div>
        </div>

        <!-- Jaluse eraldaja -->
        <hr class="my-4 border-light">

        <!-- Jaluse alumine osa -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <small class="text-muted">
                    &copy; <?php echo $praegune_aasta; ?> <?php echo $hotelli_nimi; ?>. Kõik õigused kaitstud.
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <small>
                    <a href="../privaatsus.php" class="text-muted text-decoration-none me-3">
                        Privaatsuspoliitika
                    </a>
                    <a href="../tingimused.php" class="text-muted text-decoration-none">
                        Kasutajatingimused
                    </a>
                </small>
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript uudiskirja vormile -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Uudiskirja vormi käsitlemine
    const uudiskirjaVorm = document.getElementById('uudiskiri-vorm');
    const emailSisend = document.getElementById('uudiskiri-email');
    const emailViga = document.getElementById('email-viga');
    const noustunTingimustega = document.getElementById('noustun-tingimustega');
    
    if (uudiskirjaVorm) {
        uudiskirjaVorm.addEventListener('submit', function(sundmus) {
            sundmus.preventDefault();
            
            // E-posti aadressi kontrollimine
            const emailVaartus = emailSisend.value.trim();
            const emailMuster = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            // Vea peitmise
            emailViga.classList.add('d-none');
            emailSisend.classList.remove('is-invalid');
            
            if (!emailMuster.test(emailVaartus)) {
                emailViga.classList.remove('d-none');
                emailSisend.classList.add('is-invalid');
                emailSisend.focus();
                return;
            }
            
            if (!noustunTingimustega.checked) {
                alert('Palun nõustu privaatsustingimustega');
                noustunTingimustega.focus();
                return;
            }
            
            // Siin saaks saata andmed serverisse
            console.log('Uudiskirjaga liitumine:', emailVaartus);
            alert('Täname! Oled edukalt uudiskirjaga liitunud.');
            
            // Vormi puhastamine
            uudiskirjaVorm.reset();
        });
    }
    
    // Sotsiaalmeedia linkide hõljumine
    const sotsiaalLingid = document.querySelectorAll('footer a[title]');
    sotsiaalLingid.forEach(function(link) {
        link.addEventListener('mouseenter', function() {
            this.style.color = '#0d6efd';
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'all 0.3s ease';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.color = '#6c757d';
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Menüü linkide hõljumine
    const menuLingid = document.querySelectorAll('footer .list-unstyled a');
    menuLingid.forEach(function(link) {
        link.addEventListener('mouseenter', function() {
            this.style.color = '#0d6efd';
            this.style.paddingLeft = '5px';
            this.style.transition = 'all 0.3s ease';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.color = '#6c757d';
            this.style.paddingLeft = '0';
        });
    });
});
</script>
</body>
</html>