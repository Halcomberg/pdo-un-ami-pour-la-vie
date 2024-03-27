<?php

require __DIR__ . '/_connec.php';

$pdo = new PDO(DSN, USER, PASS);

$errors = [];

if(!empty($_POST)) {
    $data = array_map('trim', $_POST);
    $data = array_map('htmlentities', $data);

    
    if (empty($data['firstname'])) {
        $errors[] = 'Veuillez entrer un prénom.';
    } 
    if (strlen($data['firstname']) >= 45) {
        $errors[] = 'Le prénom doit faire moins de 45 caractères.';
    }
    if (empty($data['lastname'])) {
        $errors[] = 'Veuillez entrer un nom.';
    } 
    if(strlen($data['lastname']) >= 45) {
        $errors[] = 'Le nom doit faire moins de 45 caractères.';
    }
    if (empty($errors)) {
        $query = 'INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname);';
        $statement = $pdo->prepare($query);
        $statement->bindValue(':firstname', $data['firstname'], PDO::PARAM_STR);
        $statement->bindValue(':lastname', $data['lastname'], PDO::PARAM_STR);
        $statement->execute();
        header('Location: index.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Challenge PDO, un ami pour la vie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.19.2/dist/css/uikit.min.css" />

</head>
<body>
    <main class="uk-container">
        <div>
            <h2>Liste des personnages présents :</h2>
            <p><ul>
                <?php
                $query = 'SELECT * FROM friend;';
                $statement = $pdo->query($query);
                $friends = $statement->fetchALL(PDO::FETCH_ASSOC);
                
                if (!empty($friends)) {
                    foreach($friends as $friend) {
                        echo '<li>' . $friend['firstname'] . ' ' . $friend['lastname'] . '</li>';
                    }
                } ?></ul>
            </p>
        </div>
        <form method="post" class="uk-grid-small" uk-grid>
            <fieldset class="uk-fieldset">
                <legend class="uk-legend">Ajouter un personnage</legend>
 
                <div class="uk-margin uk-width-1-3@s">
                    <label class="uk-form-label" for="firstname">Prénom : </label>
                    <input class="uk-input" type="text" id="firstname" name="firstname" >
                </div>

                <div class="uk-margin uk-width-1-3@s">
                    <label class="uk-form-label" for="lastname">Nom : </label>
                    <input class="uk-input" type="text" id="lastname" name="lastname" >
                </div>

                <div class="uk-margin uk-width-1-3@s">
                    <button class="uk-button uk-button-default" name="submit" type="submit">Valider</button>
                </div>
            </fieldset>
        </form>
        <div>
                    <p>
                        <?php
                        if (!empty($errors)) {
                            foreach ($errors as $error) {
                                echo $error . '<br>';

                            }
                            
                        }

                        ?>
                    </p>
        </div>   

</main>
   

</body>
</html>