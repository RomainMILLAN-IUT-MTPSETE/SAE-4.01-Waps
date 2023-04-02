<?php

namespace App\PlusCourtChemin\Lib;

use App\PlusCourtChemin\Modele\DataObject\NoeudRoutier;
use App\PlusCourtChemin\Modele\Repository\NoeudRoutierRepository;
use SplPriorityQueue;

class PlusCourtChemin {
    private array $distances;
    private array $noeudsALaFrontiere = [];

    public function __construct(
        private int $noeudRoutierDepartGid,
        private int $noeudRoutierArriveeGid
    ) {
    }

    public function calculer(): ?float {
        $noeudRoutierRepository = new NoeudRoutierRepository();
        $this->distances = [$this->noeudRoutierDepartGid => 0];
        $this->noeudsALaFrontiere[$this->noeudRoutierDepartGid] = true;
        while (count($this->noeudsALaFrontiere) !== 0) {
            $noeudRoutierGidCourant = $this->noeudALaFrontiereDeDistanceMinimale();
            if ($noeudRoutierGidCourant === $this->noeudRoutierArriveeGid)
                return $this->distances[$noeudRoutierGidCourant];
            unset($this->noeudsALaFrontiere[$noeudRoutierGidCourant]);
            $noeudRoutierCourant = $noeudRoutierRepository->recupererParClePrimaire($noeudRoutierGidCourant);
            $voisins = $noeudRoutierCourant->getVoisins();
            foreach ($voisins as $voisin) {
                $noeudVoisinGid = $voisin["noeud_routier_gid"];
                $distanceTroncon = $voisin["longueur"];
                $distanceProposee = $this->distances[$noeudRoutierGidCourant] + $distanceTroncon;
                if (!isset($this->distances[$noeudVoisinGid]) || $distanceProposee < $this->distances[$noeudVoisinGid]) {
                    $this->distances[$noeudVoisinGid] = $distanceProposee;
                    $this->noeudsALaFrontiere[$noeudVoisinGid] = true;
                }
            }
        }
    }

    private function noeudALaFrontiereDeDistanceMinimale() {
        $noeudRoutierDistanceMinimaleGid = -1;
        $distanceMinimale = PHP_INT_MAX;
        foreach ($this->noeudsALaFrontiere as $noeudRoutierGid => $valeur) {
            if ($this->distances[$noeudRoutierGid] < $distanceMinimale) {
                $noeudRoutierDistanceMinimaleGid = $noeudRoutierGid;
                $distanceMinimale = $this->distances[$noeudRoutierGid];
            }
        }
        return $noeudRoutierDistanceMinimaleGid;
    }
}