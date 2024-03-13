<?php
namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;


class Cart
{
    private $requestStack;
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack){
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }
    public function add($id){
        $session= $this->requestStack->getSession();
        $cart = $session->get('cart',[]);
        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }
        $session->set('cart',$cart);
    }
    public function get(){
        $tmp = $this->requestStack->getSession();
        return $tmp->get('cart');
    }
    public function remove(){
        $tmp = $this->requestStack->getSession();
        return $tmp->remove('cart');
    }
    public function delete($id){
        $session= $this->requestStack->getSession();
        $cart = $session->get('cart',[]);
        unset($cart[$id]);
        return $session->set('cart',$cart);;
    }
    public function descrease($id){
        $session= $this->requestStack->getSession();
        $cart = $session->get('cart',[]);
    //verifier si la quantité est suppérieure à 1
    if($cart[$id] > 1){
        // retirer une quantité dans mon panier
        $cart[$id]--;
    }else{
        // supprimer toute la quantité dans mon panier
        unset($cart[$id]);

    }
    return $session->set('cart',$cart);
       
       

    }
    public function getFull(){
        $cartCompleted = [];
        if($this->get()){
         foreach ($this->get() as $id => $quantity){
            $object_product = $this->entityManager->getRepository(Product::class)->findOneById($id);
            if(!$object_product ){
                $this->delete($id);
                continue;
            }
            $cartCompleted[] = [
                'product' => $object_product, 
                'quantity' => $quantity,

            ];
          }
        }
        return $cartCompleted;

    }
    

    
}