<?php
namespace MVC\Controllers;

use MVC\Core\Application;
use MVC\Core\Controller;
use MVC\Core\Request;
use MVC\Helpers\Common;
use MVC\Forms\EditProfileForm;
use MVC\Forms\ChangeUserPasswordForm;
use MVC\Middlewares\AuthMiddleware;
use MVC\Middlewares\AuthorizeMiddleware;
use MVC\Models\Question;
use MVC\Models\Module;
use MVC\Models\User;
use MVC\Models\Contact;
use MVC\Models\Answer;
use MVC\Exceptions\BadRequestException;


class ProfileController extends Controller
{
    public function __construct()
    {;
        $this->registerMiddleware(new AuthMiddleware([
            'index',
            'addContact',
            'editContact',
            'deleteContact',
            'editProfile',
            'editAnswer',
            'deleteAnswer',
        ]));
        $this->limit = 10;
        $this->me = Application::$app->user;
    }

    public function index()
    {
        if (isset($_GET["page"])) {
            $page = $_GET["page"];
            try {
                $page = (int) $page;
                $this->currentPage = $page;
                if ($page < 0) throw new \Exception($message="Invalid page query param.");
            } catch (\Exception $e) {
                throw new BadRequestException($e->getMessage());
            }
        }
        $tab = isset($_GET["tab"]) ? $_GET["tab"] : "profile";
        
        if (!in_array($tab, ['profile', 'aboutMe', 'questions', 'contacts', 'answers'])) throw new BadRequestException("Invalid tab query param!");

        $questions = Question::findAll(['authorID' => $this->me->id, 'isActive' => Question::BOOL_TRUE], $this->getLimit(), $this->getPageOffset());
        $totalQuestions = Question::countAll(['isActive' => Question::BOOL_TRUE, 'authorID' => $this->me->id]);
        $totalPageQuestions = ceil($totalQuestions / $this->getLimit());

        $contacts = Contact::findAll(["emailAddress" => $this->me->emailAddress], $this->getLimit(), $this->getPageOffset());
        $totalContacts = Contact::countAll(["emailAddress" => $this->emailAddress]);
        $totalPageContacts = ceil($totalContacts / $this->getLimit());

        $answers = Answer::findAll(['authorID' => $this->me->id, 'isActive' => Answer::BOOL_TRUE], $this->getLimit(), $this->getPageOffset(), "createdAt DESC");
        $totalAnswers = Answer::countAll(['authorID' => $this->me->id, 'isActive' => Answer::BOOL_TRUE]);
        $totalPageAnswers = ceil($totalContacts / $this->getLimit());

        return $this->render($view='profile', $params=[
            "questions" => $questions,
            "totalQuestions" => $totalQuestions,
            "totalPageQuestions" => $totalPageQuestions,

            "contacts" => $contacts,
            "totalContacts" => $totalContacts,
            "totalPageContacts" => $totalPageContacts,

            "answers" => $answers,
            "totalAnswers" => $totalAnswers,
            "totalPageAnswers" => $totalPageAnswers,

            "currentPage" => $this->currentPage,
            "tab" => $tab,
        ], $title="Profile");
    }

    public function editProfile(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $user = EditProfileForm::findOne(["id" => $this->me->id]);
        
        if (!$user) throw new \MVC\Exceptions\BadRequestException("Not Found Module!");
        
        if ($request->isPost()) {
            $image_path = Common::upload_file();
            echo $image_path;
            if ($image_path) {
                $user->image = $image_path;
            }
            $data = $request->getBody();
            $data["isActive"] = $data["isActive"] ? User::BOOL_TRUE : User::BOOL_FALSE;
            $data["isSuperAdmin"] = $data["isSuperAdmin"] ? User::BOOL_TRUE : User::BOOL_FALSE;
            $data["birthday"] = $data["birthday"] ? $data["birthday"] : null;
            $data["aboutMe"] = $data["aboutMe"] ? $data["aboutMe"] : null;
            $user->loadData($data);
            if ($user->validate()) {
                $updateData = $user->getUpdateData();
                EditProfileForm::update($updateData);
                Application::$app->session->setFlash('success', 'Your profile was updated successfully!');
                Application::$app->response->redirect('/profile');
            }
        }

        return $this->render('editProfile', [
            'model' => $user
        ], "Edit Profile");
    }

