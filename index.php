<?php 
include_once 'views/header.php'; 
include_once 'views/navbar.php'; 
include_once 'localizationSession.php';
?>

<form method="POST" style="position: absolute; top: 10px; right: 10px;">
    <label for="language"><?= $texts['select_language'] ?>:</label>
    <select name="language" onchange="this.form.submit()">
        <option value="en" <?= ($lang == 'en') ? 'selected' : '' ?>><?= $texts['english'] ?></option>
        <option value="dk" <?= ($lang == 'dk') ? 'selected' : '' ?>><?= $texts['danish'] ?></option>
    </select>
</form>


<main style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 50vh; text-align: center;">
    <section style="max-width: 600px; padding: 2rem; border-radius: 10px;">
        <h1><?= $texts['title'] ?></h1>
        <p><?= $texts['description1'] ?></p>
        <p><?= $texts['description2'] ?></p>
    </section>
</main>

<?php include_once 'views/footer.php'; ?>
