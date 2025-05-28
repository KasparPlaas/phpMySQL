<?php include '../pealeht/header.php'; ?>

    <!-- Pealeht -->
    <section class="bg-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-dark mb-4">Tere tulemast Eesti Hotelli!</h1>
                    <p class="lead mb-4 text-muted">Meie hubane hotell asub südamlik kesklinnas ja pakub kvaliteetset majutust sõbralige hindadega. Kogege Eesti külalislahkust meie professionaalse teenindusega.</p>
                    <div class="d-flex gap-3 flex-wrap mb-3">
                        <button class="btn btn-dark btn-lg" onclick="scrollToSection('broneerimine')">
                            <i class="bi bi-calendar-check"></i> Alusta broneerimist
                        </button>
                        <button class="btn btn-outline-dark btn-lg" onclick="showRegister()">
                            <i class="bi bi-person-plus"></i> Registreeru
                        </button>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="bg-dark text-white p-5 rounded-3 shadow">
                        <i class="bi bi-building display-1 mb-3"></i>
                        <h3>Kvaliteetne majutus</h3>
                        <p class="mb-0">Alates 45€ ööst</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Meist -->
    <section id="meist" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-4 text-dark">Meist</h2>
                    <p class="lead mb-5 text-muted">Eesti Hotell on olnud Tallinna südames külalisi teenindamas juba üle kümne aasta. Meie missioon on pakkuda kõigile külastajatele mõnusat ja meeldejäävat majutuskogemust.</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="bg-white p-4 rounded-3 h-100 border shadow-sm">
                        <i class="bi bi-award text-dark display-4 mb-3"></i>
                        <h5 class="text-dark">10+ aastat kogemust</h5>
                        <p class="mb-0 text-muted">Pikaajaline kogemus külaliste teenindamisel</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="bg-white p-4 rounded-3 h-100 border shadow-sm">
                        <i class="bi bi-geo-alt text-dark display-4 mb-3"></i>
                        <h5 class="text-dark">Suurepärane asukoht</h5>
                        <p class="mb-0 text-muted">Kesklinna südames, kõik vaatamisväärsused lähedal</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="bg-white p-4 rounded-3 h-100 border shadow-sm">
                        <i class="bi bi-people text-dark display-4 mb-3"></i>
                        <h5 class="text-dark">Sõbralik personal</h5>
                        <p class="mb-0 text-muted">Professionaalne ja abivalmis meeskond 24/7</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Miks meid valida -->
    <section id="miks" class="bg-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <h2 class="display-5 fw-bold mb-4 text-dark">Miks meid valida?</h2>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3 fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Konkurentsivõimelised hinnad</h6>
                                    <p class="mb-0 text-muted">Parimad hinnad Tallinna kesklinnas kvaliteedi säilitamisega</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3 fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Puhtus ja mugavus</h6>
                                    <p class="mb-0 text-muted">Kõrgete hügieenistandardite järgimine ja mugavad toad</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3 fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Tasuta teenused</h6>
                                    <p class="mb-0 text-muted">Wifi, parkla ja hommikusöök - kõik kaasas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-success me-3 fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1 text-dark">Paindlik broneerimine</h6>
                                    <p class="mb-0 text-muted">Lihtne broneerimisprotsess ja tasuta tühistamine</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="bg-dark text-white p-4 rounded-3 text-center">
                                <i class="bi bi-star-fill display-4 mb-2"></i>
                                <h4 class="fw-bold">4.8/5</h4>
                                <small>Keskmine hinnang</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success text-white p-4 rounded-3 text-center">
                                <i class="bi bi-people-fill display-4 mb-2"></i>
                                <h4 class="fw-bold">5000+</h4>
                                <small>Rahul klienti</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-secondary text-white p-4 rounded-3 text-center">
                                <i class="bi bi-clock-fill display-4 mb-2"></i>
                                <h4 class="fw-bold">24/7</h4>
                                <small>Vastuvõtt ja tugi</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Meie teenused -->
    <section id="teenused" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-5 text-dark">Meie teenused</h2>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 bg-white">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-wifi text-dark display-4 mb-3"></i>
                            <h5 class="card-title text-dark">Tasuta WiFi</h5>
                            <p class="card-text text-muted">Kiire internetiühendus kõigis tubades ja üldistes ruumides</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 bg-white">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-cup-hot text-dark display-4 mb-3"></i>
                            <h5 class="card-title text-dark">Hommikusöök</h5>
                            <p class="card-text text-muted">Maitsev kontinentaalne hommikusöök meie restoranis</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 bg-white">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-car-front text-dark display-4 mb-3"></i>
                            <h5 class="card-title text-dark">Tasuta parkla</h5>
                            <p class="card-text text-muted">Turvaline ja tasuta parkimiskoht kõigile külalistele</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 bg-white">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-clock text-dark display-4 mb-3"></i>
                            <h5 class="card-title text-dark">24h vastuvõtt</h5>
                            <p class="card-text text-muted">Meie personal on alati valmis teid aitama</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 bg-white">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-droplet text-dark display-4 mb-3"></i>
                            <h5 class="card-title text-dark">Pesuteenused</h5>
                            <p class="card-text text-muted">Kiire ja kvaliteetne pesu- ja keemilise puhastuse teenus</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 bg-white">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-shield-check text-dark display-4 mb-3"></i>
                            <h5 class="card-title text-dark">Turvalisus</h5>
                            <p class="card-text text-muted">Turvakaamerad ja kaardilukkudega toad teie ohutuse tagamiseks</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Alusta broneerimist kohe -->
    <section id="broneerimine" class="bg-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-4 text-dark">Alusta broneerimist kohe</h2>
                    <p class="lead mb-5 text-muted">Vali endale sobiv tuba ja naudi meeldivat puhkust Eesti pealinnas</p>
                </div>
            </div>
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="card h-100 border shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-dark">Üheinimese tuba</h5>
                            <div class="display-6 fw-bold text-success mb-3">45€<small class="fs-6">/öö</small></div>
                            <p class="card-text text-muted">Hubane tuba ühele inimesele, WiFi ja hommikusöök kaasa arvatud</p>
                            <button class="btn btn-outline-dark" onclick="broneeriTuba('Üheinimese tuba')">Broneeri</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100 border-warning shadow">
                        <div class="card-body text-center">
                            <div class="badge bg-warning text-dark mb-2">Populaarseim</div>
                            <h5 class="card-title text-dark">Kaheinimese tuba</h5>
                            <div class="display-6 fw-bold text-success mb-3">65€<small class="fs-6">/öö</small></div>
                            <p class="card-text text-muted">Mugav tuba kahele, eraldi vannituba ja linna vaade</p>
                            <button class="btn btn-dark" onclick="broneeriTuba('Kaheinimese tuba')">Broneeri</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100 border shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-dark">Perekonnatuba</h5>
                            <div class="display-6 fw-bold text-success mb-3">85€<small class="fs-6">/öö</small></div>
                            <p class="card-text text-muted">Suur tuba perele, kuni 4 inimest, köök ja elutuba</p>
                            <button class="btn btn-outline-dark" onclick="broneeriTuba('Perekonnatuba')">Broneeri</button>
                        </div>
                    </div>
                </div>
            </div>
            
            
            </div>
        </div>
    </section>

    <!-- Kontakt -->
    <section id="kontakt" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-4 text-dark">Võtke meiega ühendust</h2>
                    <p class="lead mb-5 text-muted">Kui teil on küsimusi või soovite rohkem infot, oleme alati valmis aitama</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 text-center">
                    <div class="bg-white p-4 rounded-3 h-100 border shadow-sm">
                        <i class="bi bi-telephone text-dark display-4 mb-3"></i>
                        <h5 class="text-dark">Telefon</h5>
                        <p class="mb-0 text-muted">+372 123 4567</p>
                        <p class="mb-0 text-muted">24/7 kättesaadav</p>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="bg-white p-4 rounded-3 h-100 border shadow-sm">
                        <i class="bi bi-envelope text-dark display-4 mb-3"></i>
                        <h5 class="text-dark">E-post</h5>
                        <p class="mb-0 text-muted">info@eestihotell.ee</p>
                        <p class="mb-0 text-muted">Vastame 24h jooksul</p>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="bg-white p-4 rounded-3 h-100 border shadow-sm">
                        <i class="bi bi-geo-alt text-dark display-4 mb-3"></i>
                        <h5 class="text-dark">Aadress</h5>
                        <p class="mb-0 text-muted">Viru väljak 4</p>
                        <p class="mb-0 text-muted">10111 Tallinn, Eesti</p>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card border shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-center mb-4 text-dark">Saada meile sõnum</h5>
                            <form id="kontaktVorm">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-dark">Nimi</label>
                                        <input type="text" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-dark">E-post</label>
                                        <input type="email" class="form-control" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-dark">Teema</label>
                                        <input type="text" class="form-control" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-dark">Sõnum</label>
                                        <textarea class="form-control" rows="4" required></textarea>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-dark btn-lg px-5">
                                            <i class="bi bi-send"></i> Saada sõnum
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Klientide arvustused -->
    <section id="arvustused" class="bg-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-4 text-dark">Meie külaliste arvustused</h2>
                    <p class="lead mb-5 text-muted">Lugege, mida meie rahulolnud külalised ütlevad</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card border shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-warning mb-3">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <p class="card-text text-muted">"Suurepärane teenindus ja väga puhas hotell. Asukoht on ideaalne ning hind on väga mõistlik. Kindlasti tulen tagasi!"</p>
                            <div class="d-flex align-items-center mt-3">
                                <div class="bg-secondary rounded-circle p-2 me-3">
                                    <i class="bi bi-person text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-dark">Mari Kask</h6>
                                    <small class="text-muted">Tallinn</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-warning mb-3">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <p class="card-text text-muted">"Olin äriränkus Tallinnas ja see hotell oli täiuslik valik. WiFi kiire, hommikusöök maitsev ja personal väga abivalmis."</p>
                            <div class="d-flex align-items-center mt-3">
                                <div class="bg-secondary rounded-circle p-2 me-3">
                                    <i class="bi bi-person text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-dark">Peeter Paan</h6>
                                    <small class="text-muted">Tartu</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-warning mb-3">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <p class="card-text text-muted">"Perega puhkuseks suurepärane koht! Perekonnatuba oli suur ja mugav. Lapsed olid väga rahul ning meie ka."</p>
                            <div class="d-flex align-items-center mt-3">
                                <div class="bg-secondary rounded-circle p-2 me-3">
                                    <i class="bi bi-person text-white"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-dark">Liina Tamm</h6>
                                    <small class="text-muted">Pärnu</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="bg-light py-5">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold mb-4">Oled valmis broneerima?</h2>
                    <p class="lead mb-4">Ära jäta oma unistuste puhkust edasi! Broneeri juba täna ja naudi meeldivat ööbimist Tallinna südames.</p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <button class="btn btn-warning btn-lg px-5" onclick="scrollToSection('broneerimine')">
                            <i class="bi bi-calendar-check"></i> Broneeri kohe
                        </button>
                        <button class="btn btn-dark btn-lg px-5" onclick="scrollToSection('kontakt')">
                            <i class="bi bi-telephone"></i> Võta ühendust
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        // Sujuv scrolling sektsioonideni
        function scrollToSection(sectionId) {
            const element = document.getElementById(sectionId);
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }

        // Registreerumise funktsiooni placeholder
        function showRegister() {
            alert('Registreerimise lehele suunamine tuleb implementeerida');
        }

        // Toa broneerimise funktsioon
        function broneeriTuba(toaTyyp) {
            const select = document.getElementById('toaTyyp');
            if (select) {
                select.value = toaTyyp;
                scrollToSection('broneerimine');
            }
        }

        // Vormide käsitlemine
        document.getElementById('broneerimisVorm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Kogu vormi andmed
            const saabumine = document.getElementById('saabumine').value;
            const lahkumine = document.getElementById('lahkumine').value;
            const toaTyyp = document.getElementById('toaTyyp').value;
            const kullalisteArv = document.getElementById('kullalisteArv').value;
            
            // Lihtne valideerimine
            if (!saabumine || !lahkumine || !toaTyyp || !kullalisteArv) {
                alert('Palun täitke kõik kohustuslikud väljad!');
                return;
            }
            
            // Kontrolli, et lahkumise kuupäev on hilisem kui saabumise kuupäev
            if (new Date(lahkumine) <= new Date(saabumine)) {
                alert('Lahkumise kuupäev peab olema hilisem kui saabumise kuupäev!');
                return;
            }
            
            // Näita kinnitussõnumit
            alert('Broneeringu taotlus edukalt esitatud! Võtame teiega ühendust 24 tunni jooksul.');
            
            // Lähtesta vorm
            this.reset();
        });

        // Kontaktivormi käsitlemine
        document.getElementById('kontaktVorm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Näita kinnitussõnumit
            alert('Teie sõnum on edukalt saadetud! Vastame teile esimesel võimalusel.');
            
            // Lähtesta vorm
            this.reset();
        });

        // Seadista minimaalse kuupäeva täna jaoks
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const saabumineInput = document.getElementById('saabumine');
            const lahkumineInput = document.getElementById('lahkumine');
            
            if (saabumineInput) {
                saabumineInput.min = today;
            }
            
            if (lahkumineInput) {
                lahkumineInput.min = today;
            }
            
            // Kui saabumine muutub, uuenda lahkumise min kuupäev
            if (saabumineInput && lahkumineInput) {
                saabumineInput.addEventListener('change', function() {
                    lahkumineInput.min = this.value;
                    if (lahkumineInput.value && lahkumineInput.value <= this.value) {
                        lahkumineInput.value = '';
                    }
                });
            }
        });

        // Smooth scroll animatsioon navigeerimiseks
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                scrollToSection(targetId);
            });
        });

        // Külaliste arvu automaatne uuendamine vastavalt toa tüübile
        document.getElementById('toaTyyp').addEventListener('change', function() {
            const kullalisteArvSelect = document.getElementById('kullalisteArv');
            const selectedValue = this.value;
            
            // Lähtesta valikud
            kullalisteArvSelect.innerHTML = '<option value="">Vali arv</option>';
            
            if (selectedValue === 'Üheinimese tuba') {
                kullalisteArvSelect.innerHTML += '<option value="1">1 isik</option>';
            } else if (selectedValue === 'Kaheinimese tuba') {
                kullalisteArvSelect.innerHTML += '<option value="1">1 isik</option>';
                kullalisteArvSelect.innerHTML += '<option value="2">2 isikut</option>';
            } else if (selectedValue === 'Perekonnatuba') {
                kullalisteArvSelect.innerHTML += '<option value="1">1 isik</option>';
                kullalisteArvSelect.innerHTML += '<option value="2">2 isikut</option>';
                kullalisteArvSelect.innerHTML += '<option value="3">3 isikut</option>';
                kullalisteArvSelect.innerHTML += '<option value="4">4 isikut</option>';
            } else {
                // Kõik valikud saadaval
                kullalisteArvSelect.innerHTML += '<option value="1">1 isik</option>';
                kullalisteArvSelect.innerHTML += '<option value="2">2 isikut</option>';
                kullalisteArvSelect.innerHTML += '<option value="3">3 isikut</option>';
                kullalisteArvSelect.innerHTML += '<option value="4">4 isikut</option>';
            }
        });

        // Scroll-to-top nupp (valikuline)
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Võiks lisada scroll-to-top nupu, kui see on soovitud
            if (scrollTop > 300) {
                // Näita nupp
            } else {
                // Peida nupp
            }
        });

        // Animatsioonid elementidele, mis tulevad nähtavaks
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Rakenda animatsioon kõikidele kaartidele ja sektsioonidele
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.card, .row > .col-lg-4, .row > .col-md-4, .row > .col-md-6');
            
            animatedElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });
        });
    </script>
<?php include '../pealeht/footer.php'; ?>