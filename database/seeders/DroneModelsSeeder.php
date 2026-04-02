<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DroneModelsSeeder extends Seeder
{
    public function run(): void
    {
        $models = [
            // ==================== DJI ====================
            ['marca'=>'DJI','modelo'=>'Mini 2','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>249,'pais_fabricacion'=>'China','autonomia_min'=>'31','camara'=>'12MP 4K'],
            ['marca'=>'DJI','modelo'=>'Mini 2 SE','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>249,'pais_fabricacion'=>'China','autonomia_min'=>'38','camara'=>'12MP 2.7K'],
            ['marca'=>'DJI','modelo'=>'Mini 3','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>249,'pais_fabricacion'=>'China','autonomia_min'=>'38','camara'=>'12MP 4K'],
            ['marca'=>'DJI','modelo'=>'Mini 3 Pro','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>249,'pais_fabricacion'=>'China','autonomia_min'=>'34','camara'=>'48MP 4K'],
            ['marca'=>'DJI','modelo'=>'Mini 4 Pro','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>249,'pais_fabricacion'=>'China','autonomia_min'=>'34','camara'=>'48MP 4K HDR'],
            ['marca'=>'DJI','modelo'=>'Mini 4K','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>249,'pais_fabricacion'=>'China','autonomia_min'=>'31','camara'=>'12MP 4K'],
            ['marca'=>'DJI','modelo'=>'Air 2S','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>595,'pais_fabricacion'=>'China','autonomia_min'=>'31','camara'=>'20MP 5.4K'],
            ['marca'=>'DJI','modelo'=>'Air 3','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>720,'pais_fabricacion'=>'China','autonomia_min'=>'46','camara'=>'48MP 4K'],
            ['marca'=>'DJI','modelo'=>'Air 3S','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>723,'pais_fabricacion'=>'China','autonomia_min'=>'45','camara'=>'50MP 4K'],
            ['marca'=>'DJI','modelo'=>'Mavic 3','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>895,'pais_fabricacion'=>'China','autonomia_min'=>'46','camara'=>'20MP 5.1K Hasselblad'],
            ['marca'=>'DJI','modelo'=>'Mavic 3 Classic','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>895,'pais_fabricacion'=>'China','autonomia_min'=>'46','camara'=>'20MP 5.1K Hasselblad'],
            ['marca'=>'DJI','modelo'=>'Mavic 3 Pro','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>958,'pais_fabricacion'=>'China','autonomia_min'=>'43','camara'=>'20MP Triple'],
            ['marca'=>'DJI','modelo'=>'Mavic 3 Enterprise','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>915,'pais_fabricacion'=>'China','autonomia_min'=>'45','camara'=>'20MP + Thermal'],
            ['marca'=>'DJI','modelo'=>'Mavic 3 Multispectral','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>920,'pais_fabricacion'=>'China','autonomia_min'=>'43','camara'=>'Multiespectral'],
            ['marca'=>'DJI','modelo'=>'Phantom 4 Pro V2.0','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1375,'pais_fabricacion'=>'China','autonomia_min'=>'30','camara'=>'20MP 4K'],
            ['marca'=>'DJI','modelo'=>'Phantom 4 RTK','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1391,'pais_fabricacion'=>'China','autonomia_min'=>'30','camara'=>'20MP RTK'],
            ['marca'=>'DJI','modelo'=>'Phantom 4 Multispectral','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1487,'pais_fabricacion'=>'China','autonomia_min'=>'27','camara'=>'Multiespectral'],
            ['marca'=>'DJI','modelo'=>'Inspire 2','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>3440,'pais_fabricacion'=>'China','autonomia_min'=>'27','camara'=>'CineCore 2.0'],
            ['marca'=>'DJI','modelo'=>'Inspire 3','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>4300,'pais_fabricacion'=>'China','autonomia_min'=>'28','camara'=>'8K Full Frame'],
            ['marca'=>'DJI','modelo'=>'Matrice 30','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>3770,'pais_fabricacion'=>'China','autonomia_min'=>'41','camara'=>'48MP Zoom+Wide'],
            ['marca'=>'DJI','modelo'=>'Matrice 30T','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>3810,'pais_fabricacion'=>'China','autonomia_min'=>'41','camara'=>'48MP + Thermal'],
            ['marca'=>'DJI','modelo'=>'Matrice 300 RTK','tipo_uas'=>'Multirotor','num_motores'=>6,'peso_fabrica_gr'=>6300,'pais_fabricacion'=>'China','autonomia_min'=>'55','camara'=>'Multi-payload'],
            ['marca'=>'DJI','modelo'=>'Matrice 350 RTK','tipo_uas'=>'Multirotor','num_motores'=>6,'peso_fabrica_gr'=>6470,'pais_fabricacion'=>'China','autonomia_min'=>'55','camara'=>'Multi-payload'],
            ['marca'=>'DJI','modelo'=>'Matrice 4T','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1570,'pais_fabricacion'=>'China','autonomia_min'=>'49','camara'=>'Thermal + Zoom'],
            ['marca'=>'DJI','modelo'=>'Matrice 4D','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1570,'pais_fabricacion'=>'China','autonomia_min'=>'49','camara'=>'4D Mapping'],
            ['marca'=>'DJI','modelo'=>'Matrice 4E','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1570,'pais_fabricacion'=>'China','autonomia_min'=>'49','camara'=>'Enterprise'],
            ['marca'=>'DJI','modelo'=>'Matrice 100','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>2355,'pais_fabricacion'=>'China','autonomia_min'=>'40','camara'=>'Payload customizable'],
            ['marca'=>'DJI','modelo'=>'Matrice 200','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>4690,'pais_fabricacion'=>'China','autonomia_min'=>'38','camara'=>'Multi-payload'],
            ['marca'=>'DJI','modelo'=>'Matrice 210 RTK','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>4800,'pais_fabricacion'=>'China','autonomia_min'=>'38','camara'=>'Dual-payload RTK'],
            ['marca'=>'DJI','modelo'=>'Agras T10','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>24500,'pais_fabricacion'=>'China','autonomia_min'=>'10','camara'=>'FPV'],
            ['marca'=>'DJI','modelo'=>'Agras T20P','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>42500,'pais_fabricacion'=>'China','autonomia_min'=>'10','camara'=>'FPV'],
            ['marca'=>'DJI','modelo'=>'Agras T25','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>42000,'pais_fabricacion'=>'China','autonomia_min'=>'10','camara'=>'FPV'],
            ['marca'=>'DJI','modelo'=>'Agras T40','tipo_uas'=>'Multirotor','num_motores'=>8,'peso_fabrica_gr'=>65500,'pais_fabricacion'=>'China','autonomia_min'=>'10','camara'=>'Dual FPV'],
            ['marca'=>'DJI','modelo'=>'Agras T50','tipo_uas'=>'Multirotor','num_motores'=>8,'peso_fabrica_gr'=>68000,'pais_fabricacion'=>'China','autonomia_min'=>'10','camara'=>'Dual FPV'],
            ['marca'=>'DJI','modelo'=>'FPV','tipo_uas'=>'Ala Mixta','num_motores'=>4,'peso_fabrica_gr'=>795,'pais_fabricacion'=>'China','autonomia_min'=>'20','camara'=>'4K 60fps'],
            ['marca'=>'DJI','modelo'=>'Avata','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>410,'pais_fabricacion'=>'China','autonomia_min'=>'18','camara'=>'4K 60fps'],
            ['marca'=>'DJI','modelo'=>'Avata 2','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>377,'pais_fabricacion'=>'China','autonomia_min'=>'23','camara'=>'4K 60fps'],
            ['marca'=>'DJI','modelo'=>'Neo','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>135,'pais_fabricacion'=>'China','autonomia_min'=>'18','camara'=>'4K 60fps'],
            ['marca'=>'DJI','modelo'=>'Flip','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>249,'pais_fabricacion'=>'China','autonomia_min'=>'27','camara'=>'4K HDR'],
            ['marca'=>'DJI','modelo'=>'Dock','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>3770,'pais_fabricacion'=>'China','autonomia_min'=>'41','camara'=>'Sistema Dock'],
            ['marca'=>'DJI','modelo'=>'Dock 2','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1570,'pais_fabricacion'=>'China','autonomia_min'=>'50','camara'=>'Sistema Dock 2'],

            // ==================== AUTEL ====================
            ['marca'=>'AUTEL','modelo'=>'EVO Nano','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>249,'pais_fabricacion'=>'China','autonomia_min'=>'28','camara'=>'50MP 4K'],
            ['marca'=>'AUTEL','modelo'=>'EVO Nano+','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>249,'pais_fabricacion'=>'China','autonomia_min'=>'28','camara'=>'50MP 4K RYYB'],
            ['marca'=>'AUTEL','modelo'=>'EVO Lite','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>820,'pais_fabricacion'=>'China','autonomia_min'=>'40','camara'=>'50MP 4K'],
            ['marca'=>'AUTEL','modelo'=>'EVO Lite+','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>835,'pais_fabricacion'=>'China','autonomia_min'=>'40','camara'=>'50MP 6K Variable'],
            ['marca'=>'AUTEL','modelo'=>'EVO II','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1127,'pais_fabricacion'=>'China','autonomia_min'=>'40','camara'=>'48MP 8K'],
            ['marca'=>'AUTEL','modelo'=>'EVO II Pro','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1127,'pais_fabricacion'=>'China','autonomia_min'=>'40','camara'=>'6K Rugged'],
            ['marca'=>'AUTEL','modelo'=>'EVO II Dual','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1191,'pais_fabricacion'=>'China','autonomia_min'=>'38','camara'=>'8K + Thermal 640'],
            ['marca'=>'AUTEL','modelo'=>'EVO II Enterprise','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1127,'pais_fabricacion'=>'China','autonomia_min'=>'42','camara'=>'48MP Enterprise'],
            ['marca'=>'AUTEL','modelo'=>'EVO II Pro Enterprise','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1127,'pais_fabricacion'=>'China','autonomia_min'=>'42','camara'=>'6K Enterprise'],
            ['marca'=>'AUTEL','modelo'=>'EVO II Pro V3','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1127,'pais_fabricacion'=>'China','autonomia_min'=>'42','camara'=>'6K V3'],
            ['marca'=>'AUTEL','modelo'=>'EVO Max 4T','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1580,'pais_fabricacion'=>'China','autonomia_min'=>'42','camara'=>'50MP + Thermal + Laser'],
            ['marca'=>'AUTEL','modelo'=>'EVO Max 4N','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1580,'pais_fabricacion'=>'China','autonomia_min'=>'42','camara'=>'50MP Night Vision'],
            ['marca'=>'AUTEL','modelo'=>'EVO Max 4D','tipo_uas'=>'Multirotor','num_motores'=>4,'peso_fabrica_gr'=>1580,'pais_fabricacion'=>'China','autonomia_min'=>'42','camara'=>'LiDAR + RGB'],
            ['marca'=>'AUTEL','modelo'=>'Dragonfish Lite','tipo_uas'=>'VTOL (Vertical Take Off and Landing)','num_motores'=>5,'peso_fabrica_gr'=>2800,'pais_fabricacion'=>'China','autonomia_min'=>'120','camara'=>'4K Gimbal'],
            ['marca'=>'AUTEL','modelo'=>'Dragonfish Standard','tipo_uas'=>'VTOL (Vertical Take Off and Landing)','num_motores'=>5,'peso_fabrica_gr'=>5200,'pais_fabricacion'=>'China','autonomia_min'=>'120','camara'=>'4K + Thermal'],
            ['marca'=>'AUTEL','modelo'=>'Dragonfish Pro','tipo_uas'=>'VTOL (Vertical Take Off and Landing)','num_motores'=>5,'peso_fabrica_gr'=>7500,'pais_fabricacion'=>'China','autonomia_min'=>'150','camara'=>'Multi-payload'],
            ['marca'=>'AUTEL','modelo'=>'Titan','tipo_uas'=>'Multirotor','num_motores'=>8,'peso_fabrica_gr'=>17500,'pais_fabricacion'=>'China','autonomia_min'=>'33','camara'=>'Multi-payload Enterprise'],
        ];

        if (DB::table('drone_models')->count() === 0) {
            DB::table('drone_models')->insert($models);
        }
    }
}