<?php
// File: Database.php
namespace database;



class Database
{
    private $db;

    public function getDb($fileIni)
    {
        if (!$this->db) {
            $tabProp = parse_ini_file($fileIni);
            // les proprietes des cnxs
            $lsProtocole = $tabProp["protocole"];
            $lsServeur = $tabProp["serveur"];
            $lsPort = $tabProp["port"];
            $lsUT = $tabProp["ut"];
            $lsMDP = $tabProp["mdp"];
            $lsBD = $tabProp["bd"];
            /*
             * Connexion
             */
            try {
                $this->db = new \PDO("$lsProtocole:host=$lsServeur;port=$lsPort;dbname=$lsBD;", $lsUT, $lsMDP,
                    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
                $this->db->exec("SET NAMES 'UTF8'");
            } catch (\PDOException $e) {
                echo '<div style="width: 400px; padding: 10px; background: #CCE5FF; border-radius: 4px; margin: 0 auto; color: white; text-align: center;">';
                echo "🛑 Une erreur s'est produite : 💬 " . $e->getMessage();
                echo '</div>';
            }
        }
        return $this->db;
    }
}


?>