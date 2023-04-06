<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\Utilisateur;
use Exception;

class UtilisateurRepository extends AbstractRepository
{
    /**
     * @return Utilisateur[]
     */
    public static function getUtilisateurs() : array {
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT * FROM utilisateur");

        $utilisateurs = [];
        foreach($pdoStatement as $utilisateurFormatTableau) {
            $utilisateurs[] = UtilisateurRepository::construireDepuisTableau($utilisateurFormatTableau);
        }

        return $utilisateurs;
    }

    public static function getNextLogin(): int{
        $res = 0;

        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->query("SELECT MAX(login) as id from utilisateur;");

        foreach ($pdoStatement as $fe){
            $res = $fe["id"];
        }

        return $res+1;
    }

    public function construireDepuisTableau(array $utilisateurTableau): Utilisateur
    {
        return new Utilisateur(
            $utilisateurTableau["login"],
            $utilisateurTableau["nom"],
            $utilisateurTableau["prenom"],
            $utilisateurTableau["mdp_hache"],
            $utilisateurTableau["est_admin"],
            $utilisateurTableau["email"],
            $utilisateurTableau["email_a_valider"],
            $utilisateurTableau["nonce"],
        );
    }

    public function recupererParMail(string $mail): ?AbstractDataObject
    {
        $nomClePrimaire = $this->getNomClePrimaire();
        $sql = "SELECT * from utilisateur WHERE email=:mail";
        // Préparation de la requête
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($sql);

        $values = array(
            "mail" => $mail,
        );
        // On donne les valeurs et on exécute la requête
        $pdoStatement->execute($values);

        // On récupère les résultats comme précédemment
        // Note: fetch() renvoie false si pas de voiture correspondante
        $objetFormatTableau = $pdoStatement->fetch();

        if ($objetFormatTableau !== false) {
            return $this->construireDepuisTableau($objetFormatTableau);
        }
        return null;
    }

    public function getNomTable(): string
    {
        return 'utilisateur';
    }

    protected function getNomClePrimaire(): string
    {
        return 'login';
    }

    protected function getNomsColonnes(): array
    {
        return ["login", "nom", "prenom", "mdp_hache", "est_admin", "email", "email_a_valider", "nonce"];
    }
}