<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Lists_model as listsmodel;

class Lists extends BaseController
{
    protected $session;

    /**
     * constructor
     */
    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
    }

    public function index()
    {
        $listsmodel = new listsmodel();
        $this->viewdata['lists'] = $listsmodel->listsListing();
        return $this->view("lists/listing", $this->viewdata);
    }

    public function fetchlists($branch)
    {
        $listsmodel = new listsmodel();
        $lists = $listsmodel->listsListingbybranch();
        echo json_encode(array("lists" => $lists));
        exit;
    }

    public function newList()
    {
        return $this->view("lists/new", $this->viewdata);
    }

    public function editList($id = 0)
    {
        $listsmodel = new listsmodel();
        $this->viewdata['lists'] = $listsmodel->getListInfo($id);
        if (count((array)$this->viewdata['event']) == 0) {
            return redirect()->to(base_url() . '/lists');
        }
        return $this->view("lists/edit", $this->viewdata);
    }

    function savenewlist()
    {
        $listsmodel = new listsmodel();
        $title = $this->request->getVar('title');
        //$members = $this->request->getVar('members');
        //var_dump($_POST); die;
        $info = array(
            'title' => $title,
        );
        //var_dump($info); die;

        $listid = $listsmodel->addNewList($info);
        /*foreach ($members as $itm) {
        $info2 = array(
            'listid' => $listid,
            'email' => $itm,
        );
        $listsmodel->addNewListMember($info2);
      }*/
        if ($listsmodel->status == "ok") {
            $this->session->setFlashdata('success', $listsmodel->message);
            return redirect()->to(base_url() . '/addMemberstoList/' . $listid);
        } else {
            $this->session->setFlashdata('error', $listsmodel->message);
            return redirect()->to(base_url() . '/newList');
        }
    }


    function editListData()
    {
        $id = $this->request->getVar('id');
        $title = $this->request->getVar('title');
        $info = array(
            'title' => $title,
        );

        $listsmodel = new listsmodel();
        $listsmodel->editList($info, $id);
        if ($listsmodel->status == "ok") {
            $this->session->setFlashdata('success', $listsmodel->message);
        } else {
            $this->session->setFlashdata('error', $listsmodel->message);
        }

        return redirect()->to(base_url() . '/editList/' . $id);
    }


    function deleteList($id = 0)
    {
        $listsmodel = new listsmodel();
        $listsmodel->deleteListMembers($id);
        $listsmodel->deleteList($id);
        if ($listsmodel->status == "ok") {
            $this->session->setFlashdata('success', $listsmodel->message);
        } else {
            $this->session->setFlashdata('error', $listsmodel->message);
        }
        return redirect()->to(base_url() . '/lists');
    }

    function removeFromList($id, $listid)
    {
        $listsmodel = new listsmodel();
        $listsmodel->removeFromList($id);
        if ($listsmodel->status == "ok") {
            $this->session->setFlashdata('success', $listsmodel->message);
        } else {
            $this->session->setFlashdata('error', $listsmodel->message);
        }
        return redirect()->to(base_url() . '/viewListMembers/' . $listid);
    }

    public function viewListMembers($listid)
    {
        $listsmodel = new listsmodel();
        $this->viewdata['lists'] = $listsmodel->getListInfo($listid);
        if (count((array)$this->viewdata['lists']) == 0) {
            return redirect()->to(base_url() . '/lists');
        }
        $this->viewdata['list'] = $listsmodel->getListInfo($listid);
        $this->viewdata['members'] = $listsmodel->listsMembersListing($listid);
        return $this->view("lists/members", $this->viewdata);
    }

    public function addMemberstoList($listid)
    {
        $listsmodel = new listsmodel();
        $this->viewdata['list'] = $listsmodel->getListInfo($listid);
        if (count((array)$this->viewdata['list']) == 0) {
            return redirect()->to(base_url() . '/lists');
        }
        $this->viewdata['members'] = $listsmodel->fetchMembersNotinList($this->viewdata['list']);
        //var_dump($this->viewdata['members']); die;
        return $this->view("lists/addmembers", $this->viewdata);
    }

    function savenewmemberslist()
    {
        $listsmodel = new listsmodel();
        $listid = $this->request->getVar('id');
        $members = $this->request->getVar('members');
        foreach ($members as $itm) {
            $info2 = array(
                'listid' => $listid,
                'email' => $itm,
            );
            $listsmodel->addNewListMember($info2);
        }
        $this->session->setFlashdata('success', "Members added to list");
        return redirect()->to(base_url() . '/viewListMembers/' . $listid);
    }
}
