<?php

require_once 'database.php';
require_once 'logger.php';

/**
 * It retrieves all departments from the database
 * @param $pdo A PDO database connection
 * @return An associative array with employee information,
 *         or false if there was an error
 */
function getAllDepartments(PDO $pdo): array|false
{
    $sql =<<<SQL
        SELECT nDepartmentID, cName
        FROM department
        ORDER BY cName
    SQL;

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    } catch (PDOException $e) {
        logText('Error getting all employees: ', $e);
        return false;
    }
}

?>