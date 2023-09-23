<?php 

    class MessagesGateway
    {
        private $connection;
        private $table = 'table_messages';


        public function __construct($connection)
        {
            $this->connection = $connection;
        }


        public function create($message)
         {
            // create query
            $query = 'INSERT INTO ' . $this->table . ' SET contenu = :contenu, auteur = :auteur, date_post = :date_post, heure_post = :heure_post, user_name = :user_name, user_image = :user_image,  message_image = :message_image, est_actif = :est_actif, created_by = :created_by, updated_by = :updated_by ';
  
            // Preparing statement
            $stmt = $this->connection->prepare($query);

            
  
            // Binding data
            $stmt->bindParam(':contenu', $message->getContenu());
            $stmt->bindParam(':auteur', $message->getAuteur());
            $stmt->bindParam(':date_post', $message->getDate_Post());
            $stmt->bindParam(':heure_post', $message->getHeure_Post());
            $stmt->bindParam(':user_name', $message->getUser_Name());
            $stmt->bindParam(':user_image', $message->getUser_Image());
            $stmt->bindParam(':message_image', $message->getMessage_Image());
            $stmt->bindParam(':est_actif', $message->getEst_Actif());
            $stmt->bindParam(':created_by', $message->getCreated_By());
            $stmt->bindParam(':updated_by', $message->getUpdated_By());
  
            
            if($stmt->execute()) {

                return true;
              }

              // Print error if something goes wrong
              printf("Error: %s.\n", $stmt->error);

              return false;
                
        }

        public function getAll()
        {
            $sql = 'SELECT date_post, id, contenu, auteur, date_post date_create, heure_post, user_name, user_image   FROM ' .$this->table. '  ORDER BY id';


            $stmt = $this->connection->prepare($sql);
            $result = $stmt->execute();
    
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP); 
    
            $stmt->closeCursor();
    
            return $messages;
        }
    }

?>