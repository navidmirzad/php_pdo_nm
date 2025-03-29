<?php

require_once '../src/project.php';

$searchText = trim($_GET['search'] ?? '');

$projectObj = new Project();

if (!$searchText) {
    $projects = $projectObj->getAllProjects();
} else {
    $projects = $projectObj->searchProjects($searchText);
}

if (!$projects) {
    $errorMessage = 'There was an error retrieving the list of projects.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    $projectID = $_POST['id'];
    $deleteProject = $projectObj->deleteProject($projectID);
    header('Location: projects.php');
    exit;
}

include_once '../views/header.php';
include_once '../views/navbar.php';

?>

<main style="padding-top: 4rem;">
    <?php if (isset($errorMessage)): ?>
        <section>
            <p class="error"><?= $errorMessage ?></p>
        </section>
    <?php else: ?>
        <form action="projects.php" method="GET" style="display: flex; align-items: center; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 5px;">
                <label for="txtSearch">Search:</label>
                <input type="search" id="txtSearch" name="search">
            </div>
            <div>
                <button type="submit">Search</button>
            </div>
            <nav style="margin-left: auto;">
                <ul>
                    <li style="display: inline;">
                        <button><a href="new.php" style="text-decoration: none;">Add Project</a></button>
                    </li>
                </ul>
            </nav>
        </form>
        <section>
            <?php foreach ($projects as $project): ?>
            <article style="border: 2px solid black; border-radius: 1rem; padding: 1rem; margin-top: 1rem;">
                <p><strong>Project ID: </strong><?= $project['nProjectID']; ?></p>
                <p><strong>Project name:  </strong><?= $project['cName']; ?></p>
                <br>
                <div style="display: flex; gap: 0.1rem;">
                    <button><a href="view.php?id<?= $project['nProjectID'] ?>" style="text-decoration: none;">View details</a></button>    
                    <button><a href="edit.php?id<?= $project['nProjectID'] ?>" style="text-decoration: none;">Edit details</a></button>    
                    <form action="projects.php" method="POST">
                        <input type="hidden" id="id" value="<?= $project['nProjectID'] ?>">
                        <button type="submit" style="background-color: red;">Delete</button>
                    </form>
                </div>
            </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>

<?php include_once '../views/footer.php'; ?>
