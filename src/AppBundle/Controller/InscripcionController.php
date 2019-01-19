<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Curso\Curso;
use AppBundle\Entity\Estudiante\Estudiante;
use AppBundle\Entity\Inscripcion\Inscripcion;
use AppBundle\Entity\Periodo\Periodo;
use AppBundle\Utils\Rest\RestResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class InscripcionController
 * @package AppBundle\Controller
 *
 * @Route("inscripciones")
 */
class InscripcionController extends Controller
{

    public function indexAction()
    {

    }

    /**
     * @Route("/actualPorCurso/{cursoId}", name="active_inscripciones_por_curso", methods={"GET"}, requirements={"cursoId"="\d+"})
     * @param $cursoId
     * @return RestResponse
     */
    public function getCurrentByCursoAction($cursoId)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Curso $curso */
        $curso = $em->getRepository(Curso::class)->find($cursoId);
        if (!$curso)
            return new RestResponse(null, 404, "Curso no encontrado.");

        /** @var Periodo $periodo */
        $periodo = $em->getRepository(Periodo::class)->findOneBy(array('activo' => true));
        if (!$periodo)
            return new RestResponse(null, 404, "Actualmente no existe un periodo activo.");

        $inscripcion = $em->getRepository(Inscripcion::class)->findOneBy(
            array(
                'curso' => $curso->getId(),
                'periodo' => $periodo->getId(),
            )
        );

        if (!$inscripcion){
            $inscripcion = new Inscripcion();
            $inscripcion->setPeriodo($periodo);
            $inscripcion->setCurso($curso);
            try{
                $em->persist($inscripcion);
                $em->flush();
            }catch (\Exception $exception){
                return new RestResponse(null, 500, "Ocurrio un error procesando la inscripcion..");
            }
        }

        return new RestResponse($this->get('jms_serializer')->toArray($inscripcion), 200);
    }

    /**
     * @Route("/", name="inscripciones_crear", methods={"POST"})
     * @param Request $request
     * @return RestResponse
     */
    public function createAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();

        if (!array_key_exists('periodo', $data) || !is_array($data['periodo'])) {
            return new RestResponse(null, 400, "El periodo es requerido.");
        } elseif (!array_key_exists('curso', $data) || !is_array($data['curso'])) {
            return new RestResponse(null, 400, "El curso es requerido.");
        }

        $periodoId = $data['periodo']['id'];
        $cursoId = $data['curso']['id'];

        /** @var Periodo $periodo */
        $periodo = $em->getRepository(Periodo::class)->find($periodoId);
        if (!$periodo)
            return new RestResponse(null, 404, "Periodo no encontrada.");

        /** @var Curso $curso */
        $curso = $em->getRepository(Curso::class)->find($cursoId);
        if (!$curso)
            return new RestResponse(null, 404, "Curso no encontrado.");

        $inscripcion = $em->getRepository(Inscripcion::class)->findOneBy(
          array(
              'curso' => $curso->getId(),
              'periodo' => $periodo->getId()
          )
        );
        if ($inscripcion)
            return new RestResponse(null, 400, "Inscripcion duplicada.");

        $inscripcion = new Inscripcion();
        $inscripcion->setCurso($curso);
        $inscripcion->setPeriodo($periodo);

        try{
            $em->persist($inscripcion);
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($inscripcion), 201);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error creando la inscripcion.");
        }

    }

    /**
     * @Route("/{id}", name="inscripciones_actualizar", methods={"PUT"},requirements={"id"="\d+"})
     * @param Request $request
     * @return RestResponse
     */
    public function updateAction(Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();

        /** @var Inscripcion $inscripcion */
        $inscripcion = $em->getRepository(Inscripcion::class)->find($id);
        if (!$inscripcion)
            return new RestResponse(null, 400, "Inscripcion no encontrada.");

        if (!array_key_exists('periodo', $data) || !is_array($data['periodo'])) {
            return new RestResponse(null, 400, "El periodo es requerido.");
        } elseif (!array_key_exists('curso', $data) || !is_array($data['curso'])) {
            return new RestResponse(null, 400, "El curso es requerido.");
        }

        $periodoId = $data['periodo']['id'];
        $cursoId = $data['curso']['id'];

        /** @var Periodo $periodo */
        $periodo = $em->getRepository(Periodo::class)->find($periodoId);
        if (!$periodo)
            return new RestResponse(null, 404, "Periodo no encontrada.");

        /** @var Curso $curso */
        $curso = $em->getRepository(Curso::class)->find($cursoId);
        if (!$curso)
            return new RestResponse(null, 404, "Curso no encontrado.");

        // buscar duplicados de inscripcion de alguna forma
        if ($inscripcion->getCurso() !== $curso || $inscripcion->getPeriodo() !== $periodo) {

            $inscripciones = $em->getRepository(Inscripcion::class)->findBy(
                array(
                    'curso' => $curso->getId(),
                    'periodo' => $periodo->getId()
                )
            );

            if (count($inscripciones) > 0){
                // verifica si esta actualizando la misma inscripcion
                if (!(count($inscripciones) == 1 && $inscripciones[0]->getId() == $inscripcion->getId())){
                    return new RestResponse(null, 400, "Inscripcion duplicada.");
                }
            }

        }

        $inscripcion->setCurso($curso);
        $inscripcion->setPeriodo($periodo);

        try{
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($inscripcion), 200);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error actualizando la inscripcion.");
        }

    }

    /**
     * @Route("/{id}/estudiantes/{estudianteId}",
     *     name="inscripciones_agregar_estudiante", methods={"POST"},
     *     requirements={"id"="\d+", "estudianteId"="\d+"}
     * )
     * @param $id
     * @param $estudianteId
     * @return RestResponse
     */
    public function agregarEstudianteAction($id, $estudianteId)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Inscripcion $inscripcion */
        $inscripcion = $em->getRepository(Inscripcion::class)->find($id);
        if (!$inscripcion)
            return new RestResponse(null, 400, "Inscripcion no encontrada.");

        /** @var Estudiante $estudiante */
        $estudiante = $em->getRepository(Estudiante::class)->find($estudianteId);
        if (!$estudiante)
            return new RestResponse(null, 400, "Estudiante no encontrado.");

        if ($inscripcion->getEstudiantes()->contains($estudiante))
            return new RestResponse( null, 400,"El estudiante ya esta inscrito en este curso.");

        $inscripcion->addEstudiante($estudiante);

        try{
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($inscripcion), 200);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error inscribiendo al estudiante.");
        }

    }

    /**
     * @Route("/{id}/estudiantes/{estudianteId}",
     *     name="inscripciones_remover_estudiante", methods={"DELETE"},
     *     requirements={"id"="\d+", "estudianteId"="\d+"}
     * )
     * @param $id
     * @param $estudianteId
     * @return RestResponse
     */
    public function removerEstudianteAction($id, $estudianteId)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Inscripcion $inscripcion */
        $inscripcion = $em->getRepository(Inscripcion::class)->find($id);
        if (!$inscripcion)
            return new RestResponse(null, 400, "Inscripcion no encontrada.");

        /** @var Estudiante $estudiante */
        $estudiante = $em->getRepository(Estudiante::class)->find($estudianteId);
        if (!$estudiante)
            return new RestResponse(null, 400, "Estudiante no encontrado.");

        $inscripcion->removeEstudiante($estudiante);

        try{
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($inscripcion), 200);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error inscribiendo al estudiante.");
        }

    }


}