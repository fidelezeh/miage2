
  <?php include_once '../../config/env.php' ?>
  <?php include_once '../../config/response.php' ?>
  <?php include_once '../../config/database.php' ?>

  <?php include_once '../../models/publication.php' ?>
  <?php include_once '../../gateways/publications.php' ?>

<?php

  include_get();


  $database = new DatabaseConnector();
  $db = $database->getConnection();

  $publicationGateway = new PublicationsGateway($db);


  $publication_id = isset($_GET['v_publication_id']) ? $_GET['v_publication_id'] : NULL;

  //sendResponse('200', $ublication_id, '200');

  $data = $publicationGateway->read_single($publication_id);

  $message = ($data != null)? "ok": "No data found";
  $code = ($data != null)? "200" : "403";

  sendResponse($code, $data, $message);

?>
