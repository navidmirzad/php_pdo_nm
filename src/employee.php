<?php

require_once 'database.php';
require_once 'logger.php';

class Employee extends Database
{
    /**
     * It retrieves all employees from the database
     * @return An associative array with employee information,
     *         or false if there was an error
     */
    function getAllEmployees(): array|false
    {
        $pdo = $this->connect();

        $sql = <<<SQL
            SELECT nEmployeeID, cFirstName, cLastName, dBirth
            FROM employee
            ORDER BY cFirstName, cLastName;
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
     * It retrieves employees from the database based
     * on a text search on the first and last name
     * @param $searchText The text to search in the database
     * @return An associative array with employee information,
     *         or false if there was an error
     */
    function searchEmployees(string $searchText): array|false
    {

        $pdo = $this->connect();

        $sql = <<<SQL
            SELECT nEmployeeID, cFirstName, cLastName, dBirth
            FROM employee
            WHERE cFirstName LIKE :firstName
               OR cLastName LIKE :lastName
            ORDER BY cFirstName, cLastName;
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':firstName', "%$searchText%");
            $stmt->bindValue(':lastName', "%$searchText%");
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::logText('Error searching for employees: ', $e);
            return false;
        }
    }

    function getProjectsByEmployeeID(int $employeeID): array|false
    {
        $pdo = $this->connect();

        $sql =<<<SQL
            SELECT project.nProjectID, project.cName
            FROM project
            INNER JOIN emp_proy ON project.nProjectID = emp_proy.nProjectID
            WHERE emp_proy.nEmployeeID = :employeeID;
        SQL;


        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':employeeID', $employeeID,PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::LogText('Error retrieving employee projects.');
            return false;
        }
    }

    /**
     * It retrieves information of an employee
     * @param $employeeID The ID of the employee
     * @return An associative array with employee information,
     *         or false if there was an error
     */
    function getEmployeeByID(int $employeeID): array|false
    {

        $pdo = $this->connect();

        $sql = <<<SQL
            SELECT
                employee.nEmployeeID AS employee_id,
                employee.cFirstName AS first_name,
                employee.cLastName AS last_name,
                employee.cEmail AS email,
                employee.dBirth AS birth_date,
                employee.nDepartmentID AS department_id,
                department.cName AS department_name
            FROM employee INNER JOIN department
                ON employee.nDepartmentID = department.nDepartmentID
            WHERE nEmployeeID = :employeeID;
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':employeeID', $employeeID);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            Logger::logText('Error retrieving employee information: ', $e);
            return false;
        }
    }

    /**
     * Validates employee data before adding it to the database
     * @param mixed $employee in an associative array
     * @return<array> An array with all validation error messages
     */
    function validateEmployee($employee): array|bool
    {

        $firstName = trim($employee['first_name'] ?? '');
        $lastName = trim($employee['last_name'] ?? '');
        $email = trim($employee['email'] ?? '');
        $birthDate = trim($employee['birth_date'] ?? '');
        $departmentID = (int) ($employee['department'] ?? 0);

        $validationsErrors = [];

        if ($firstName === '') {
            $validationsErrors[] = 'First name is mandatory';
        }

        if ($lastName === '') {
            $validationsErrors[] = 'Last name is mandatory';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $validationsErrors[] = 'Invalid email format.';
        }

        if ($birthDate === '') {
            $validationsErrors[] = 'Birth date is mandatory.';
        } elseif (!DateTime::createFromFormat('Y-m-d', $birthDate)) {
            $validationsErrors[] = 'Invalid birth date format.';
        } elseif (DateTime::createFromFormat('Y-m-d', $birthDate) > new DateTime('-16 years')) {
            $validationsErrors[] = 'The employee must be atleast 16 years old.';
        }

        if ($departmentID === 0) {
            $validationsErrors[] = 'Department is mandatory.';
        } else {
            $department = new Department();

            if (!$department->getDepartmentByID($departmentID)) {
                $validationsErrors[] = "The department doesn't exist.";
            }
        } 
        return $validationsErrors;
    }

    /**
     * It inserts a new employee in the database
     * @param $employee An associative array with employee data
     * @return true if the insert was succesful,
     *         or false if there was an error
     */
    function createEmployee(array $employee): bool
    {

        $pdo = $this->connect();

        $sql = <<<SQL
            INSERT INTO employee
                (cFirstName, cLastName, cEmail, dBirth, nDepartmentID)
            VALUES
                (:firstName, :lastName, :email, :birth, :departmentID)
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':firstName', $employee['first_name']);
            $stmt->bindValue(':lastName', $employee['last_name']);
            $stmt->bindValue(':email', $employee['email']);
            $stmt->bindValue(':birth', $employee['birth_date']);
            $stmt->bindValue(':departmentID', $employee['department']);
            $stmt->execute();

            return $stmt->rowCount() === 1;
        } catch (PDOException $e) {
            Logger::logText('Error inserting a new employee: ', $e);
            return false;
        }
    }

    /**
     * It updates an employee in the database
     * @param $employee An associative array with employee data
     * @return true if the insert was succesful,
     *         or false if there was an error
     */
    function updateEmployee(array $employee): bool
    {

        $pdo = $this->connect();

        $sql = <<<SQL
            UPDATE employee
            SET
                cFirstName = :firstName,
                cLastName = :lastName,
                cEmail = :email,
                dBirth = :birth,
                nDepartmentID = :department
            WHERE nEmployeeID = :id
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':firstName', $employee['first_name']);
            $stmt->bindValue(':lastName', $employee['last_name']);
            $stmt->bindValue(':email', $employee['email']);
            $stmt->bindValue(':birth', $employee['birth_date']);
            $stmt->bindValue(':department', $employee['department']);
            $stmt->bindValue(':id', $employee['id']);

            return $stmt->execute();
        } catch (PDOException $e) {
            Logger::logText('Error updating employee: ', $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes an employee record from the database.
     * @param array $employee An array where we get employee on index [0] with an 'id' property.
     * @return bool True on success, false on failure.
     */
    function deleteEmployee(int $employeeID): bool
    {

        $pdo = $this->connect();

        $sql = <<<SQL
            DELETE FROM employee WHERE nEmployeeID = :id
        SQL;

        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $employeeID, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            Logger::LogText('Error deleting employee: ', $e->getMessage());
            return false;
        }
    }
}
