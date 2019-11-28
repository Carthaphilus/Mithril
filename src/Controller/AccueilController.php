<?php

namespace App\Controller;

use App\ClassPerso\DateClass;
use App\Entity\Acces;
use App\Entity\Agenda;
use App\Entity\Evenement;
use App\Entity\User;
use App\FormType\AgendaType;
use App\FormType\EvenementType;
use App\Repository\AccesRepository;
use App\Repository\AgendaRepository;
use App\Repository\EvenementRepository;
use App\Security\AuthentificatorAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function dump;

class AccueilController extends Controller {

    /**
     * @Route("/Accueil/{year}/{month}/{agendaSpe}", name="accueil", defaults={"agendaSpe"=null, "year"=null, "month"=null})
     */
    public function index(Request $request, AgendaRepository $repositoryAgenda, EvenementRepository $repositoryEvenement, $agendaSpe, $year, $month) {

        $bdd = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $query = $bdd->createQuery('SELECT a FROM App\Entity\Acces a JOIN a.Agenda ag LEFT JOIN ag.evenement ev WHERE a.User=:user');
        $query->setParameter('user', $user);
        $AllAcces = $query->getResult();

        dump($AllAcces);
        foreach ($AllAcces as $unAcces) {
            $Agendas[] = $unAcces->getAgenda();
        }

        if (!isset($Agendas)) {
            $Agendas = array();
        }

        dump($Agendas);

        foreach ($Agendas as $unAgenda) {
            $query3 = $bdd->createQuery('SELECT a FROM App\Entity\Acces a WHERE a.Agenda=:agenda');
            $query3->setParameter('agenda', $unAgenda);
            $AllAccesAgenda = $query3->getResult();
            $tabAccesAgenda[$unAgenda->getId()] = $AllAccesAgenda;
            dump($unAgenda->getEvenement());
        }

        if (!isset($tabAccesAgenda)) {
            $tabAccesAgenda = array();
        }

//        dump($tabAccesAgenda);

        foreach ($Agendas as $unAgenda) {
            $evenements = $unAgenda->getEvenement();
            foreach ($evenements as $unEvenement) {
                $tabEvenement[$unAgenda->getId()][] = $this->createForm(EvenementType::class, $unEvenement)->createView();
            }
        }

        if (!isset($tabEvenement)) {
            $tabEvenement = array();
        }

        dump($tabEvenement);

        $query2 = $bdd->createQuery('SELECT a FROM App\Entity\User a');
        $Users = $query2->getResult();

        if (!empty($Agendas)) {
            foreach ($Agendas as $unAgenda) {
                $tabAgenda[$unAgenda->getId()] = $this->createForm(AgendaType::class, $unAgenda)->createView();
            }
        } else {
            $tabAgenda = array();
        }

        dump($tabAgenda);

        $date = new DateClass($month ?? date('m'), $year ?? date('Y'));
        $formAgenda = $this->createForm(AgendaType::class);
        $formEvenement = $this->createForm(EvenementType::class);
        return $this->render('accueil/index.html.twig', [
                    'formAgenda' => $formAgenda->createView(),
                    'formEvenement' => $formEvenement->createView(),
                    'Date' => $date,
                    'AllAgenda' => $Agendas,
                    'tabAgenda' => $tabAgenda,
                    'AllUsers' => $Users,
                    'user' => $user,
                    'agendaSpe' => $agendaSpe,
                    'tabEvenement' => $tabEvenement,
        ]);
    }

    /**
     * 
     * @Route("/Agenda/Ajout", name="ajoutAgenda")
     */
    public function ajoutAgendaAction(Request $request) {

        $agenda = new Agenda();
        $formAgenda = $this->createForm(AgendaType::class, $agenda);
        $formAgenda->handleRequest($request);

        if ($formAgenda->isSubmitted() && $formAgenda->isValid()) {

            $user = $this->getUser();
            $Acces = new Acces();
            $Acces->setUser($user);

            $agenda = $formAgenda->getData();

            $file = $formAgenda['image']->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                            $this->getParameter('file_directory'),
                            $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $agenda->setImage($newFilename);
            }

            $bdd = $this->getDoctrine()->getManager();
            $bdd->persist($agenda);
            $bdd->persist($Acces);
            $Acces->setAgenda($agenda);
            $Acces->setDroit(1);
            $bdd->flush();
        }
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/Evenement/Ajout", name="ajoutEvenement")
     */
    public function ajoutEvenementAction(Request $request) {
        $evenement = new Evenement();
        $formEvenement = $this->createForm(EvenementType::class, $evenement);
        $formEvenement->handleRequest($request);

        if ($formEvenement->isSubmitted() && $formEvenement->isValid()) {
            $evenement = $formEvenement->getData();

            $dateDeb = date_create_from_format('d/m/Y H:i', $_POST['evenement']['dateDeb']);
            $dateFin = date_create_from_format('d/m/Y H:i', $_POST['evenement']['dateFin']);
            dump($_POST['evenement']['dateDeb']);
            $evenement->setDateDeb($dateDeb);
            $evenement->setDateFin($dateFin);

            $bdd = $this->getDoctrine()->getManager();
            $bdd->persist($evenement);
            $bdd->flush();
        }
        return $this->redirectToRoute('accueil');
    }

