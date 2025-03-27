<?php

require_once '../src/employee.php';

$searchText = trim($_GET['search'] ?? '');

$employee = new Employee();

if ($searchText === '') {
    $employees = $employee->getAllEmployees();
} else {
    $employees = $employee->searchEmployees($searchText);
}

if (!$employees) {
    $errorMessage = 'There was an error while retrieving the list of employees.';
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["id"])) {
    $employeeID = $_POST["id"] ?? "";
    $employee = $employee->deleteEmployee($employeeID);
    header("Location: employees.php");
    exit;
}

include_once '../views/header.php';
include_once '../views/navbar.php';
?>

<main style="padding-top: 4rem;";>
    <?php if (isset($errorMessage)): ?>
    <section>
        <p class="error"><?= $errorMessage ?></p>
    </section>
    <?php else: ?>
        <form action="employees.php" method="GET" style="display: flex; align-items: center; gap: 10px;">
    <div style="display: flex; align-items: center; gap: 5px;">
        <label for="txtSearch">Search:</label>
        <input type="search" id="txtSearch" name="search">
    </div>
    <div>
        <button type="submit">Search</button>
    </div>
    <nav style="margin-left: auto;">
        <ul style="list-style: none; margin: 0; padding: 0;">
            <li style="display: inline;">
                <button><a href="new.php" style="text-decoration: none";>Add employee</a></button>
            </li>
        </ul>
    </nav>
</form>
    <section>
        <?php foreach ($employees as $employee): ?>
            <article style="border: 2px solid black; border-radius: 1rem; padding: 1rem; margin-top: 1rem;">
            <p><strong>First name: </strong><?= $employee['cFirstName'] ?></p>
            <p><strong>Last name: </strong><?= $employee['cLastName'] ?></p>
            <p><strong>Birth date: </strong><?= $employee['dBirth'] ?></p>
            <br>
            <div style="display: flex; gap: 0.1rem;">
                <button><a href="view.php?id=<?= $employee['nEmployeeID'] ?>" style="text-decoration: none;">View details</a></button>
                <button><a href="edit.php?id=<?= $employee['nEmployeeID'] ?>" style="text-decoration: none;">Edit details</a></button>
                <form action="employees.php" method="POST">
                    <input type="hidden" name="id" value="<?= $employee['nEmployeeID'] ?>">
                    <button type="submit" style="background-color: red;">Delete</button>
                </form>
            </div>
        </article>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>
</main>
<?php include_once '../views/footer.php'; ?>
