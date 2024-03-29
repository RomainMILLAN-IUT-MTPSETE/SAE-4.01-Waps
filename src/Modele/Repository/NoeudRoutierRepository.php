<?php

namespace App\PlusCourtChemin\Modele\Repository;

use App\PlusCourtChemin\Modele\DataObject\AbstractDataObject;
use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;
use PDO;

class NoeudRoutierRepository extends AbstractRepository
{

    public function construireDepuisTableau(array $noeudRoutierTableau): NoeudRoutier
    {
        return new NoeudRoutier(
            $noeudRoutierTableau["gid"],
            $noeudRoutierTableau["id_rte500"],
            null
        );
    }

    protected function getNomTable(): string
    {
        return 'noeud_routier';
    }

    protected function getNomClePrimaire(): string
    {
        return 'gid';
    }

    protected function getNomsColonnes(): array
    {
        return ["gid", "id_rte500"];
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

    /**
     * Renvoie le tableau des voisins d'un noeud routier
     *
     * Chaque voisin est un tableau avec les 3 champs
     * `noeud_routier_gid`, `troncon_gid`, `longueur`
     *
     * @param int $noeudRoutierGid
     * @return String[][]
     **/
    public function getVoisins(int $noeudRoutierGid): array
    {
        $requeteSQL = "SELECT
        CASE WHEN noeud_voisin = ? THEN noeud_routier ELSE noeud_voisin END AS noeud_routier_gid,
        CASE WHEN noeud_voisin = ? THEN noeud_routier_latitude ELSE noeud_voisin_latitude END AS latitude,
        CASE WHEN noeud_voisin = ? THEN noeud_routier_longitude ELSE noeud_voisin_longitude END AS longitude,
        longueur
        FROM voisins
        WHERE noeud_voisin = ? OR noeud_routier = ?;";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);
        $pdoStatement->execute(array($noeudRoutierGid, $noeudRoutierGid, $noeudRoutierGid, $noeudRoutierGid, $noeudRoutierGid));
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatitudeLongitude(int $noeudRoutierGid): array {
        $requeteSQL = "SELECT 
        ST_Y(ST_AsText(geom)) AS latitude,
        ST_X(ST_AsText(geom)) AS longitude
        FROM geom_noeud_routier
        WHERE gid = ?";
        $pdoStatement = ConnexionBaseDeDonnees::getPdo()->prepare($requeteSQL);
        $pdoStatement->execute(array($noeudRoutierGid));
        $tab = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        return $tab[0];
    }

}
