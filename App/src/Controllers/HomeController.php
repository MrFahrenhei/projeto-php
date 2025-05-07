<?php

namespace App\Controllers;

use App\Core\Controllers;
use App\Core\Request;
use App\Exceptions\ClassNotFoundException;
use App\Models\Home;

class HomeController extends Controllers
{
    public function insertHome(Request $request): string
    {
        $home = new Home();
        if($request->isPost()){
            $home->loadData($request->getBody());
            if($home->validate() && $home->save()){
                return $this->render(['success' => 'Home added successfully.']);
            }
            return $this->render($home->errors);
        }
        return $this->render(['error' => 'Something went wrong.']);
    }

    public function getAllHome(Request $request): string
    {
        $home = new Home();
        if($request->isGet()) {
            $status = $request->getQueryParam("status", true);
            return $this->render($home->findMany(["status"=>$status]));
        }
        return $this->render(['error' => 'Something went wrong.']);
    }

    /**
     * @throws ClassNotFoundException
     */
    public function getSingleHome(Request $request): string
    {
        $home = new Home();
        if($request->isGet()) {
            $home->loadData($request->getBody());
            if ($home->getHomeID() AND empty($home->errors)) {
                $singleHome = $home->findOne(["home_id" => $home->home_id]);
                return $this->render($singleHome->hydrated());
            }
            return $this->render($home->errors);
        }
        return $this->render(['error' => 'Something went wrong.']);
    }
    public function deleteHome(Request $request): string
    {
        $home = new Home();
        if($request->isPost()) {
            $home->loadData($request->getBody());
            if($home->getHomeID() AND empty($home->errors)){
                if($home->deleteOne(["status"=>false],["home_id"=>$home->home_id])) {
                    return $this->render(['success' => 'Home deleted successfully.']);
                }
            }
            return $this->render($home->errors);
        }
        return $this->render(['error' => 'Something went wrong.']);
    }
    public function updateHome(Request $request): string
    {
        $home = new Home();
        if($request->isPost()) {
            $home->loadData($request->getBody());
            if($home->getHomeID() AND empty($home->errors)){
                $updateData = $home->loadedFields;
                unset($updateData[$home->primaryKey()]);
                if($home->updateOne($updateData, ["home_id" => $home->home_id])) {
                    return $this->render(['success' => 'Home edited successfully.']);
                }
                return $this->render($home->errors);
            }
        }
        return $this->render(['error' => 'Something went wrong.']);
    }

}