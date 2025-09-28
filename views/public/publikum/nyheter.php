<?php
// Set page variables
$page_title = 'Nyheter - Jaktfeltcup';
$page_description = 'Hold deg oppdatert på det som skjer i Jaktfeltcup. Siste nytt, resultater og oppdateringer.';
$current_page = 'publikum_nyheter';

// Include required files
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';
?>

<?php include_header(); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-newspaper me-2"></i>Nyheter</h3>
                </div>
                <div class="card-body">
                    <p class="lead">Hold deg oppdatert på det som skjer i Jaktfeltcup.</p>
                    
                    <!-- News Articles -->
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="card">
                                <img src="https://via.placeholder.com/800x400?text=Jaktfeltcup+2024" class="card-img-top" alt="Jaktfeltcup 2024">
                                <div class="card-body">
                                    <h5 class="card-title">Jaktfeltcup 2024 er i gang!</h5>
                                    <p class="card-text small text-muted">
                                        <i class="fas fa-calendar me-2"></i>
                                        15. januar 2024
                                    </p>
                                    <p class="card-text">
                                        Første stevne av året er avholdt med stor suksess. Over 100 deltakere møtte opp 
                                        til det spennende stevnet i Oslo. Flere nye rekorder ble satt og konkurransen 
                                        var tett som aldri før.
                                    </p>
                                    <a href="#" class="btn btn-outline-primary">Les mer</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <img src="https://via.placeholder.com/400x250?text=Nye+Rekorder" class="card-img-top" alt="Nye rekorder">
                                <div class="card-body">
                                    <h5 class="card-title">Nye rekorder satt i Bergen</h5>
                                    <p class="card-text small text-muted">
                                        <i class="fas fa-calendar me-2"></i>
                                        10. januar 2024
                                    </p>
                                    <p class="card-text">
                                        Flere nye rekorder ble satt under stevnet i Bergen i helgen. 
                                        Konkurransen var tett og spennende.
                                    </p>
                                    <a href="#" class="btn btn-outline-primary btn-sm">Les mer</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <img src="https://via.placeholder.com/400x250?text=Nye+Arrangører" class="card-img-top" alt="Nye arrangører">
                                <div class="card-body">
                                    <h5 class="card-title">Arrangører melder seg på</h5>
                                    <p class="card-text small text-muted">
                                        <i class="fas fa-calendar me-2"></i>
                                        5. januar 2024
                                    </p>
                                    <p class="card-text">
                                        Flere nye arrangører har meldt seg på for 2024-sesongen. 
                                        Dette betyr flere stevner og mer konkurranse.
                                    </p>
                                    <a href="#" class="btn btn-outline-primary btn-sm">Les mer</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <img src="https://via.placeholder.com/400x250?text=Sponsorer" class="card-img-top" alt="Nye sponsorer">
                                <div class="card-body">
                                    <h5 class="card-title">Nye sponsorer melder seg på</h5>
                                    <p class="card-text small text-muted">
                                        <i class="fas fa-calendar me-2"></i>
                                        2. januar 2024
                                    </p>
                                    <p class="card-text">
                                        Flere nye sponsorer har meldt seg på for 2024-sesongen. 
                                        Dette gir oss mulighet til å utvide cupen.
                                    </p>
                                    <a href="#" class="btn btn-outline-primary btn-sm">Les mer</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <img src="https://via.placeholder.com/400x250?text=Premier" class="card-img-top" alt="Premier">
                                <div class="card-body">
                                    <h5 class="card-title">Premier for 2024-sesongen</h5>
                                    <p class="card-text small text-muted">
                                        <i class="fas fa-calendar me-2"></i>
                                        30. desember 2023
                                    </p>
                                    <p class="card-text">
                                        Se hvilke premier som venter deltakerne i 2024-sesongen. 
                                        Det blir spennende!
                                    </p>
                                    <a href="#" class="btn btn-outline-primary btn-sm">Les mer</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Newsletter Signup -->
                    <div class="card mt-4">
                        <div class="card-body text-center">
                            <h5 class="card-title">Hold deg oppdatert</h5>
                            <p class="card-text">
                                Meld deg på vårt nyhetsbrev for å få de siste nyhetene 
                                direkte i innboksen din.
                            </p>
                            <form class="row g-3 justify-content-center">
                                <div class="col-auto">
                                    <input type="email" class="form-control" placeholder="Din e-postadresse">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-envelope me-2"></i>Meld deg på
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="card mt-4">
                        <div class="card-body text-center">
                            <h5 class="card-title">Følg oss på sosiale medier</h5>
                            <p class="card-text">
                                Hold deg oppdatert på det som skjer i Jaktfeltcup.
                            </p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="#" class="btn btn-outline-primary">
                                    <i class="fab fa-facebook me-2"></i>Facebook
                                </a>
                                <a href="#" class="btn btn-outline-primary">
                                    <i class="fab fa-instagram me-2"></i>Instagram
                                </a>
                                <a href="#" class="btn btn-outline-primary">
                                    <i class="fab fa-twitter me-2"></i>Twitter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_footer(); ?>
