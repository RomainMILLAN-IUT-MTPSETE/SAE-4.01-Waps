create index idx_geom_noeud_routier
    on noeud_routier using gist (geom);

create index idx_gid_noeud_routier
    on noeud_routier (gid);

create index idx_geom_troncon_route
    on troncon_route using gist (geom);

create index idx_gid_troncon_route
    on troncon_route (gid);

create materialized view geom_noeud_routier as
SELECT nr.gid,
       nr.geom
FROM noeud_routier nr;

create index geom_noeud_routier_vue
    on geom_noeud_routier using gist (geom);

create index id_noeud_routier_vue
    on geom_noeud_routier (gid);

create materialized view geom_troncon_route as
SELECT troncon_route.gid,
       troncon_route.geom,
       troncon_route.longueur
FROM troncon_route;

create index geom_troncon_route_vue
    on geom_troncon_route using gist (geom);

create index id_troncon_route_vue
    on geom_troncon_route (gid);

create materialized view info_communes as
SELECT noeud_commune.gid,
       noeud_commune.id_rte500,
       noeud_commune.insee_comm,
       noeud_commune.nom_chf,
       noeud_commune.statut,
       noeud_commune.nom_comm,
       noeud_commune.superficie,
       noeud_commune.population,
       noeud_commune.id_nd_rte
FROM noeud_commune;

create index id_commune_vue_info
    on info_communes (gid);

create index nom_commune_vue_info
    on info_communes (nom_comm);

create materialized view info_noeud_routier as
SELECT nr.gid,
       nr.id_rte500,
       nr.nature,
       nr.insee_comm
FROM noeud_routier nr;

create index id_noeud_routier_vue_info
    on info_noeud_routier (gid);

create materialized view info_troncon_route as
SELECT troncon_route.gid,
       troncon_route.id_rte500,
       troncon_route.vocation,
       troncon_route.nb_chausse,
       troncon_route.nb_voies,
       troncon_route.etat,
       troncon_route.acces,
       troncon_route.res_vert,
       troncon_route.sens,
       troncon_route.num_route,
       troncon_route.res_europe,
       troncon_route.class_adm
FROM troncon_route;

create index id_troncon_route_vue_info
    on info_troncon_route (gid);

create materialized view voisins as
SELECT nr1.gid                             AS noeud_routier,
       nr2.gid                             AS noeud_voisin,
       tr.gid                              AS troncon_id,
       tr.longueur,
       st_y(st_astext(nr1.geom)::geometry) AS noeud_routier_latitude,
       st_x(st_astext(nr1.geom)::geometry) AS noeud_routier_longitude,
       st_y(st_astext(nr2.geom)::geometry) AS noeud_voisin_latitude,
       st_x(st_astext(nr2.geom)::geometry) AS noeud_voisin_longitude
FROM geom_troncon_route tr
         CROSS JOIN LATERAL ( SELECT nr.gid,
                                     nr.geom
                              FROM geom_noeud_routier nr
                              ORDER BY (nr.geom <-> st_startpoint(tr.geom))
    LIMIT 1) nr1
         CROSS JOIN LATERAL ( SELECT nr.gid,
    nr.geom
FROM geom_noeud_routier nr
ORDER BY (nr.geom <-> st_endpoint(tr.geom))
    LIMIT 1) nr2;

create index id_noeud_routier_vue_voisins
    on voisins (noeud_routier);

create index id_noeud_voisin_vue_voisins
    on voisins (noeud_voisin);

create index id_troncon_vue_voisins
    on voisins (troncon_id);