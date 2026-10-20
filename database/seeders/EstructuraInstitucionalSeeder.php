<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstructuraInstitucionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // =========================================================================
            // 1. POBLAR TABLA `area` (Nivel 1 - Órganos y Áreas Principales de la UGEL)
            // =========================================================================
            $areas = [
                1 => ['nombre_area' => 'GESTIÓN ADMINISTRATIVA', 'siglas' => 'OGA', 'descripcion' => 'Área de Administración de la UGEL Alto Amazonas', 'estado' => 1],
                2 => ['nombre_area' => 'ORGANO DE CONTROL INSTITUCIONAL', 'siglas' => 'OCI', 'descripcion' => 'Órgano de Control Institucional', 'estado' => 1],
                3 => ['nombre_area' => 'ASESORÍA JURÍDICA', 'siglas' => 'OAJ', 'descripcion' => 'Área de Asesoría Jurídica', 'estado' => 1],
                4 => ['nombre_area' => 'GESTIÓN PEDAGÓGICA', 'siglas' => 'AGP', 'descripcion' => 'Área de Gestión Pedagógica', 'estado' => 1],
                5 => ['nombre_area' => 'GESTIÓN INSTITUCIONAL', 'siglas' => 'AGI', 'descripcion' => 'Área de Gestión Institucional', 'estado' => 1],
                6 => ['nombre_area' => 'DIRECCIÓN', 'siglas' => 'DIR', 'descripcion' => 'Dirección de la UGEL Alto Amazonas (Despacho Principal)', 'estado' => 1],
                7 => ['nombre_area' => 'COMISIONES INSTITUCIONALES', 'siglas' => 'COM', 'descripcion' => 'Comisiones Permanentes y Especiales', 'estado' => 1],
            ];

            foreach ($areas as $id => $data) {
                DB::table('area')->updateOrInsert(['id' => $id], $data);
            }

            // =========================================================================
            // 2. POBLAR TABLA `equipo` (Nivel 2 - Equipos de Trabajo dependientes de Áreas)
            // =========================================================================
            // are_id apunta a `area.id`
            $equipos = [
                // Dependientes de GESTIÓN ADMINISTRATIVA (are_id: 1)
                1  => ['nombre_equipo' => 'RECURSOS HUMANOS (ERP)', 'are_id' => 1, 'siglas' => 'RRHH', 'descripcion' => 'Equipo de Recursos Humanos / Personal / ERP', 'estado' => 1],
                3  => ['nombre_equipo' => 'CONTABILIDAD', 'are_id' => 1, 'siglas' => 'CONT', 'descripcion' => 'Equipo de Contabilidad', 'estado' => 1],
                4  => ['nombre_equipo' => 'TESORERÍA', 'are_id' => 1, 'siglas' => 'TES', 'descripcion' => 'Equipo de Tesorería', 'estado' => 1],
                5  => ['nombre_equipo' => 'ABASTECIMIENTO', 'are_id' => 1, 'siglas' => 'ABAST', 'descripcion' => 'Equipo de Abastecimiento y Servicios Generales', 'estado' => 1],
                6  => ['nombre_equipo' => 'INFRAESTRUCTURA', 'are_id' => 1, 'siglas' => 'INFRA', 'descripcion' => 'Equipo de Infraestructura', 'estado' => 1],
                7  => ['nombre_equipo' => 'SECRETARÍA DE ADMINISTRACIÓN', 'are_id' => 1, 'siglas' => 'SADM', 'descripcion' => 'Secretaría de Gestión Administrativa', 'estado' => 1],

                // Dependientes de DIRECCIÓN (are_id: 6)
                14 => ['nombre_equipo' => 'TRÁMITE DOCUMENTARIO', 'are_id' => 6, 'siglas' => 'TD', 'descripcion' => 'Equipo de Trámite Documentario y Archivo', 'estado' => 1],
                16 => ['nombre_equipo' => 'IMAGEN INSTITUCIONAL', 'are_id' => 6, 'siglas' => 'II', 'descripcion' => 'Equipo de Comunicación e Imagen Institucional', 'estado' => 1],
                17 => ['nombre_equipo' => 'COMISION PROCESOS ADMINISTRATIVOS DOCENTE', 'are_id' => 6, 'siglas' => 'CPADD', 'descripcion' => 'Comisión Permanente de Procesos Administrativos Disciplinarios para Docentes', 'estado' => 1],
                34 => ['nombre_equipo' => 'SECRETARIA TECNICA SERVIR - CEPAT', 'are_id' => 6, 'siglas' => 'CEPAT', 'descripcion' => 'Secretaría Técnica de Procedimientos Administrativos SERVIR', 'estado' => 1],
                39 => ['nombre_equipo' => 'SECRETARÍA DE DIRECCIÓN', 'are_id' => 6, 'siglas' => 'SDIR', 'descripcion' => 'Secretaría de Dirección General', 'estado' => 1],

                // Dependientes de ASESORÍA JURÍDICA (are_id: 3)
                18 => ['nombre_equipo' => 'ASESORÍA LEGAL', 'are_id' => 3, 'siglas' => 'ALEG', 'descripcion' => 'Equipo de Asesoría Legal', 'estado' => 1],
                42 => ['nombre_equipo' => 'SECRETARÍA DE ASESORÍA JURÍDICA', 'are_id' => 3, 'siglas' => 'SAJ', 'descripcion' => 'Secretaría de Asesoría Jurídica', 'estado' => 1],

                // Dependientes de OCI (are_id: 2)
                13 => ['nombre_equipo' => 'CONTROL INTERNO', 'are_id' => 2, 'siglas' => 'CI', 'descripcion' => 'Equipo de Control Interno', 'estado' => 1],
                93 => ['nombre_equipo' => 'AUDITORES', 'are_id' => 2, 'siglas' => 'AUD', 'descripcion' => 'Equipo de Auditores', 'estado' => 1],

                // Dependientes de GESTIÓN PEDAGÓGICA (are_id: 4)
                20 => ['nombre_equipo' => 'EDUCACIÓN INICIAL', 'are_id' => 4, 'siglas' => 'EI', 'descripcion' => 'Equipo de Nivel Inicial', 'estado' => 1],
                21 => ['nombre_equipo' => 'EDUCACIÓN PRIMARIA', 'are_id' => 4, 'siglas' => 'EP', 'descripcion' => 'Equipo de Nivel Primaria', 'estado' => 1],
                22 => ['nombre_equipo' => 'EDUCACIÓN SECUNDARIA', 'are_id' => 4, 'siglas' => 'ES', 'descripcion' => 'Equipo de Nivel Secundaria', 'estado' => 1],
                23 => ['nombre_equipo' => 'EDUCACIÓN INTERCULTURAL BILINGÜE', 'are_id' => 4, 'siglas' => 'EIB', 'descripcion' => 'Equipo EIB', 'estado' => 1],
                24 => ['nombre_equipo' => 'TOE Y CONVIVENCIA ESCOLAR', 'are_id' => 4, 'siglas' => 'TOE', 'descripcion' => 'Tutoría, Orientación Educativa y Convivencia Escolar', 'estado' => 1],
                25 => ['nombre_equipo' => 'DITE (TECNOLOGÍAS DE INFORMACIÓN)', 'are_id' => 4, 'siglas' => 'DITE', 'descripcion' => 'Dirección de Innovación Tecnológica en Educación', 'estado' => 1],
                26 => ['nombre_equipo' => 'PRONOEI', 'are_id' => 4, 'siglas' => 'PRONOEI', 'descripcion' => 'Programas No Escolarizados de Educación Inicial', 'estado' => 1],
                35 => ['nombre_equipo' => 'EDUCACIÓN SUPERIOR', 'are_id' => 4, 'siglas' => 'ESUP', 'descripcion' => 'Educación Superior no Universitaria', 'estado' => 1],

                // Dependientes de GESTIÓN INSTITUCIONAL (are_id: 5)
                28 => ['nombre_equipo' => 'PREVAED', 'are_id' => 5, 'siglas' => 'PREVAED', 'descripcion' => 'Reducción de la Vulnerabilidad y Atención de Emergencias', 'estado' => 1],
                29 => ['nombre_equipo' => 'PRESUPUESTO', 'are_id' => 5, 'siglas' => 'PPTO', 'descripcion' => 'Equipo de Presupuesto', 'estado' => 1],
                30 => ['nombre_equipo' => 'PLANIFICACIÓN', 'are_id' => 5, 'siglas' => 'PLAN', 'descripcion' => 'Equipo de Planificación', 'estado' => 1],
                31 => ['nombre_equipo' => 'ESTADÍSTICA', 'are_id' => 5, 'siglas' => 'EST', 'descripcion' => 'Equipo de Estadística Institucional', 'estado' => 1],
                32 => ['nombre_equipo' => 'RACIONALIZACIÓN', 'are_id' => 5, 'siglas' => 'RAC', 'descripcion' => 'Equipo de Racionalización', 'estado' => 1],
                33 => ['nombre_equipo' => 'ACCESO Y SIAGIE', 'are_id' => 5, 'siglas' => 'ACC', 'descripcion' => 'Acceso y Soporte al SIAGIE', 'estado' => 1],
                38 => ['nombre_equipo' => 'SECRETARÍA DE AGI', 'are_id' => 5, 'siglas' => 'SAGI', 'descripcion' => 'Secretaría de Gestión Institucional', 'estado' => 1],
            ];

            foreach ($equipos as $id => $data) {
                DB::table('equipo')->updateOrInsert(['id' => $id], $data);
            }

            // =========================================================================
            // 3. POBLAR TABLA `subequipo` (Nivel 3 - Subequipos u Oficinas Operativas)
            // =========================================================================
            // equipo_id apunta a `equipo.id`
            $subequipos = [
                // En Recursos Humanos (equipo_id: 1)
                1  => ['nombre_subequipo' => 'INFORMÁTICA', 'equipo_id' => 1, 'siglas' => 'INFO', 'descripcion' => 'Oficina / Subequipo de Informática y Sistemas', 'estado' => 1],
                2  => ['nombre_subequipo' => 'REMUNERACIONES', 'equipo_id' => 1, 'siglas' => 'REM', 'descripcion' => 'Subequipo de Remuneraciones y Planillas', 'estado' => 1],
                3  => ['nombre_subequipo' => 'PENSIONES', 'equipo_id' => 1, 'siglas' => 'PEN', 'descripcion' => 'Subequipo de Pensiones y Jubilaciones', 'estado' => 1],
                4  => ['nombre_subequipo' => 'ESCALAFÓN', 'equipo_id' => 1, 'siglas' => 'ESC', 'descripcion' => 'Subequipo de Escalafón del Personal', 'estado' => 1],
                5  => ['nombre_subequipo' => 'CONTROL DE PERSONAL', 'equipo_id' => 1, 'siglas' => 'CP', 'descripcion' => 'Subequipo de Control de Asistencia y Personal', 'estado' => 1],
                6  => ['nombre_subequipo' => 'BIENESTAR SOCIAL', 'equipo_id' => 1, 'siglas' => 'BS', 'descripcion' => 'Subequipo de Bienestar Social y Salud Ocupacional', 'estado' => 1],
                7  => ['nombre_subequipo' => 'PROYECCIONES', 'equipo_id' => 1, 'siglas' => 'PROY', 'descripcion' => 'Subequipo de Proyecciones de Planilla', 'estado' => 1],
                8  => ['nombre_subequipo' => 'NEXUS', 'equipo_id' => 1, 'siglas' => 'NEXUS', 'descripcion' => 'Sistema de Administración y Control de Plazas NEXUS', 'estado' => 1],

                // En Trámite Documentario (equipo_id: 14)
                10 => ['nombre_subequipo' => 'MESA DE PARTES', 'equipo_id' => 14, 'siglas' => 'MP', 'descripcion' => 'Mesa de Partes Físico y Virtual de UGELAA', 'estado' => 1],
                11 => ['nombre_subequipo' => 'ARCHIVO CENTRAL Y ACCESO A LA INFORMACIÓN', 'equipo_id' => 14, 'siglas' => 'ARCH', 'descripcion' => 'Archivo General y Transparencia Pública', 'estado' => 1],
                12 => ['nombre_subequipo' => 'ACTAS, CERTIFICADOS Y TÍTULOS', 'equipo_id' => 14, 'siglas' => 'ACT', 'descripcion' => 'Emisión de Actas, Certificados de Estudio y Títulos', 'estado' => 1],
                13 => ['nombre_subequipo' => 'REGISTRO Y DISTRIBUCIÓN DE RESOLUCIONES', 'equipo_id' => 14, 'siglas' => 'RDR', 'descripcion' => 'Registro Oficial y Despacho de Resoluciones Directorales', 'estado' => 1],

                // En Tesorería (equipo_id: 4)
                20 => ['nombre_subequipo' => 'CAJA', 'equipo_id' => 4, 'siglas' => 'CAJA', 'descripcion' => 'Ventanilla de Pagos e Ingresos de Caja', 'estado' => 1],
                21 => ['nombre_subequipo' => 'CONSTANCIA DE PAGOS', 'equipo_id' => 4, 'siglas' => 'CPAG', 'descripcion' => 'Emisión de Constancias y Boletas de Pago', 'estado' => 1],

                // En Abastecimiento (equipo_id: 5)
                30 => ['nombre_subequipo' => 'ALMACÉN CENTRAL', 'equipo_id' => 5, 'siglas' => 'ALM', 'descripcion' => 'Recepción, Custodia y Despacho de Bienes y Materiales', 'estado' => 1],
                31 => ['nombre_subequipo' => 'CONTROL PATRIMONIAL', 'equipo_id' => 5, 'siglas' => 'PAT', 'descripcion' => 'Control de Bienes Patrimoniales y Margesí', 'estado' => 1],

                // En Infraestructura (equipo_id: 6)
                40 => ['nombre_subequipo' => 'MANTENIMIENTO DE LOCALES ESCOLARES', 'equipo_id' => 6, 'siglas' => 'PMIIEE', 'descripcion' => 'Programa de Mantenimiento de Infraestructura de II.EE.', 'estado' => 1],
            ];

            foreach ($subequipos as $id => $data) {
                DB::table('subequipo')->updateOrInsert(['id' => $id], $data);
            }

            // =========================================================================
            // 4. SINCRONIZAR LA TABLA MAESTRA `dominio` (Llave Universal y Compatibilidad)
            // =========================================================================

            // A) Asegurar Áreas en dominio (domi_tipo = 'AREA')
            $areasDominio = [
                1 => ['domi_idpadre' => 0, 'domi_tipo' => 'AREA', 'domi_nombre' => 'GESTION ADMINISTRATIVA', 'domi_siglas' => 'OGA', 'domi_idarea' => 1, 'domi_idarea_str' => 'GESTION ADMINISTRATIVA', 'domi_idequipo' => 0, 'domi_idequipo_str' => ''],
                2 => ['domi_idpadre' => 0, 'domi_tipo' => 'AREA', 'domi_nombre' => 'ORGANO DE CONTROL INSTITUCIONAL', 'domi_siglas' => 'OCI', 'domi_idarea' => 2, 'domi_idarea_str' => 'ORGANO DE CONTROL INSTITUCIONAL', 'domi_idequipo' => 0, 'domi_idequipo_str' => ''],
                3 => ['domi_idpadre' => 0, 'domi_tipo' => 'AREA', 'domi_nombre' => 'ASESORIA JURÍDICA', 'domi_siglas' => 'OAJ', 'domi_idarea' => 3, 'domi_idarea_str' => 'ASESORIA JURÍDICA', 'domi_idequipo' => 0, 'domi_idequipo_str' => ''],
                4 => ['domi_idpadre' => 0, 'domi_tipo' => 'AREA', 'domi_nombre' => 'GESTIÓN PEDAGÓGICA', 'domi_siglas' => 'AGP', 'domi_idarea' => 4, 'domi_idarea_str' => 'GESTIÓN PEDAGÓGICA', 'domi_idequipo' => 0, 'domi_idequipo_str' => ''],
                5 => ['domi_idpadre' => 0, 'domi_tipo' => 'AREA', 'domi_nombre' => 'GESTIÓN INSTITUCIONAL', 'domi_siglas' => 'AGI', 'domi_idarea' => 5, 'domi_idarea_str' => 'GESTIÓN INSTITUCIONAL', 'domi_idequipo' => 0, 'domi_idequipo_str' => ''],
                6 => ['domi_idpadre' => 0, 'domi_tipo' => 'AREA', 'domi_nombre' => 'DIRECCIÓN', 'domi_siglas' => 'DIR', 'domi_idarea' => 6, 'domi_idarea_str' => 'DIRECCIÓN', 'domi_idequipo' => 0, 'domi_idequipo_str' => ''],
                7 => ['domi_idpadre' => 0, 'domi_tipo' => 'AREA', 'domi_nombre' => 'COMISIONES', 'domi_siglas' => 'COM', 'domi_idarea' => 7, 'domi_idarea_str' => 'COMISIONES', 'domi_idequipo' => 0, 'domi_idequipo_str' => ''],
            ];

            foreach ($areasDominio as $id => $d) {
                DB::table('dominio')->updateOrInsert(['id' => $id], array_merge($d, [
                    'domi_descripcion' => 'Órgano de Nivel 1 (Área)',
                    'domi_responsable' => '',
                    'domi_idsubequipo' => null,
                ]));
            }

            // B) Sincronizar Equipos en dominio (domi_tipo = 'EQUIPO')
            // Se actualizan o insertan respetando sus IDs originales
            $equiposDominio = [
                8  => ['padre' => 1, 'nom' => 'ABASTECIMIENTO', 'siglas' => 'ABAST', 'idarea' => 1, 'idequipo' => 5, 'area_str' => 'GESTION ADMINISTRATIVA'],
                9  => ['padre' => 1, 'nom' => 'CONTABILIDAD', 'siglas' => 'CONT', 'idarea' => 1, 'idequipo' => 3, 'area_str' => 'GESTION ADMINISTRATIVA'],
                10 => ['padre' => 1, 'nom' => 'RECURSOS HUMANOS', 'siglas' => 'RRHH', 'idarea' => 1, 'idequipo' => 1, 'area_str' => 'GESTION ADMINISTRATIVA'],
                11 => ['padre' => 1, 'nom' => 'INFRAESTRUCTURA', 'siglas' => 'INFRA', 'idarea' => 1, 'idequipo' => 6, 'area_str' => 'GESTION ADMINISTRATIVA'],
                12 => ['padre' => 1, 'nom' => 'TESORERIA', 'siglas' => 'TES', 'idarea' => 1, 'idequipo' => 4, 'area_str' => 'GESTION ADMINISTRATIVA'],
                13 => ['padre' => 2, 'nom' => 'CONTROL INTERNO', 'siglas' => 'CI', 'idarea' => 2, 'idequipo' => 13, 'area_str' => 'ORGANO DE CONTROL INSTITUCIONAL'],
                14 => ['padre' => 6, 'nom' => 'TRÁMITE DOCUMENTARIO', 'siglas' => 'TD', 'idarea' => 6, 'idequipo' => 14, 'area_str' => 'DIRECCIÓN'],
                16 => ['padre' => 6, 'nom' => 'IMAGEN INSTITUCIONAL', 'siglas' => 'II', 'idarea' => 6, 'idequipo' => 16, 'area_str' => 'DIRECCIÓN'],
                17 => ['padre' => 6, 'nom' => 'COMISION PROCESOS ADMINISTRATIVOS DOCENTE', 'siglas' => 'CPADD', 'idarea' => 6, 'idequipo' => 17, 'area_str' => 'DIRECCIÓN'],
                18 => ['padre' => 3, 'nom' => 'ASESORIA JURÍDICA (LEGAL)', 'siglas' => 'ALEG', 'idarea' => 3, 'idequipo' => 18, 'area_str' => 'ASESORIA JURÍDICA'],
                20 => ['padre' => 4, 'nom' => 'EDUCACIÓN INICIAL', 'siglas' => 'EI', 'idarea' => 4, 'idequipo' => 20, 'area_str' => 'GESTIÓN PEDAGÓGICA'],
                21 => ['padre' => 4, 'nom' => 'EDUCACIÓN PRIMARIA', 'siglas' => 'EP', 'idarea' => 4, 'idequipo' => 21, 'area_str' => 'GESTIÓN PEDAGÓGICA'],
                22 => ['padre' => 4, 'nom' => 'EDUCACIÓN SECUNDARIA', 'siglas' => 'ES', 'idarea' => 4, 'idequipo' => 22, 'area_str' => 'GESTIÓN PEDAGÓGICA'],
                23 => ['padre' => 4, 'nom' => 'EDUCACIÓN INTERCULTURAL BILINGUE', 'siglas' => 'EIB', 'idarea' => 4, 'idequipo' => 23, 'area_str' => 'GESTIÓN PEDAGÓGICA'],
                24 => ['padre' => 4, 'nom' => 'TOE Y CONVIVENCIA ESCOLAR', 'siglas' => 'TOE', 'idarea' => 4, 'idequipo' => 24, 'area_str' => 'GESTIÓN PEDAGÓGICA'],
                25 => ['padre' => 4, 'nom' => 'DITE (TECNOLOGÍAS DE INFORMACIÓN)', 'siglas' => 'DITE', 'idarea' => 4, 'idequipo' => 25, 'area_str' => 'GESTIÓN PEDAGÓGICA'],
                26 => ['padre' => 4, 'nom' => 'PRONOEI', 'siglas' => 'PRONOEI', 'idarea' => 4, 'idequipo' => 26, 'area_str' => 'GESTIÓN PEDAGÓGICA'],
                28 => ['padre' => 5, 'nom' => 'PREVAED', 'siglas' => 'PREVAED', 'idarea' => 5, 'idequipo' => 28, 'area_str' => 'GESTIÓN INSTITUCIONAL'],
                29 => ['padre' => 5, 'nom' => 'PRESUPUESTO', 'siglas' => 'PPTO', 'idarea' => 5, 'idequipo' => 29, 'area_str' => 'GESTIÓN INSTITUCIONAL'],
                30 => ['padre' => 5, 'nom' => 'PLANIFICACIÓN', 'siglas' => 'PLAN', 'idarea' => 5, 'idequipo' => 30, 'area_str' => 'GESTIÓN INSTITUCIONAL'],
                31 => ['padre' => 5, 'nom' => 'ESTADISTICA', 'siglas' => 'EST', 'idarea' => 5, 'idequipo' => 31, 'area_str' => 'GESTIÓN INSTITUCIONAL'],
                32 => ['padre' => 5, 'nom' => 'RACIONALIZACIÓN', 'siglas' => 'RAC', 'idarea' => 5, 'idequipo' => 32, 'area_str' => 'GESTIÓN INSTITUCIONAL'],
                33 => ['padre' => 5, 'nom' => 'ACCESO Y SIAGIE', 'siglas' => 'ACC', 'idarea' => 5, 'idequipo' => 33, 'area_str' => 'GESTIÓN INSTITUCIONAL'],
                34 => ['padre' => 6, 'nom' => 'SECRETARIA TECNICA SERVIR - CEPAT', 'siglas' => 'CEPAT', 'idarea' => 6, 'idequipo' => 34, 'area_str' => 'DIRECCIÓN'],
                35 => ['padre' => 4, 'nom' => 'EDUCACIÓN SUPERIOR', 'siglas' => 'ESUP', 'idarea' => 4, 'idequipo' => 35, 'area_str' => 'GESTIÓN PEDAGÓGICA'],
                38 => ['padre' => 5, 'nom' => 'SECRETARIA DE AGI', 'siglas' => 'SAGI', 'idarea' => 5, 'idequipo' => 38, 'area_str' => 'GESTIÓN INSTITUCIONAL'],
                39 => ['padre' => 6, 'nom' => 'SECRETARIA DE DIRECCIÓN', 'siglas' => 'SDIR', 'idarea' => 6, 'idequipo' => 39, 'area_str' => 'DIRECCIÓN'],
                40 => ['padre' => 1, 'nom' => 'SECRETARIA DE ADMINISTRACIÓN', 'siglas' => 'SADM', 'idarea' => 1, 'idequipo' => 7, 'area_str' => 'GESTION ADMINISTRATIVA'],
                42 => ['padre' => 3, 'nom' => 'SECRETARIA DE ASESORÍA JURÍDICA', 'siglas' => 'SAJ', 'idarea' => 3, 'idequipo' => 42, 'area_str' => 'ASESORIA JURÍDICA'],
                93 => ['padre' => 2, 'nom' => 'AUDITORES', 'siglas' => 'AUD', 'idarea' => 2, 'idequipo' => 93, 'area_str' => 'ORGANO DE CONTROL INSTITUCIONAL'],
            ];

            foreach ($equiposDominio as $id => $eq) {
                DB::table('dominio')->updateOrInsert(['id' => $id], [
                    'domi_idpadre'      => $eq['padre'],
                    'domi_tipo'         => 'EQUIPO',
                    'domi_nombre'       => $eq['nom'],
                    'domi_siglas'       => $eq['siglas'],
                    'domi_descripcion'  => 'Equipo de Nivel 2 dependiente de ' . $eq['area_str'],
                    'domi_responsable'  => '',
                    'domi_idarea'       => $eq['idarea'],
                    'domi_idarea_str'   => $eq['area_str'],
                    'domi_idequipo'     => $eq['idequipo'],
                    'domi_idequipo_str' => $eq['nom'],
                    'domi_idsubequipo'  => null,
                ]);
            }

            // C) Sincronizar Subequipos en dominio (domi_tipo = 'SUBEQUIPO')
            // Notar que ID 82 es INFORMÁTICA (vital para usuario 40)
            // e ID 19 se unifica como PENSIONES (eliminando la duplicidad con Remuneraciones e Informática)
            $subequiposDominio = [
                19 => ['padre' => 10, 'nom' => 'PENSIONES', 'siglas' => 'PEN', 'idarea' => 1, 'area_str' => 'GESTION ADMINISTRATIVA', 'idequipo' => 1, 'equipo_str' => 'RECURSOS HUMANOS', 'idsub' => 3],
                71 => ['padre' => 8,  'nom' => 'CONTROL PATRIMONIAL', 'siglas' => 'PAT', 'idarea' => 1, 'area_str' => 'GESTION ADMINISTRATIVA', 'idequipo' => 5, 'equipo_str' => 'ABASTECIMIENTO', 'idsub' => 31],
                72 => ['padre' => 8,  'nom' => 'ALMACÉN CENTRAL', 'siglas' => 'ALM', 'idarea' => 1, 'area_str' => 'GESTION ADMINISTRATIVA', 'idequipo' => 5, 'equipo_str' => 'ABASTECIMIENTO', 'idsub' => 30],
                73 => ['padre' => 10, 'nom' => 'PROYECCIONES', 'siglas' => 'PROY', 'idarea' => 1, 'area_str' => 'GESTION ADMINISTRATIVA', 'idequipo' => 1, 'equipo_str' => 'RECURSOS HUMANOS', 'idsub' => 7],
                74 => ['padre' => 12, 'nom' => 'CAJA', 'siglas' => 'CAJA', 'idarea' => 1, 'area_str' => 'GESTION ADMINISTRATIVA', 'idequipo' => 4, 'equipo_str' => 'TESORERIA', 'idsub' => 20],
                75 => ['padre' => 10, 'nom' => 'CONTROL DE PERSONAL', 'siglas' => 'CP', 'idarea' => 1, 'area_str' => 'GESTION ADMINISTRATIVA', 'idequipo' => 1, 'equipo_str' => 'RECURSOS HUMANOS', 'idsub' => 5],
                76 => ['padre' => 10, 'nom' => 'ESCALAFÓN', 'siglas' => 'ESC', 'idarea' => 1, 'area_str' => 'GESTION ADMINISTRATIVA', 'idequipo' => 1, 'equipo_str' => 'RECURSOS HUMANOS', 'idsub' => 4],
                77 => ['padre' => 10, 'nom' => 'BIENESTAR SOCIAL', 'siglas' => 'BS', 'idarea' => 1, 'area_str' => 'GESTION ADMINISTRATIVA', 'idequipo' => 1, 'equipo_str' => 'RECURSOS HUMANOS', 'idsub' => 6],
                78 => ['padre' => 11, 'nom' => 'MANTENIMIENTO DE LOCALES ESCOLARES', 'siglas' => 'PMIIEE', 'idarea' => 1, 'area_str' => 'GESTION ADMINISTRATIVA', 'idequipo' => 6, 'equipo_str' => 'INFRAESTRUCTURA', 'idsub' => 40],
                79 => ['padre' => 12, 'nom' => 'CONSTANCIA DE PAGOS', 'siglas' => 'CPAG', 'idarea' => 1, 'area_str' => 'GESTION ADMINISTRATIVA', 'idequipo' => 4, 'equipo_str' => 'TESORERIA', 'idsub' => 21],
                80 => ['padre' => 14, 'nom' => 'MESA DE PARTES', 'siglas' => 'MP', 'idarea' => 6, 'area_str' => 'DIRECCIÓN', 'idequipo' => 14, 'equipo_str' => 'TRÁMITE DOCUMENTARIO', 'idsub' => 10],
                81 => ['padre' => 10, 'nom' => 'REMUNERACIONES', 'siglas' => 'REM', 'idarea' => 1, 'area_str' => 'GESTION ADMINISTRATIVA', 'idequipo' => 1, 'equipo_str' => 'RECURSOS HUMANOS', 'idsub' => 2],
                82 => ['padre' => 10, 'nom' => 'INFORMÁTICA', 'siglas' => 'INFO', 'idarea' => 1, 'area_str' => 'GESTION ADMINISTRATIVA', 'idequipo' => 1, 'equipo_str' => 'RECURSOS HUMANOS', 'idsub' => 1],
                83 => ['padre' => 14, 'nom' => 'ACTAS, CERTIFICADOS Y TITULOS', 'siglas' => 'ACT', 'idarea' => 6, 'area_str' => 'DIRECCIÓN', 'idequipo' => 14, 'equipo_str' => 'TRÁMITE DOCUMENTARIO', 'idsub' => 12],
                84 => ['padre' => 14, 'nom' => 'ARCHIVO CENTRAL Y ACCESO A LA INFORMACION', 'siglas' => 'ARCH', 'idarea' => 6, 'area_str' => 'DIRECCIÓN', 'idequipo' => 14, 'equipo_str' => 'TRÁMITE DOCUMENTARIO', 'idsub' => 11],
                86 => ['padre' => 14, 'nom' => 'REGISTRO Y DISTRIBUCION DE RESOLUCIONES', 'siglas' => 'RDR', 'idarea' => 6, 'area_str' => 'DIRECCIÓN', 'idequipo' => 14, 'equipo_str' => 'TRÁMITE DOCUMENTARIO', 'idsub' => 13],
                94 => ['padre' => 10, 'nom' => 'NEXUS', 'siglas' => 'NEXUS', 'idarea' => 1, 'area_str' => 'GESTION ADMINISTRATIVA', 'idequipo' => 1, 'equipo_str' => 'RECURSOS HUMANOS', 'idsub' => 8],
            ];

            foreach ($subequiposDominio as $id => $se) {
                DB::table('dominio')->updateOrInsert(['id' => $id], [
                    'domi_idpadre'      => $se['padre'],
                    'domi_tipo'         => 'SUBEQUIPO',
                    'domi_nombre'       => $se['nom'],
                    'domi_siglas'       => $se['siglas'],
                    'domi_descripcion'  => 'Oficina / Subequipo de Nivel 3 dependiente de ' . $se['equipo_str'],
                    'domi_responsable'  => '',
                    'domi_idarea'       => $se['idarea'],
                    'domi_idarea_str'   => $se['area_str'],
                    'domi_idequipo'     => $se['idequipo'],
                    'domi_idequipo_str' => $se['equipo_str'],
                    'domi_idsubequipo'  => $se['idsub'],
                ]);
            }

            // Inactivar clones redundantes del sistema antiguo (15: Administración duplicado, 36/87: Convivencia duplicado, 37/88: Gestión Pedagógica duplicado)
            DB::table('dominio')->whereIn('id', [15, 36, 37, 85, 87, 88, 89, 90])->update([
                'domi_tipo' => 'INACTIVO',
                'domi_descripcion' => 'Registro duplicado del sistema anterior (deprecado)',
            ]);

            // D) Clasificar las CONVOCATORIAS CAS históricas (IDs 91 al 127) para no borrarlas pero aislarlas
            DB::table('dominio')
                ->whereBetween('id', [91, 127])
                ->whereNotIn('id', [93, 94]) // 93 es Auditores y 94 es Nexus
                ->update([
                    'domi_tipo'        => 'CONVOCATORIA',
                    'domi_siglas'      => 'CAS',
                    'domi_descripcion' => 'Proceso de Selección / Convocatoria CAS (Histórico UGELAA)',
                ]);

            // E) Asegurar USUARIO VIRTUAL en ID 128
            DB::table('dominio')->updateOrInsert(['id' => 128], [
                'domi_idpadre'      => 0,
                'domi_tipo'         => 'EXTERNO',
                'domi_nombre'       => 'USUARIO VIRTUAL',
                'domi_siglas'       => 'UV',
                'domi_descripcion'  => 'Dominio asignado a ciudadanos y usuarios de Trámite Virtual',
                'domi_responsable'  => '',
                'domi_idarea'       => 0,
                'domi_idarea_str'   => 'EXTERNO',
                'domi_idequipo'     => 0,
                'domi_idequipo_str' => '',
                'domi_idsubequipo'  => null,
            ]);
        });
    }
}
