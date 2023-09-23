<?php 

    class CommentairesGateway
    {
        private $connection;
        private $table = 'table_commentaires';


        public function __construct($connection)
        {
            $this->connection = $connection;
        }

        // Creating new comment

        public function create($commentaire)
         {
            // create query
            $query = 'INSERT INTO ' . $this->table . ' SET contenu = :contenu, publication = :publication_id, commentateur = :user_id, commentateur_name = :commentateur_name, commentateur_image = :commentateur_image, est_actif = :est_actif, created_by = :created_by, updated_by = :updated_by ';
  
            // Preparing statement
            $stmt = $this->connection->prepare($query);

            
  
            // Binding data
            $stmt->bindParam(':contenu', $commentaire->getContenu());
            $stmt->bindParam(':publication_id', $commentaire->getPublication_Id());
            $stmt->bindParam(':user_id', $commentaire->getCreated_By());
            $stmt->bindParam(':commentateur_name', $commentaire->getCommentateur_Name());
            $stmt->bindParam(':commentateur_image', $commentaire->getCommentateur_Image());
            $stmt->bindParam(':est_actif', $commentaire->getActif());
            $stmt->bindParam(':created_by', $commentaire->getCreated_By());
            $stmt->bindParam(':updated_by', $commentaire->getUpdated_By());
  
            // Executing query
            if($stmt->execute()) {

                // If comment successfully added, then update number of comments in publication table

                // Create query
              $query = 'UPDATE `table_publications` SET `nombre_commentaires` = (`nombre_commentaires` + 1) WHERE id = ?';

              // Prepare statement
              $stmt = $this->connection->prepare($query);

              // Bind data
              $stmt->bindParam(1, $commentaire->getPublication_Id());

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

      /*
      public function read() {
        // Create query
        $query = 'SELECT *
                      FROM ' . $this->table . '
                      ORDER BY created_at DESC';
  
        // Prepare statement
        $stmt = $this->conn->prepare($query);
  
        // Execute query
        $stmt->execute();
  
        return $stmt;
      }
      */

      public function read_single($publication_id, $user_id) {
            // query
            $query = 'SELECT *
                          FROM ' . $this->table . '
                          WHERE publication = ? AND commentateur = ?
                          LIMIT 0,1';
      
            // Preparing statement
            $stmt = $this->connection->prepare($query);
      
            // Binding data param
            $stmt->bindParam(1, $publication_id);
            $stmt->bindParam(2, $user_id);
      
            // Executing query
            $stmt->execute();
      
            $row = $stmt->fetch(PDO::FETCH_OBJ);
      

            return $row;
      
          }

          public function getCommentaires($publication_id) {
            // query
            $query = 'SELECT *
                          FROM ' . $this->table . '
                          WHERE publication = ?
                          ORDER BY created_at DESC';
      
            // Preparing statement
            $stmt = $this->connection->prepare($query);
      
            // Binding data param
            $stmt->bindParam(1, $publication_id);
      
            // Executing query
            $stmt->execute();
      
            $row = $stmt->fetchAll(PDO::FETCH_OBJ);
      

            return $row;
      
          }
    }
?>