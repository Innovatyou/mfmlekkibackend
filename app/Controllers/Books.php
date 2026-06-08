<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Books_model as booksmodel;
//use App\Models\Home_model as homemodel;

class Books extends BaseController
{
  protected $session;
  protected $apitoken = "";

  /**
   * constructor
   */
  public function __construct()
  {
    helper(['form', 'url']);
    $this->session = session();
    $this->apitoken = $this->session->get('apitoken');
    if ($this->session->get('status') != 0) {
      header("Location: " . base_url());
      exit();
    }
  }

  public function index()
  {
    $booksmodel = new booksmodel();
    $this->viewdata['books'] = $booksmodel->booksListing($this->apitoken);
    return $this->view("books/listing", $this->viewdata);
  }

  public function newBook()
  {
    return $this->view("books/new", $this->viewdata);
  }

  public function editBook($id = 0)
  {
    $booksmodel = new booksmodel();
    $this->viewdata['book'] = $booksmodel->getBookInfo($id, $this->apitoken);
    if (count((array)$this->viewdata['book']) == 0) {
      return redirect()->to(base_url() . '/books');
    }
    return $this->view("books/edit", $this->viewdata);
  }

  function saveNewBook()
  {
    $booksmodel = new booksmodel();
    if (empty($_FILES['thumbnail']['name'])) {
      $this->session->setFlashdata('error', "You need to select ebook thumbnail");
    } else if (empty($_FILES['book']['name'])) {
      $this->session->setFlashdata('error', "You need to select a pdf file to upload");
    } else {
      $title = $this->request->getVar('title');
      $description = $this->request->getVar('description');
      $author = $this->request->getVar('author');
      $pages = $this->request->getVar('pages');

      $info = array(
        'apitoken' => $this->apitoken,
        'title' => $title,
        'description' => $description,
        'author' => $author,
        'pages' => $pages,
      );
      $upload = $this->upload_thumbnail();
      if ($upload[0] == 'ok') {
        $info['thumbnail'] =  $upload[1];
      } else {
        $this->session->setFlashdata('error', $upload[1]['thumbnail']);
        return redirect()->to(base_url() . '/newBook');
      }
      $upload2 = $this->upload_book();
      if ($upload2[0] == 'ok') {
        $info['book'] =  $upload2[1];
      } else {
        //var_dump($upload2); die;
        $this->session->setFlashdata('error', $upload2[1]['book']);
        return redirect()->to(base_url() . '/newBook');
      }
      $booksmodel->addNewBook($info);
      if ($booksmodel->status == "ok") {
        $this->session->setFlashdata('success', $booksmodel->message);
      } else {
        $this->session->setFlashdata('error', $booksmodel->message);
      }
    }
    return redirect()->to(base_url() . '/books');
  }


  function editBookData()
  {
    $booksmodel = new booksmodel();
    $id = $this->request->getVar('id');
    $title = $this->request->getVar('title');
    $description = $this->request->getVar('description');
    $author = $this->request->getVar('author');
    $pages = $this->request->getVar('pages');

    $info = array(
      'title' => $title,
      'description' => $description,
      'author' => $author,
      'pages' => $pages,
    );

    if (!empty($_FILES['thumbnail']['name'])) {
      $upload = $this->upload_thumbnail();
      if ($upload[0] == 'ok') {
        $info['thumbnail'] =  $upload[1];
      } else {
        $this->session->setFlashdata('error', $upload[1]['thumbnail']);
        return redirect()->to(base_url() . '/editBook/' . $id);
      }
    }
    if (!empty($_FILES['book']['name'])) {
      $upload2 = $this->upload_book();
      if ($upload2[0] == 'ok') {
        $info['book'] =  $upload2[1];
      } else {
        $this->session->setFlashdata('error', $upload2[1]['book']);
        return redirect()->to(base_url() . '/editBook/' . $id);
      }
    }

    $booksmodel->editBook($info, $id, $this->apitoken);
    if ($booksmodel->status == "ok") {
      $this->session->setFlashdata('success', $booksmodel->message);
    } else {
      $this->session->setFlashdata('error', $booksmodel->message);
    }
    return redirect()->to(base_url() . '/editBook/' . $id);
  }


  function deleteBook($id = 0)
  {
    $booksmodel = new booksmodel();
    $book = $booksmodel->getBookInfo($id, $this->apitoken);
    if (count((array)$book) > 0) {
      @unlink('./uploads/thumbnails/' . $this->apitoken . "/" . $book->thumb);
      @unlink('./uploads/books/' . $this->apitoken . "/" . $book->pdf);
    }
    $booksmodel->deleteBook($id, $this->apitoken);
    if ($booksmodel->status == "ok") {
      $this->session->setFlashdata('success', $booksmodel->message);
    } else {
      $this->session->setFlashdata('error', $booksmodel->message);
    }
    return redirect()->to(base_url() . '/books');
  }

  function upload_thumbnail()
  {
    if (!file_exists('./uploads/thumbnails/' . $this->apitoken)) {
      mkdir('./uploads/thumbnails/' . $this->apitoken, 0777, true);
    }
    helper(['form', 'url']);
    $input = $this->validate([
      'thumbnail' => [
        'uploaded[thumbnail]',
        'mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
        'max_size[thumbnail,10024]',
      ]
    ]);
    if (!$input) {
      //$data = ['errors' => $this->validator->getErrors()];
      return ['error', $this->validator->getErrors()];
    } else {
      $img = $this->request->getFile('thumbnail');
      $img->move('./uploads/thumbnails/' . $this->apitoken);
      $data = [
        'name' =>  $img->getName(),
        'type'  => $img->getClientMimeType()
      ];
      return ['ok', $img->getName()];
    }
  }

  function upload_book()
  {
    if (!file_exists('./uploads/books/' . $this->apitoken)) {
      mkdir('./uploads/books/' . $this->apitoken, 0777, true);
    }
    helper(['form', 'url']);
    $input = $this->validate([
      'book' => [
        'uploaded[book]',
        'mime_in[book,application/pdf]',
        'max_size[book,100024]',
      ]
    ]);
    if (!$input) {
      //$data = ['errors' => $this->validator->getErrors()];
      return ['error', $this->validator->getErrors()];
    } else {
      $img = $this->request->getFile('book');
      $img->move('./uploads/books/' . $this->apitoken);
      $data = [
        'name' =>  $img->getName(),
        'type'  => $img->getClientMimeType()
      ];
      return ['ok', $img->getName()];
    }
  }
}
