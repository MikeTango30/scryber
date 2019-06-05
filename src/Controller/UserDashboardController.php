<?php


namespace App\Controller;


use App\Entity\CreditLog;
use App\Entity\File;
use App\Entity\User;
use App\Entity\UserFile;
use App\Repository\UserFileRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserDashboardController extends AbstractController
{
    const ITEMS_PER_PAGE = 10;

    public function showTranscriptions(Request $request, UserFileRepository $userFileRepository)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        /** @var User $user */
        $user = $this->getUser();

        $currentPage = $request->query->has('page') ? $request->query->get('page') : 1;
        $pagesTotal = (int)ceil($userFileRepository->getUserfilesTotal($user) / self::ITEMS_PER_PAGE);

        $transcription_container = $userFileRepository->getUserfilesSorted($user, $currentPage, self::ITEMS_PER_PAGE);

        return $this->render('userDashboard.html.twig', [
            "title" => "Mano Transkripcijos",
            "transcriptions" => $transcription_container,
            "remainingTime" => $user->getCredits(),
            'scrybe_not_made' => UserFile::SCRYBE_STATUS_NOT_SCRYBED,
            'scrybe_in_progress' => UserFile::SCRYBE_STATUS_IN_PROGRESS,
            'scrybe_imposible' => UserFile::SCRYBE_STATUS_SCRYBE_IMPOSIBLE,
            'scrybe_done' => UserFile::SCRYBE_STATUS_COMPLETED,
            'currentPage' => $currentPage,
            'nbPages' => $pagesTotal,
            'url' => 'user_dashboard',
            'itemsPerPage' => self::ITEMS_PER_PAGE
        ]);
    }

    public function exportTranscription()
    {
        //TODO
    }

    public function uploadFile()
    {
        //TODO
    }

    public function buyTime()
    {
        //TODO
    }

    public function deleteUserfile(string $userfileId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userfile = $entityManager->getRepository(UserFile::class)->findOneBy(['id' => $userfileId, 'user' => $this->getUser()]);

        $entityManager->remove($userfile);
        $entityManager->flush();


        return $this->redirectToRoute('user_dashboard');
    }

    public function logout()
    {
        //TODO render homepage
        // return $this->render();
    }
}