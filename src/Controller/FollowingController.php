<?php


namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FollowingController
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 */
class FollowingController extends AbstractController
{
    /**
     * @Route("/follow/{id}", name="following_follow")
     * @param User $userToFollow
     * @return RedirectResponse
     */
    public function follow(User $userToFollow): RedirectResponse
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        if($userToFollow->getId() !== $currentUser->getId()) {
            //to add a mtm-relation it has to be done through the owing side
            $currentUser->follow($userToFollow);
            /**
             * otherwise it's only possible if you inverse it through an helper method
             * $userToFollow->addFollowing($currentUser);
             * see user-entity
             */
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirectToRoute('micro_post_user', ['username' => $userToFollow->getUsername()]);
    }

    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
     * @param User $userToUnfollow
     * @return RedirectResponse
     */
    public function unfollow(User $userToUnfollow): RedirectResponse
    {
        $currentUser = $this->getUser();
        $currentUser->getFollowing()->removeElement($userToUnfollow);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('micro_post_user', ['username' => $userToUnfollow->getUsername()]);
    }
}
