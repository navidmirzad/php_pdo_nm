<?php

require_once 'database.php';
require_once 'logger.php';

class Department extends Database
{
    /**
     * It retrieves all departments from the database
     * @return<array> An associative array with employee information,
     *         or false if there was an error
     */
    function getAllDepartments(): array|false
    {

        $pdo = $this->connect();

        $sql = <<<SQL
            SELECT nDepartmentID, cName
            FROM department
            ORDER BY nDepartmentID
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::logText('Error getting all employees: ', $e);
            return false;
        }
    }

    /**
     * It retrieves information regarding one department
     * @param int $departmentID
     * @return true or false depending on succes or error
     */
    function getDepartmentByID(int $departmentID): array|false
    {

        $pdo = $this->connect();
        
        $sql = <<<SQL
            SELECT cName, nDepartmentID
            FROM department
            WHERE nDepartmentID = :departmentID;
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':departmentID', $departmentID);
            $stmt->execute();

            if ($stmt->rowCount() === 1) {
                return $stmt->fetch();
            }
            return false;
        } catch (PDOException $e) {
            Logger::logText('Error getting departmentByID: ', $e->getMessage());
            return false;
        }
    }

    function getEmployeesByDepartmentID(int $departmentID): array|false
    {
        $pdo = $this->connect();

        $sql = <<<SQL
            SELECT cFirstName, cLastName
            FROM employee
            WHERE nDepartmentID like :departmentID
            ORDER BY cLastName, cFirstName;
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':departmentID', $departmentID, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::logText("Error retrieving employees by departmentID", $e->getMessage());
            return false;
        }
    } 

    /**
     * It retrieves departments from the database based
     * on a text search on the departmentID or departmentName
     * @param $searchText The text to search in the database
     * @return An associative array with department information,
     *         or false if there was an error
     */
    function searchDepartments(string $searchText): array|false
    {

        $pdo = $this->connect();

        $sql = <<<SQL
            SELECT nDepartmentID, cName
            FROM department
            WHERE nDepartmentID LIKE :departmentID
               OR cName LIKE :departmentName
            ORDER BY nDepartmentID, cName;
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':departmentID', "%$searchText%");
            $stmt->bindValue(':departmentName', "%$searchText%");
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::logText('Error searching for departments: ', $e);
            return false;
        }
    }

    function validateDepartment($department): array|bool 
    {
        $departmentName = trim($department['cName'] ?? '');

        $validationsErrors = [];

        if ($departmentName === '') {
            $validationsErrors[] = 'Department Name is mandatory';
        }

        return $validationsErrors;
    }

    function createDepartment(array $department): bool
    {
        $pdo = $this->connect();

        $sql = <<<SQL
            INSERT INTO department
                (cName)
            VALUES
                (:cName)
            SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':cName', $department['cName']);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            Logger::logText('Error inserting a new department: ', $e);
            return false;
        }
    }

    function updateDepartment(array $department): bool 
    {
        $pdo = $this->connect();

        $sql = <<<SQL
            UPDATE department
            SET cName = :cName
            WHERE nDepartmentID = :nDepartmentID
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nDepartmentID', $department['nDepartmentID']);
            $stmt->bindValue(':cName', $department['cName']);

            return $stmt->execute();
        } catch (PDOException $e) {
            Logger::logText('Error updating department: ', $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes an department record from the database.
     * @param array $department An array where we get department on index [0] with an 'id' property.
     * @return bool True on success, false on failure.
     */
    function deleteDepartment(int $departmentID): bool
    {
        $pdo = $this->connect();

        $sql = <<<SQL
            DELETE FROM department WHERE nDepartmentID = :departmentId
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':departmentId', $departmentID, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            Logger::logText('Error deleting department: ', $e->getMessage());
            return false;
        }
    }

}

