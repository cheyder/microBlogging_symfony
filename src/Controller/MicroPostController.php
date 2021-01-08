<?php


namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route("/micro-post")
 */
class MicroPostController extends AbstractController
{
    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(
        MicroPostRepository $microPostRepository,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashBag, 
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @Route("/", name="micro_post_index")
     */
    public function index()
    {
        return $this->render(
            'micro-post/index.html.twig', [
                'posts' => $this->microPostRepository
                    ->findBy([], ['time' => 'DESC'])
            ]);
    }

    /**
     * @Route("/edit/{id}", name="micro_post_edit")
     *
     * @param MicroPost $microPost
     * @param Request $request
     * @return Response
     */
    public function edit(MicroPost $microPost, Request $request)
    {
        if (!$this->authorizationChecker->isGranted('edit', $microPost)) {
            throw new UnauthorizedHttpException();
        }

        // $this->denyAccessUnlessGranted('edit', $microPost);
        
        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirect($this->generateUrl('micro_post_index'));
        }

        return $this->render(
            'micro-post/add.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/add", name="micro_post_add")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @param TokenStorageInterface $tokenStorage
     * @return Response
     */
    public function add(Request $request, TokenStorageInterface $tokenStorage): Response
    {
        $user = $tokenStorage->getToken()->getUser();

        $microPost = new MicroPost();
        $microPost->setUser($user);

        $form = $this->formFactory->create(MicroPostType::class, $microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            return $this->redirect($this->generateUrl('micro_post_index'));
        }

        return $this->render(
            'micro-post/add.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/user/{username}", name="micro_post_user")
     * @param User $userWithPosts
     * @return Response
     */
    public function userPosts(User $userWithPosts): Response
    {
        return $this->render(
            'micro-post/user-posts.html.twig', [
                'posts' => $this->microPostRepository->findBy(
                    ['user' => $userWithPosts],
                    ['time' => 'DESC']
                ),
                'user' => $userWithPosts
        ]);
    }

    /**
     * @Route("/{id}", name="micro_post_post")
     * @param MicroPost $post
     * @return Response
     */
    public function post(MicroPost $post)
    {
        return $this->render(
            'micro-post/post.html.twig',
            ['post' => $post]
        );
    }

    /**
     * @Route("/delete/{id}", name="micro_post_delete")
     * @Security("is_granted('delete', microPost)", message="Access denied")
     *
     * @param MicroPost $post
     */
    public function delete(MicroPost $microPost)
    {
        $this->entityManager->remove($microPost);
        $this->entityManager->flush();

        $this->flashBag->add('notice', 'Micropost was deleted');

        return $this->redirect($this->generateUrl('micro_post_index'));
    }
}