    /**
     * 
     * @Route("/Agenda/Suppression/{id}",name="deleteAgenda")
     */
    public function deleteAgendaAction(Agenda $Agenda, AccesRepository $repositoryAcces) {
        $bdd = $this->getDoctrine()->getManager();
        $query3 = $bdd->createQuery('SELECT a FROM App\Entity\Acces a WHERE a.Agenda=:agenda');
        $query3->setParameter('agenda', $Agenda);
        $AllAccesAgenda = $query3->getResult();
        
        foreach($AllAccesAgenda as $unAcces){
            $em = $this->getDoctrine()->getManager();
            $em->remove($unAcces);
        }
        
        $bdd->remove($Agenda);
        $bdd->flush();
        return $this->redirectToRoute('accueil');
    }

    /**
     * 
     * @Route("/Evenement/Suppression/{id}",name="deleteEvenement")
     */
    public function deleteEvenementAction(Request $request, Evenement $Evenement) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($Evenement);
        $em->flush();
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/Agenda/Droit/{id}",name="droitAgenda")
     */
    public function droitAgendaAction(Agenda $Agenda) {

        $repositoryUser = $this->getDoctrine()->getRepository(User::class);
        $Acces = new Acces();
        $idUser = $_POST['userDroit'];
        dump($idUser);
        $User = $repositoryUser->find($idUser);
        $Acces->setUser($User);
        $Acces->setAgenda($Agenda);
        $droitUser = $_POST['acces'];
        $Acces->setDroit($droitUser);

        $bdd = $this->getDoctrine()->getManager();
        $bdd->persist($Acces);

        $bdd->flush();

        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/User/Modif/{id}",name="modifUser")
     */
    public function updateUserAction(User $user, AuthentificatorAuthenticator $authenticator) {
        $user->setNom($_POST['nomUser']);
        $user->setMail($_POST['mailUser']);
        $user->setDatenaissance($_POST['dateNaissance']);
        $mdp = $authenticator->encoderMdp($user, $_POST['mdpUser']);
        $user->setMdp($mdp);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/Update/Agenda",name="modifAgenda")
     */
    public function updateAgenda(Request $request, AgendaRepository $repositoryAgenda) {
        $Agenda = new Agenda();
        $formAgenda = $this->createForm(AgendaType::class, $Agenda);
        $formAgenda->handleRequest($request);

        if ($formAgenda->isSubmitted() && $formAgenda->isValid()) {

            $agenda = $formAgenda->getData();

            $Agenda2 = $repositoryAgenda->find($agenda->getId());
            $agenda->setAcces($Agenda2->getAcces());

            $bdd = $this->getDoctrine()->getManager();
            $bdd->merge($agenda);
            $bdd->flush();
        }
        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/Update/Evenement",name="modifEvenement")
     */
    public function updateEvenement(Request $request, AgendaRepository $repositoryAgenda) {
        $Evenement = new Evenement();
        $formEvenement = $this->createForm(EvenementType::class, $Evenement);
        $formEvenement->handleRequest($request);

        if ($formEvenement->isSubmitted() && $formEvenement->isValid()) {

            $evenement = $formEvenement->getData();
            dump($_POST['evenement']['dateDeb'], $_POST['evenement']['dateFin']);
//            $dateDeb = date_create_from_format('d/m/Y H:i', $_POST['evenement']['dateDeb']);
//            $dateFin = date_create_from_format('d/m/Y H:i', $_POST['evenement']['dateFin']);
            $dateDeb = date_create($_POST['evenement']['dateDeb']);
            $dateFin = date_create($_POST['evenement']['dateFin']);
            dump($dateDeb, $dateFin);
            $evenement->setDateDeb($dateDeb);
            $evenement->setDateFin($dateFin);

            $Agenda = $repositoryAgenda->find($evenement->getAgenda()->getId());
            $evenement->setAgenda($Agenda);
            dump($Agenda, $evenement);

            $bdd = $this->getDoctrine()->getManager();
            $bdd->merge($evenement);
            $bdd->flush();
        }
        return $this->redirectToRoute('accueil');
    }

}
