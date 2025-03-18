<?php 
session_start();
//var_dump($_SESSION);

if (isset($_POST["language"])) {
    $_SESSION['lang'] = $_POST['language'];
}

$lang = $_SESSION["lang"] ?? "en";

$translations = [
    'en' => [
        'title' => 'Welcome to Business - the company',
        'description1' => 'Your go-to place for managing employees, departments, and projects efficiently.',
        'description2' => 'Navigate through the menu to explore our system.',
        'select_language' => 'Select Language',
        'english' => 'English',
        'danish' => 'Dansk'
    ],
    'dk' => [
        'title' => 'Velkommen til Business - virksomheden',
        'description1' => 'Dit foretrukne sted til effektiv styring af medarbejdere, afdelinger og projekter.',
        'description2' => 'Naviger gennem menuen for at udforske vores system.',
        'select_language' => 'Vælg sprog',
        'english' => 'Engelsk',
        'danish' => 'Dansk'
    ]
];

$texts = $translations[$lang];