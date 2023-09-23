<?php
    include_once("config/env.php");
    include_once("config/database.php");
    include_once("model/Users.php");
    include_once("model/usersDao.php");


    $db = new DatabaseConnector();
    $connexion = $db->getConnection();

    if($_SERVER['REQUEST_METHOD']=='POST')
    { 
        $nom = $_POST['nom'];
        $mail = $_POST['mail'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $pwd_cript = password_hash($password, PASSWORD_DEFAULT);

        $user = new User();
        $user->setName($nom);
        $user->setMail($mail);
        $user->setPassword($pwd_cript);
        $user->setRole($role);

        //var_dump($user);

        $usersDao = new usersDao($connexion);
        $statut = $usersDao->create($user);
        if($statut){
            echo 'Utilisateur créé avec succès!!';
        }else{
            echo 'Création de l\utilisateur échouée!!';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Inscription</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="col-md-6 offset-md-3">
            <div>
                <h2>Inscription</h2>
            </div>
            <form action="" method="post">
                <div class="form-group">
                    <label for="nom">Nom <span class="text-danger">(*)</span></label>
                    <input type="text" class="form-control" name="nom" placeholder="Votre nom" required>
                </div>
                <div class="form-group">
                    <label for="mail">Email <span class="text-danger">(*)</span></label>
                    <input type="email" class="form-control" name="mail" placeholder="Votre email" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe <span class="text-danger">(*)</span></label>
                    <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
                </div>
                <div class="form-group">
                    <label for="role"> <span class="text-danger">(*)</span></label>
                    <input type="text" class="form-control" name="role" placeholder="Saisir le profil" required>
                </div>
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </form>
            <p class="mt-3">Vous avez déjà un compte? <a href="login.php"><span class="text-success underline-success">Connectez-vous ici</span></a>.</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>