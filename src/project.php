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
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                return $stmt->fetch();
            }
            return false;
        } catch (PDOException $e) {
            Logger::logText('Error retrieving project by ID', $e->getMessage());
            return false;
        }
    }

    function getEmployeesByProjectID(int $projectID): array|false
    {
        $pdo = $this->connect();

        $sql = <<<SQL
            SELECT employee.cLastName, employee.cFirstName, department.cName
            FROM emp_proy
            JOIN employee ON emp_proy.nEmployeeID = employee.nEmployeeID
            JOIN department ON employee.nDepartmentID = department.nDepartmentID
            WHERE emp_proy.nProjectID = :projectID
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':projectID', $projectID);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::logText('Error retrieving employees by ProjectID: ', $e->getMessage());
            return false;
        }
    }

    function searchProjects(string $searchText): array|false
    {
        $pdo = $this->connect();

        $sql = <<<SQL
            SELECT nProjectID, cName
            FROM project
            WHERE nProjectID like :projectID
                OR cName LIKE :projectName
            ORDER BY nProjectID, cName;
        SQL;

        try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':projectID', "%$searchText%");
        $stmt->bindValue(':projectName', "%$searchText%");
        $stmt->execute();

        return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::logText('Error searching for projects.', $e->getMessage());
            return false;
        }
    }

    function validateProject(array $project): array|bool
    {
        $projectName = trim($project['cName'] ?? '');

        $validationsErrors = [];

        if (empty($projectName)) {
            $validationsErrors = 'Project name is mandatory!';
        }
        
        return $validationsErrors;
    } 

    function createProject(array $project): array|bool
    {
        $pdo = $this->connect();
        
        $sql =<<<SQL
            INSERT INTO project
                (cName)
            VALUES
                (:cName)
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':cName', $project['cName']);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            Logger::logText('Error inserting new project: ', $e->getMessage());
            return false;
        }
    }

    function updateProject(array $project): bool 
    {
        $pdo = $this->connect();

        $sql =<<<SQL
            UPDATE project
            set cName = :cName
            WHERE nProjectID = :nProjectID
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':cName', $project['cName']);
            $stmt->bindValue(':nProjectID', $project['projectID']);

            return $stmt->execute();
        } catch (PDOException $e) {
            Logger::logText('Error updating project: ', $e->getMessage());
            return false;
        }
    }

    function deleteProject(int $projectID): bool 
    {
        $pdo = $this->connect();

        $sql =<<<SQL
            DELETE FROM project WHERE nProjectID = :projectID
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':projectID', $projectID);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            Logger::logText('Error deleting project: ', $e->getMessage());
            return false;
        }
    }



}