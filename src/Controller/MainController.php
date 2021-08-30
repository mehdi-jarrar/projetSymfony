<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Dishes;
use App\Entity\Category;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
    
    $repository = $this->getDoctrine()->getRepository(Dishes::class);
    $dishes = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
    
    return $this->render('index.html.twig', array('dishes'=>$dishes));

    }
    /**
     * @Route("/adddishform", name="adddishform")
     */
    public function Adddishes()
    {
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');
        if ($authChecker->isGranted('ROLE_ADMIN') || $authChecker->isGranted('ROLE_USER') ) {
            $repository = $this->getDoctrine()->getRepository(Category::class);
            $cat = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
             
            
            return $this->render('add.html.twig' ,array('cat'=>$cat));
        } else{
            
            return $this->render('login.html.twig');
        }
        
    }
     /**
     * @Route("/mydishes", name="mydishes")
     */
    public function mydishes()
    {
     
        
  
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');
        
        if ($authChecker->isGranted('ROLE_ADMIN')  ) {
            $repository = $this->getDoctrine()->getRepository(Dishes::class);
            $dishes = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
            return $this->render('mydishes.html.twig', array('dishes'=>$dishes));

        }elseif( $authChecker->isGranted('ROLE_USER')){
            $user=$this->getUser();  
           
            
            $dishes = $user->getDishes() ; 
            
            
            return $this->render('mydishes.html.twig', array('dishes'=>$dishes));

        }else{
            var_dump("error") ; 
            die(); 
        }
        
    }




    
    /**
     * @Route("/deldishes", name="deldishes")
     */
    public function deldishes(Request $request)
    {
        $idn=$request->get('id') ; 
        $repository = $this->getDoctrine()->getRepository(Dishes::class);

        $dish = $repository->find($idn);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($dish);
        $entityManager->flush();

        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');

        if ($authChecker->isGranted('ROLE_ADMIN')  ) {
            $repository = $this->getDoctrine()->getRepository(Dishes::class);
            $dishes = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
            return $this->render('mydishes.html.twig', array('dishes'=>$dishes));

        }elseif( $authChecker->isGranted('ROLE_USER')){
            $user=$this->getUser();  
            $dishes = $user->getDishes() ; 
            return $this->render('mydishes.html.twig', array('dishes'=>$dishes));

        }


        
    }





    /**
     * @Route("/enreg", name="enreg")
     */
    public function enreg()
    {
        return $this->render('register.html.twig');
    }



    /**
     * @Route("/modify/{id}", name="modify")
     */
    public function Modify(int $id)
    {
        $dish = $this->getDoctrine()
        ->getRepository(Dishes::class)
        ->find($id);
        
        $repository = $this->getDoctrine()->getRepository(Category::class);
            $cat = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
             
        return $this->render('modify.html.twig' ,array('dish'=>$dish , 'cat'=>$cat ) );
    }

    /**
     * @Route("/single/{id}", name="single")
     */
    public function Single(int $id)
    {
        $dish = $this->getDoctrine()
        ->getRepository(Dishes::class)
        ->find($id);
        
        return $this->render('single.html.twig' ,array('dish'=>$dish) );
    }

    /**
     * @Route("/update", name="update")
     */
    public function update(Request $request)
    {

        

         $idn=$request->get('id') ; 
        $repository = $this->getDoctrine()->getRepository(Dishes::class);

        $dish = $repository->find($idn);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($dish);
        $entityManager->flush();



        $description   = $request->get('editor1');
        $title   = $request->get('title');
        $idc   = $request->get('category');
        $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->find($idc);
        
        
        $img   = $request->get('image');
        $date = new \DateTime();

        $dish = new Dishes() ; 
        $dish->AddCategory($category) ; 
        $user=$this->getUser(); 
        $dish->setTitle($title) ;
        $dish->setSubject($description) ;
        $dish->setDate($date) ;
        $dish->AddUser($user) ; 

        $tmpFilePath = $_FILES['image']['tmp_name'];

                  if ($tmpFilePath != "")
                  {
                   
                    $name = $_FILES["image"]["name"];
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    $filename = md5(time().$_FILES['image']['name']).'.jpg';
                    $newFilePath = "img/" . $filename;
                    if(move_uploaded_file($tmpFilePath, $newFilePath))
                    {
                     $dish->setImage($filename);
                    }
                  }
                  $em = $this->getDoctrine()->getManager();
            $em->persist($dish);
            $em->flush();
            
            $repository = $this->getDoctrine()->getRepository(Dishes::class);
            $dishes = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
            
        
                return $this->render('index.html.twig', array('dishes'=>$dishes));




    }

    

     /**
     * @Route("/adddishfunction", name="adddishfunction")
     */
    public function AdddishFunction(Request $request)
    {
        $description   = $request->get('editor1');
        $title   = $request->get('title');
        $idc   = $request->get('category');
        $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->find($idc);
        
        
        $img   = $request->get('image');
        $date = new \DateTime();

        $dish = new Dishes() ; 
        $dish->AddCategory($category) ; 
        $user=$this->getUser(); 
        $dish->setTitle($title) ;
        $dish->setSubject($description) ;
        $dish->setDate($date) ;
        $dish->AddUser($user) ; 

        $tmpFilePath = $_FILES['image']['tmp_name'];

                  if ($tmpFilePath != "")
                  {
                   
                    $name = $_FILES["image"]["name"];
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    $filename = md5(time().$_FILES['image']['name']).'.jpg';
                    $newFilePath = "img/" . $filename;
                    if(move_uploaded_file($tmpFilePath, $newFilePath))
                    {
                     $dish->setImage($filename);
                    }
                  }
                  $em = $this->getDoctrine()->getManager();
            $em->persist($dish);
            $em->flush();
            
            $repository = $this->getDoctrine()->getRepository(Dishes::class);
            $dishes = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
            
        
                return $this->render('index.html.twig', array('dishes'=>$dishes));




    }


    
}
