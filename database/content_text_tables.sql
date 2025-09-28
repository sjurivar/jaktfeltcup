-- Content Text Management Tables
-- Allows content managers to edit text content on pages

-- Page content table for storing editable text content
CREATE TABLE IF NOT EXISTS jaktfelt_page_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    page_key VARCHAR(100) NOT NULL,
    section_key VARCHAR(100) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    language VARCHAR(10) DEFAULT 'no',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by INT,
    is_test_data BOOLEAN DEFAULT FALSE,
    UNIQUE KEY unique_page_section (page_key, section_key),
    INDEX idx_page_section (page_key, section_key),
    INDEX idx_language (language),
    INDEX idx_active (is_active),
    INDEX idx_test_data (is_test_data),
    FOREIGN KEY (updated_by) REFERENCES jaktfelt_users(id) ON DELETE SET NULL
);

-- Insert default content for existing pages
INSERT INTO jaktfelt_page_content (page_key, section_key, title, content, is_test_data) VALUES
-- Arrangør section
('arrangor', 'hero_title', 'Arrangør', 'Bli arrangør og bidra til utvikling av skyteidretten', 1),
('arrangor', 'hero_subtitle', 'Din rolle', 'Som arrangør har du ansvar for å organisere og gjennomføre stevner', 1),
('arrangor', 'benefits_title', 'Fordeler', 'Få tilgang til verktøy og støtte for å arrangere stevner', 1),
('arrangor', 'benefits_content', 'Hva du får', 'Verktøy for registrering, resultathåndtering og kommunikasjon med deltakere', 1),

-- Sponsor section  
('sponsor', 'hero_title', 'Sponsor', 'Støtt Jaktfeltcup og få synlighet for ditt selskap', 1),
('sponsor', 'hero_subtitle', 'Din rolle', 'Som sponsor bidrar du til utvikling av skyteidretten', 1),
('sponsor', 'packages_title', 'Sponsorpakker', 'Velg den pakken som passer best for ditt selskap', 1),
('sponsor', 'packages_content', 'Hva du får', 'Synlighet på nettsiden, i materiale og under arrangementer', 1),

-- Deltaker section
('deltaker', 'hero_title', 'Deltaker', 'Meld deg på stevner og konkurrer i Jaktfeltcup', 1),
('deltaker', 'hero_subtitle', 'Din rolle', 'Som deltaker kan du delta i stevner og følge ditt resultat', 1),
('deltaker', 'registration_title', 'Påmelding', 'Meld deg på stevner og følg ditt resultat', 1),
('deltaker', 'registration_content', 'Hvordan', 'Registrer deg som bruker og meld deg på stevner', 1),

-- Publikum section
('publikum', 'hero_title', 'Publikum', 'Følg Jaktfeltcup og se resultater fra stevner', 1),
('publikum', 'hero_subtitle', 'Din rolle', 'Som publikum kan du følge stevner og se resultater', 1),
('publikum', 'calendar_title', 'Kalender', 'Se kommende stevner og arrangementer', 1),
('publikum', 'calendar_content', 'Hva du finner', 'Oversikt over alle stevner, resultater og nyheter', 1),

-- General content
('general', 'site_title', 'Jaktfeltcup', 'Norges ledende skytekonkurranse', 1),
('general', 'site_subtitle', 'Undertekst', 'For arrangører, sponsorer, deltakere og publikum', 1),
('general', 'footer_text', 'Footer', '© 2024 Jaktfeltcup. Alle rettigheter forbeholdt.', 1);
