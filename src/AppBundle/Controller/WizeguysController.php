<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Message;
use AppBundle\Entity\Login;
use AppBundle\Entity\Reservation;
use AppBundle\Entity\Address;
use AppBundle\Entity\Code;
use Symfony\Component\HttpFoundation\Response;

class WizeguysController extends Controller
{

    /**
     * @Route("/index", name="index")
     */
    public function indexAction(Request $request)
    {
        //mail("8622372962@tmomail.net", "", "Your packaged has arrived!");

         $Message = new Message;
          if (($request->getMethod() == Request::METHOD_POST) & (isset($_POST['submit_message']))) {
              $full_name = $request->request->get('full_name');
              $email_address = $request->request->get('email_address');
              $message = $request->request->get('message');
              $now = new\DateTime('now');

                  $Message->setFullName($full_name);
                  $Message->setEmailAddress($email_address);
                  $Message->setMessage($message);
                  $Message->setDate($now);

                  $em = $this->getDoctrine()->getManager();

                  $em->persist($Message);
                  $em->flush();

              $message = \Swift_Message::newInstance()
                  ->setSubject('New Reservation')
                  ->setFrom(array('yassine.b@byteacademy.co' => 'WizeGuys Support'))
                  ->setTo('metallicarow@gmail.com')
                  ->setBody('You have a new message from customer '.$full_name. '! you can check the database for more details but his message is:'.`"<br>"` .$message, 'text/html');
              $this->get('mailer')->send($message);

              }

        $Reservation = new Reservation;
        if (($request->getMethod() == Request::METHOD_POST) & (isset($_POST['submit_reservation']))) {
            $full_name = $request->request->get('full_name');
            $email_address = $request->request->get('email_address');
            $phone_number = $request->request->get('phone_number');
            $guest_number = $request->request->get('guest_number');
            $date = $request->request->get('date');
            $time = $request->request->get('time');

            $Reservation->setFullName($full_name);
            $Reservation->setEmailAddress($email_address);
            $Reservation->setPhoneNumber($phone_number);
            $Reservation->setDate($date);
            $Reservation->setTime($time);
            $Reservation->setGuestNumber($guest_number);

            $em = $this->getDoctrine()->getManager();

            $em->persist($Reservation);
            $em->flush();


            $message = \Swift_Message::newInstance()
                ->setSubject('New Reservation')
                ->setFrom(array('yassine.b@byteacademy.co' => 'WizeGuys Support'))
                ->setTo('metallicarow@gmail.com')
                ->setBody('You have a reservation! check the database list to find out who', 'text/html');
            $this->get('mailer')->send($message);

        }

        $message = $this->getDoctrine()
            ->getRepository('AppBundle:Message')
            ->findAll();


        return $this->render('main/one-page.twig', array('messages' => $message,));

    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction(Request $request)
    {
        $user_statue = $this->get('session')->get('UserStatue');
        if($user_statue == "Logged In") {
            return $this->render('main/about.twig');
        }

        else {
            return $this->render('main/about.twig');
        }
    }

    /**
     * @Route("/error-404", name="error-404")
     */
    public function error404Action(Request $request)
    {
        return $this->render('main/error-404.twig');
    }

    /**
     * @Route("/login-register", name="login-register")
     */
    public function login_registerAction(Request $request)
    {
            $login = new Login;
            if ($request->getMethod() == Request::METHOD_POST  & (isset($_POST['submit_register']))) {
                $first_name = $request->request->get('first_name');
                $last_name = $request->request->get('last_name');
                $email_address = $request->request->get('email_address');
                $phone_number = $request->request->get('phone_number');
                $password = $request->request->get('password');
                $confirm_password = $request->request->get('confirm_password');
                $now = new\DateTime('now');

                $email_unique = $this->getDoctrine()
                    ->getRepository('AppBundle:Login')
                    ->checkUniqueEmail($email_address);
                if( isset( $email_unique[0] )) {
                    $test = implode("|", $email_unique[0]);
                    if (($email_address == $test)) {

                      /*  $this->addFlash(
                            'notice',
                            'Email already registered'
                        );*/
                        return $this->redirectToRoute('login-register');
                    }
                }
                else  if ( ($email_address != $email_unique) && ($password == $confirm_password)) {

                    $login->setFirstName($first_name);
                    $login->setLastName($last_name);
                    $login->setEmailAddress($email_address);
                    $login->setPassword(sha1($password));
                    $login->setPhoneNumber($phone_number);
                    $login->setDate($now);

                    $em = $this->getDoctrine()->getManager();

                    $em->persist($login);
                    $em->flush();

                    $email_unique = $this->getDoctrine()
                        ->getRepository('AppBundle:Login')
                        ->checkUniqueEmail($email_address);
                    $this->get('session')->set('loginUserEmail', $email_unique);

                    $first = $this->getDoctrine()->getRepository('AppBundle:Login')->getFirstName($email_address);
                    $this->get('session')->set('UserStatue', "Logged In");

                    return $this->render('main/profile.twig', array('first' => $first[0]
                    ));
                }
                else if ( ($password != $confirm_password)) {
                   /* $this->addFlash(
                        'notice',
                        'Passwords unmatched. Try Again'
                    );*/
                    return $this->redirectToRoute('login-register');
                }

            }



        if ($request->getMethod() == Request::METHOD_POST & (isset($_POST['submit_login']))) {
            $email_address = $request->request->get('email_address');
            $password = $request->request->get('password');

            $email_password = $this->getDoctrine()
                ->getRepository('AppBundle:Login')
                ->checkEmail($email_address);
            if (isset($email_password[0])) {


                $test = implode("|", $email_password[0]);
                if ((sha1($password)) == $test) {

                    $email_unique = $this->getDoctrine()
                        ->getRepository('AppBundle:Login')
                        ->checkUniqueEmail($email_address);
                    $this->get('session')->set('loginUserEmail', $email_unique);

                    $first = $this->getDoctrine()->getRepository('AppBundle:Login')->getFirstName($email_address);
                    $first = implode("|", $first[0]);
                    $this->get('session')->set('loginUserId', $first);


                    
                    return $this->redirectToRoute('index', array('first' => $first[0] ));

                } else if (sha1($password) !== $test) {
                  /*  $this->addFlash(
                        'notice',
                        'password not match'
                    );*/
                    return $this->redirectToRoute('login-register');
                }
            }
            else {
               /* $this->addFlash(
                    'notice',
                    'email not found'
                );*/
                return $this->redirectToRoute('login-register');
            }


        }


        return $this->render('main/login-register.twig');

    }


    /**
     * @Route("/profile", name="profile")
     */
    public function profileAction(Request $request)
    {

        if ($request->getMethod() == Request::METHOD_POST ) {

            $address_database = new Address;
           // $login = new Login;
          /*  $login->setFirstName('ah');
            $login->setLastName('yo');
            $login->setEmailAddress('za3ma@gmail.com');
            $login->setPassword('qq');
            $login->setPhoneNumber('1546231589');*/
          $za3ma=$this->get('session')->get('loginUserEmail');
             $user_id = $this->getDoctrine()
              ->getRepository('AppBundle:Login')
              ->getUserID($za3ma);


            $user_id = implode("|", $user_id[0]);


            $login = $this->getDoctrine()
                ->getRepository('AppBundle:Login')
                ->findOneBy(['id' => $user_id]);


            $address = $request->request->get('address');
            $city = $request->request->get('city');
            $state = $request->request->get('state');
            $zip = $request->request->get('zip');
            $instruction = $request->request->get('instruction');
            $now = new\DateTime('now');



               // $user_id = implode("|", $user_id[0]);

                $address_database->setAddress($address);
                $address_database->setCity($city);
                $address_database->setState($state);
                $address_database->setZip($zip);
                $address_database->setInstruction($instruction);
                $address_database->setLogin($login);
                $address_database->setDate($now);



                $em = $this->getDoctrine()->getManager();

                $em->persist($address_database);
               // $em->persist($login);
                $em->flush();




            $first = $this->getDoctrine()->getRepository('AppBundle:Login')->getFirstName($za3ma);
            $first = implode("|", $first[0]);
           /* $this->addFlash(
                'notice',
                $first
            );*/

            return $this->redirectToRoute('index', array('first' => $first
            ));
            }







        return $this->render('main/profile.twig');
    }


    /**
     * @Route("/message", name="message")
     */
    public function messageAction(Request $request)
    {



        $message = $this->getDoctrine()
            ->getRepository('AppBundle:Message')
              ->findAll();
          return $this->render('database/message.twig', array(
              'messages' => $message
          ));
    }

    /**
     * @Route("/reservation", name="reservation")
     */
    public function reservationAction(Request $request)
    {



        $reservation = $this->getDoctrine()
            ->getRepository('AppBundle:Reservation')
            ->findAll();
        return $this->render('database/reservation.twig', array(
            'reservations' => $reservation
        ));
    }

    /**
     * @Route("/user_info", name="user_info")
     */
    public function userinfoAction(Request $request)
    {



        $em = $this->getDoctrine()->getManager();
        $entities = $em->createQuery(
            'SELECT a, l
             FROM AppBundle:Address a
             JOIN a.login l
             ORDER BY l.id')
            ->getResult();

        return $this->render('database/user_info.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * @Route("/database", name="database")
     */
    public function databaseAction(Request $request)
    {


         $message = $this->getDoctrine()
             ->getRepository('AppBundle:Message')
             ->findAll();


        $reservation = $this->getDoctrine()
            ->getRepository('AppBundle:Reservation')
            ->findAll();


        $em = $this->getDoctrine()->getManager();
        $entities = $em->createQuery(
            'SELECT a, l
             FROM AppBundle:Address a
             JOIN a.login l
             ORDER BY l.id')
            ->getResult();



        return $this->render('database/database.twig', array(
            'reservations' => $reservation ,
                'messages' => $message,
                'entities' => $entities)
        );
    }


    /**
     * @Route("/first", name="first")
     */
    public function firstAction(Request $request)
    {

        if ($request->getMethod() == Request::METHOD_POST ) {
            $city = $request->request->get('city');

            if ($city == "Clifton" || $city == "Elmwood Park" || $city == "Garfield" || $city == "Paterson" ){
                return $this->redirectToRoute('clifton');
            }
            if ($city == "Hackensack" || $city == "Lodi" || $city == "Rochelle Park" || $city == "Little Ferry"
                || $city == "Hasbrouck Heights" || $city == "Woodridge" || $city == "Maywood"){
                return $this->redirectToRoute('index');
            }
            }
        return $this->render('main/first.twig');
    }

    /**
     * @Route("/clifton", name="clifton")
     */
    public function cliftonAction(Request $request)
    {
        return $this->render('main/clifton.twig');
    }

    /**
     * @Route("/profile-info", name="profile-info")
     */
    public function profileinfoAction(Request $request)
    {

        if ($request->getMethod() == Request::METHOD_POST ) {

            $login_user_email = $this->get('session')->get('loginUserEmail');
            $user_id = $this->getDoctrine()
                ->getRepository('AppBundle:Login')
                ->getUserID($login_user_email);

            $first = $this->getDoctrine()->getRepository('AppBundle:Login')->getFirstName($login_user_email);
            $first = implode("|", $first[0]);


            if( isset($_POST['profile-edit'])) {


                $user_id = implode("|", $user_id[0]);
                $id = $this->getDoctrine()
                    ->getRepository('AppBundle:Address')
                    ->getUserAddressId($user_id);


                //Get Data
                $address = $request->request->get('address');
                $city = $request->request->get('city');
                $state = $request->request->get('state');
                $zip = $request->request->get('zip');
                $instruction = $request->request->get('instruction');

                $em = $this->getDoctrine()->getManager();
                $id = implode("|", $id[0]);
                $address_database = $em->getRepository('AppBundle:Address')->findOneBy(['id' => $id]);


                $address_database->setAddress($address);
                $address_database->setCity($city);
                $address_database->setState($state);
                $address_database->setZip($zip);
                $address_database->setInstruction($instruction);


                $em->flush();
            }



            return $this->redirectToRoute('index', array('first' => $first
            ));
        }

        $email=$this->get('session')->get('loginUserEmail');
        $email = implode("|", $email[0]);
        $user_info = $this->getDoctrine()
            ->getRepository('AppBundle:Address')
            ->fetchUserInfo($email);


        return $this->render('main/profile-info.twig', array('user_info' => $user_info));


    }

    /**
     * @Route("/reset-password", name="reset-password")
     */
    public function resetpasswordAction(Request $request)
    {
        if ($request->getMethod() == Request::METHOD_POST) {
            $email_address = $request->request->get('email_address');
            $current_password = $request->request->get('current_password');
            $new_password = $request->request->get('new_password');
            $confirm_password = $request->request->get('confirm_password');


            $get_current_password = $this->getDoctrine()
                ->getRepository('AppBundle:Login')
                ->checkEmail($email_address);

            $get_current_password = implode("|", $get_current_password[0]);
            if (($new_password == $confirm_password) && ($get_current_password == sha1($current_password))) {

                $em = $this->getDoctrine()->getManager();
                $login_database = $em->getRepository('AppBundle:Login')->findOneBy(['emailAddress' => $email_address]);
                $login_database->setPassword(sha1($new_password));
                $em->flush();

                $message = \Swift_Message::newInstance()
                    ->setSubject('Password Modified')
                    ->setFrom(array('yassine.b@byteacademy.co' => 'WizeGuys Support'))
                    ->setTo($email_address)
                    ->setBody('You have just changed your password. From now on, use your new password to login! ', 'text/html');
                $this->get('mailer')->send($message);

                return $this->redirectToRoute('login-register');
            }
        }


        return $this->render('main/reset-password.twig');
    }

    /**
     * @Route("/forgot-password", name="forgot-password")
     */
    public function forgotpasswordAction(Request $request)
    {
        if ($request->getMethod() == Request::METHOD_POST) {
            $email_address = $request->request->get('email_address');
            $code = $request->request->get('code');
            $new_password = $request->request->get('new_password');
            $confirm_password = $request->request->get('confirm_password');


            $get_code = $this->getDoctrine()
                ->getRepository('AppBundle:Code')
                ->getCode($email_address);

            $get_code = implode("|", $get_code[0]);
            if ( ($new_password == $confirm_password) && ($code == $get_code)){

                $em = $this->getDoctrine()->getManager();
                $login_database = $em->getRepository('AppBundle:Login')->findOneBy(['emailAddress' => $email_address]);
                $login_database->setPassword(sha1($new_password));
                $em->flush();

                $message = \Swift_Message::newInstance()
                    ->setSubject('You have a new password')
                    ->setFrom(array('yassine.b@byteacademy.co' => 'WizeGuys Support'))
                    ->setTo('metallicarow@gmail.com')
                    ->setBody('You now have a new password. Use it to login! ', 'text/html');
                $this->get('mailer')->send($message);

                return $this->redirectToRoute('login-register');

            }
        }
        return $this->render('main/forgot-password.twig');
    }

    /**
     * @Route("/get-code", name="get-code")
     */
    public function getCodeAction(Request $request)
    {


        if ($request->getMethod() == Request::METHOD_POST) {

            $code_database = new Code;
            $code = mt_rand(100000, 999999);


            $email_address = $request->request->get('email_address');
            $now = new\DateTime('now');

            $code_database->setEmailAddress($email_address);
            $code_database->setCode($code);
            $code_database->setDate($now);


            $em = $this->getDoctrine()->getManager();
            $em->persist($code_database);
            $em->flush();


            $message = \Swift_Message::newInstance()
                ->setSubject('Forgot Password')
                ->setFrom(array('yassine.b@byteacademy.co' => 'WizeGuys Support'))
                ->setTo('metallicarow@gmail.com')
                ->setBody('Code:' . $code . `"<br>"` . 'Click on this link http://localhost/wizeguys/web/app_dev.php/forgot-password and follow the instruction ', 'text/html');
            $this->get('mailer')->send($message);

        }

            return $this->render('main/get-code.twig');
        }

    /**
     * @Route("/log-out", name="log-out")
     */
    public function logoutAction(Request $request)
    {

        session_destroy();
        return $this->redirectToRoute('index');

    }



}


