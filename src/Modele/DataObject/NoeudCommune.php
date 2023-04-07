<?php

namespace App\PlusCourtChemin\Modele\DataObject;

class NoeudCommune extends AbstractDataObject
{
    public function __construct(
        private int $gid,
        private string $id_rte500,
        private string $insee_comm,
        private string $nom_chf,
        private string $status,
        private string $superfecie,
        private string $nom_comm,
        private string $population,
        private string $id_nd_rte,
        private string $geom,
    ) {
    }

    public function getGid(): int
    {
        return $this->gid;
    }

    public function getId_rte500(): string
    {
        return $this->id_rte500;
    }

    public function getId_nd_rte(): string
    {
        return $this->id_nd_rte;
    }

    public function getNomCommune(): string
    {
        return $this->nom_comm;
    }

    /**
     * @return string
     */
    public function getInseeComm(): string
    {
        return $this->insee_comm;
    }

    /**
     * @return string
     */
    public function getNomChf(): string
    {
        return $this->nom_chf;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getSuperfecie(): string
    {
        return $this->superfecie;
    }

    /**
     * @return string
     */
    public function getPopulation(): string
    {
        return $this->population;
    }

    /**
     * @return string
     */
    public function getGeom(): string
    {
        return $this->geom;
    }

    public function exporterEnFormatRequetePreparee(): array
    {
        // Inutile car on ne fait pas d'ajout ni de mise-à-jour
        return [];
    }
}
