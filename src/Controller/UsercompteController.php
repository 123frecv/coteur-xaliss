<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Depot;
use App\Entity\Compte;
use App\Entity\Partenaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/api")
 */

class UsercompteController extends AbstractController
{

        private $tokenStorage;
        public function __construct(TokenStorageInterface $tokenStorage)
        {
            $this->tokenStorage = $tokenStorage;
        }
        
        /** 
         * @Route("/compte/partenaire", name="compte_partenaire", methods={"POST","PUT"})
         */
    
        public function create_compte(Request $request , EntityManagerInterface $em, UserPasswordEncoderInterface $userPasswordEncoder)
        {
    
           $userconnct= $this->tokenStorage->getToken()->getUser();
            $val = json_decode($request->getContent());
           // var_dump($val);die;
             if(isset($val->ninea,$val->rc,$val->montant))
             {
    
                 $dateCreate=new \DateTime();
                  $depot = new Depot();
                  $user =new User();
                  $compte = new Compte();
                  $partenaire = new Partenaire();
                  // affectation des class
                      
                    //partenaire
    
                    $partenaire->setNinea($val->ninea);
                    $partenaire->setRmc($val->rc);    
    
                    $em->persist($partenaire);
                    $em->flush(); 
    
                    $rolerep = $this->getDoctrine()->getRepository(Role::class);
                    $role = $rolerep->find($val->role);
                    // user
                    $user->setUsername($val->username);
                    $user->setPassword($userPasswordEncoder->encodePassword($user,$val->password));
                    $user->setPrenom($val->prenom);
                    $user->setNom($val->nom);
                    $user->setImage($val->image);
                    $user->setRole($role);
                    $user->setPartenaire($partenaire);
                    
                    $em->persist($user);
                    $em->flush();       
    
                    //gener num compte
    
                    $an = Date('y');
                    $cont = $this->getLastCompte();
                    $long = strlen($cont);
                    $ninea2 = substr($partenaire->getNinea() , -2);
                    $numCompte = str_pad("MF".$an.$ninea2, 11-$long, "0").$cont;
                  
                   //compte
                    
                    $compte->setNumCom($numCompte);
                    $compte->setSolde(0);
                    $compte->setDateCreate($dateCreate);
                    $compte->setUser($userconnct);
                    $compte->setPartenaireComp($partenaire);
    
                 $em->persist($compte);
                 $em->flush(); 
    
                 //depot 
                 $depot->setMontant($val->montant);
                 $depot->setDateDepot($dateCreate);
                 $depot->setUser($userconnct1);
                 $depot->setNumCompt($compte);
                 $em->persist($depot);
             $em->flush(); 
             //mis a jour le depot
             $nouveau = ($val->montant+$compte->getSolde());
             $compte->setSolde($nouveau);
             $em->persist($compte);
             $em->flush();
             $data = [
                'status' => 201,
                'message' => 'Le compte de partenaire a été créé:'.$val->montant
            ];
    
            return new JsonResponse($data, 201);
             
         }
         $data = [
            'status' => 500,
           'message' => 'renseigner tout les champs'
        ];
         return new JsonResponse($data, 500);
        }
        /** 
         *  @Route("/cpt_partenaire_exist", name="compte_partenaire_ex", methods={"POST","PUT"})
         */
        public function partenaier_exist(Request $request, EntityManagerInterface $em)
        {
              //utilisateur qui connecte
              $userconnct= $this->tokenStorage->getToken()->getUser();

            $val = json_decode($request->getContent());
            if( isset($val->ninea,$val->montant))
            {
                $repoattribut =$this->getDoctrine()->getRepository(Partenaire::class);
                $attribut=$repoattribut->findOneByNinea($val->ninea);
               if($attribut)
               {
                 if($val->montant > 0)
                 {
                    $datej=new \DateTime();
                    $depot=new Depot();
                    $compte=new Compte();
                  
                   // num compte

                        $an = Date('y');
                    $cont = $this->getLastCompte();
                    $long = strlen($cont);
                    $ninea2 = substr($attribut->getNinea() , -2);
                    $numCompte = str_pad("MF".$an.$ninea2, 11-$long, "0").$cont;

                    $compte->setNumCompte($numCompte);
                    $compte->setSolde(0);
                    $compte->setDateCreate($datej);
                    $compte->setUsercreate($userconnct);
                    $compte->setPartenaire($attribut);

                    $em->persist($compte);
                    $em->flush();
                    ###depot ###
                    $repCompt= $this->getDoctrine()->getRepository(Compte::class);
                    $depCompte = $repCompt->findOneByNumCompte($numCompte);
                    $depot->setDatedep($datej);
                    $depot->setMontant($val->montant);
                    $depot->setNumCompt($depCompte);
                    $depot->setUser($userconnct);
                    $em->persist($depot);
                    $em->flush();
                    //mis a jour le depot
                        $nouveau = ($val->montant+$compte->getSolde());
                        $compte->setSolde($nouveau);
                        $em->persist($compte);
                        $em->flush();

                    $data = [
                        'status' => 201,
                        'message' => 'Le compte  a été créé:'.$val->montant
                    ];
            
                    return new JsonResponse($data, 201);
                 }
                 $data = [
                    'status' => 500,
                    'message' => 'veiller saisie le montant  '
                ];
                 return new JsonResponse($data, 500);
               }
               $data = [
                'status' => 500,
                'message' => 'desole le ninea n\exist pas'
            ];
             return new JsonResponse($data, 500);
            }
            $data = [
                'status' => 500,
                'message' => 'veuillez saisie le ninea et le solde'
            ];
             return new JsonResponse($data, 500);
            
        }
        /** 
         *  @Route("/depot", name="nouveau_depot", methods={"POST","PUT"})
         */
        public function faire_depot(Request $request, EntityManagerInterface $em)
        {
              //utilisateur qui connecte
              $userconnct= $this->tokenStorage->getToken()->getUser();
              $val = json_decode($request->getContent());
            if($val->montant > 0)
            {

                   $dateday=new \DateTime();
                    $depot=new Depot();                  

                $repCompt= $this->getDoctrine()->getRepository(Compte::class);
                $depCompte = $repCompt->findOneById($val->id);
                $depot->setDateDepot($dateday);
                $depot->setMontant($val->montant);
                $depot->setNumCom($depCompte);
                $depot->setUser($userconnct);
                $em->persist($depot);
                $em->flush();
                //mis a jour le depot
                    $nouveau = ($val->montant+$depCompte->getSolde());
                    $depCompte->setSolde($nouveau);
                    $em->persist($depCompte);
                    $em->flush();

                    $data = [
                        'status' => 201,
                        'message' => 'vous avez faire un depot de:'.$val->montant
                    ];
            
                    return new JsonResponse($data, 201);

            }
            $data = [
                'status' => 500,
                'message' => 'le compte n\existe pas'
            ];
    
            return new JsonResponse($data, 500);

        }
      
        public function getLastCompte(){
            $ripo = $this->getDoctrine()->getRepository(Compte::class);
            $compte = $ripo->findBy([], ['id'=>'DESC']);
            if(!$compte){
                $cont= 1;
            }else{
                $cont = ($compte[0]->getId()+1);
            }
            return $cont;
          }

   
}
