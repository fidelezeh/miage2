
  <?php header("Access-Control-Allow-Origin: *"); ?>
  <?php include_once '../../config/env.php' ?>
  <?php include_once '../../config/response.php' ?>
  <?php include_once '../../config/database.php' ?>

  <?php include_once '../../models/publication.php' ?>
  <?php include_once '../../gateways/publications.php' ?>

<?php

  //include_post();

  function generateCode($auteur, $dateHeure)
  {
    $code = "pub".$dateHeure. $auteur;
    return $code;
  }
  
  $database = new DatabaseConnector();
  $db = $database->getConnection();

  

  // Instantiate publication object
  $publication = new Publication($db);



  // Check if a e posts
  $title = isset($_POST['v_title']) ? $_POST['v_title'] : NULL;
  // Clean data
  $title = htmlspecialchars(strip_tags($title));
  $auteur = isset($_POST['v_user_id']) ? $_POST['v_user_id'] : NULL;
  $categorie = isset($_POST['v_categorie']) ? $_POST['v_categorie'] : NULL;
  $user_name = isset($_POST['v_user_name']) ? $_POST['v_user_name'] : NULL;
  $user_image = isset($_POST['v_user_image']) ? $_POST['v_user_image'] : NULL;

  //die();
  
  //$camera = isset($_POST['v_camera']) ? $_POST['v_camera'] : NULL;;

  //if ($camera == 0) {
  if (isset($_FILES['v_image'])) {
      $path = $_FILES['v_image']['tmp_name'];
      $type = pathinfo($path, PATHINFO_EXTENSION);
      $data = file_get_contents($path);
      $image_base64 = base64_encode($data);
      $publication->setImage('data:image/'.$type.';base64,'.$image_base64);
  } else {
        $publication->setImage(NULL);
  }

  $date = date('y-m-d H:s:i');
  $code = generateCode($auteur, $date);
  $publication->setCode($code);
  $publication->setTitre($title);
  $publication->setAuteur($auteur);
  $publication->setCategorie($categorie);
  $publication->setNombre_Likes(0);
  $publication->setNombre_Commentaires(0);
  $publication->setTitre($title);
  $publication->setUser_Name($user_name);
  $publication->setUser_Image($user_image);
  $publication->setCreated_By($auteur);
  $publication->setUpdated_By($auteur);
  $publication->setDate_Publication($date);
  $publication->setVisibilite(true);
  $publication->setActif(true);



  $publicationGateWay = new publicationsGateway($db);

  $result = $publicationGateWay->insert($publication);


  $codeResponse = ($result)? "200" : "500";

  sendResponse($codeResponse, $result, "ok");

?>
