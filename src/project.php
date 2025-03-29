<?php

require_once 'database.php';
require_once 'logger.php';

class Project extends Database {

    function getAllProjects(): array|bool 
    {

        $pdo = $this->connect();

        $sql =<<<SQL
            SELECT nProjectID, cName
            FROM project
            ORDER BY nProjectID;
        SQL;

        try {
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::LogText('Error getting all projects: ', $e->getMessage());
            return false;
        }
        
    }

    function getProjectByID(int $projectID): array|bool
    {
        $pdo = $this->connect();

        $sql =<<<SQL
            SELECT nProjectID, cName
            FROM project
            WHERE nProjectID = :projectID;
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':projectID', $projectID);

            if ($stmt->rowCount() === 1) {
                return $stmt->fetch();
            }
            return false;
        } catch (PDOException $e) {
            Logger::logText('Error retrieving project by ID', $e->getMessage());
            return false;
        }
    }

    function searchProjects(string $searchText): array|false
    {

    }

    function validateProject(): array|bool
    {

    } 

    function createDepartment(): bool
    {

    }

    function updateDepartment(): bool 
    {

    }

    function deleteProject(int $projectID): bool 
    {

    }


}