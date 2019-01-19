<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Curso\Curso;
use AppBundle\Entity\Curso\CursoMateria;
use AppBundle\Entity\Docente\Docente;
use AppBundle\Entity\Materia\Materia;
use AppBundle\Form\Curso\CursoType;
use AppBundle\Utils\Rest\RestResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CursoController
 * @package AppBundle\Controller
 *
 * @Route("/cursos")
 */
class CursoController extends Controller
{
    /**
     * @Route("/", name="lista_cursos", methods={"GET"})
     * @return JsonResponse
     */
    public function indexAction(){
        $cursos = $this->getDoctrine()->getRepository(Curso::class)->findBy([],['nombre' => 'asc']);
        return new JsonResponse($this->get('jms_serializer')->toArray($cursos));
    }

    /**
     * @Route("/{id}", name="lista_cursos_by_id", methods={"GET"}, requirements={"id"="\d+"})
     * @return JsonResponse
     */
    public function getByIdAction($id){
        $curso = $this->getDoctrine()->getRepository(Curso::class)->find($id);
        if (!$curso)
            return new RestResponse(null, 404, "Curso no encontrado.");

        return new JsonResponse($this->get('jms_serializer')->toArray($curso));
    }

    /**
     * @Route("/niveles", name="lista_cursos_niveles", methods={"GET"})
     * @return JsonResponse
     */
    public function nivelesAction(){
        return new JsonResponse(Curso::NIVELES);
    }

    /**
     * @Route("/secciones", name="lista_cursos_secciones", methods={"GET"})
     * @return JsonResponse
     */
    public function seccionesAction(){
        return new JsonResponse(Curso::SECCIONES);
    }

    /**
     * @Route("/", name="crear_curso", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request){
        $data = json_decode($request->getContent(), true);

        $curso = new Curso();

        $form = $this->createForm(CursoType::class, $curso);
        $form->submit($data);
        if (!$form->isValid())
            return new RestResponse(null,400,"Error en formulario", $form);

        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($curso);
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($curso), 201);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error creando el curso.");
        }
    }

    /**
     * @Route("/{id}", name="actualizar_curso", methods={"PUT"}, requirements={"id"="\d+"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAction(Request $request, $id){

        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();

        $curso = $em->getRepository(Curso::class)->find($id);
        if (!$curso)
            return new RestResponse(null,404,"Curso no encontrado.");

        $form = $this->createForm(CursoType::class, $curso);
        $form->submit($data);
        if (!$form->isValid())
            return new RestResponse(null,400,"Error en formulario", $form);

        try{
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($curso), 200);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error actualizando el curso.");
        }
    }

    /**
     * @Route("/{cursoId}/curso-materia", name="curso_materia_agregar", methods={"POST"}, requirements={"cursoId"="\d+"})
     * @param Request $request
     * @param $id
     * @return RestResponse
     */
    public function addCursoMateria(Request $request, $cursoId)
    {

        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();

        /** @var Curso $curso */
        $curso = $em->getRepository(Curso::class)->find($cursoId);
        if (!$curso)
            return new RestResponse(null, 404, "Curso no encontrado.");

        if (!array_key_exists('materia', $data) || !is_array($data['materia'])) {
            return new RestResponse(null, 400, "La materia es requerida.");
        } elseif (!array_key_exists('docente', $data) || !is_array($data['docente'])) {
            return new RestResponse(null, 400, "El docente es requerido.");
        }

        $materiaId = $data['materia']['id'];
        $docenteId = $data['docente']['id'];

        /** @var Materia $materia */
        $materia = $em->getRepository(Materia::class)->find($materiaId);
        if (!$materia)
            return new RestResponse(null, 404, "Materia no encontrada.");

        /** @var Docente $docente */
        $docente = $em->getRepository(Docente::class)->find($docenteId);
        if (!$docente)
            return new RestResponse(null, 404, "Docente no encontrado.");

        /** @var CursoMateria $cm */
        foreach ($curso->getMaterias() as $cm) {
            if ($cm->getMateria() === $materia)
                return new RestResponse(null, 400, "El curso tiene agregado la materia {$materia->getNombre()}");

            if ($cm->getDocente() === $docente && $cm->getMateria() === $materia)
                return new RestResponse(null, 400, "Registro duplicado. {$materia->getNombre()}");
        }

        $cm = new CursoMateria();
        $cm->setCurso($curso);
        $cm->setMateria($materia);
        $cm->setDocente($docente);
        $curso->addMateria($cm);

        try{
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($cm), 201);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error agregando el curso materia.");
        }

    }

    /**
     * @Route("/{id}/curso-materia/{cmId}", name="curso_materia_actualizar", methods={"PUT"}, requirements={"id"="\d+", "cmId"="\d+"})
     * @param Request $request
     * @param $id
     * @return RestResponse
     */
    public function updateCursoMateria(Request $request, $id, $cmId)
    {

        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();

        /** @var Curso $curso */
        $curso = $em->getRepository(Curso::class)->find($id);
        if (!$curso)
            return new RestResponse(null, 404, "Curso no encontrado.");

        /** @var CursoMateria $cm */
        $cm = $em->getRepository(CursoMateria::class)->find($cmId);
        if (!$cm)
            return new RestResponse(null, 404, "Curso Materia no encontrado.");

        if (!array_key_exists('materia', $data) || !is_array($data['materia'])) {
            return new RestResponse(null, 400, "La materia es requerida.");
        } elseif (!array_key_exists('docente', $data) || !is_array($data['docente'])) {
            return new RestResponse(null, 400, "El docente es requerido.");
        }

        $materiaId = $data['materia']['id'];
        $docenteId = $data['docente']['id'];

        /** @var Materia $materia */
        $materia = $em->getRepository(Materia::class)->find($materiaId);
        if (!$materia)
            return new RestResponse(null, 404, "Materia no encontrada.");

        /** @var Docente $docente */
        $docente = $em->getRepository(Docente::class)->find($docenteId);
        if (!$docente)
            return new RestResponse(null, 404, "Docente no encontrado.");

        /** @var CursoMateria $cm */
        foreach ($curso->getMaterias() as $cursomateria) {
            if ($cursomateria->getDocente() === $docente && $cursomateria->getMateria() === $materia)
                return new RestResponse(null, 400, "Registro duplicado. {$materia->getNombre()}");
        }

        $cm->setMateria($materia);
        $cm->setDocente($docente);

        try{
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($cm), 200);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error actualizando el curso materia.");
        }

    }

    /**
     * @Route("/{id}/curso-materia/{cmId}", name="curso_materia_delete", methods={"DELETE"}, requirements={"id"="\d+", "cmId"="\d+"})
     * @param Request $request
     * @param $id
     * @return RestResponse
     */
    public function deleteCursoMateria(Request $request, $id, $cmId)
    {

        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();

        /** @var Curso $curso */
        $curso = $em->getRepository(Curso::class)->find($id);
        if (!$curso)
            return new RestResponse(null, 404, "Curso no encontrado.");

        /** @var CursoMateria $cm */
        $cm = $em->getRepository(CursoMateria::class)->find($cmId);
        if (!$cm)
            return new RestResponse(null, 404, "Curso Materia no encontrado.");

        try{
            $curso->removeMateria($cm);
            $em->remove($cm);
            $em->flush();
            return new RestResponse(null, 200, "Registro borrado.");
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error actualizando el curso materia.");
        }

    }

}