<?php
// Set page variables
$page_title = 'Bli Arrangør - Jaktfeltcup';
$page_description = 'Meld din interesse for å bli arrangør for Jaktfeltcup.';
$current_page = 'arrangor_bli';

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
                    <h3 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Bli Arrangør for Jaktfeltcup</h3>
                </div>
                <div class="card-body">
                    <p class="lead">Fyll ut skjemaet under for å melde din interesse for å bli arrangør.</p>
                    
                    <form method="POST" action="<?= base_url('arrangor/kontakt') ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">Fornavn *</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Etternavn *</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">E-postadresse *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Telefonnummer</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="organization" class="form-label">Organisasjon/Klubb</label>
                            <input type="text" class="form-control" id="organization" name="organization">
                        </div>
                        
                        <div class="mb-3">
                            <label for="location" class="form-label">Sted/Område *</label>
                            <input type="text" class="form-control" id="location" name="location" required 
                                   placeholder="F.eks. Oslo, Bergen, Trondheim">
                        </div>
                        
                        <div class="mb-3">
                            <label for="experience" class="form-label">Erfaring med skyteidrett</label>
                            <select class="form-select" id="experience" name="experience">
                                <option value="">Velg erfaring</option>
                                <option value="nybegynner">Nybegynner</option>
                                <option value="noe-erfaring">Noe erfaring</option>
                                <option value="erfaren">Erfaren</option>
                                <option value="meget-erfaren">Meget erfaren</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="stevneType" class="form-label">Type stevne du ønsker å arrangere</label>
                            <select class="form-select" id="stevneType" name="stevneType">
                                <option value="">Velg type</option>
                                <option value="jaktfelt">Jaktfelt</option>
                                <option value="trap">Trap</option>
                                <option value="skeet">Skeet</option>
                                <option value="olympisk">Olympisk</option>
                                <option value="annet">Annet</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Melding *</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required 
                                      placeholder="Fortell oss om din interesse, erfaring og hva du kan bidra med..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" value="1">
                                <label class="form-check-label" for="newsletter">
                                    Jeg ønsker å motta nyheter og oppdateringer fra Jaktfeltcup
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="privacy" name="privacy" value="1" required>
                                <label class="form-check-label" for="privacy">
                                    Jeg godtar <a href="<?= base_url('about') ?>" target="_blank">personvernreglene</a> *
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Send interesse
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_footer(); ?>
