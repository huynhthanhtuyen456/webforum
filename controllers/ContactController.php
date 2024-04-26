<?php
namespace MVC\Controllers;

use MVC\Core\Application;
use MVC\Core\Controller;
use MVC\Core\Request;

use MVC\Models\Contact;


class ContactController extends Controller
{
    private $model;
    public function __construct()
    {
        $this->model = new Contact();
    }
    public function get()
    {
        return $this->render('contact', [
            'model' => $this->model
        ], "Contact");
    }

    public function post(Request $request)
    {
        $contact = $this->model;
        $contact->loadData($request->getBody());
    
        if ($contact->validate() && $contact->save()) {
            return $this->render('thankyou', [
            ], "Thank You");
        }

        return $this->render('contact', [
            'model' => $contact
        ], "Contact");
    }
}
?>