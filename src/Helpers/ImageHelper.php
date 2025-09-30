<?php
/**
 * Image Helper for Jaktfeltcup
 */

namespace Jaktfeltcup\Helpers;

class ImageHelper {
    
    /**
     * Get image URL with fallback
     */
    public static function getImageUrl($path, $fallback = null) {
        $fullPath = __DIR__ . '/../../' . $path;
        
        if (file_exists($fullPath)) {
            return base_url($path);
        }
        
        return $fallback;
    }
    
    /**
     * Get logo URL
     */
    public static function getLogoUrl() {
        // Try assets/images/logoer/ first (preferred)
        $logoUrl = self::getImageUrl('assets/images/logoer/jaktfeltcup_logo.png');
        if ($logoUrl) {
            return $logoUrl;
        }
        
        // Fallback to bilder/logoer/ (legacy)
        return self::getImageUrl('bilder/logoer/jaktfeltcup_logo.png');
    }
    
    
    /**
     * Get sponsor images from database
     */
    public static function getSponsorImages() {
        try {
            // Try to get sponsors from database first
            global $db_config;
            $database = new \Jaktfeltcup\Core\Database($db_config);
            
            $sponsors = $database->queryAll(
                "SELECT name, logo_url, logo_filename, sponsor_level FROM jaktfelt_sponsors 
                 WHERE is_active = 1 
                 ORDER BY sponsor_level DESC, name ASC"
            );
            
            if (!empty($sponsors)) {
                // Verify that logo files exist and fix URLs if needed
                foreach ($sponsors as &$sponsor) {
                    if (!empty($sponsor['logo_filename'])) {
                        $filePath = __DIR__ . '/../../assets/images/sponsors/' . $sponsor['logo_filename'];
                        if (file_exists($filePath)) {
                            // Use direct path that works
                            $sponsor['logo_url'] = base_url('assets/images/sponsors/' . $sponsor['logo_filename']);
                        } else {
                            // File doesn't exist, try to find it in new location
                            $newFilePath = __DIR__ . '/../../assets/images/sponsors/' . $sponsor['logo_filename'];
                            if (file_exists($newFilePath)) {
                                $sponsor['logo_url'] = base_url('assets/images/sponsors/' . $sponsor['logo_filename']);
                            } else {
                                // Try to find it in old location
                                $sponsorDir = __DIR__ . '/../../src/Bilder/sponsorer/';
                                if (is_dir($sponsorDir)) {
                                    $files = scandir($sponsorDir);
                                    foreach ($files as $file) {
                                        if (stripos($file, $sponsor['name']) !== false) {
                                            $sponsor['logo_url'] = base_url('src/Bilder/sponsorer/' . $file);
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                return $sponsors;
            }
        } catch (Exception $e) {
            error_log("Could not fetch sponsors from database: " . $e->getMessage());
        }
        
        // Fallback to file system scan - try new location first
        $sponsorDir = __DIR__ . '/../../assets/images/sponsors/';
        $images = [];
        
        if (is_dir($sponsorDir)) {
            $files = scandir($sponsorDir);
            foreach ($files as $file) {
                if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['png', 'jpg', 'jpeg', 'gif', 'svg'])) {
                    $images[] = [
                        'name' => pathinfo($file, PATHINFO_FILENAME),
                        'logo_url' => base_url('assets/images/sponsors/' . $file),
                        'sponsor_level' => 'bronze'
                    ];
                }
            }
        } else {
            // Try old location as fallback
            $sponsorDir = __DIR__ . '/../../src/Bilder/sponsorer/';
            if (is_dir($sponsorDir)) {
                $files = scandir($sponsorDir);
                foreach ($files as $file) {
                    if (in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['png', 'jpg', 'jpeg', 'gif', 'svg'])) {
                        $images[] = [
                            'name' => pathinfo($file, PATHINFO_FILENAME),
                            'logo_url' => base_url('src/Bilder/sponsorer/' . $file),
                            'sponsor_level' => 'bronze'
                        ];
                    }
                }
            }
        }
        
        return $images;
    }
    
    /**
     * Generate CSS for background image
     */
    public static function getBackgroundStyle($imageUrl, $opacity = 0.1) {
        if ($imageUrl) {
            return "background-image: url('$imageUrl'); background-size: cover; background-position: center; background-repeat: no-repeat; opacity: $opacity;";
        }
        return '';
    }
}
