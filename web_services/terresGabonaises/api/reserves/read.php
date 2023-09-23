
  <?php include_once '../../config/env.php' ?>
  <?php include_once '../../config/response.php' ?>
  <?php include_once '../../config/database.php' ?>

  <?php include_once '../../gateways/reserves.php' ?>

<?php

  include_get();
  
  $database = new DatabaseConnector();
  $db = $database->getConnection();

  $reservesGateway = new reservesGateway($db);

  $data = $reservesGateway->getAll();

  $code = "200";
  $message = "ok";

  sendResponse($code, $data, $message);

