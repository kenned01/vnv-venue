<?php

namespace App\Repositories;

class VenueRepository extends BaseRepository
{
    const STATUSES = ["PENDING", "APPROVED", "REJECTED", "SUSPENDED"];

    public function __construct() {
        $this->table = "venues";
        $this->db = new Connection();
    }

    public function getByNameLike(string $name, $limit = 0): array
    {
        $limitQuery = $limit === 0 ? "" : "LIMIT {$limit}";

        $this->db->query("SELECT * FROM venues WHERE name LIKE :name AND status = 'APPROVED' $limitQuery");
        $this->db->bind(":name", "%$name%");
        $this->db->execute();
        return $this->db->fetchAll();
    }

    public function getAllByRoundDistance($lat, $lon, $distance, $limit = 0): array {
        $limitQuery = $limit === 0 ? "" : "LIMIT {$limit}";

        $this->db->query("SELECT
            *,
            (6371 * ACOS(
                COS(RADIANS(:lat)) * COS(RADIANS(lat)) *
                COS(RADIANS(lng) - RADIANS(:long)) +
                SIN(RADIANS(:lat)) * SIN(RADIANS(lat))
            )) AS distance
        FROM venues
        WHERE status = 'APPROVED'
        HAVING distance <= :distance
        $limitQuery
        ");

        $this->db->bind(":lat", $lat);
        $this->db->bind(":long", $lon);
        $this->db->bind(":lat", $lat);
        $this->db->bind(":distance", $distance);

        $this->db->execute();
        return $this->db->fetchAll();
    }
}