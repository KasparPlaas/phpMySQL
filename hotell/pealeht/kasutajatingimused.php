<?php include 'header.php'; ?>

<main class="kasutajatingused-container">
    <div class="container">
        <div class="content-wrapper">
            <header class="page-header">
                <h1>Kasutajatingimused</h1>
                <p class="subtitle">Kehtiv alates: <?php echo date('d.m.Y'); ?></p>
            </header>

            <div class="terms-content">
                <section class="terms-section">
                    <h2>1. Üldised tingimused</h2>
                    <p>Need kasutajatingimused reguleerivad käesoleva veebisaidi kasutamist. Veebisaiti kasutades nõustute järgmiste tingimustega.</p>
                </section>

                <section class="terms-section">
                    <h2>2. Kasutaja kohustused</h2>
                    <p>Kasutaja kohustub:</p>
                    <ul>
                        <li>Kasutama veebisaiti seaduslikul eesmärgil</li>
                        <li>Mitte kahjustama veebisaidi toimimist</li>
                        <li>Austama teiste kasutajate õigusi</li>
                        <li>Esitama õigeid andmeid registreerimisel</li>
                    </ul>
                </section>

                <section class="terms-section">
                    <h2>3. Andmekaitse</h2>
                    <p>Teie isikuandmete töötlemine toimub kooskõlas Eesti Vabariigi andmekaitseseaduse ja GDPR-iga. Täpsem info meie privaatsuspoliitikast.</p>
                </section>

                <section class="terms-section">
                    <h2>4. Vastutuse piiramine</h2>
                    <p>Veebisaidi operaator ei vastuta otseste ega kaudsete kahjude eest, mis võivad tekkida veebisaidi kasutamisest.</p>
                </section>

                <section class="terms-section">
                    <h2>5. Muudatused tingimustesse</h2>
                    <p>Kasutajatingimusi võidakse muuta. Muudatustest teavitatakse veebisaidil või e-posti teel.</p>
                </section>

                <section class="terms-section">
                    <h2>6. Kontakt</h2>
                    <p>Küsimuste korral võtke meiega ühendust:</p>
                    <div class="contact-info">
                        <p><strong>E-post:</strong> info@example.ee</p>
                        <p><strong>Telefon:</strong> +372 123 4567</p>
                        <p><strong>Aadress:</strong> Tallinn, Eesti</p>
                    </div>
                </section>
            </div>

            <div class="acceptance-section">
                <p class="acceptance-text">
                    <strong>Nõustumine:</strong> Veebisaiti kasutades kinnitate, et olete tutvunud kasutajatingimustega ja nõustute nendega.
                </p>
            </div>
        </div>
    </div>
</main>

<style>
.kasutajatingused-container {
    min-height: 80vh;
    background-color: #ffffff;
    padding: 2rem 0;
    font-family: 'Arial', 'Helvetica', sans-serif;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 1rem;
}

.content-wrapper {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.subtitle {
    margin: 0;
    opacity: 0.9;
    font-size: 1rem;
}

.terms-content {
    padding: 2rem;
    line-height: 1.6;
    color: #333;
}

.terms-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
}

.terms-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.terms-section h2 {
    color: #0072ce;
    font-size: 1.4rem;
    margin: 0 0 1rem;
    font-weight: 600;
}

.terms-section p {
    margin: 0 0 1rem;
    text-align: justify;
}

.terms-section ul {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.terms-section li {
    margin-bottom: 0.5rem;
}

.contact-info {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 5px;
    border-left: 4px solid #0072ce;
}

.contact-info p {
    margin: 0.5rem 0;
}

.acceptance-section {
    background: #e3f2fd;
    padding: 1.5rem 2rem;
    border-top: 1px solid #bbdefb;
}

.acceptance-text {
    margin: 0;
    text-align: center;
    color: #1565c0;
}

/* Responsive design */
@media (max-width: 768px) {
    .kasutajatingused-container {
        padding: 1rem 0;
    }
    
    .page-header {
        padding: 2rem 1rem 1.5rem;
    }
    
    .page-header h1 {
        font-size: 2rem;
    }
    
    .terms-content {
        padding: 1.5rem;
    }
    
    .acceptance-section {
        padding: 1rem 1.5rem;
    }
}

/* Print styles */
@media print {
    .kasutajatingused-container {
        background: white;
        box-shadow: none;
    }
    
    .page-header {
        background: white !important;
        color: black !important;
    }
    
    .terms-section {
        break-inside: avoid;
    }
}
</style>

<?php include 'footer.php'; ?>