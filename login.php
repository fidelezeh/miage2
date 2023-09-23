<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Connexion</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="col-md-6 offset-md-3">
            <div>
                <h2>Connexion</h2>
            </div>
            <form>
                <div class="form-group">
                    <label for="loginEmail">Email <span class="text-danger">(*)</span></label>
                    <input type="email" class="form-control" id="loginEmail" placeholder="Votre email" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Mot de passe <span class="text-danger">(*)</span></label>
                    <input type="password" class="form-control" id="loginPassword" placeholder="Mot de passe" required>
                </div>
                <button type="submit" class="btn btn-success">Se connecter</button>
            </form>
            <p class="mt-3">Vous n'avez pas de compte? <a href="register.php">Inscrivez-vous ici</a>.</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
