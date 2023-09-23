<?php 

    class LikesGateway
    {
        private $connection;
        private $table = 'table_likes';


        public function __construct($connection)
        {
            $this->connection = $connection;
        }

        public function like($likeModel)
         {
            // create query
            $query = 'INSERT INTO ' . $this->table . ' SET  publication = :publication_id, likeur = :user_id, est_actif = :est_actif, created_by = :created_by, updated_by = :updated_by ';
  
            // Preparing statement
            $stmt = $this->connection->prepare($query);
  
            // Binding data
            $stmt->bindParam(':publication_id', $likeModel->getPublication_Id());
            $stmt->bindParam(':user_id', $likeModel->getCreated_By());
            $stmt->bindParam(':est_actif', $likeModel->getActif());
            $stmt->bindParam(':created_by', $likeModel->getCreated_By());
            $stmt->bindParam(':updated_by', $likeModel->getUpdated_By());
  
            // Executing query
            if($stmt->execute()) {

                // If comment successfully added, then update number of comments in publication table

                // Create query
              $query = 'UPDATE `table_publications` SET `nombre_likes` = (`nombre_likes` + 1) WHERE id = ?';

              // Prepare statement
              $stmt = $this->connection->prepare($query);

              // Bind data
              $stmt->bindParam(1, $likeModel->getPublication_Id());

              // Execute query
              if($stmt->execute()) {
                return true;
              }

              // Print error if something goes wrong
              printf("Error: %s.\n", $stmt->error);

              return false;
                
            }
  
        return false;
      }

        public function read_single($publication_id, $user_id) {
            // Create query
            $query = 'SELECT *
                          FROM ' . $this->table . '
                          WHERE publication = ? AND likeur = ?
                          LIMIT 0,1';
      
            // Prepare statement
            $stmt = $this->connection->prepare($query);
      
            // Bind user_id
            $stmt->bindParam(1, $publication_id);
            $stmt->bindParam(2, $user_id);
      
            // Execute query
            $stmt->execute();
      
            $row = $stmt->fetch(PDO::FETCH_OBJ);
      
            // Set properties
            //$this->id = $row['id'];
            //$this->publication_id = $row['publication_id'];
            //$this->user_id = $row['user_id'];

            return $row;
      
          }


          public function dislike($publication_id, $user_id)
           {
            // Delete query
            $query = 'DELETE FROM ' . $this->table . ' WHERE publication = ? AND likeur = ?';

            // Preparing statement
            $stmt = $this->connection->prepare($query);

            // Bind data
            $stmt->bindParam(1, $publication_id);
            $stmt->bindParam(2, $user_id);

            // Execute query
            if($stmt->execute()) {

                $query = 'UPDATE `table_publications` SET `nombre_likes`= (`nombre_likes` - 1) WHERE id = ?';

                // Prepare statement
                $stmt = $this->connection->prepare($query);
  
                // Bind data
                $stmt->bindParam(1, $publication_id);
  
                // Execute query
                if($stmt->execute()) {
                  return true;
                }
            }

            return false;
      }
    }
?>