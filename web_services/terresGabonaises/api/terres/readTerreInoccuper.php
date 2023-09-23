
  <?php include_once '../../config/env.php' ?>
  <?php include_once '../../config/response.php' ?>
  <?php include_once '../../config/database.php' ?>

  <?php include_once '../../gateways/terres.php' ?>

<?php

  include_get();
  
  $database = new DatabaseConnector();
  $db = $database->getConnection();

  $terresGateway = new terresGateway($db);

  $data = $terresGateway->getProvinceInoccuper();

  
  
  $code = "200";
  $message = "ok";

  sendResponse($code, $data, $message);