    public function changePassword(Request $request)
    {
        $user = new ChangeUserPasswordForm();
        
        if (!$user) throw new \MVC\Exceptions\BadRequestException("Not Found Module!");
        
        if ($request->isPost()) {
            $data = $request->getBody();
            if (!password_verify($data["oldPassword"], $this->me->password)) {
                $user->addError("oldPassword", "Password is incorrect!");
                return $this->render('profileChangePassword', [
                    'model' => $user
                ], "Change Password");
            }
            $user->loadData($data);
            $user->id = $this->me->id;
            if ($user->validate()) {
                $user->password = password_hash($user->newPassword, PASSWORD_DEFAULT);
                $updateData = $user->getUpdateData();
                ChangeUserPasswordForm::update($updateData);
                Application::$app->session->setFlash('success', 'Your password was changed successfully!');
                Application::$app->response->redirect('/profile');
            }
        }

        return $this->render('profileChangePassword', [
            'model' => $user
        ], "Change Password");
    }

    public function addContact(Request $request)
    {
        $contact = new Contact();
        $contact->emailAddress = $this->me->emailAddress;

        if ($request->isPost()) {
            $data = $request->getBody();
            $contact->loadData($data);
            if ($contact->validate() && $contact->save()) {
                Application::$app->session->setFlash('success', 'Your contact was created successfully!');
                Application::$app->response->redirect('/profile?tab=contacts&page=1');
            }
        }

        return $this->render('profileAddContact', [
            'model' => $contact,
        ], "Add Contact");
    }

    public function editContact(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $contact = Contact::findOne(["id" => $id, "emailAddress" => $this->me->emailAddress]);
        
        if (!$contact) throw new \MVC\Exceptions\BadRequestException("Not Found Contact!");
        
        if ($request->isPost()) {
            $data = $request->getBody();
            $contact->loadData($data);
            if ($contact->validate()) {
                $contact->setUpdatedAt("now");
                $updateData = $contact->getUpdateData();
                Contact::update($updateData);
                Application::$app->session->setFlash('success', 'Your contact was updated successfully!');
                Application::$app->response->redirect('/profile?tab=contacts');
            }
        }

        return $this->render('profileEditContact', [
            'model' => $contact
        ], "Edit Contact");
    }

    public function deleteContact(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $contact = Contact::findOne(["id" => $id, "emailAddress" => $this->me->emailAddress]);

        if (!$request->isGet()) throw new \MVC\Exceptions\BadRequestException("Method is not allowed!");

        if (!$contact) throw new \MVC\Exceptions\BadRequestException("Not Found This Contact!");

        $contact->isActive = Contact::BOOL_FALSE;
        $contact->setUpdatedAt("now");
        $updateData = $contact->getUpdateData();
        Contact::update($updateData);
        Application::$app->session->setFlash('success', 'Your contact was deleted successfully!');
        Application::$app->response->redirect('/profile?tab=contacts');
    }

    public function editAnswer(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $answer = Answer::findOne(["id" => $id, "authorID" => $this->me->id]);
        $question = Question::findOne(["id" => $answer->questionID, "isActive" => Question::BOOL_TRUE]);

        if (!$question) {
            Application::$app->session->setFlash('editAnswerfailed', 'You cannot answer for this question because it was deleted!');
            Application::$app->response->redirect('/profile?tab=answers');            
        }
        
        if (!$answer) throw new \MVC\Exceptions\BadRequestException("Not Found Contact!");
        
        if ($request->isPost()) {
            $data = $request->getBody();
            $answer->loadData($data);
            if ($answer->validate()) {
                $answer->setUpdatedAt("now");
                $updateData = $answer->getUpdateData();
                Answer::update($updateData);
                Application::$app->session->setFlash('success', 'Your answer was updated successfully!');
                Application::$app->response->redirect('/profile?tab=answers');
            }
        }

        return $this->render('profileEditAnswer', [
            'model' => $answer
        ], "Edit Answer");
    }

    public function deleteAnswer(Request $request)
    {
        $id = (int)$request->getRouteParam($param="id");
        $answer = Answer::findOne(["id" => $id, "authorID" => $this->me->id]);

        if (!$request->isGet()) throw new \MVC\Exceptions\BadRequestException("Method is not allowed!");

        if (!$answer) throw new \MVC\Exceptions\BadRequestException("Not Found This Contact!");

        $answer->isActive = Answer::BOOL_FALSE;
        $answer->setUpdatedAt("now");
        $updateData = $answer->getUpdateData();
        Answer::update($updateData);
        Application::$app->session->setFlash('success', 'Your answer was deleted successfully!');
        Application::$app->response->redirect('/profile?tab=answers');
    }
}
?>