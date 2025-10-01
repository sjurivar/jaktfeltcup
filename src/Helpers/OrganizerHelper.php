<?php
/**
 * Organizer Helper for Jaktfeltcup
 */

namespace Jaktfeltcup\Helpers;

use Jaktfeltcup\Core\Database;

class OrganizerHelper {
    
    /**
     * Get all active organizers with their upcoming events
     */
    public static function getOrganizersWithEvents() {
        try {
            global $db_config;
            $database = new Database($db_config);
            
            // Get current active season
            $season = $database->queryOne(
                "SELECT id FROM jaktfelt_seasons WHERE is_active = 1 ORDER BY year DESC LIMIT 1"
            );
            
            if (!$season) {
                return [];
            }
            
            // Get all rounds with their organizers and events
            $sql = "SELECT 
                        r.id as round_id,
                        r.round_number,
                        r.name as round_name,
                        r.start_date as round_start,
                        r.end_date as round_end,
                        r.result_deadline,
                        o.id as organizer_id,
                        o.name as organizer_name,
                        o.organization_type,
                        o.contact_person,
                        o.email,
                        o.phone,
                        o.city,
                        c.id as competition_id,
                        c.name as competition_name,
                        c.competition_date,
                        c.location
                    FROM jaktfelt_rounds r
                    LEFT JOIN jaktfelt_competitions c ON c.round_id = r.id AND c.is_published = 1
                    LEFT JOIN jaktfelt_organizers o ON c.organizer_id = o.id
                    WHERE r.season_id = ? AND r.is_active = 1
                    ORDER BY r.round_number, COALESCE(c.competition_date, r.start_date), o.name";
            
            $results = $database->queryAll($sql, [$season['id']]);
            
            // Group by rounds
            $rounds = [];
            foreach ($results as $row) {
                $round_id = $row['round_id'];
                
                if (!isset($rounds[$round_id])) {
                    $rounds[$round_id] = [
                        'round_number' => $row['round_number'],
                        'round_name' => $row['round_name'],
                        'start_date' => $row['round_start'],
                        'end_date' => $row['round_end'],
                        'result_deadline' => $row['result_deadline'],
                        'events' => []
                    ];
                }
                
                // Add event if exists
                if ($row['competition_id']) {
                    $rounds[$round_id]['events'][] = [
                        'competition_id' => $row['competition_id'],
                        'competition_name' => $row['competition_name'],
                        'competition_date' => $row['competition_date'],
                        'location' => $row['location'],
                        'organizer_name' => $row['organizer_name'],
                        'organizer_type' => $row['organization_type'],
                        'contact_person' => $row['contact_person'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'city' => $row['city']
                    ];
                }
            }
            
            return array_values($rounds);
            
        } catch (\Exception $e) {
            error_log("Could not fetch organizers: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get all active organizers
     */
    public static function getAllOrganizers() {
        try {
            global $db_config;
            $database = new Database($db_config);
            
            $sql = "SELECT 
                        id,
                        name,
                        organization_type,
                        contact_person,
                        email,
                        phone,
                        city,
                        description,
                        logo_url
                    FROM jaktfelt_organizers
                    WHERE is_active = 1
                    ORDER BY name ASC";
            
            return $database->queryAll($sql);
            
        } catch (\Exception $e) {
            error_log("Could not fetch organizers: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Format organization type for display
     */
    public static function formatOrganizationType($type) {
        $types = [
            'skytterlag' => 'Skytterlag',
            'njff_lokallag' => 'NJFF Lokallag',
            'dfs_lokallag' => 'DFS Lokallag',
            'annet' => 'Annet'
        ];
        
        return $types[$type] ?? $type;
    }
    
    /**
     * Format date for display
     */
    public static function formatDate($date) {
        if (empty($date)) return '';
        
        $timestamp = strtotime($date);
        return date('d.m.Y', $timestamp);
    }
    
    /**
     * Format date range for display
     */
    public static function formatDateRange($start_date, $end_date) {
        if (empty($start_date) || empty($end_date)) return '';
        
        return self::formatDate($start_date) . ' - ' . self::formatDate($end_date);
    }
}

