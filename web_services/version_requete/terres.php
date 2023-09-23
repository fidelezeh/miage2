<?php

class terresGateway{

    private $connection;
    private $table = "G_PROV";


    

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    function getAll()
    {
        $sql = 'SELECT *, ST_AsGeoJson("SP_GEOMETRY", 5) AS geojson FROM  "' . $this->table.'"';

        $stmt = $this->connection->prepare($sql);
        $result = $stmt->execute();

        $terre = $stmt->fetchAll(PDO::FETCH_OBJ); 

        $stmt->closeCursor();

        return $terre;
    }

    function getTerreByProvince()
    {
        
        $sql = 'SELECT g_reserv."CODE", g_reserv."NOM", g_reserv."AREA", g_prov."PROVINCE"
        FROM "G_PROV" as g_prov, "G_RESERV" as g_reserv
        WHERE ST_Intersects(g_prov."SP_GEOMETRY",g_reserv."SP_GEOMETRY")
        AND  g_prov."PROVINCE" = \'NYANGA\' ';

        $stmt = $this->connection->prepare($sql);
        $result = $stmt->execute();

        $terre = $stmt->fetchAll(PDO::FETCH_OBJ); 

       $stmt->closeCursor();

       return $terre;
    }

    function getProvinceInoccuper()
    {
        $sql = ' SELECT g_prov."CODE",g_prov."Surf_Km2", g_prov."PROVINCE"  
        FROM "G_PROV" as g_prov
        EXCEPT 
        SELECT g_prov."CODE",g_prov."Surf_Km2", g_prov."PROVINCE"
        FROM "G_PROV" as g_prov, "G_RESERV" as g_reserv
        WHERE ST_Intersects(g_prov."SP_GEOMETRY",g_reserv."SP_GEOMETRY") ';

        $stmt = $this->connection->prepare($sql);
        $result = $stmt->execute();

        $terre = $stmt->fetchAll(PDO::FETCH_OBJ); 

       $stmt->closeCursor();

       return $terre;
    }

    function getTauxOccupationReserve()
    {
        $sql = ' SELECT R1.totalP, R2.totalR, (R2.totalR / R1.totalP) *100 as totalT
        FROM 
        (SELECT trunc(SUM(g_prov."Surf_Km2"),2)  as totalP
        FROM "G_PROV" as g_prov) AS R1,
        (SELECT SUM(g_reserv."AREA")  as totalR
        FROM "G_RESERV" as g_reserv ) AS R2';

        $stmt = $this->connection->prepare($sql);
        $result = $stmt->execute();

        $terre = $stmt->fetchAll(PDO::FETCH_OBJ); 

       $stmt->closeCursor();

       return $terre;
    }

    // Get Single Publication' nb_likes by id;
    
    public function read_single($publication_id) {
        // Create query
        $query = 'SELECT *
                      FROM ' . $this->table . '
                      WHERE id = ?
                      LIMIT 0,1';
  
        // Preparing statement
        $stmt = $this->connection->prepare($query);
  
        // Binding parameter 
        $stmt->bindParam(1, $publication_id);
  
        // Executing query
        $result = $stmt->execute();
  
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        $stmt->closeCursor();

        return $row;

  
      }

    function insert($publication)
    {
        $query = 'INSERT INTO ' . $this->table . ' SET code = :code, image_url = :image_url, titre = :titre, user_name = :user_name, user_image = :user_image, categorie = :categorie, visibilite = :visibilite, auteur = :auteur, nombre_likes = :nombre_likes, nombre_commentaires = :nombre_commentaires, est_actif = :est_actif, created_by = :created_by,  updated_by = :updated_by';

        $stmt = $this->connection->prepare($query);

        

        // Bind data
        $stmt->bindParam(':code', $publication->getCode());
        $stmt->bindParam(':image_url', $publication->getImage());
        $stmt->bindParam(':titre', $publication->getTitre());
        $stmt->bindParam(':user_name', $publication->getUser_Name());
        $stmt->bindParam(':user_image', $publication->getUser_Image());
        $stmt->bindParam(':categorie', $publication->getCategorie());
        $stmt->bindParam(':visibilite', $publication->getVisibilite());
        $stmt->bindParam(':auteur', $publication->getAuteur());
        $stmt->bindParam(':nombre_likes', $publication->getNombre_Likes());
        $stmt->bindParam(':nombre_commentaires', $publication->getNombre_Commentaires());
        $stmt->bindParam(':est_actif', $publication->getActif());
        $stmt->bindParam(':created_by', $publication->getCreated_By());
        $stmt->bindParam(':updated_by', $publication->getUpdated_By());

        if($stmt->execute()) {
            return true;
        }

        return false;


    }
}

?>