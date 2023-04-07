<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\NoeudCommune;
use PDO;

class NoeudCommuneRepository extends AbstractRepository
{

    public function construireDepuisTableau(array $noeudRoutierTableau): NoeudCommune
    {
        return new NoeudCommune(
            $noeudRoutierTableau["gid"],
            $noeudRoutierTableau["id_rte500"],
            $noeudRoutierTableau["insee_comm"],
            $noeudRoutierTableau["nom_chf"],
            $noeudRoutierTableau["statut"],
            $noeudRoutierTableau["superficie"],
            $noeudRoutierTableau["nom_comm"],
            $noeudRoutierTableau["population"],
            $noeudRoutierTableau["id_nd_rte"],
            $noeudRoutierTableau["geom"]
        );
    }

    protected function getNomTable(): string
    {
        return 'noeud_commune';
    }

    protected function getNomClePrimaire(): string
    {
        return 'gid';
    }

    protected function getNomsColonnes(): array
    {
        return ["gid", "id_rte500", "insee_comm", "nom_chf", "statut", "superficie", "nom_comm", "population", "id_nd_rte", "geom"];
    }

    // On bloque l'ajout, la màj et la suppression pour ne pas modifier la table
    // Normalement, j'ai restreint l'accès à SELECT au niveau de la BD
    public function supprimer(string $valeurClePrimaire): bool
    {
        return false;
    }

    public function mettreAJour(AbstractDataObject $object): void
    {
        return;
    }

    public function ajouter(AbstractDataObject $object): bool
    {
        return false;
    }

    public function getNoeudCommune($gid = 0): ?NoeudCommune{
        $requeteSQL = "SELECT * FROM noeud_commune WHERE gid='$gid'";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);
        $pdoStatement->execute();

        foreach ($pdoStatement as $item) {
            return $this->construireDepuisTableau($item);
        }
        return null;
    }

    public function getNoeudCommuneByName($nomComm): ?NoeudCommune{
        $requeteSQL = "SELECT * FROM noeud_commune WHERE nom_comm='$nomComm' LIMIT 1";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);
        $pdoStatement->execute();

        foreach ($pdoStatement as $item) {
            return $this->construireDepuisTableau($item);
        }
        return null;
    }

    public function getAllNoeudCommuneOrderByName(): array{
        $requeteSQL = "SELECT * FROM noeud_commune ORDER BY nom_comm";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);
        $pdoStatement->execute();

        $res = [];
        foreach ($pdoStatement as $item) {
            $res[] = $this->construireDepuisTableau($item);
        }
        return $res;
    }

    public function getNomsCommunes(): array{
        $requeteSQL = "SELECT nom_comm FROM noeud_commune";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);
        $pdoStatement->execute();

        $objets = [];
        foreach ($pdoStatement as $objetFormatTableau) {
            $objets[] = $this->construireDepuisTableau($objetFormatTableau);
        }
        return $objets;
    }

    public function getNomsCommunesJSON(): array{
        $requeteSQL = "SELECT nom_comm FROM noeud_commune";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);
        $pdoStatement->execute();
        $communes = [];
        $pdoStatement->setFetchMode(ConnexionBaseDeDonnees::getPdo()::FETCH_OBJ);
        while ($row = $pdoStatement->fetch(PDO::FETCH_ASSOC)) {
            $communes[] = $row['nom_comm'];
        }
        return $communes;
    }

}
