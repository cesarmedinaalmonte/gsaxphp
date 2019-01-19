<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Curso\Curso;
use AppBundle\Entity\Docente\Docente;
use AppBundle\Entity\Estudiante\Estudiante;
use AppBundle\Entity\Materia\Materia;
use AppBundle\Utils\Rest\RestResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController
 * @package AppBundle\Controller
 *
 * @Route("/dashboard")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="resumen_dashboard")
     */
    public function getDashboardResume(){

        $em = $this->getDoctrine()->getManager();

        $cursos = $em->getRepository(Curso::class)->totalCursos();
        $docentes = $em->getRepository(Docente::class)->totalDocentes();
        $estudiantes = $em->getRepository(Estudiante::class)->totalEstudiantes();
        $materias = $em->getRepository(Materia::class)->totalMaterias();

        $response = array('cursos' => $cursos, 'docentes' => $docentes, 'estudiantes' => $estudiantes, 'materias' => $materias);

        return new RestResponse($response);
    }

}