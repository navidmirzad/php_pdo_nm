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
            ORDER BY cName
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
            SELECT cName
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
}
?>
