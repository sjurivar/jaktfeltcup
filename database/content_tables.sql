-- Content Management Tables for Jaktfeltcup

-- News table
CREATE TABLE IF NOT EXISTS jaktfelt_news (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    excerpt TEXT NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(500) NULL,
    is_published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    INDEX idx_news_published (is_published),
    INDEX idx_news_created (created_at),
    INDEX idx_news_test_data (is_test_data)
);

-- Sponsors table
CREATE TABLE IF NOT EXISTS jaktfelt_sponsors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    sponsor_level ENUM('bronze', 'silver', 'gold') NOT NULL DEFAULT 'bronze',
    website_url VARCHAR(500) NULL,
    logo_filename VARCHAR(255) NULL,
    logo_url VARCHAR(500) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_test_data BOOLEAN DEFAULT FALSE,
    INDEX idx_sponsors_active (is_active),
    INDEX idx_sponsors_level (sponsor_level),
    INDEX idx_sponsors_order (display_order),
    INDEX idx_sponsors_test_data (is_test_data)
);

-- Sample data for testing
INSERT INTO jaktfelt_news (title, excerpt, content, is_test_data) VALUES
('Jaktfeltcup 2024 er i gang!', 'Første stevne av året er avholdt med stor suksess.', 'Vi er stolte over å kunne presentere resultatene fra vårt første stevne i 2024. Over 50 deltakere møtte opp og konkurrerte i ulike kategorier.', TRUE),
('Nye arrangører melder seg på', 'Flere nye arrangører har meldt seg på for 2024-sesongen.', 'Vi er begeistret over interessen fra nye arrangører som vil bidra til å utvide Jaktfeltcup til nye områder.', TRUE);

INSERT INTO jaktfelt_sponsors (name, description, sponsor_level, is_test_data) VALUES
('Jarnheimr', 'Stolt hovedsponsor av Jaktfeltcup', 'gold', TRUE),
('Test Sponsor', 'Eksempel på bronze sponsor', 'bronze', TRUE);
