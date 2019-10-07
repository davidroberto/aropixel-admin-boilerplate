<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Aropixel\AdminBundle\Services\Position;
use Aropixel\AdminBundle\Services\Status;
use App\Entity\PostCategory;
use App\Form\PostCategoryType;
use App\Repository\PostCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog/categorie")
 */
class PostCategoryController extends AbstractController
{
    /**
     * @Route("/", name="post_category_index", methods={"GET"})
     */
    public function index(PostCategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $deleteForms = array();
        foreach ($categories as $entity) {
            $deleteForm = $this->createDeleteForm($entity);
            $deleteForms[$entity->getId()] = $deleteForm->createView();
        }

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'delete_forms' => $deleteForms,
        ]);
    }

    /**
     * @Route("/order", name="post_category_order", methods="GET|POST")
     */
    public function order(Request $request, Position $position)
    {
        if ($request->isXmlHttpRequest()) {
            return $position->updatePosition(PostCategory::class);
        }

        $categories = $this->getDoctrine()
            ->getRepository(PostCategory::class)
            ->findBy(array(), array('position' => 'ASC'));

        return $this->render('admin/category/order.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * @Route("/new", name="post_category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $category = new PostCategory();

        $form = $this->createForm(PostCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('notice', 'La catégorie a bien été enregistrée.');

            return $this->redirectToRoute('post_category_edit', array(
                'id' => $category->getId(),
            ));
        }

        return $this->render('admin/category/form.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/status", name="post_category_status", methods={"GET"})
     */
    public function statusAction(PostCategory $category, Status $status)
    {
        return $status->changeStatus($category);
    }

    /**
     * @Route("/{id}/edit", name="post_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PostCategory $category): Response
    {
        $deleteForm = $this->createDeleteForm($category);
        $form = $this->createForm(PostCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice', 'La catégorie a bien été enregistrée.');
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('post_category_edit', array(
                'id' => $category->getId(),
            ));
        }

        return $this->render('admin/category/form.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PostCategory $category): Response
    {
        $titre = $category->getName();
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();

            $this->get('session')
                ->getFlashBag()
                ->add(
                    'notice',
                    'La catégorie "'.$titre.'" a bien été supprimée.'
                );
        }

        return $this->redirectToRoute('post_category_index');
    }

    /**
     * Creates a form to delete the entity.
     *
     * @return FormInterface The form
     */
    private function createDeleteForm(PostCategory $category)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl('post_category_delete', array(
                    'id' => $category->getId(),
                ))
            )
            ->setMethod('DELETE')
            ->getForm();
    }
}
