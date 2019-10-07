<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use Aropixel\AdminBundle\Entity\Publishable;
use Aropixel\AdminBundle\Services\Status;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        $deleteForms = [];
        foreach ($posts as $entity) {
            $deleteForm = $this->createDeleteForm($entity);
            $deleteForms[$entity->getId()] = $deleteForm->createView();
        }

        return $this->render('admin/post/index.html.twig', [
            'posts' => $posts,
            'delete_forms' => $deleteForms,
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $post->setStatus(Publishable::STATUS_OFFLINE);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('notice', 'Le post a bien été enregistré.');

            return $this->redirectToRoute('post_edit', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('admin/post/form.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post): Response
    {
        $em = $this->getDoctrine()->getManager();
        $image = $post->getImage();
        $deleteForm = $this->createDeleteForm($post);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($image && null == $post->getImage()->getImage()) {
                $em->remove($image);
            }
            $this->addFlash('notice', 'Le post a bien été enregistré.');
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('post_edit', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('admin/post/form.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/status", name="post_status", methods={"GET"})
     */
    public function statusAction(Post $post, Status $status)
    {
        return $status->changeStatus($post);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        $titre = $post->getTitle();
        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();

            $this->get('session')
                ->getFlashBag()
                ->add(
                    'notice',
                    'Le post "'.$titre.'" a bien été supprimé.'
                );
        }

        return $this->redirectToRoute('post_index');
    }

    /**
     * Creates a form to delete the entity.
     *
     * @return FormInterface The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction(
                $this->generateUrl('post_delete', ['id' => $post->getId()])
            )
            ->setMethod('DELETE')
            ->getForm();
    }
}
