<?php

namespace App\Repositories;

use PDOException;

class BaseRepository
{
    /**
     * @var null|Connection
     */
    protected ?Connection $db = null;

    /**
     * @var string
     */
    protected string $table = "";

    /**
     * @var bool
     */
    protected bool $showError = true;

    /**
     * @param array $data
     * @return bool
     */
    public function add(Array $data): bool
    {

        try {
            $keys = array_keys( $data );
            $insert = "(";
            $values = "(";

            for ($i=0; $i< count( $keys ) ; $i++ ){
                $insert .= " `$keys[$i]`";
                $values .= " :$keys[$i]";
                if($i != count( $keys ) -1 ){
                    $insert .= ", ";
                    $values .= ", ";
                }
            }

            $insert .= ")";
            $values .= ")";

            $query = "INSERT INTO `$this->table` $insert VALUES $values";

            //now we have our query we have to bind the data to be inserted
            $this->db->query( $query );
            for($i = 0; $i < count ( $keys ) ; $i++ ){
                $this->db->bind(":$keys[$i]", $data[ $keys[$i] ] );
            }
            $this->db->execute();

            return true;
        } catch (PDOException $th) {
            if($this->showError){
                echo $th->getMessage();
            }

            return false;
        }
    }

    /**
     * @param array $keys
     * @return bool
     */
    public function delete(Array $keys): bool
    {
        try {

            $keys2 = array_keys($keys);
            $this->db->query("DELETE FROM `$this->table` WHERE `$keys2[0]` = :data ;");
            $this->db->bind(":data",  $keys[ $keys2[0] ] );

            $this->db->execute();
            return true;
        } catch (PDOException $th) {
            if($this->showError){
                echo $th->getMessage();
            }
            return false;
        }
    }

    /**
     * @param array $data
     * @param array $criteriaVals
     * @return bool
     */
    public function update(array $data, array $criteriaVals): bool
    {

        try {
            $keys = array_keys( $data );
            $keysCriteria = array_keys($criteriaVals);
            $update = "";
            $criteria = "";

            for ($i = 0; $i < count( $keys ) ; $i++ ){
                $update .= " `$keys[$i]` = :$keys[$i] ";

                if($i != count( $keys ) -1 ){
                    $update .= ", ";
                }
            }

            for ($i = 0; $i< count( $keysCriteria ) ; $i++ ){
                $criteria .= " `$keysCriteria[$i]` = :$keysCriteria[$i] ";

                if($i != count( $keysCriteria ) -1 ){
                    $update .= " AND ";
                }
            }

            $query = "UPDATE `$this->table` SET $update WHERE $criteria;";

            //now we have our query we gotta bind the data to be inserted
            $this->db->query( $query );
            for($i = 0; $i < count ( $keys ) ; $i++ ){
                $this->db->bind(":$keys[$i]", $data[ $keys[$i] ] );
            }

            for($i = 0; $i < count ( $keysCriteria ) ; $i++ ){
                $this->db->bind(":$keysCriteria[$i]", $criteriaVals[ $keysCriteria[$i] ] );
            }

            $this->db->execute();

            return true;
        } catch (PDOException $th) {
            if($this->showError){
                echo $th->getMessage();
            }
            return false;
        }
    }

    /**
     * @param array $criteriaVal
     * @param array $columns
     * @return object|null
     */
    public function getOne(Array $criteriaVal, Array $columns = []): ?object
    {
        try {

            $keys2 = array_keys($criteriaVal);
            $columnsSQl = $this->columnsQuery($columns);

            $this->db->query("SELECT $columnsSQl FROM `$this->table` WHERE `$keys2[0]` = :data ;");
            $this->db->bind(":data", $criteriaVal[ $keys2[0] ]);

            $data = $this->db->fetchOne();
            return $data == false ? null : $data;
        } catch (PDOException $th) {

            if($this->showError){
                echo $th->getMessage();
            }

            return null;
        }
    }

    /**
     * @param array $columns
     * @param int $limit
     * @return array
     */
    public function getAll(Array $columns = [], int $limit = 0): array
    {
        try {


            $columnsSQl = $this->columnsQuery($columns);
            $limitQuery = $limit > 0 ? "LIMIT $limit" : "";

            $this->db->query("SELECT $columnsSQl FROM `$this->table` $limitQuery;");
            return $this->db->fetchAll();
        } catch (PDOException $th) {

            if($this->showError){
                echo $th->getMessage();
            }

            return array();
        }
    }

    public function getAllBy(array $criteriaVals, array $columns = [], int $limit = 0): array
    {
        try {
            $columnsSQl = $this->columnsQuery($columns);
            $keysCriteria = array_keys($criteriaVals);
            $limitQuery = $limit > 0 ? "LIMIT $limit" : "";
            $criteria = "";

            for ($i = 0; $i< count( $keysCriteria ) ; $i++ ){
                $criteria .= " `$keysCriteria[$i]` = :$keysCriteria[$i] ";

                if($i != count( $keysCriteria ) -1 ){
                    $criteria .= " AND ";
                }
            }

            $query = "SELECT $columnsSQl FROM `$this->table` WHERE $criteria $limitQuery;";


            //now we have our query we have to bind the data to be inserted
            $this->db->query( $query );
            for($i = 0; $i < count ( $keysCriteria ) ; $i++ ){
                $this->db->bind(":$keysCriteria[$i]", $criteriaVals[ $keysCriteria[$i] ] );
            }

            return $this->db->fetchAll();
        } catch (PDOException $th) {
            if($this->showError){
                echo $th->getMessage();
            }
            return [];
        }
    }

    /**
     * @param array $columns
     * @return string
     */
    private function columnsQuery(Array $columns = [] ): string
    {

        $columnsSQl = "";

        // construct columns query
        // so that it can be fetched
        if( count($columns) > 0 ){
            for($i = 0; $i < count($columns); $i++){
                $columnsSQl .= $columns[$i];

                if( $i != (count($columns) - 1) ){
                    $columnsSQl .= ",";
                }
            }


        }else{
            $columnsSQl =  "*";
        }

        return $columnsSQl;
    }

    /**
     * @param array $data
     * @param array $values
     * @return array
     */
    public function sanitize(array $data = [], array $values = []): array
    {
        $dataSanitize = [];
        foreach($values as $key){
            $dataSanitize[$key] = $data[$key];
        }

        return $dataSanitize;
    }

    /**
     * @return int
     */
    public function getLastId(): int
    {
        try {
            return $this->db->lastId();
        } catch (PDOException $th) {

            if($this->showError){
                echo $th->getMessage();
            }

            return 0;
        }
    }

}