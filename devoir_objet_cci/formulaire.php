<?php
require_once 'class_formgenerator.php';

$form = new FormGenerator($_SERVER['PHP_SELF'], "POST");

$form->addField("name", "text", "nom", [
    'required' => true,
    'class' => 'input-text',
    'id' => 'name-field'
]);

$form->addField("email", "email", "email", [
    'required' => true,
    'class' => 'input-email',
    'id' => 'email-field'
]);

$form->addField("lettre", "textarea", "message", [
    'required' => true,
    'class' => 'textarea-message',
    'id' => 'message-field'
]);

$form->addField("subject", "select", "sujet", [
    'required' => true,
    'options' => ["Question", "Problème technique", "Autre"],
    'class' => 'select-subject',
    'id' => 'subject-field'
]);

$form->addField("file_upload", "file", "télécharger un fichier", [
    'required' => true,
    'class' => 'input-file',
    'id' => 'file-upload'
]);

if ($_SERVER["REQUEST_METHOD"] === "POST" || $_SERVER["REQUEST_METHOD"] === "GET") {
    if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['lettre']) && !empty($_POST['file_upload']))
    {
        if ($form->handleSubmission()) {
            echo "<p style='color: green;'>Le formulaire a été soumis avec succès !</p>";
        } else {
            echo "<p style='color: red;'>Veuillez corriger les erreurs ci-dessous.</p>";
        }
    }elseif(!empty($_GET['name']) && !empty($_GET['email']) && !empty($_GET['lettre']))
    {
        if ($form->handleSubmission()) {
            echo "<p style='color: green;'>Le formulaire a été soumis avec succès !</p>";
        } else {
            echo "<p style='color: red;'>Veuillez corriger les erreurs ci-dessous.</p>";
        }
    }
}

$form->render();


?>