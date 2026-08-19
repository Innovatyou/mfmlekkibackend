<?php

namespace App\Models;

use CodeIgniter\Model;

class MobileAdvert_model extends Model
{
    protected $table = 'tbl_mobile_adverts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'image', 'link', 'active', 'sort_order', 'created_at'];
    protected $returnType = 'object';

    public function activeAdverts()
    {
        return $this->where('active', 1)->orderBy('sort_order', 'ASC')->orderBy('id', 'DESC')->findAll();
    }
}
