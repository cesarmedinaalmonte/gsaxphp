<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Docente\Docente;
use AppBundle\Form\Docente\DocenteType;
use AppBundle\Utils\Rest\RestResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DocenteController
 * @package AppBundle\Controller
 *
 * @Route("/docentes")
 */
class DocenteController extends Controller
{
    /**
     * @Route("/", name="lista_docentes", methods={"GET"})
     * @return JsonResponse
     */
    public function indexAction(){
        $docentes = $this->getDoctrine()->getRepository(Docente::class)->findBy([],['nombre' => 'asc']);
        return new RestResponse($this->get('jms_serializer')->toArray($docentes));
    }

    /**
     * @Route("/{id}", name="docente_by_id", methods={"GET"}, requirements={"id"="\d+"})
     * @return JsonResponse
     */
    public function getByIdAction($id){
        $docente = $this->getDoctrine()->getRepository(Docente::class)->find($id);
        if (!$docente)
            return new RestResponse(null, 404, "Docente no encontrado.");

        return new JsonResponse($this->get('jms_serializer')->toArray($docente));
    }

    /**
     * @Route("/", name="crear_docente", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request){
        $data = json_decode($request->getContent(), true);

        $docente = new Docente();

        $form = $this->createForm(DocenteType::class, $docente);
        $form->submit($data);
        if (!$form->isValid())
            return new RestResponse(null,400,"Error en formulario", $form);

        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($docente);
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($docente), 201);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error creando el docente.");
        }
    }

    /**
     * @Route("/{id}", methods={"PUT"}, name="actualizar_docente", requirements={"id"="\d+"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAction(Request $request, $id){

        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();

        $docente = $em->getRepository(Docente::class)->find($id);
        if (!$docente)
            return new RestResponse(null,404,"Docente no encontrado.");

        $form = $this->createForm(DocenteType::class, $docente);
        $form->submit($data);
        if (!$form->isValid())
            return new RestResponse(null,400,"Error en formulario", $form);

        try{
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($docente), 200);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error actualizando el docente.");
        }
    }
}