<?php
namespace Models;

class ModeleParent
{
    protected $pdo;

    public function __construct()
    {
        $dbhost = $_ENV["DB_HOST"];
        $dbport = $_ENV["DB_PORT"];
        $dbname = $_ENV["DB_NAME"];
        $dbuser = $_ENV["DB_USER"];
        $dbpassword = $_ENV["DB_PASSWORD"];

        try {
            $dsn = "mysql:host=$dbhost;port=$dbport;dbname=$dbname;";
            $this->pdo = new \PDO($dsn, $dbuser, $dbpassword);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Erreur de connexion ou de requête : " . $e->getMessage());
        }
    }

    // Ajoutez cette méthode pour obtenir l'objet PDO
    public function getPdo()
    {
        return $this->pdo;
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}