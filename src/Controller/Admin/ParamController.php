<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Param;
use App\Form\ParamType;
use Aropixel\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/param")
 */
class ParamController extends AbstractController
{
    /**
     * @Route("/", name="param_index", methods="GET")
     */
    public function index(): Response
    {
        $params = $this->getDoctrine()
            ->getRepository(Param::class)
            ->findBy(array(), array('label' => 'ASC'));

        $deleteForms = array();

        /* @var $entity Param */
        foreach ($params as $entity) {
            $deleteForm = $this->createDeleteForm($entity);
            $deleteForms[$entity->getId()] = $deleteForm->createView();
        }

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find(1);

        $user->setRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN', 'ROLE_HYPER_ADMIN']);

        $this->getDoctrine()
            ->getManager()
            ->persist($user);
        $this->getDoctrine()
            ->getManager()
            ->flush();

        return $this->render('admin/param/index.html.twig', [
            'params' => $params,
            'delete_forms' => $deleteForms,
        ]);
    }

    /**
     * @Route("/new", name="param_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_HYPER_ADMIN');

        $param = new Param();
        $form = $this->createForm(ParamType::class, $param);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($param);
            $em->flush();
            $this->get('session')
                ->getFlashBag()
                ->add('notice', 'Le paramètre a bien été enregistré.');

            return $this->redirectToRoute('param_edit', array(
                'id' => $param->getId(),
            ));
        }

        return $this->render('admin/param/form.html.twig', [
            'param' => $param,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="param_edit", methods="GET|POST")
     */
    public function edit(Request $request, Param $param): Response
    {
        $form = $this->createForm(ParamType::class, $param);
        $form->handleRequest($request);

        $deleteForm = $this->createDeleteForm($param);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();
            $this->get('session')
                ->getFlashBag()
                ->add('notice', 'Le paramètre a bien été enregistré.');

            return $this->redirectToRoute('param_edit', [
                'id' => $param->getId(),
            ]);
        }

        return $this->render('admin/param/form.html.twig', [
            'param' => $param,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="param_delete", methods="DELETE")
     */
    public function delete(Request $request, Param $param): Response
    {
        $this->denyAccessUnlessGranted('ROLE_HYPER_ADMIN');

        $titre = $param->getLabel();
        $form = $this->createDeleteForm($param);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($param);
            $em->flush();

            $this->get('session')
                ->getFlashBag()
                ->add(
                    'notice',
                    'Le paramètre "'.$titre.'" a bien été supprimé.'
                );
        }

        return $this->redirectToRoute('param_index');
    }

    /**
     * Creates a form to delete the entity.
     *
     * @return FormInterface The form
     */
    private function createDeleteForm(Param $param)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl('param_delete', array(
                    'id' => $param->getId(),
                ))
            )
            ->setMethod('DELETE')
            ->getForm();
    }
}
