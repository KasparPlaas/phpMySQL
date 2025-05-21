        <footer class="bg-dark text-white pt-5 pb-4">
            <div class="container">
                <div class="row g-4">
                    <!-- Contact Info -->
                    <div class="col-lg-4 col-md-6">
                        <a class="navbar-brand d-flex align-items-center mb-3" href="#">
                            <i class="fas fa-spa fs-3 text-primary me-2"></i>
                            <span class="h5 fw-bold mb-0">Spaa Hotell</span>
                        </a>
                        <address class="mb-3">
                            <p class="mb-2"><i class="fas fa-map-marker-alt me-2 text-primary"></i> Spaatee 12, Puhkuseküla</p>
                            <p class="mb-2"><i class="fas fa-envelope me-2 text-primary"></i> <a href="mailto:info@spahotell.ee" class="text-white text-decoration-none">info@spahotell.ee</a></p>
                            <p class="mb-0"><i class="fas fa-phone-alt me-2 text-primary"></i> <a href="tel:+37255555555" class="text-white text-decoration-none">+372 5555 5555</a></p>
                        </address>
                        <div class="d-flex gap-3">
                            <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-tripadvisor"></i></a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6">
                        <h5 class="fw-bold mb-3">Info</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="#" class="text-white text-decoration-none link-hover-primary">Klienditugi / Kontakt</a></li>
                            <li class="mb-2"><a href="#" class="text-white text-decoration-none link-hover-primary">Tutvustus</a></li>
                            <li class="mb-2"><a href="#" class="text-white text-decoration-none link-hover-primary">Tingimused</a></li>
                            <li class="mb-2"><a href="#" class="text-white text-decoration-none link-hover-primary">Küpsised</a></li>
                            <li class="mb-2"><a href="#" class="text-white text-decoration-none link-hover-primary">Privaatsus</a></li>
                        </ul>
                    </div>

                    <!-- Newsletter -->
                    <div class="col-lg-3 col-md-6">
                        <h5 class="fw-bold mb-3">Uudiskiri</h5>
                        <p class="small">Liitu meie uudiskirjaga eripakkumiste ja uudiste jaoks.</p>
                        <form class="mb-3" novalidate>
                            <div class="input-group">
                                <input type="email" class="form-control bg-black border-dark text-white" placeholder="Sinu e-post" required>
                                <button class="btn btn-primary" type="submit"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </form>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="newsletterConsent" checked>
                            <label class="form-check-label small" for="newsletterConsent">Nõustun privaatsustingimustega</label>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="col-lg-3 col-md-6">
                        <h5 class="fw-bold mb-3">Asukoht</h5>
                        <div class="ratio ratio-16x9 rounded overflow-hidden">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d20382.22319616376!2d24.7531885!3d59.4372297!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x469293672b5b9f0f%3A0xf827c9f8c6a1dff0!2sTallinn%2C%20Estonia!5e0!3m2!1sen!2sus!4v1620000000000!5m2!1sen!2sus" 
                                class="w-100 h-100 border-0 opacity-75" 
                                loading="lazy" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>

                <hr class="my-4 border-secondary">

                <!-- Footer Bottom -->
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <small>&copy; <?= date('Y') ?> Spaa Hotell. Kõik õigused kaitstud.</small>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <small>
                            <a href="#" class="text-white text-decoration-none me-2">Privaatsuspoliitika</a>
                            <a href="#" class="text-white text-decoration-none">Kasutajatingimused</a>
                        </small>
                    </div>
                </div>
            </div>
        </footer>
    </body>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</html>